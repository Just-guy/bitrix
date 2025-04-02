<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->addExternalJS($templateFolder . "/vendor/node/node_modules/imask/dist/imask.js");

CJSCore::Init(['popup']);

use Bitrix\Main\Localization\Loc;

$jsObjectName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
$formId = 'universal-form-' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
$formCallButton = 'universal-open-form__' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
$jsParams = []; ?>
<? if ($arParams["CALLING_VIA_AJAX"] == "Y"): ?>
	<a href="javascript:void(0)" class="<?= $formCallButton ?>"><?= $arParams['NAME_FORM_CALL_BUTTON'] ?></a>
<? else: ?>
	<div class="universal-form" id="<?= $formId ?>">
		<div class="universal-form__shell">
			<? if (!empty($arParams['TITLE']) || !empty($arResult["arForm"]["NAME"])):  ?>
				<div class="universal-form__title">
					<?= (!empty($arParams['TITLE']) ? $arParams['TITLE'] : $arResult["arForm"]["NAME"]) ?>
				</div>
			<? endif; ?>
			<? if (!empty($arParams['DESCRIPTION']) || !empty($arResult["FORM_DESCRIPTION"])):  ?>
				<div class="universal-form__description">
					<?= (!empty($arParams['DESCRIPTION']) ? $arParams['DESCRIPTION'] : $arResult["FORM_DESCRIPTION"]) ?>
				</div>
			<? endif; ?>
			<div class="universal-form__content">
				<?= $arResult["FORM_HEADER"] ?>
				<div class="universal-form__block-field">
					<? foreach ($arResult["QUESTIONS"] as $keyQuestion => $valueQuestion) : ?>
						<? $type = $valueQuestion["STRUCTURE"][0]["FIELD_TYPE"];
						$name = "form_" . $type . "_" . $valueQuestion["STRUCTURE"][0]["ID"]; ?>
						<div class="universal-form__field-<?= $type ?> universal-form__name-field<?= $class ?>">
							<p class="universal-form__text-name">
								<?= $valueQuestion["CAPTION"] ?>
								<? if ($valueQuestion["REQUIRED"] == 'Y') : ?><span class="universal-form__required">*</span> <? endif; ?>
							</p>
							<? if (isset($arResult["FORM_ERRORS"][$keyQuestion])): ?>
								<span class="form-error"><?= htmlspecialcharsbx($arResult["FORM_ERRORS"][$keyQuestion]) ?></span>
							<? endif; ?>

							<? switch ($type) {
								case 'textarea': ?>
									<textarea name="<?= $name ?>" placeholder="<?= $valueQuestion["CAPTION"] ?>" <?= $valueQuestion["STRUCTURE"][0]["FIELD_PARAM"] ?> style="resize: vertical;"></textarea>
								<? break;

								default: ?>
									<input type="<?= $type ?>" name="<?= $name ?>" placeholder="<?= $valueQuestion["CAPTION"] ?>" <?= $valueQuestion["STRUCTURE"][0]["FIELD_PARAM"] ?> value="">
							<? break;
							} ?>
						</div>

						<?
						$jsParams['INPUTS'][$keyQuestion]['DATA_NAME'] = $name;
						$jsParams['INPUTS'][$keyQuestion]['CAPTION'] = $valueQuestion["CAPTION"];
						$jsParams['INPUTS'][$keyQuestion]['ID'] = $valueQuestion["STRUCTURE"][0]["ID"];
						$jsParams['INPUTS'][$keyQuestion]['REQUIRED'] = $valueQuestion["REQUIRED"];
						$jsParams['INPUTS'][$keyQuestion]['TYPE'] = $type;
						?>
					<? endforeach; ?>

					<? if ($arResult["isUseCaptcha"] == "Y"): ?>
						<div class="universal-form__field-captcha">
							<p class="universal-form__text-name">Введите код проверки</p>
							<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>">
							<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext">
							<img class="universal-form__captcha-code" src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
								width="180" height="40" />
						</div>
					<? endif; ?>
					<div class="universal-form__field-submit universal-form__action-block">
						<input <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="submit" class="form-submit btn" name="web_form_submit" value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>" />
					</div>
				</div>

				<? if ($arParams['USER_CONSENT'] == 'Y'): ?>
					<div class="universal-form__agreement">
						<? $APPLICATION->IncludeComponent(
							"bitrix:main.userconsent.request",
							"",
							array(
								"ID" => $arParams["USER_CONSENT_ID"],
								"IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
								"AUTO_SAVE" => "Y",
								"IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
							)
						); ?>
					</div>
				<? endif; ?>
				<?= $arResult["FORM_FOOTER"] ?>
			</div>
		</div>
	</div>
<? endif; ?>
<? $arParamsCleared = array_filter($arParams, function ($key) {
	return strpos($key, '~') === false;
}, ARRAY_FILTER_USE_KEY);

$jsParams += [
	'FORM_NAME' => $arResult["arForm"]["SID"],
	'PATH_TO_AJAX' => $templateFolder . ($arParams["CALLING_VIA_AJAX"] == "Y" ? '/ajax-component.php' : '/ajax-result.php'),
	'USE_CAPTCHA' => $arResult["isUseCaptcha"],
	'CALLING_VIA_AJAX' => $arParams["CALLING_VIA_AJAX"],
	'FORM_ID' => $formId,
	'CLASS_FORM_CALL_BUTTON' => $formCallButton,
	'PARAMETERS' => \Bitrix\Main\Component\ParameterSigner::signParameters($component->__name, $arParamsCleared),
	"COMPONENT_NAME" => $component->__name,
	"TEMPLATE_NAME" => $templateName
];
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
	BX.message(<?= CUtil::PhpToJSObject($messages) ?>);
	BX.JCWebForm.init({
		result: <?= CUtil::PhpToJSObject($jsParams, false, true) ?>
	});
</script>
