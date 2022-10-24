<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="reviews-form">
	<? if ($arResult["isFormErrors"] == "Y") : ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>
	<?= $arResult["FORM_NOTE"] ?>
	<? if ($arResult["isFormNote"] != "Y") { ?>
		<?= $arResult["FORM_HEADER"] ?>
		<!-- form -->
		<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
			if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
				echo $arQuestion["HTML_CODE"];
			} else { ?>
				<? if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])) : ?>
					<span class="reviews-form__error-fld" title="<?= htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID]) ?>"></span>
				<? endif; ?>
				<? switch ($arQuestion["STRUCTURE"][0]["FIELD_TYPE"]) {
					case "radio": ?>
						<div class=" reviews-form__field">
							<div class="reviews-form__name">
								<?= $arQuestion["CAPTION"]; ?>
							</div>
							<div class="reviews-form__radio">
								<? foreach ($arQuestion["STRUCTURE"] as $keyRadio => $valueRadio) { ?>
									<input type="radio" id="<?= $valueRadio["ID"] ?>" value="<?= $valueRadio["ID"] ?>" name="form_radio_REVIEWS_RATING" <?= (!empty($valueRadio["FIELD_PARAM"]) ? $valueRadio["FIELD_PARAM"] : '') ?>>
									<label for="<?= $valueRadio["ID"] ?>">
										<svg class="reviews-form__rating-start reviews-form__rating-start_empty" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M14.9323 5.70151C14.7521 5.1491 14.2602 4.79217 13.6791 4.79217H9.93204L8.75206 1.20877C8.57068 0.657915 8.07979 0.302856 7.50041 0.302856C7.49856 0.302856 7.49666 0.302856 7.49481 0.302886C6.91306 0.305171 6.42237 0.664624 6.24477 1.21864L5.09918 4.79217H1.3209C0.738384 4.79217 0.246088 5.1503 0.0667575 5.70452C-0.112603 6.25874 0.0765706 6.8374 0.548713 7.17866L3.60575 9.38834L2.42905 12.9618C2.24694 13.5149 2.43295 14.0943 2.90292 14.4381C3.13896 14.6107 3.40989 14.6971 3.681 14.6971C3.94964 14.697 4.21844 14.6122 4.45331 14.4424L7.51564 12.229L10.5416 14.4387C11.011 14.7815 11.619 14.7832 12.0904 14.443C12.5618 14.1028 12.7517 13.5253 12.5743 12.9717L11.4254 9.38834L14.4565 7.17488C14.9258 6.83221 15.1125 6.25388 14.9323 5.70151ZM13.9382 6.46517L10.395 9.05267L11.7374 13.24C11.8277 13.5218 11.6367 13.6866 11.5761 13.7304C11.5154 13.7742 11.2988 13.9035 11.0598 13.7289L7.51825 11.1427L3.93848 13.7302C3.69825 13.9038 3.48222 13.773 3.4217 13.7288C3.36121 13.6845 3.17101 13.5182 3.26375 13.2366L4.64148 9.05264L1.06351 6.46646C0.823129 6.2927 0.879781 6.0464 0.902864 5.97507C0.925947 5.90374 1.02431 5.67095 1.3209 5.67095H5.74032L7.08164 1.4869C7.17207 1.20484 7.42343 1.18197 7.49833 1.18164H7.49985C7.5766 1.18164 7.82538 1.20417 7.91742 1.48362L9.29624 5.67095H13.6792C13.975 5.67095 14.0737 5.90295 14.0969 5.97408C14.1201 6.0452 14.1772 6.29073 13.9382 6.46517Z" fill="#FFB820" />
										</svg>
										<svg class="reviews-form__rating-start reviews-form__rating-start_selected" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M14.9323 5.7015C14.7521 5.14909 14.2601 4.79216 13.6791 4.79216H9.93201L8.75204 1.20877C8.57065 0.657915 8.07977 0.302856 7.50038 0.302856C7.5 0.302856 5.74243 6.2602 5.74243 6.2602L7.5 12.2175L10.5414 14.4386C11.0119 14.7822 11.6206 14.7833 12.0923 14.4415C12.564 14.0996 12.7525 13.5208 12.5725 12.9668L11.4098 9.38833H11.4254L14.4565 7.17487C14.9257 6.83223 15.1125 6.25387 14.9323 5.7015V5.7015Z" fill="#FF9A27" />
											<path d="M7.49481 0.302886C6.91305 0.305171 6.42237 0.664623 6.24477 1.21864L5.09918 4.79216H1.3209C0.738384 4.79216 0.246088 5.1503 0.0667575 5.70452C-0.112603 6.25874 0.0765706 6.83739 0.548713 7.17865L3.59309 9.37916L2.42741 12.9667C2.24741 13.5208 2.43588 14.0996 2.90758 14.4414C3.37925 14.7832 3.98804 14.7822 4.45849 14.4386L7.49996 12.2175C7.50002 12.2175 7.50002 0.302856 7.50002 0.302856C7.4983 0.302856 7.49651 0.302856 7.49481 0.302886V0.302886Z" fill="#FFB820" />
										</svg>
									</label>
								<? } ?>
							</div>
						</div>
					<? break;
					case "text": ?>
						<div class="reviews-form__input reviews-form__field">
							<input type="text" name="form_<?= $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] . '_' . $arQuestion["STRUCTURE"][0]["ID"] ?>" placeholder="<?= $arQuestion["CAPTION"] ?>">
						</div>
					<? break;
					case "textarea": ?>
						<div class="reviews-form__textarea reviews-form__field">
							<textarea name="form_<?= $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] . '_' . $arQuestion["STRUCTURE"][0]["ID"] ?>" cols="40" rows="5" placeholder="<?= $arQuestion["CAPTION"] ?>"></textarea>
						</div>
		<? break;
				}
			}
		} ?>

		<!-- Captcha -->
		<? if ($arResult["isUseCaptcha"] == "Y") { ?>
			<b><?= GetMessage("FORM_CAPTCHA_TABLE_TITLE") ?></b>
			<input type="hidden" name="captcha_sid" value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>" width="180" height="40" />
			<?= GetMessage("FORM_CAPTCHA_FIELD_TITLE") ?><?= $arResult["REQUIRED_SIGN"]; ?></td>
			<td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></td>
			</tr>
		<? } ?>

		<!-- button -->
		<input type="hidden" name="recaptcha_token" value="">
		<input <?= (intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : ""); ?> type="submit" name="web_form_submit" value="<?= htmlspecialcharsbx(trim($arResult["arForm"]["BUTTON"]) == '' ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]); ?>" />
		<?= $arResult["FORM_FOOTER"] ?>
	<? } //endif (isFormNote) 
	?>
</div>