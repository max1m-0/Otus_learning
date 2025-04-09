<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<h3>Выберите валюту:</h3>
<form method="GET">
	<select name="currency" onchange="this.form.submit();">
		<?php foreach ($arResult['CURRENCIES'] as $currency): ?>
			<option value="<?= $currency ?>"
				<?= $currency === $_GET['currency'] ? 'selected' : '' ?>>
				<?= htmlspecialchars($currency) ?>
			</option>
		<?php endforeach; ?>
	</select>
</form>

<p>Курс выбранной валюты: <strong><?= $arResult['CURRENCY_RATE'] ?></strong></p>
