<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="testing-list">
	<div class="testing-list__link-list">
		<? foreach ($arResult["TESTS_DATA"] as $keyTest => $dataTest) {
			$pageTemplate = CComponentEngine::makePathFromTemplate($arParams["TEST_PAGE_TEMPLATE"], array("TEST_ID" => $dataTest["ID"])); ?>	
			<a href="<?= $pageTemplate ?>" class="testing-list__link"><?= $dataTest["NAME"] ?></a>
		<? } ?>
	</div>
</div>