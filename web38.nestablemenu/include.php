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
use Bitrix\Web38\Nestablemenu\SettingsMenuTable;


Loader::registerAutoLoadClasses("web38.nestablemenu", array(
    'Bitrix\Web38\Nestablemenu\SettingsMenuTable' => 'lib/SettingsMenuTable.php',
    "CNestablemenu" => "classes/CNestablemenu.php",
));