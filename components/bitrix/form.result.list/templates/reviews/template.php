<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="reviews-list">
	<div class="reviews-list__shell" data-ajax-next>
		<? foreach ($arResult["arrAnswers"] as $answerKey => $answerValue) { ?>
			<? //if($answerValue["REVIEWS_ID_ELEMENT"]["USER_TEXT"] == $arParams["ID_ELEMENT"] || $arParams["ID_ELEMENT"] == 'ALL'): 
			?>
			<div class="reviews-list__block">
				<div class="reviews-list__top">
					<div class="reviews-list__name">
						<?= $answerValue["REVIEWS_NAME"]["USER_TEXT"] ?>
					</div>
					<div class="reviews-list__rating">
						<? for ($i = 1; $i <= $answerValue["REVIEWS_RATING"]["ANSWER_TEXT"]; $i++) { ?>
							<div class="reviews-list__rating-start reviews-list__rating-start_selected">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.4888 9.9254C6.18533 9.75553 5.81539 9.75553 5.51191 9.9254L3.77781 10.8961C3.03321 11.3129 2.14226 10.6656 2.30853 9.82864L2.69578 7.87936C2.76355 7.53824 2.64922 7.18641 2.39387 6.95029L0.934578 5.60091C0.308046 5.02156 0.648324 3.97415 1.49572 3.87365L3.46938 3.63958C3.81471 3.59862 4.11397 3.3812 4.25964 3.06542L5.09232 1.26039C5.44975 0.485576 6.55097 0.485574 6.9084 1.26039L7.74107 3.06542C7.88674 3.3812 8.186 3.59862 8.53133 3.63958L10.505 3.87365C11.3524 3.97415 11.6927 5.02156 11.0661 5.60091L9.60685 6.95029C9.3515 7.18641 9.23716 7.53824 9.30493 7.87936L9.69218 9.82864C9.85845 10.6656 8.9675 11.3129 8.2229 10.8961L6.4888 9.9254Z" fill="#E5C5AE" />
								</svg>
							</div>
						<? } ?>
						<? for ($i = 1; $i <= 5 - $answerValue["REVIEWS_RATING"]["ANSWER_TEXT"]; $i++) { ?>
							<div class="reviews-list__rating-start reviews-list__rating-start reviews-list__rating-start_empty">
								<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.4888 9.9254C6.18533 9.75553 5.81539 9.75553 5.51191 9.9254L3.77781 10.8961C3.03321 11.3129 2.14226 10.6656 2.30853 9.82864L2.69578 7.87936C2.76355 7.53824 2.64922 7.18641 2.39387 6.95029L0.934578 5.60091C0.308046 5.02156 0.648324 3.97415 1.49572 3.87365L3.46938 3.63958C3.81471 3.59862 4.11397 3.3812 4.25964 3.06542L5.09232 1.26039C5.44975 0.485576 6.55097 0.485574 6.9084 1.26039L7.74107 3.06542C7.88674 3.3812 8.186 3.59862 8.53133 3.63958L10.505 3.87365C11.3524 3.97415 11.6927 5.02156 11.0661 5.60091L9.60685 6.95029C9.3515 7.18641 9.23716 7.53824 9.30493 7.87936L9.69218 9.82864C9.85845 10.6656 8.9675 11.3129 8.2229 10.8961L6.4888 9.9254Z" fill="#F0F0F0" />
								</svg>
							</div>
						<? } ?>
					</div>
				</div>
				<div class="reviews-list__meadle">
					<div class="reviews-list__date">
						<?= $answerValue["DATE"] ?>
					</div>
				</div>
				<div class="reviews-list__bottom">
					<div class="reviews-list__value">
						<?= $answerValue["REVIEWS_VALUE"]["USER_TEXT"] ?>
					</div>
				</div>
			</div>
			<? //endif; 
			?>
		<? } ?>
	</div>
	<span class="reviews-list__loader"></span>
	<div class="reviews-list__button-container">
		<? if ($arParams["USE_LAZY_LOAD"]) : ?>
			<? $arParamsCleared = array_filter($arParams,  function ($key) {
				return strpos($key, '~') === false;
			}, ARRAY_FILTER_USE_KEY);

			$pageNavCurrent = $arResult["NAV_RESULT"]["NavPageNomer"];
			$pageNavTotal = $arResult["NAV_RESULT"]["NavPageCount"];
			$pageNavNum = $arResult["NAV_RESULT"]["NavNum"];

			$arParamsSigned = \Bitrix\Main\Component\ParameterSigner::signParameters($component->__name, $arParamsCleared); ?>
			<div class="reviews__lazy-load <?= ($pageNavCurrent >= $pageNavTotal ? 'reviews__lazy-load_disabled ' : '') ?>opacity-hover">
				<span data-ajax-src="<?= $templateFolder ?>/ajax.php"
				data-params="<?= $arParamsSigned ?>"
				data-component-name="<?= $component->__name ?>"
				data-template-name="<?= $templateName ?>"
				data-pagenav-total="<?= $pageNavTotal ?>"
				data-pagenav-current="<?= $pageNavCurrent ?>"
				data-pagenav-num="<?= $pageNavNum ?>">
				<?= $arParams['TITLE_BUTTON'] ?></span>
			</div>
		<? endif; ?>

		<? if ($arParams["SRC_ALL_REVIEWS"]) : ?>
			<a href="<?= $arParams["SRC_ALL_REVIEWS"] ?>" class="reviews-list__all-reviews opacity-hover">Все отзывы</a>
		<? endif; ?>

		<? //= $arResult["pager"] ?>
	</div>
</div>
<!--  -->