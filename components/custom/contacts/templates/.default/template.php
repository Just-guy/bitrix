<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="contacts">
	<span class="contacts__title"></span>
	<? if (!empty($arParams["EMAIL"])) : ?>
		<div class="contacts__email-list">
			<? foreach ($arParams["EMAIL"] as $key => $value) : ?>
				<a href="mailto:<?= $value ?>" class="contacts__email-element"><?= $value ?></a>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	<? if (!empty($arParams["PHONE_NUMBERS"])) : ?>
		<div class="contacts__phone-number-list">
			<? foreach ($arResult["PHONE_NUMBERS"] as $key => $value) : ?>
				<a href="<?= $value["CLEAR"] ?>" class="contacts__phone-number-element"><?= $value["NO_CLEAR"] ?></a>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	<? if (!empty($arParams["ADDRESSES"])) : ?>
		<div class="contacts__address-list">
			<? foreach ($arParams["ADDRESSES"] as $key => $value) : ?>
				<div class="contacts__address-element"></div>
			<? endforeach; ?>
		</div>
	<? endif; ?>
	<? if (!empty(["SOCIAL_NETWORKS_VK"]) || !empty(["SOCIAL_NETWORKS_TG"]) || !empty(["SOCIAL_NETWORKS_WA"])) : ?>
		<div class="contacts__social-network-list">
			<div class="contacts__social-network-element"></div>
		</div>
	<? endif; ?>
</div>