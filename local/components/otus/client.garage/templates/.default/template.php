<?php
if (!empty($arResult["CARS"])){
foreach ($arResult["CARS"] as $car): ?>
    <div class="garage-car">
        <a href="history.php?car_id=<?= $car["ID"] ?>">
        <h3><?= htmlspecialchars($car["NAME"]) ?></h3>

        <?php
        if (!empty($car["PHOTOS"])): ?>
            <div class="garage-photos">
                <?php
                foreach ($car["PHOTOS"] as $photoSrc): ?>
                    <img src="<?= htmlspecialchars($photoSrc) ?>" alt="Фото машины" class="garage-photo">
                <?php
                endforeach; ?>
            </div>
        <?php
        endif; ?>

        <ul>
            <?php
            if ($car["BRAND"]): ?><li><strong>Бренд:</strong> <?= htmlspecialchars($car["BRAND"]) ?></li>
            <?php
            endif; ?>
            <?php
            if ($car["MODEL"]): ?><li><strong>Модель:</strong> <?= htmlspecialchars($car["MODEL"]) ?></li>
            <?php
            endif; ?>
            <?php
            if ($car["YEAR"]): ?><li><strong>Год:</strong> <?= htmlspecialchars($car["YEAR"]) ?></li>
            <?php
            endif; ?>
            <?php
            if ($car["COLOR"]): ?><li><strong>Цвет:</strong> <?= htmlspecialchars($car["COLOR"]) ?></li>
            <?php
            endif; ?>
            <?php
            if (isset($car["KILOMETERS"])): ?><li><strong>Пробег:</strong> <?= htmlspecialchars($car["KILOMETERS"]) ?> км</li>
            <?php
            endif; ?>
        </ul>

      </a>
    </div>
<?php
endforeach;
}else
{
    echo "Ваш гараж пока пустой";
}
?>
