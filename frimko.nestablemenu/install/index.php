<?php
/**
 * Created by PhpStorm.
 * User: Ranerg
 * Date: 27.08.2015
 * Time: 14:52
 */

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Frimko\Nestablemenu\SettingsMenuTable;

Loc::loadMessages(__FILE__);
if(class_exists('frimko_nestablemenu')) return;

class frimko_nestablemenu extends CModule
{
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public $PATCH;
    public function __construct()
    {
        require("/version.php");
        $this->MODULE_ID = "frimko.nestablemenu";
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = getMessage("FRIMKO_NESTABLEMENU_REG_MODULE_NAME");
        $this->MODULE_DESCRIPTION = getMessage("FRIMKO_NESTABLEMENU_REG_MODULE_DESCRIPTION");;
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = "Frimko";
        $this->PARTNER_URI = "mailto:ccc-car@yandex.ru";
        $this->PATCH = $_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID;
    }

    public function DoInstall()
    {
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallDB();
    }

    public function DoUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    function InstallFiles()
    {
        CopyDirFiles($this->PATCH."/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        CopyDirFiles($this->PATCH."/install/css", $_SERVER["DOCUMENT_ROOT"]."/bitrix/css/frimko.nestablemenu", true, true);
        CopyDirFiles($this->PATCH."/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/frimko.nestablemenu", true, true);
        CopyDirFiles($this->PATCH."/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/", true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($this->PATCH."/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        DeleteDirFilesEx("/bitrix/components/frimko");
        DeleteDirFilesEx("/bitrix/css/frimko.nestablemenu");
        DeleteDirFilesEx("/bitrix/js/frimko.nestablemenu");
        return true;
    }

    public function installDB()
    {
        if(Loader::includeModule($this->MODULE_ID))
        {
            SettingsMenuTable::getEntity()->createDbTable();
            $name = 'new_menu';
            $site = '1';
            $data = '[{"id":1,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":2,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":1,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"","hide":""}]}]}]';
            SettingsMenuTable::add(array(
                'NAME'=>$name,
                'SITE'=>$site,
                'DATA'=>$data,
            ));
        }
    }

    public function uninstallDB()
    {
        if(Loader::includeModule($this->MODULE_ID))
        {
            $connection = Application::getInstance()->getConnection();
            if(SettingsMenuTable::getList()) {
                $connection->dropTable(SettingsMenuTable::getTableName());
            }
        }
    }
}