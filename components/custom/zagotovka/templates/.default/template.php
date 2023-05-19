<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $APPLICATION->SetTitle($arParams['WRITE_YOUR_TITLE']); ?>
<div class="custom-component">
	<div class="custom-component__title">
		<?= $arParams['WRITE_YOUR_TITLE'] ?>
	</div>
	<div class="custom-component__list">
		<? foreach ($arResult['ITEMS'] as $key => $value) : ?>
			<? $this->AddEditAction($value['ID'], $value['EDIT_LINK'], CIBlock::GetArrayByID($value["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($value['ID'], $value['DELETE_LINK'], CIBlock::GetArrayByID($value["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('C_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
			<div class="custom-component__item" id="<?= $this->GetEditAreaId($value['ID']); ?>">
				<div class="custom-component__item-inner">
					<div class="custom-component__item-title">
						<?= $value['NAME'] ?>
					</div>
					<div class="custom-component__item-description">
						<?= $value['PREVIEW_TEXT'] ?>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</div>