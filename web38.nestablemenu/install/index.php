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
use Bitrix\Web38\Nestablemenu\SettingsMenuTable;

Loc::loadMessages(__FILE__);
if(class_exists('web38_nestablemenu')) return;

class web38_nestablemenu extends CModule
{
    var $MODULE_ID = "web38.nestablemenu";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public $PATH;

    public function __construct()
    {
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");


        $this->MODULE_ID = "web38.nestablemenu";
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = getMessage("FRIMKO_NESTABLEMENU_REG_MODULE_NAME");
        $this->MODULE_DESCRIPTION = getMessage("FRIMKO_NESTABLEMENU_REG_MODULE_DESCRIPTION");;
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = "frimko";
        $this->PARTNER_URI = "mailto:ccc-car@yandex.ru";
        $this->PATH = $_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID;
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
        $this->UnInstallFiles();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    function InstallFiles()
    {
        CopyDirFiles($this->PATH."/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        CopyDirFiles($this->PATH."/install/css", $_SERVER["DOCUMENT_ROOT"]."/bitrix/css/".$this->MODULE_ID, true, true);
        CopyDirFiles($this->PATH."/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/".$this->MODULE_ID, true, true);
        CopyDirFiles($this->PATH."/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/", true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($this->PATH."/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        DeleteDirFilesEx("/bitrix/components/web38");
        DeleteDirFilesEx("/bitrix/css/".$this->MODULE_ID);
        DeleteDirFilesEx("/bitrix/js/".$this->MODULE_ID);
        return true;
    }

    public function installDB()
    {
        if(Loader::includeModule($this->MODULE_ID))
        {
            SettingsMenuTable::getEntity()->createDbTable();
            $name = 'new_menu';
            $data = '[{"id":1,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":2,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":1,"text":"'.getMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"","hide":""}]}]}]';
            SettingsMenuTable::add(array(
                'NAME'=>$name,
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