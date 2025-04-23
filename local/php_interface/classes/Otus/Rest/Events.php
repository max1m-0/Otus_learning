<?php

namespace Otus\Rest;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;
use InvalidArgumentException;
use Otus\Logger\OtusExceptionHandler;
use Otus\Models\HospitalClientsTable;

class Events
{
    /**
     * @return array[]
     */
    public static function OnRestServiceBuildDescriptionHandler(): array
    {
        Loc::getMessage('REST_SCOPE_OTUS.HOSPITALCLIENTS');
        return [
            'otus.hospitalclients' => [
                'otus.hospitalclients.create' => [__CLASS__, 'create'],
                'otus.hospitalclients.read' => [__CLASS__, 'read'],
                'otus.hospitalclients.update' => [__CLASS__, 'update'],
                'otus.hospitalclients.delete' => [__CLASS__, 'delete'],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public static function create($arParams, $navStart, \CRestServer $server): int|array|string|null
    {
        $data = self::validateData($arParams);
        if ($data)
        {
            $result = HospitalClientsTable::add($data);
            if ($result->isSuccess())
            {
                return $result->getId();
            }
            throw new InvalidArgumentException(implode(',',$result->getErrorMessages()));
        }

        return Loc::getMessage('EMPTY_REQUEST');
    }

    /**
     * @param $arParams
     * @return array
     */
    private static function validateData($arParams): array
    {
        $map = HospitalClientsTable::getMap();

        $data = [];
        foreach ($arParams['data'] as $key => $field)
        {
            if (isset($map[$key]) && !is_int($map[$key]))
            {
                $data[$key] = $field;
            }
        }

        return $data;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function read($arParams, $navStart, \CRestServer $server): array|string|null
    {
        $validatedParams = self::validate($arParams);
        if ($validatedParams)
        {
            return HospitalClientsTable::getList($validatedParams)->fetchAll();
        }

        throw new InvalidArgumentException(Loc::getMessage('EMPTY_REQUEST'));
    }

    /**
     * @throws Exception
     */
    public static function update($arParams, $navStart, \CRestServer $server): array|string|null
    {
        if (isset($arParams['primary']) && is_int($arParams['primary']))
        {
            $data = self::validateData($arParams);
            if ($data)
            {
                $result = HospitalClientsTable::update($arParams['primary'], $data);
                if ($result->isSuccess())
                {
                    return Loc::getMessage('SUCCESS_MESSAGE');
                }

                throw new InvalidArgumentException(implode(',',$result->getErrorMessages()));
            }
        }

        throw new InvalidArgumentException(Loc::getMessage('EMPTY_REQUEST'));
    }

    /**
     * @throws Exception
     */
    public static function delete($arParams, $navStart, \CRestServer $server): array|string|null
    {
        if (isset($arParams['primary']) && is_int($arParams['primary']))
        {
            $result = HospitalClientsTable::delete($arParams['primary']);
            if ($result->isSuccess())
            {
                return Loc::getMessage('SUCCESS_MESSAGE');
            }

            throw new InvalidArgumentException(implode(',',$result->getErrorMessages()));
        }
        throw new InvalidArgumentException(Loc::getMessage('EMPTY_REQUEST'));
    }

    /**
     * @var array|string[]
     */
    protected static array $allowedKeys = ['filter', 'select', 'order', 'limit', 'offset'];

    /**
     * @param array $params
     * @return array
     */
    public static function validate(array $params): array
    {
        $validated = [];
        foreach ($params as $key => $value)
        {
            if (!in_array($key, self::$allowedKeys, true))
            {
                throw new InvalidArgumentException(Loc::getMessage('REJECT_PARAM') . " $key");
            }

            switch ($key)
            {
                case 'filter':
                    if (!is_array($value))
                    {
                        throw new InvalidArgumentException(Loc::getMessage('REJECT_FILTER'));
                    }
                    $validated['filter'] = self::validateFilter($value);
                    break;

                case 'select':
                    if (!is_array($value))
                    {
                        throw new InvalidArgumentException(Loc::getMessage('REJECT_SELECT'));
                    }
                    $validated['select'] = array_map('strval', $value);
                    break;

                case 'order':
                    if (!is_array($value))
                    {
                        throw new InvalidArgumentException(Loc::getMessage('REJECT_ORDER'));
                    }
                    $validated['order'] = self::validateOrder($value);
                    break;

                case 'limit':
                    if (!is_int($value) || $value <= 0)
                    {
                        throw new InvalidArgumentException(Loc::getMessage('REJECT_LIMIT'));
                    }
                    $validated['limit'] = $value;
                    break;

                case 'offset':
                    if (!is_int($value) || $value < 0)
                    {
                        throw new InvalidArgumentException(Loc::getMessage('REJECT_OFFSET'));
                    }
                    $validated['offset'] = $value;
                    break;
            }
        }

        return $validated;
    }

    /**
     * @param array $filter
     * @return array
     */
    protected static function validateFilter(array $filter): array
    {
        foreach ($filter as $key => $value)
        {
            if (!is_string($key))
            {
                throw new InvalidArgumentException(Loc::getMessage('REJECT_FILTER_KEYS'));
            }
        }

        return $filter;
    }

    /**
     * @param array $order
     * @return array
     */
    protected static function validateOrder(array $order): array
    {
        foreach ($order as $field => $direction)
        {
            if (!is_string($field) || !in_array(strtoupper($direction), ['ASC', 'DESC'], true))
            {
                throw new InvalidArgumentException(Loc::getMessage('REJECT_ORDER_PARAMS'));
            }
        }

        return $order;
    }
}