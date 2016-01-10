<?
/**
 * Company developer: frimko
 * Developer: samokhvalov
 * Site: NaN
 * E-mail: ccc-car@yandex.ru
 * @copyright (c) 2015-2015 frimko
 */

$module_id = 'frimko.nestablemenu';
use Bitrix\Frimko\Nestablemenu\SettingsMenuTable;

IncludeModuleLangFile(__FILE__);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/frimko.nestablemenu/include.php');

if ($REQUEST_METHOD=="GET" && $USER->IsAdmin() && strlen($RestoreDefaults)>0 && check_bitrix_sessid()) {
    SettingsMenuTable::update('1', array(
        'DATA' => '[{"id":1,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":2,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":1,"text":"'.GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT").'","link":"link","additional_links":"","params":"","permission":"","hide":""}]}]}]'
    ));

}
if($REQUEST_METHOD=="POST" && $USER->IsAdmin() && strlen($Update)>0  && check_bitrix_sessid())
{
    $result = SettingsMenuTable::update('1', array(
        'DATA' => $form_textarea_nestable_output_change
    ));
}


$aTabs = array(
    array(
        'DIV' => 'edit1',
        'TAB' => GetMessage('MAIN_TAB_SET'),
        'TITLE' => GetMessage('MAIN_TAB_TITLE_SET')),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);?>

<form method='POST' action='<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>'>
<?
echo bitrix_sessid_post();
$tabControl->Begin();

$tabControl->BeginNextTab();


$result = SettingsMenuTable::getList(array(
    'select'  => array('NAME','DATA'),
));
$row = $result->fetch();

$tableInBase='';

?>
<tr>
    <td valign='top' width='40%' class='field-name'>
        <label for='nestable_output_change'><?=GetMessage("FRIMKO_NESTABLEMENU_CODE_MENU");?></label>
    </td>
    <td valign='middle' width='60%'>
        <textarea id="nestable-output-change" style="width: 60%;font-size: 15px;"  name="form_textarea_nestable_output_change"><?=$row['DATA']?></textarea>
    </td>
</tr>


<?$tabControl->Buttons();?>
</form>
<script language='JavaScript'>
    function RestoreDefaults()
    {
        if(confirm('<?echo AddSlashes(GetMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING'))?>'))
            window.location = '<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>&<?=bitrix_sessid_get()?>';
    }
</script>
<input type='submit' <?if(!$USER->IsAdmin())echo ' disabled';?> name='Update' value='<?echo GetMessage('BUTTON_SAVE')?>' class="adm-btn-save">
<input type='reset' <?if(!$USER->IsAdmin())echo ' disabled';?> name='reset' value='<?echo GetMessage('BUTTON_RESET')?>' onClick = 'window.location.reload()'>
<input type='button' <?if(!$USER->IsAdmin())echo ' disabled';?> title='<?echo GetMessage('BUTTON_DEF')?>' OnClick='RestoreDefaults();' value='<?echo GetMessage('BUTTON_DEF')?>'>

<?$tabControl->EndTab();
$tabControl->End();


?>
