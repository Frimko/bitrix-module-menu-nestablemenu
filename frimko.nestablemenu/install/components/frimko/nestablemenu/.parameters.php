<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$site = ($_REQUEST["site"] <> ''? $_REQUEST["site"] : ($_REQUEST["src_site"] <> ''? $_REQUEST["src_site"] : false));
$arMenu = GetMenuTypes($site);

$arComponentParameters = array(
	"GROUPS" => array(
		"CACHE_SETTINGS" => array(
			"NAME" => GetMessage("COMP_GROUP_CACHE_SETTINGS"),
			"SORT" => 600
		),
	),
	"PARAMETERS" => array(

		"MENU_CACHE_TIME" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("COMP_PROP_CACHE_TIME"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => 3600,
			"COLS" => 5,
		),


	)
);
?>
