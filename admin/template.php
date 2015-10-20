<?
/*все что нам надо для красоты*/
$APPLICATION->AddHeadScript('/bitrix/js/frimko.nestablemenu/jquery-2.1.4.min.js');
$APPLICATION->AddHeadScript('/bitrix/js/frimko.nestablemenu/jquery.nestable.js');
$APPLICATION->SetAdditionalCSS("/bitrix/css/frimko.nestablemenu/style.css");



$result = frimko\nestablemenu\CreateTable::getList(array(
    'select'  => array('NAME','DATA')
));
$row = $result->fetch();
$menu = new CNestablemenu;
$menu->constructMenu(
    json_decode($row['DATA'])
);
$arFullMenu = $menu->arFullMenu;
//CNestablemenu::compileTable();
?>
<!--<pre>
    <?/*var_dump($arFullMenu);*/?>
</pre>
-->
<?
/*выгружает реальное меню, но...*/
/*$obParents = new CMenu("top");
$obFullmenu = new CNestablemenu;
$obParents->Init($APPLICATION->GetCurDir());
$arParentMenu = $obParents->arMenu;
$arFullMenu = $obFullmenu->getSiteMenuRecursive($arParentMenu);

/*получим менюшку сайта*/
function incode($item){
    return htmlspecialchars($item);
}
function template_li($id,$item){
    if($item['HIDE'])
        $hide['2']=$item['HIDE'];
    else
        $hide['1']='disabled';
    $title=$item['TEXT']." \"".$item['LINK']."\"";

    return '
    <li class="dd-item"
data-id="'.$id.'"
data-text="'.incode($item['TEXT']).'"
data-link="'.incode($item['LINK']).'"
data-additional_links="'.incode($item['ADDITIONAL_LINKS']).'"
data-params="'.incode($item['PARAMS']).'"
data-permission="'.incode($item['PERMISSION']).'"
data-hide="'.incode($item['HIDE']).'"
>
<div title="'.htmlspecialchars($title).'" class="drag-btn dd-handle">
<div class="dd-dragel drag-btn text-group">
<div class="drag-btn name-item"><span>'.$item['TEXT'].'</span></div>
<div class="drag-btn url-item"><span>'.$item['LINK'].'</span></div>
</div>
<div class="noDrag-btn btn-group">
    <div class="btn-default" title="'.GetMessage("FRIMKO_NESTABLEMENU_ADD_ELEMENT").'"><span class="glyphicon glyphicon-plus"></span></div>
    <div class="btn-default" title="'.GetMessage("FRIMKO_NESTABLEMENU_EDIT_ELEMENT").'"><span class="glyphicon glyphicon-pencil"></span></div>
    <div class="btn-default '.incode($hide['1']).' " title="'.GetMessage("FRIMKO_NESTABLEMENU_SHOW_ELEMENT").'"><span class="glyphicon glyphicon-eye-open"></span></div>
    <div class="btn-default '.incode($hide['2']).' " title="'.GetMessage("FRIMKO_NESTABLEMENU_HIDE_ELEMENT").'"><span class="glyphicon glyphicon-eye-close"></span></div>
    <div class="btn-default" title="'.GetMessage("FRIMKO_NESTABLEMENU_DELETE_ELEMENT").'"><span class="glyphicon glyphicon-trash"></span></div>
</div>
</div>';

}
?>

<menu id="nestable-menu">
    <button class="adm-btn-save" data-action="expand-all" type="button"><?=GetMessage("FRIMKO_NESTABLEMENU_EXPAND_ALL");?></button>
    <button class="adm-btn-save" data-action="collapse-all" type="button"><?=GetMessage("FRIMKO_NESTABLEMENU_COLLAPSE_ALL");?></button>
</menu>
<div class="cf nestable-lists">
    <div class="dd" id="nestable">
        <ol class="dd-list">
            <?
            $flagParent = true;
            $previousLevel = 0;
            $id_li = 0;
            ?>

            <? foreach ($arFullMenu as $arItem): ?>
            <? $id_li++; ?>

            <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
                <?= str_repeat("</ol></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
            <? endif ?>

            <? if ($arItem["IS_PARENT"]): ?>
            <? print template_li($id_li, $arItem); ?>
            <ol class="dd-list">
                <? else:?>
                <?print template_li($id_li,$arItem);?>
                </li>
            <? endif ?>
                <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

                <? endforeach ?>

                <? if ($previousLevel > 1): ?>
                    <?= str_repeat("</ol></li>", ($previousLevel - 1)); ?>
                <? endif ?>
            </ol>
    </div>
</div>

<?

/*echo CForm::GetTextAreaField('nestable_output_first','', '', 'id="nestable-output-first"', serialize($arFullMenu));*/

?>
<textarea id="nestable-output-change"  name="form_textarea_nestable_output_change"></textarea>
<style>
    #nestable-menu{
        position: relative;
        left: 100px;
        width: 300px;
    }
    #nestable-output-first,
    #nestable-output-change {
        width: 70%;
        height: 10em;
        font-size: 15px;
        line-height: 1.333333em;
        font-family: Consolas, monospace;
        padding: 5px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        display: none;


    }

</style>
<script>

    $(document).ready(function()
    {
        var NameItem,UrlItem;
        $('#nestable').on('click', '.btn-default, .btn-Form', function () {
            if ($(this).children().hasClass('glyphicon-plus')) {
                var longString ='<?
                    $id=1;
                    $item=array(
                    'TEXT'=>GetMessage("FRIMKO_NESTABLEMENU_NEW_ELEMENT"),
                    'LINK'=>'link',
                    'ADDITIONAL_LINKS'=>Array(),
                    'PARAMS'=>Array(),
                    'PERMISSION'=>''
                    );
                    print str_replace(array("\r", "\n"),'',template_li($id,$item));
                    ?>';

                $(this).parents('li.dd-item:first').after(longString);
            }
            /*delete buton*/
            if (($(this).children().hasClass('glyphicon-trash')) && (confirm("<?GetMessage("FRIMKO_NESTABLEMENU_UDALITQ");?>"))) {
                $(this).parents('li.dd-item:first').remove();
            }
            /*show buton*/
            if ($(this).children().hasClass('glyphicon-eye-open')) {
                if(!$(this).hasClass('disabled')) {
                    $(this).addClass('disabled');
                    $(this).parents('li.dd-item:first').find('.glyphicon-eye-close').parent().removeClass('disabled');
                    $(this).parents('li.dd-item:first').attr("data-hide", "").data("hide", "");;
                }
            }
            /*hide buton*/
            if ($(this).children().hasClass('glyphicon-eye-close')) {
                if(!$(this).hasClass('disabled')) {
                    $(this).addClass('disabled');
                    $(this).parents('li.dd-item:first').find('.glyphicon-eye-open').parent().removeClass('disabled');
                    $(this).parents('li.dd-item:first').attr("data-hide", "disabled").data("hide", "disabled");;
                }
            }
            /*button edit*/
            if ($(this).children().hasClass('glyphicon-pencil')) {
                NameItem = $(this).parents('li.dd-item:first').find('.text-group div.name-item span:first').text();
                UrlItem = $(this).parents('li.dd-item:first').find('.text-group div.url-item span:first').text();
                var EditorForm =
                    '<div class="noDrag-btn input-Form-group">' +
                    '<input type="text" class="text-input form-control" placeholder="Name" value="' + NameItem.trim() + '"> ' +
                    '<input type="text" class="url-input form-control" placeholder="URL" value="' + UrlItem.trim() + '"> ' +
                    '<div class="btn-group-form" style=""> ' +
                    '<div class="btn-Form" ><span class="glyphicon glyphicon-ok"></span></div> ' +
                    '<div class="btn-Form" ><span class="glyphicon glyphicon-remove"></span></div> ' +
                    '</div> ' +
                    '</div>'+
                    '<div style="background: rgba(248,80,50,1);' +
                    'border-radius: 4px;position: absolute;left:0px; top:0px; width: 100%;height: 34px;opacity: 0.5; z-index: 3;"></div>';
                $(this).parents('li.dd-item:first').find('.text-group:first').removeClass('dd-dragel').html(EditorForm);
            }

            /*form 'ok'*/
            if ($(this).children().hasClass('glyphicon-ok')) {
                var NameItem2 = $(this).parents('li.dd-item:first').find('.text-group div .text-input').val();
                var UrlItem2 = $(this).parents('li.dd-item:first').find('.text-group div .url-input').val();
                var TextItems =
                    '<div class="drag-btn name-item"><span>' + NameItem2.trim() + '</span></div> ' +
                    '<div class="drag-btn url-item"><span>' + UrlItem2.trim() + '</span></div>';
                $(this).parents('li.dd-item:first')
                    .find('.text-group:first')
                    .addClass('dd-dragel')
                    .html(TextItems)
                    .parents('li.dd-item:first')
                    .attr("data-text", NameItem2.trim())
                    .data("text", NameItem2.trim())
                    .attr("data-link", UrlItem2.trim())
                    .data("link", UrlItem2.trim());
            }

            /*form 'cancel'*/
            if ($(this).children().hasClass('glyphicon-remove')) {
                var TextItemsNot =
                    '<div class="drag-btn name-item"><span>' + NameItem.trim() + '</span></div> ' +
                    '<div class="drag-btn url-item"><span>' + UrlItem.trim() + '</span></div>';
                $(this).parents('li.dd-item:first').find('.text-group:first').addClass('dd-dragel').html(TextItemsNot);
            }
            updateOutput($('#nestable').data('output', $('#nestable-output-change')));
        });



        var updateOutput = function(e)
        {
            var list   = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));
            } else {
                output.val('JSON browser support required for this script.');
            }
        };

        // activate Nestable for list 1
        $('#nestable').nestable({
            listNodeName: 'ol',
            itemNodeName: 'li',
            rootClass: 'dd',
            listClass: 'dd-list',
            itemClass: 'dd-item',
            dragClass: 'dd-dragel',
            handleClass: 'drag-btn',
            collapsedClass: 'dd-collapsed',
            placeClass: 'dd-placeholder',
            noDragClass: 'noDrag-btn',
            emptyClass: 'dd-empty',
            expandBtnHTML: '<button data-action="expand" type="button">Expand</button>',
            collapseBtnHTML: '<button data-action="collapse" type="button">Collapse</button>',
            group: 1,
            maxDepth: 5,
            threshold: 10
        })
            .on('change', updateOutput);

        // output initial serialised data


        updateOutput($('#nestable').data('output', $('#nestable-output-change')));


        $('#nestable-menu').on('click', function(e)
        {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });

        $('.dd').nestable('collapseAll');

    });
</script>