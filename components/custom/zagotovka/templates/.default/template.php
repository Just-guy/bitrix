<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $APPLICATION->SetTitle($arParams['WRITE_YOUR_TITLE']); ?>

<div class="custom-component">
	<? foreach ($arResult["SECTION_LIST"] as $keySection => $valueSection) :
		//$this->AddEditAction($valueSection['ID'], $valueSection['EDIT_LINK'], CIBlock::GetArrayByID($valueSection["IBLOCK_ID"], "ELEMENT_EDIT"));
		//$this->AddDeleteAction($valueSection['ID'], $valueSection['DELETE_LINK'], CIBlock::GetArrayByID($valueSection["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('C_BNL_ELEMENT_DELETE_CONFIRM')));
		$jsObjectName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
		$setionId = 'custom-component__section-' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
		$lazyLoadinButtonId = 'custom-component__button-' . preg_replace("/[^a-zA-Z0-9_]/", "x", $this->randString());
	?>
		<div class="custom-component__wrapper" id="<?= $setionId ?>">
			<div class="custom-component__title">
				<?= $valueSection["NAME"] ?>
			</div>
			<div class="custom-component__element-list">
				<? if ($arParams['AJAX_CALL'] == "Y") {
					ob_start();
				} ?>
				<? foreach ($arResult['ITEMS'] as $keyElement => $valueElement) : ?>
					<? if ($valueSection["ID"] != $valueElement["IBLOCK_SECTION_ID"]) break; ?>
					<? $this->AddEditAction($valueElement['ID'], $valueElement['EDIT_LINK'], CIBlock::GetArrayByID($valueElement["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($valueElement['ID'], $valueElement['DELETE_LINK'], CIBlock::GetArrayByID($valueElement["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('C_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
					<div class="custom-component__item" id="<?= $this->GetEditAreaId($valueElement['ID']); ?>">
						<div class="custom-component__item-inner">
							<div class="custom-component__item-title">
								<?= $valueElement['NAME'] ?>
							</div>
							<div class="custom-component__item-description">
								<?= $valueElement['PREVIEW_TEXT'] ?>
							</div>
						</div>
					</div>
				<? endforeach; ?>
				<? if ($arParams['AJAX_CALL'] == "Y") $out1 = ob_get_contents(); ?>
			</div>
			<!-- Создаем элемент заглушку, через CSS присваиваем любые стили, например можно присвоить gif анимацию. -->

			<span class="custom-component__loader"></span>
			<div class="custom-component__button-container">
				<? if ($arParams["USE_LAZY_LOAD"]) : ?>

					<!-- Фильтруем массив с параметрами компонента и избавляемся от элементов массива с ~(тильдой), все элементы с ~ хранятся не в безопасном html формате -->
					<? $arParamsCleared = array_filter($arParams,  function ($key) {
						return strpos($key, '~') === false;
					}, ARRAY_FILTER_USE_KEY); ?>

					<!-- Создаем переменные с текущей и общим количеством страниц: -->
					<? $pageNavCurrent = $arResult["NAV_RESULT"]["NavPageNomer"];
					$pageNavTotal = $arResult["NAV_RESULT"]["NavPageCount"];
					$pageNavNum = $arResult["NAV_RESULT"]["NavNum"]; ?>

					<!-- Сериализуем параметры компонента и превращаем массив в закодированную строку -->
					<? $arParamsSigned = \Bitrix\Main\Component\ParameterSigner::signParameters($component->__name, $arParamsCleared) ?>

					<!-- Если текуща страница больше или равна общему количеству страниц, то присваиваем элементу класс custom-component__lazy-load_disabled -->
					<div class="custom-component__lazy-load <?= ($pageNavCurrent >= $pageNavTotal ? 'custom-component__lazy-load_disabled' : '') ?> opacity-hover">

						<!-- Создаем кнопку c data-параметрами
			data-ajax-src="<?= $templateFolder ?>/ajax.php" — путь к обрабатываемому ajax'ом файлу, в нем находится вызов компонента
			data-params="<?= $arParamsSigned ?>"            — сериализованный массив с параметрми для компонента
			data-component-name="<?= $component->__name ?>" — Название компонента 
			data-template-name="<?= $templateName ?>"       — Навзвание шаблона компонента
			data-pagenav-total="<?= $pageNavTotal ?>"       — Общее количество страниц
			data-pagenav-current="<?= $pageNavCurrent ?>"   — Текущая страница
-->
						<span id="<?= $lazyLoadinButtonId ?>" data-ajax-src="<?= $templateFolder ?>/ajax.php" data-params="<?= $arParamsSigned ?>" data-component-name="<?= $component->__name ?>" data-template-name="<?= $templateName ?>" data-pagenav-total="<?= $pageNavTotal ?>" data-pagenav-current="<?= $pageNavCurrent ?>" data-pagenav-num="<?= $pageNavNum ?>">
							<?= $arParams['NAME_BUTTON_LAZY_LOAD'] ?></span>
					</div>
				<? endif; ?>

				<? //if ($arParams["SRC_ALL_REVIEWS"]) : 
				?>
				<!--<a href="<? //= $arParams["SRC_ALL_REVIEWS"] 
									?>" class="custom-component__all-reviews opacity-hover">Все отзывы</a>-->
				<? //endif; 
				?>
			</div>
		</div>
		<?
		$jsParams = [
			'SECTION_CONTAINER_ID' => $setionId,
			'LAZY_LOADING_BUTTON_ID' => $lazyLoadinButtonId
		];
		?>
		<script>
			let <?= $jsObjectName ?> = new JCCustom(<?= CUtil::PhpToJSObject($jsParams, false, true) ?>);
		</script>
	<? endforeach; ?>
</div>
<? if ($arParams['AJAX_CALL'] == "Y") {
	ob_flush();
	echo $out1;
} ?>
