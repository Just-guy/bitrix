# Вызов компонента
```
$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"universal", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"DESCRIPTION" => "",
		"EDIT_URL" => "result_edit.php",
		"SUCCESSFUL_RESULT_SEPARATE_WINDOW" => "Y",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "result_list.php",
		"NAME_FORM_CALL_BUTTON" => "",
		"OPEN_FORM_IN_MODAL_WINDOW" => "N",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "",
		"TITLE" => "",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"WEB_FORM_ID" => "1",
		"COMPONENT_TEMPLATE" => "universal",
		"TITLE_SUCCESSFUL_RESULT" => "Спасибо!",
		"DESCRIPTION_SUCCESSFUL_RESULT" => "В ближайшее время с вами свяжется администратор для уточнения деталей",
		"TITLE_FAILURE_RESULT" => "Ошибка",
		"DESCRIPTION_FAILURE_RESULT" => "Попробуйте ещё раз",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);
```

# Валидация полей
Для валидации поля в настройках найти поле «Параметры»:
<img width="1422" height="320" alt="image" src="https://github.com/user-attachments/assets/e64ee36b-5715-4b92-9015-17539aaf8241" />

Для телефона добавить строку:
```html
data-validation="telephone"
```

Для email добавить строку:
```html
data-validation="email"
```

# Особенности
В решениях Aspro есть параметр <b>$arTheme['SHOW_LICENCE']</b> = «Информирование об обработке персональных данных»:
<img width="1403" height="617" alt="image" src="https://github.com/user-attachments/assets/0b328a8a-6065-4b5d-9856-88de781cb6ce" />
В случае, если форме нам нет необходимости использовать соглашение, но активен параметр <b>SHOW_LICENCE</b>, мы не сможем сохранить результат формы.

Кастомный обработчик события <b>onBeforeResultAddHandler»</b> будет «выкидывать» Exception с сообщение «Согласитесь с условиями»:
<img width="1199" height="277" alt="image" src="https://github.com/user-attachments/assets/80fe11f3-4b15-4f02-834f-1c150861a2a2" />

