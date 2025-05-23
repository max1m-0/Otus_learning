<?php

namespace Otus\Models;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\ORM\Fields\Validators\RegExpValidator,
    Bitrix\Main\ORM\Fields\Relations\Reference,
    Bitrix\Main\ORM\Fields\Relations\OneToMany,
    Bitrix\Main\Entity\Query\Join;
use Bitrix\Main\ORM\Fields\Validators\RangeValidator;

/**
 * Class HospitalClientsTable
 *
 * @package Models
 */
class HospitalClientsTable extends DataManager
{

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'hospital_clients';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            'id' => (new IntegerField('id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_ID_FIELD'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            'first_name' => (new StringField('first_name',
                [
                    'validation' => [__CLASS__, 'validateFirstName']
                ]
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_FIRST_NAME_FIELD')),
            'age' => (new IntegerField(
                'age',
                [
                    'validation' => [__CLASS__, 'validateAge']
                ]
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_AGE_FIELD')),

            'doctor_id' => (new IntegerField('doctor_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_DOCTOR_ID_FIELD')),
            'procedure_id' => (new IntegerField('procedure_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_PROCEDURE_ID_FIELD')),
            'contact_id' => (new IntegerField('contact_id',
                []
            ))->configureTitle(Loc::getMessage('CLIENTS_ENTITY_CONTACT_ID_FIELD')),

            (new Reference('CONTACT', \Bitrix\CRM\ContactTable::class,
                Join::on('this.contact_id', 'ref.ID')))
                ->configureJoinType('inner'),
        ];
    }

    /**
     * Returns validators for first_name field.
     *
     * @return array
     */
    public static function validateFirstName()
    {
        return [
            new LengthValidator(3, 50),
            new RegExpValidator("/^([а-яё]+|[a-z]+)$/ui")
        ];
    }

    /**
     * Returns validators for age field.
     * @return array
     * @throws ArgumentTypeException
     */

    public static function validateAge(){
        return [
            new RangeValidator(18, 100),
        ];
    }





}
