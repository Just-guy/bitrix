<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParamsCleared = [
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_ID" => $arResult["TEST_ID"],
];

$arParamsSigned = \Bitrix\Main\Component\ParameterSigner::signParameters($component->__name, $arParamsCleared);

if (empty($arResult["ANSWER"])) {
	ShowError("Создайте вопросы");
	return;
} ?>

<div class="test-element">
	<div class="test-element__form">
		<div class="test-element__input quiz__fio">
			<input type="text" name="form_text_1" placeholder="Ваше Ф.И.О." data-validate>
			<span class="test-element__hint">Обязательно для заполнения</span>
		</div>
		<div class="test-element__input quiz__telephone">
			<input type="text" name="form_text_2" placeholder="Укажите ваш телефон" data-validate>
			<span class="test-element__hint">Обязательно для заполнения</span>
		</div>
	</div>
	<div class="test-element__container">
		<h2 class="test-element__question-title"></h2>
		<ul class="test-element__list">
		</ul>
		<img src="" alt="" class="test-element__question-image">
	</div>
	<button class="test-element__button btn btn-default" data-ajax-src="<?= $this->GetFolder() ?>/ajax.php" data-params="<?= $arParamsSigned ?>" data-component-name="<?= $component->__name ?>">Дальше</button>
</div>

<? $jsParams = [
	"ANSWER" => $arResult["ANSWER"]
] ?>
<script>
	BX.ready(function() {
		let jcQuizObj = new JCQuiz(<?= json_encode($jsParams) ?>);
	})
</script>