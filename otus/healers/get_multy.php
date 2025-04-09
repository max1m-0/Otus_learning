<?php

use Otus\Models\Lists\HealersPropertyValuesTable;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
try
{
    $healers = \Bitrix\Iblock\Elements\ElementhealersTable::query()
        ->setSelect(
            [
                "*",
                "PROCEDURE_ID.ELEMENT",
            ],
        )
//    ->registerRuntimeField(
//        null,
//        new Bitrix\Main\Entity\ReferenceField(
//            "PROCEDURE",
//            \Otus\Models\Lists\ProceduresPropertyValuesTable::getEntity(),
//            ["=this.PROCEDURE_ID"=>"ref.IBLOCK_ELEMENT_ID"]
//        ))
        ->fetchCollection();
    ?>
    <div class="container">
        <div class="row landing-block-inner">
            <div
                class="landing-block-card-header text-uppercase u-heading-v2-4--bottom text-left g-mb-20 g-border-color g-border-color--hover"
                style="--border-color: #04a9e8;--border-color--hover: #04a9e8;">
                <h6 class="landing-block-node-subtitle g-font-weight-700 g-letter-spacing-1 g-color-primary g-mb-20">
                    <br></h6>
                <h2 class="landing-block-node-title h1 u-heading-v2__title g-line-height-1_3 g-font-weight-700 g-text-break-word g-color"
                    style="--color: #ffffff;">Список врачей</h2>
            </div>
            <?php
            foreach ($healers

                     as $healer)
            {
                foreach ($healer->getProcedureId()->getAll() as $item)
                {
                    ?>

                    <div class="landing-block-node-text g-color" style="--color: #ffffff;">
                        <a href="index.php"><?= $item->getElement()->getName() ?><br>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}
catch (Exception $exception)
{
    print_r($exception);
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');