<?php
namespace Otus\Homework\Crm;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class Handlers
{
    public static function updateTabs(Event $event): EventResult
    {
        $tabs = $event->getParameter('tabs');
        $entityTypeId = $event->getParameter('entityTypeID');
        $dealId = $event->getParameter('entityID');
        $tabs[] = [
            'id' => 'test',
            'name' => 'Тестовая вкладка',
            'loader' => [
                'serviceUrl' => '/bitrix/components/otus.homework/otus.grid/lazyload.ajax.php?&site=' . \SITE_ID . '&' . \bitrix_sessid_get() . '&ID=' . $dealId. '&TYPE_ID=' . $entityTypeId,
                'componentData' => [
                    'template' => '',
                ],
            ],
        ];
        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);
    }
}
