<?php if (!empty($arResult["CARS"])): ?>
    <?php foreach ($arResult["CARS"] as $car): ?>
        <div class="garage-car">
            <a onclick="openCarHistorySlider(<?= (int)$car['ID'] ?>)">
                <h3><?= htmlspecialchars($car["NAME"]) ?></h3>

                <?php if (!empty($car["PHOTOS"])): ?>
                    <div class="garage-photos">
                        <?php foreach ($car["PHOTOS"] as $photoSrc): ?>
                            <img src="<?= htmlspecialchars($photoSrc) ?>" alt="<?= GetMessage("IMG_ALT") ?>" class="garage-photo">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <ul>
                    <?php if ($car["BRAND"]): ?><li><strong><?= GetMessage("CAR_BRAND") ?>:</strong> <?= htmlspecialchars($car["BRAND"]) ?></li><?php endif; ?>
                    <?php if ($car["MODEL"]): ?><li><strong><?= GetMessage("CAR_MODEL") ?>:</strong> <?= htmlspecialchars($car["MODEL"]) ?></li><?php endif; ?>
                    <?php if ($car["YEAR"]): ?><li><strong><?= GetMessage("CAR_YEAR") ?>:</strong> <?= htmlspecialchars($car["YEAR"]) ?></li><?php endif; ?>
                    <?php if ($car["COLOR"]): ?><li><strong><?= GetMessage("CAR_COLOR") ?>:</strong> <?= htmlspecialchars($car["COLOR"]) ?></li><?php endif; ?>
                    <?php if (isset($car["KILOMETERS"])): ?><li><strong><?= GetMessage("CAR_KILOMETERS") ?>:</strong> <?= htmlspecialchars($car["KILOMETERS"]) ?> <?= GetMessage("CAR_KILOMETERS_MESURE_SHORT") ?></li><?php endif; ?>
                </ul>
            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?= GetMessage("CAR_GARAGE_EMPTY") ?>
<?php endif; ?>

<script>
    function openCarHistorySlider(carId) {
        BX.SidePanel.Instance.open('history.php?car_id=' + carId, {
            width: 1400,
            animationDuration: 300
        });
    }
</script>
