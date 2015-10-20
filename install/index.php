<?
/**
 * Company developer: frimko
 * Developer: samokhvalov Sergey
 * Site: NaN
 * E-mail: ccc-car@yandex.ru
 * @copyright (c) 2015-2015 frimko
 */

global $MESS;

$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class frimko_Nestablemenu extends CModule
{
    var $MODULE_ID = "frimko.nestablemenu";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function frimko_Nestablemenu()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = GetMessage("FRIMKO_NESTABLEMENU_REG_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("FRIMKO_NESTABLEMENU_REG_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = "Frimko";
        $this->PARTNER_URI = "mailto:ccc-car@yandex.ru";
    }

    function InstallFiles($arParams = array())
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/css", $_SERVER["DOCUMENT_ROOT"]."/bitrix/css/frimko.nestablemenu", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/frimko.nestablemenu", true, true);
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        DeleteDirFilesEx("/bitrix/components/frimko");
        DeleteDirFilesEx("/bitrix/css/frimko.nestablemenu");
        DeleteDirFilesEx("/bitrix/js/frimko.nestablemenu");
        return true;
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        $this->InstallDB();
        RegisterModule("frimko.nestablemenu");
        $GLOBALS["errors"] = $this->errors;
        $APPLICATION->IncludeAdminFile(GetMessage("FRIMKO_FEEDBACK_REG_INSTALL_TITLE"), $DOCUMENT_ROOT . "/bitrix/modules/frimko.nestablemenu/install/step.php");
    }

    function DoUninstall()
    {
        global $APPLICATION, $step;
        $step = IntVal($step);
        if($step<2)
        {
            $APPLICATION->IncludeAdminFile(GetMessage("FRIMKO_NESTABLEMENU_REG_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/unstep.php");
        }
        elseif($step==2)
        {
            $this->UnInstallDB();
            $this->UnInstallFiles();
            //$this->UnInstallEvents();
            UnRegisterModule("frimko.nestablemenu");
            $APPLICATION->IncludeAdminFile(GetMessage("FRIMKO_NESTABLEMENU_REG_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/frimko.nestablemenu/install/unstep2.php");
        }
    }
    function UnInstallDB(){
        if(check_bitrix_sessid()&&($_GET['dbUninstall']=='on')){
        $sql='DROP TABLE `frimko_nestablemenu`';
        $connection = Bitrix\Main\Application::getConnection();
        $connection->query($sql);
        }
    }
    function InstallDB(){
        global $DB;
        if(!$DB->Query("SELECT 'ID' FROM frimko_nestablemenu", true)) {
            $sql = "CREATE TABLE `frimko_nestablemenu` (`ID` int NOT NULL AUTO_INCREMENT, `NAME` varchar(255) NOT NULL, `DATA` text NOT NULL, PRIMARY KEY(`ID`))";
            $name = 'new_menu';
            $data = '[{"id":1,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":2,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":1,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"","hide":""}]}]}]';
            $sql2 = "INSERT INTO `frimko_nestablemenu` (`NAME`, `DATA`) VALUES ('" . $name . "', '" . $data . "'); ";
            $connection = Bitrix\Main\Application::getConnection();
            $connection->query($sql);
            $connection->query($sql2);
        }
    }

}

?>