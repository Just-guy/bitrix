<?
public function Register($USER_LOGIN, $USER_NAME, $USER_LAST_NAME, $USER_PASSWORD, $USER_CONFIRM_PASSWORD, $USER_EMAIL, $SITE_ID = false, $captcha_word = "", $captcha_sid = 0, $bSkipConfirm = false, $USER_PHONE_NUMBER = "")
{
	/**
	 * @global CMain $APPLICATION
	 * @global CUserTypeManager $USER_FIELD_MANAGER
	 */
	global $APPLICATION, $DB, $USER_FIELD_MANAGER;

	//=== Проверяем, находимся ли мы в административной панели, и если да, то запрещаем использовать метод CUser::Register
	$APPLICATION->ResetException();
	if(defined("ADMIN_SECTION") && ADMIN_SECTION===true && $SITE_ID!==false)
	{
		$APPLICATION->ThrowException(GetMessage("MAIN_FUNCTION_REGISTER_NA_INADMIN"));
		return array("MESSAGE"=>GetMessage("MAIN_FUNCTION_REGISTER_NA_INADMIN"), "TYPE"=>"ERROR");
	}
	//===



	$strError = "";

	//=== Ловим ошибку не введенной капчи
	if (COption::GetOptionString("main", "captcha_registration", "N") == "Y")
	{
		if (!($APPLICATION->CaptchaCheckCode($captcha_word, $captcha_sid)))
		{
			$strError .= GetMessage("MAIN_FUNCTION_REGISTER_CAPTCHA")."<br>";
		}
	}
	//===

	//=== Если имеется ошибка и если включена регистрация ошибок в log файл, то закидываем ее в лог ошибок и показываем исключение в публичном разделе(на странице регистрации)
	if($strError)
	{
		if(COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
		{
			CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", false, $strError);
		}

		$APPLICATION->ThrowException($strError);
		return array("MESSAGE"=>$strError, "TYPE"=>"ERROR");
	}
	//===

	//=== Определяем ID сайта в переменную
	if($SITE_ID === false)
		$SITE_ID = SITE_ID;
	//===

	//=== Если пользователь подтвердил регистрацию
	$bConfirmReq = !$bSkipConfirm && (COption::GetOptionString("main", "new_user_registration_email_confirmation", "N") == "Y" && COption::GetOptionString("main", "new_user_email_required", "Y") <> "N");
	//===

	//=== Определяем, отмечена ли галочка «Регистрировать пользователей по номеру телефона» в главном модуле
	$phoneRegistration = (COption::GetOptionString("main", "new_user_phone_auth", "N") == "Y");
	//===

	//=== Определяем, отмечена ли галочка «Номер телефона является обязательным» в главном модуле
	$phoneRequired = ($phoneRegistration && COption::GetOptionString("main", "new_user_phone_required", "N") == "Y");
	//

	//=== Генерируем уникальную строку
	$checkword = md5(uniqid().CMain::GetServerUniqID());
	//===

	//=== Определяем, активен пользователь или нет
	$active = ($bConfirmReq || $phoneRequired? "N": "Y");
	//===

	//=== Формируем значения для пользовательских свойств (пользовательские поля находятся в настройках каждого пользователя)
		$arFields = array(
		"LOGIN" => $USER_LOGIN,
		"NAME" => $USER_NAME,
		"LAST_NAME" => $USER_LAST_NAME,
		"PASSWORD" => $USER_PASSWORD,
		"CHECKWORD" => Password::hash($checkword),
		"~CHECKWORD_TIME" => $DB->CurrentTimeFunction(),
		"CONFIRM_PASSWORD" => $USER_CONFIRM_PASSWORD,
		"EMAIL" => $USER_EMAIL,
		"PHONE_NUMBER" => $USER_PHONE_NUMBER,
		"ACTIVE" => $active,
		"CONFIRM_CODE" => ($bConfirmReq? Random::getString(8, true): ""),
		"SITE_ID" => $SITE_ID,
		"LANGUAGE_ID" => LANGUAGE_ID,
		"USER_IP" => $_SERVER["REMOTE_ADDR"],
		"USER_HOST" => @gethostbyaddr($_SERVER["REMOTE_ADDR"]),
	);
	$USER_FIELD_MANAGER->EditFormAddFields("USER", $arFields);
	//===

	//=== Группа, которая добавляется при регистрации нового пользователя
	$def_group = COption::GetOptionString("main", "new_user_registration_def_group", "");
	if($def_group!="")
		$arFields["GROUP_ID"] = explode(",", $def_group);
	//===

	//=== Проверяем, имеются ли exception(исключение или ошибка) до регистрации
	$bOk = true;
	$result_message = true;
	foreach(GetModuleEvents("main", "OnBeforeUserRegister", true) as $arEvent)
	{
		if(ExecuteModuleEventEx($arEvent, array(&$arFields)) === false)
		{
			if($err = $APPLICATION->GetException())
			{
				$result_message = array("MESSAGE"=>$err->GetString()."<br>", "TYPE"=>"ERROR");
			}
			else
			{
				$APPLICATION->ThrowException("Unknown error");
				$result_message = array("MESSAGE"=>"Unknown error"."<br>", "TYPE"=>"ERROR");
			}

			$bOk = false;
			break;
		}
	}
	//===


	$ID = false;
	$phoneReg = false;

	//=== Если исключений и ошибок не обнаружилось, то продолжаем выполнение программы
	if($bOk)
	{
		//=== Получаем ID сайта
		if($arFields["SITE_ID"] === false)
		{
			$arFields["SITE_ID"] = CSite::GetDefSite();
		}
		$arFields["LID"] = $arFields["SITE_ID"];
		//===

		//=== Выполняем условие, если значения сохранены в пользовательские поля
		if($ID = $this->Add($arFields))
		{
			//=== Выполняем условие, если разрешена регистрация по телефону и значение поля «Номер телефона» не пустое
			if($phoneRegistration && $arFields["PHONE_NUMBER"] <> '')
			{
				$phoneReg = true;

				//Получаем код и номер телефона
				list($code, $phoneNumber) = static::GeneratePhoneCode($ID);
				//===

				//=== Формируем событие на отправку смс сообщения для подтверждения регистрации
				$sms = new \Bitrix\Main\Sms\Event(
					"SMS_USER_CONFIRM_NUMBER",
					[
						"USER_PHONE" => $phoneNumber,
						"CODE" => $code,
					]
				);
				//===

				//=== Устанавливаем сайт для контекста (для смс, возможно смс службе необходимы данные, с какого сайта отправляется смс)
				$sms->setSite($arFields["SITE_ID"]);
				//===

				//=== Отправляем смс и результат отправки помещаем в переменную
				$smsResult = $sms->send(true);
				//===

				//=== Не до конца разобрался, но возможно это какой-то индивидуальный токен или ID, который присваивается каждой смске
				$signedData = \Bitrix\Main\Controller\PhoneAuth::signData(['phoneNumber' => $phoneNumber]);

				//=== Если смс доставлена успешно, то формируем сообщение с результатом
				if($smsResult->isSuccess())
				{
					$result_message = array(
						"MESSAGE" => GetMessage("main_register_sms_sent"),
						"TYPE" => "OK",
						"SIGNED_DATA" => $signedData,
						"ID" => $ID,
					);
				}
				//===
				else
				//=== Иначе формируем сообщение с ошибкой
				{
					$result_message = array(
						"MESSAGE" => $smsResult->getErrorMessages(),
						"TYPE" => "ERROR",
						"SIGNED_DATA" => $signedData,
						"ID" => $ID,
					);
				}
				//===
			}
			//===
			else
			//=== Если регистрация по телефону не разрешена и значение поля «Номер телефона» пустое, то регистрируем без всей этой фигни и отправляем сообщение об успешной регистрации
			{
				$result_message = array(
					"MESSAGE" => GetMessage("USER_REGISTER_OK"),
					"TYPE" => "OK",
					"ID" => $ID
				);
			}
			//===

			//=== Помещаем в переменные ID пользователя и уникальную строку
			$arFields["USER_ID"] = $ID;
			$arFields["CHECKWORD"] = $checkword;
			//===

			$arEventFields = $arFields;

			//=== Удаляем переменные
			unset($arEventFields["PASSWORD"]);
			unset($arEventFields["CONFIRM_PASSWORD"]);
			unset($arEventFields["~CHECKWORD_TIME"]);
			//===

			//=== Создаем экземпляр класса для работы с почтовыми событиями
			$event = new CEvent;
			//===

			//=== Отправляем сообщение(немедленно) о регистрации нового пользователя
			$event->SendImmediate("NEW_USER", $arEventFields["SITE_ID"], $arEventFields);
			//===

			//=== Если пользователю необходимо подтвердить регистрацию, то высылаем ему письмо
			if($bConfirmReq)
			{
				$event->SendImmediate("NEW_USER_CONFIRM", $arEventFields["SITE_ID"], $arEventFields);
			}
			//===
		}
		//===
		else
		//=== Если значения не сохранены в пользовательские поля, выдаем ошибку
		{
			$APPLICATION->ThrowException($this->LAST_ERROR);
			$result_message = array("MESSAGE"=>$this->LAST_ERROR, "TYPE"=>"ERROR");
		}
		//===
	}

	//=== Если сообщение с результатом является массивом, то:
	if(is_array($result_message))
	{
		//=== Если тип сообщение равен "OK", то:
		if($result_message["TYPE"] == "OK")
		{
			//=== Если в главном модуле работает функция записи регистрации нового пользователя в лог, то:
			if(COption::GetOptionString("main", "event_log_register", "N") === "Y")
			{
				//=== Записывае информацию о новой регистрации в лог
				$res_log["user"] = ($USER_NAME != "" || $USER_LAST_NAME != "") ? trim($USER_NAME." ".$USER_LAST_NAME) : $USER_LOGIN;
				CEventLog::Log("SECURITY", "USER_REGISTER", "main", $ID, serialize($res_log));
				//===
			}
			//===
		}
		//===
		else
		{
			//=== Если в главном модуле работает функция записи ошибок регистрации в лог, то:
			if(COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
			{
				//=== Записывае ошибку регистрации в лог
				CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", $ID, $result_message["MESSAGE"]);
				//===
			}
			//===
		}
	}
	//===

	//authorize succesfully registered user, except email or phone confirmation is required
	//=== Авторизовать успешно зарегистрированного пользователя, за исключением того, что требуется подтверждение по электронной почте или телефону
	$isAuthorize = false;

	//=== Если имеетс ID пользователя, он автивен и регистрация по телефону отсутствовала(не была обязательной), то :
	if($ID !== false && $arFields["ACTIVE"] === "Y" && $phoneReg === false)
	{
		//=== Происходит авторизация
		$isAuthorize = $this->Authorize($ID);
		//===
	}
	//===

	// Помещаем в переменную ID пользовательского соглашения, которое используется на сайте
	$agreementId = intval(COption::getOptionString("main", "new_user_agreement", ""));

	//=== Если имеем ID пользовательского соглашения и авторизованного пользователя, то:
	if ($agreementId && $isAuthorize)
	{
		//=== Получаем объект пользовательского соглашения по ID и помещаем его в переменную
		$agreementObject = new \Bitrix\Main\UserConsent\Agreement($agreementId);
		//===

		//=== Если объект существует, активен и пользователь согласился с пользовательским соглашением, то:
		if ($agreementObject->isExist() && $agreementObject->isActive() && $_REQUEST["USER_AGREEMENT"] == "Y")
		{
			\Bitrix\Main\UserConsent\Consent::addByContext($agreementId, "main/reg", "register");
		}
		//===
	}
	//===

	$arFields["RESULT_MESSAGE"] = $result_message;
	
	//=== Проводим через цикл список всех обработчиков события "OnAfterUserRegister" в главном модуле(main)
	foreach (GetModuleEvents("main", "OnAfterUserRegister", true) as $arEvent)
		//=== Запускаем обаботчики и передаем им параметры $arFields
		ExecuteModuleEventEx($arEvent, array(&$arFields));
		//===
	return $arFields["RESULT_MESSAGE"];
	//===
}
