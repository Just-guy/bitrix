<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CJSCore::Init(['masked_input', 'popup']);

use Bitrix\Main\Localization\Loc;

$jsObjectName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
$jsParams = []; ?>

<div class="callback-form">
	<div class="callback-form__shell">
		<? if ($arParams["USE_TITLE"] == "Y" && (!empty($arParams['TITLE']) || !empty($arResult["arForm"]["NAME"]))):  ?>
			<div class="callback-form__title">
				<?= (!empty($arParams['TITLE']) ? $arParams['TITLE'] : $arResult["arForm"]["NAME"]) ?>
			</div>
		<? endif; ?>
		<? if (!empty($arParams['DESCRIPTION']) || !empty($arResult["FORM_DESCRIPTION"])):  ?>
			<div class="callback-form__description">
				<?= (!empty($arParams['DESCRIPTION']) ? $arParams['DESCRIPTION'] : $arResult["FORM_DESCRIPTION"]) ?>
			</div>
		<? endif; ?>
		<div class="callback-form__content">
			<?= $arResult["FORM_HEADER"] ?>
			<div class="callback-form__block-field">
				<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) : ?>
					<?
					$type	= $arQuestion["STRUCTURE"][0]["FIELD_TYPE"];
					$id	= $arQuestion["STRUCTURE"][0]["ID"];
					$class = $type == 'hidden' ? ' hidden-field' : '';
					$placeholder = "";
					$name = "form_" . $type . "_" . $arQuestion["STRUCTURE"][0]["ID"];
					?>
					<? if ($FIELD_SID == "NAME") : ?>
						<div class="callback-form__field-<?= $type ?> callback-form__name-field<?= $class ?>">
							<p class="callback-form__text-name">
								<?= $arQuestion["CAPTION"] ?>
								<? if ($arQuestion["REQUIRED"]) : ?><span class="callback-form__required">*</span> <? endif; ?>
							</p>
							<input type="<?= $type ?>" name="<?= $name ?>" placeholder="Ваше имя" value="">
						</div>
					<? elseif ($FIELD_SID == "PHONE_NUMBER") : ?>
						<div class="callback-form__field-<?= $type ?> callback-form__phone-field<?= $class ?>">
							<p class="callback-form__text-name">
								<?= $arQuestion["CAPTION"] ?>
								<? if ($arQuestion["REQUIRED"]) : ?><span class="callback-form__required">*</span><? endif; ?>
							</p>
							<input type="<?= $type ?>" name="<?= $name ?>" placeholder="+7 999 999 99 99" value="" data-pattern="^\+?(\d{1,3})?[- .]?\(?(?:\d{2,3})\)?[- .]?\d\d\d[- .]?\d\d[- .]{0,1}\d\d$" data-validation="tel">
						</div>
					<? elseif ($FIELD_SID == "EMAIL") : ?>
						<div class="callback-form__field-<?= $type ?> callback-form__email-field<?= $class ?>">
							<p class="callback-form__text-name">
								<?= $arQuestion["CAPTION"] ?>
								<? if ($arQuestion["REQUIRED"]) : ?><span class="callback-form__required">*</span><? endif; ?>
							</p>
							<input type="<?= $type ?>" name="<?= $name ?>" placeholder="Email" value="" data-pattern="^[A-Z0-9._%+-]+@[A-Z0-9-]+\.[A-Z]{2,4}$" data-validation="email">
						</div>
					<? elseif ($FIELD_SID == "ORDER") : ?>
						<input type="<?= $type ?>" name="<?= $name ?>" value="<?= $arParams["ADDITIONAL_VALUES"]["ORDER"] ?>">
					<? endif; ?>

					<?
					$jsParams['INPUTS'][$FIELD_SID]['DATA_NAME'] = $name;
					$jsParams['INPUTS'][$FIELD_SID]['CAPTION'] = $arQuestion["CAPTION"];
					$jsParams['INPUTS'][$FIELD_SID]['ID'] = $arQuestion["STRUCTURE"][0]["ID"];
					$jsParams['INPUTS'][$FIELD_SID]['REQUIRED'] = $arQuestion["REQUIRED"];
					$jsParams['INPUTS'][$FIELD_SID]['TYPE'] = $type;
					?>
				<? endforeach; ?>

				<div class="callback-form__field-submit callback-form__action-block">
					<input <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="submit" class="form-submit" name="web_form_submit" value="<?= htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>" onclick="this.form.recaptcha_token.value = window.recaptcha.getToken()">
					<input type="hidden" name="recaptcha_token" value="">
				</div>
				<? if ($arParams["PERSONAL_DATA"] == "Y"): ?>
					<div class="callback-form__agreement">
						Нажимая на кнопку, вы даете согласие на <a href="/about/informations/politika-konfidentsialnosti/" target="_blank" class="privacy">обработку персональных данных</a>
					</div>
				<? endif; ?>
				<?= $arResult["FORM_FOOTER"] ?>
			</div>
		</div>
	</div>
</div>
<?
$jsParams += [
	'FORM_NAME' => $arResult["arForm"]["SID"],
	'PATH_TO_AJAX' => $templateFolder . '/ajax.php',
	'USE_CAPTCHA' => $arResult["isUseCaptcha"],
	'BASKET' => !empty($arParams["ADDITIONAL_VALUES"]["BASKET"]) ? json_encode($arParams["ADDITIONAL_VALUES"]["BASKET"]) : false
];
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
	BX.message(<?= CUtil::PhpToJSObject($messages) ?>);
	BX.JCWebForm.init({
		result: <?= CUtil::PhpToJSObject($jsParams, false, true) ?>
	});
</script>
