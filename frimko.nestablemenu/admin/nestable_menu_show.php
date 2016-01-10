<?


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/frimko.nestablemenu/include.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/frimko.nestablemenu/prolog.php");
use Bitrix\Frimko\Nestablemenu\SettingsMenuTable;

IncludeModuleLangFile(__FILE__);

/*require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");*/


$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));


// ******************************************************************** //
//                ОБРАБОТКА ИЗМЕНЕНИЙ ФОРМЫ                             //
// ******************************************************************** //
$ID = intval($ID);
$message = null;
$bVarsFromForm = false;
$strError=null;

if(
    $REQUEST_METHOD == "POST" // проверка метода вызова страницы
    &&
    ($save!="" || $apply!="") // проверка нажатия кнопок "Сохранить" и "Применить"
    &&
    $POST_RIGHT=="W"          // проверка наличия прав на запись для модуля
    &&
    check_bitrix_sessid()     // проверка идентификатора сессии
)
{


    // обработка данных формы
    $arFields = Array(
        "FORM_TEXTAREA_NESTABLE_OUTPUT" => $form_textarea_nestable_output_change
    );
    if ($form_textarea_nestable_output_change == '') $strError = GetMessage("FRIMKO_NESTABLEMENU_EMPTY");


    if (!$strError) {
        $result = SettingsMenuTable::update('1', array(
            'DATA' => $form_textarea_nestable_output_change
        ));

        if ($result->isSuccess()) {
            $message=GetMessage("FRIMKO_NESTABLEMENU_SUCCESS");
        }
    }
    //$send_first=unserialize($form_textarea_nestable_output_first);
    $send_change = json_decode($form_textarea_nestable_output_change);


/*



    function obToArr($item, $key,&$array){
        $array[]= array($key=>$item);
    }


    function uncode($item){
        return unserialize(json_decode(htmlspecialchars_decode($item)));
    }

    CModule::IncludeModule('fileman');


    foreach ($send_change as $row ) {
        $arTopMenu[]=array(
            uncode($row->text),
            uncode($row->link),
            uncode($row->additional_links),
            uncode($row->params),
            uncode($row->permission)
        );
    }
    $site = "s1";
    $menufilename='/.top.menu.php';
    $sMenuTemplateTmp='';
    $aMenuLinksTmp= $arTopMenu;*/
    //CFileMan::SaveMenu(Array($site, $menufilename), $aMenuLinksTmp, $sMenuTemplateTmp);
/*


    function setMenuRecursive($arChildMenu=array(),$path=false){
        foreach ($arChildMenu as $row) {
            if ($row->children){
                $r=array();
                foreach ($row->children as $row2) {
                $r[]=array(
                    uncode($row2->text),
                    uncode($row2->link),
                    uncode($row2->additional_links),
                    uncode($row2->params),
                    uncode($row2->permission)
                );
                }
                $site = "s1";
                $menufilename = uncode($row->link).'.left.menu.php';
                $sMenuTemplateTmp ='';
                $aMenuLinksTmp = $r;
                global $arMenu;
                $arMenu[]=Array(Array($site, $menufilename), $aMenuLinksTmp, $sMenuTemplateTmp);
                //CFileMan::SaveMenu(Array($site, $menufilename), $aMenuLinksTmp, $sMenuTemplateTmp);
                setMenuRecursive($row->children);
            }
        }
        return $arMenu;
    }
    $bb = setMenuRecursive($send_change);*/


}
// ******************************************************************** //
//                КОНЕЦ ОБРАБОТКИ ИЗМЕНЕНИЙ ФОРМЫ                       //
// ******************************************************************** //

$APPLICATION->SetTitle(GetMessage("FRIMKO_NESTABLEMENU_TITLE"));
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// если есть сообщения об ошибках или об успешном сохранении - выведем их.

if($strError)
{
    $aMsg = array();
    $arrErr = explode("<br />",$strError);
    reset($arrErr);
    while (list(,$err)=each($arrErr)) $aMsg[]['text']=$err;

    $e = new CAdminException($aMsg);
    $GLOBALS["APPLICATION"]->ThrowException($e);
    $messageErr = new CAdminMessage(GetMessage("FRIMKO_NESTABLEMENU_ERROR"), $e);
    echo $messageErr->Show();
}
if($message) {
    CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("FRIMKO_NESTABLEMENU_SUCCESS"), "TYPE"=>"OK"));
}

/*сформируем список закладок*/

$aTabs = array(
    array('DIV' => 'edit1', 'TAB' => GetMessage("FRIMKO_NESTABLEMENU_TAB"), 'TITLE' =>GetMessage("FRIMKO_NESTABLEMENU_TAB_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>

<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">

<?
/*проверка идентификатора сессии*/
echo bitrix_sessid_post();

$tabControl->Begin();

$tabControl->BeginNextTab();

// информационная подсказка
echo BeginNote();?>

<span class="required">*</span><?echo GetMessage("FRIMKO_NESTABLEMENU_INFO")?>

<?echo EndNote();
/*грузим внутренности */
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/frimko.nestablemenu/admin/template.php");

$tabControl->Buttons(
    array(
        "disabled"=>($POST_RIGHT<"W"),
        "back_url"=>"/bitrix/admin",
    )
);

$tabControl->EndTab();


?>


<?  require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php"); ?>