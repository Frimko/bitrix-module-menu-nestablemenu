<?php
/**
 * Created by PhpStorm.
 * User: frimko
 * Date: 11.10.2015
 * Time: 14:54
 */

namespace frimko\nestablemenu;
use Bitrix\Main\Entity;


class CreateTable extends Entity\DataManager
{


    public static function getTableName()
    {
        return 'frimko_nestablemenu';
    }

    public static function getMap()
    {
        return array(
            new Entity\IntegerField('ID',array(
                'primary' => true,
                'autoincrement' => true,
                'autocomplete' => true
            )),
            new Entity\StringField('NAME'),
            new Entity\TextField('DATA'),
        );
    }

}