<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

/**
 * Company developer: frimko
 * Developer: samokhvalov Sergey
 * Site: NaN
 * E-mail: ccc-car@yandex.ru
 * @copyright (c) 2015-2015 frimko
 */
use Bitrix\Main\Loader;
use Bitrix\Frimko\Nestablemenu\SettingsMenuTable;


Loader::registerAutoLoadClasses("frimko.nestablemenu", array(
    'Bitrix\Frimko\Nestablemenu\SettingsMenuTable' => 'lib/SettingsMenuTable.php',
    "CNestablemenu" => "classes/CNestablemenu.php",
));