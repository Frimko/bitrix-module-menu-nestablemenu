<?php
/**
 * Created by PhpStorm.
 * User: frimko
 * Date: 13.10.2015
 * Time: 15:58
 */

IncludeModuleLangFile(__FILE__);

if($APPLICATION->GetGroupRight("form")>"D")
{
    $aMenu = array(
        "parent_menu" => "global_menu_services",
        /*"sort" => 100,*/
        "text" => GetMessage("FRIMKO_NESTABLEMENU_MENU_TITLE"),
        "title" => GetMessage("FRIMKO_NESTABLEMENU_MENU_MAIN"),
        "url" => "nestable_menu_show.php?lang=".LANG,
        "icon"=>"default_menu_icon",
        "page_icon"=>"default_page_icon",
        "items_id" => "menu_nestable",
        "more_url" => array(
            "nestable_menu_show.php"
        ),
        "items" => array()
    );

    return $aMenu;
}
return false;
?>
