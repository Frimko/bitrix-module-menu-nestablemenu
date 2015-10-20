<?
/**
 * Company developer: frimko
 * Developer: samokhvalov Sergey
 * Site: NaN
 * E-mail: ccc-car@yandex.ru
 * @copyright (c) 2015-2015 frimko
 */
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?echo LANG?>">
    <input type="hidden" name="id" value="frimko.nestablemenu">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <?echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
    <div>
    <label for="dbUninstall" title=""><?=GetMessage("FRIMKO_NESTABLEMENU_REG_DROP_TABLE_DATABASE");?></label>
    <input type="checkbox" checked="" id="dbUninstall" name="dbUninstall">
    </div>
    <input type="submit" name="inst" value="<?echo GetMessage("MOD_UNINST_DEL")?>">
</form>
