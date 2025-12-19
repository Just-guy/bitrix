<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="main-map">
	<ul>
		<?
		$keys = array_keys($arResult['SECTIONS']);
		foreach ($arResult['SECTIONS'] as $key => $value): ?>
			<?
				$nextKey = $keys[array_search($key, $keys) + 1] ?? null;
				$nextDepth = $arResult['SECTIONS'][$nextKey]["DEPTH_LEVEL"];
			?>

			<? if (($value["DEPTH_LEVEL"] < $lastDepth)): ?>
					</ul>
				</li>
			<? endif; ?>
			
			<? if ($value["RIGHT_MARGIN"] > ($value["LEFT_MARGIN"] + 1)): ?>
				<li class="main-map__item main-map__section main-map__depth-level-<?= $value["DEPTH_LEVEL"] ?>">
					<a href="<?= $value['SECTION_PAGE_URL'] ?>"><?= $value["NAME"] ?></a>
					<? if(!empty($value['ELEMENTS'])): ?>
						<ul>
							<? foreach($value['ELEMENTS'] as $keyElement => $valueElement): ?>
								<li class="main-map__item main-map__element">
									<a href="<?= $valueElement['DETAIL_PAGE_URL'] ?>"><?= $valueElement['NAME'] ?></a>
								</li>
							<? endforeach; ?>
						</ul>
					<? endif; ?>
				<? if ($value["DEPTH_LEVEL"] < $nextDepth): ?>
					<ul>
				<? endif; ?>
			<? elseif($value["RIGHT_MARGIN"] = ($value["LEFT_MARGIN"] + 1)): ?>
				<li class="main-map__item main-map__section main-map__depth-level-<?= $value["DEPTH_LEVEL"] ?>">
					<a href="<?= $value['SECTION_PAGE_URL'] ?>"><?= $value["NAME"] ?></a>
						<? if(!empty($value['ELEMENTS'])): ?>
							<ul>
								<? foreach($value['ELEMENTS'] as $keyElement => $valueElement): ?>
									<li class="main-map__item main-map__element">
										<a href="<?= $valueElement['DETAIL_PAGE_URL'] ?>"><?= $valueElement['NAME'] ?></a>
									</li>
								<? endforeach; ?>
							</ul>
						<? endif; ?>
				</li>
			<? endif; ?> 

			<? $lastDepth = $value["DEPTH_LEVEL"] ?>
		<? endforeach; ?>
	</ul>
</div>
