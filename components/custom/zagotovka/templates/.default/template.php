<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="custom-component">
	<div class="custom-component__title">
		<?= $arParams['WRITE_YOUR_TITLE'] ?>
	</div>
	<div class="custom-component__list">
		<? foreach ($arResult['ITEMS'] as $key => $value) : ?>
			<div class="custom-component__item">
				<div class="custom-component__item-inner">
					<div class="custom-component__item-title">
						<?= $arResult['ITEMS'][$key]['NAME'] ?>
					</div>
					<div class="custom-component__item-description">
						<?= $arResult['ITEMS'][$key]['PREVIEW_TEXT'] ?>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</div>