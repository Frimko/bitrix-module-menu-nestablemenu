<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $CACHE_MANAGER;
use Bitrix\Frimko\Nestablemenu\SettingsMenuTable;

if($USER->IsAuthorized()) {
    $buttonID = "nestablemenu";
    $menu_edit_url = "/bitrix/admin/nestable_menu_show.php?lang=" . LANGUAGE_ID .
        "&site=" . SITE_ID . "&back_url=" . urlencode($_SERVER["REQUEST_URI"]);

    $this->AddIncludeAreaIcons(array(
        "URL" => $menu_edit_url,
        "ICON" => "bx-context-toolbar-edit-icon",
        "TITLE" => GetMessage("MAIN_MENU_EDIT"),
        "DEFAULT" => true,
    ));
    $APPLICATION->AddPanelButton(array(
        "HREF" => $menu_edit_url,
        "ID" => $buttonID,
        "ICON" => "bx-panel-menu-icon",
        "ALT" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_ALT'),
        "TEXT" => GetMessage("MAIN_MENU_TOP_PANEL_BUTTON_TEXT"),
        "MAIN_SORT" => "300",
        "SORT" => 10,
        "RESORT_MENU" => true,
        "HINT" => array(
            "TITLE" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_TEXT'),
            "TEXT" => GetMessage('MAIN_MENU_TOP_PANEL_BUTTON_HINT'),
        )
    ));
}

$cache_id = md5(serialize($arParams));
$cache_dir = "/".SITE_ID."/frimko/nestablemenu";
$obCache = new CPHPCache;
if ($obCache->InitCache($arParams['MENU_CACHE_TIME'], $cache_id, $cache_dir)) {
    $arResult = $obCache->GetVars();
} elseif (CModule::IncludeModule('frimko.nestablemenu') && $obCache->StartDataCache()) {

    $result = SettingsMenuTable::getList(array(
        'select'  => array('NAME','DATA')
    ));
    $row = $result->fetch();
    $menu = new CNestablemenuComponent;
    $menu->constructMenu(
        json_decode($row['DATA'])
    );
    $arResult = $menu->arFullMenu;
    $obCache->EndDataCache($arResult);
}

$this->IncludeComponentTemplate();

