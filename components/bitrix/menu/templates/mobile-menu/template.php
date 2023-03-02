<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)) : ?>

	<div class="menu-mobile">
		<ul>
			<?
			$previousLevel = 0;
			foreach ($arResult as $arItem) :
			?>
				<? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel) : ?>
					<?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
				<? endif ?>

				<? if ($arItem["IS_PARENT"]) : ?>
					<li class="menu-mobile__level-item menu-mobile__level-item-<?= $arItem["DEPTH_LEVEL"] ?>">
						<div class="menu-mobile__item-text">
							<a href="<?= $arItem["LINK"] ?>">
								<?= $arItem["TEXT"] ?>
							</a>
							<div class="menu-mobile__next fade-out" onClick="OpenMenuNode(this)">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6 12L10 8L6 4" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</div>
						</div>
						<ul class="menu-mobile__level-list menu-mobile__level-list-<?= $arItem["DEPTH_LEVEL"] ?>" style="z-index: <?= $arItem["DEPTH_LEVEL"] + 1 ?>">
							<svg onClick="OpenMenuNode(this)" class="menu-mobile__button-back fade-out" width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.41667 8.66683L1.75 5.00016M1.75 5.00016L5.41667 1.3335M1.75 5.00016L18.25 5.00016" stroke="#CACACA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
							<div class="menu-mobile__parent-name">
								<?= $arItem["TEXT"] ?>
							</div>
						<? else : ?>

							<? if ($arItem["PERMISSION"] > "D") : ?>
								<li>
									<div class="menu-mobile__item-text"><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></div>
								</li>
							<? endif ?>

						<? endif ?>

						<? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

					<? endforeach ?>

					<? if ($previousLevel > 1) : //close last item tags
					?>
						<?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
					<? endif ?>

						</ul>
	</div>
<? endif ?>