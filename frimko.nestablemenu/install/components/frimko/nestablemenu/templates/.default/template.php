<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery"));
if ($this->__folder)
    $pathToTemplateFolder = $this->__folder;
else
    $pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
?>
<style>
    @font-face {
        font-family: 'PT Sans Narrow';
        font-style: normal;
        font-weight: 400;
        src: local('PT Sans Narrow'), local('PTSans-Narrow'), url(<?=$pathToTemplateFolder?>/font/font1.woff2) format('woff2');
    }
    @font-face {
        font-family: 'PT Sans Narrow';
        font-style: normal;
        font-weight: 700;
        src: local('PT Sans Narrow Bold'), local('PTSans-NarrowBold'), url(<?=$pathToTemplateFolder?>/font/font2.woff2) format('woff2');
    }
</style>

<?
$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/sm-blue.css");
$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.smartmenus.js");
?>



<? if (!empty($arResult)): ?>
    <nav>
        <div class="smartnavmenu">
            <ul id="smartmenus" class="sm sm-vertical sm-blue">

                <?
                $previousLevel = 0;
                foreach ($arResult as $arItem): ?>

                <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
                    <?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
                <? endif ?>

                <? if ($arItem["IS_PARENT"]): ?>

                <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
                <li><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                    <ul>
                        <? else: ?>
                        <li><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                            <ul>
                                <? endif ?>

                                <? else:?>

                                    <? if ($arItem["PERMISSION"] > "D"):?>

                                        <? if ($arItem["DEPTH_LEVEL"] == 1):?>
                                            <li><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
                                        <? else:?>
                                            <li><a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
                                        <? endif ?>

                                    <? else:?>

                                        <? if ($arItem["DEPTH_LEVEL"] == 1):?>
                                            <li><a href=""
                                                   title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><?= $arItem["TEXT"] ?></a>
                                            </li>
                                        <? else:?>
                                            <li><a href=""
                                                   title="<?= GetMessage("MENU_ITEM_ACCESS_DENIED") ?>"><?= $arItem["TEXT"] ?></a>
                                            </li>
                                        <? endif ?>

                                    <? endif ?>

                                <? endif ?>

                                <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>

                                <? endforeach ?>

                                <? if ($previousLevel > 1)://close last item tags?>
                                    <?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
                                <? endif ?>

                            </ul>
        </div>
    </nav>
<?endif?>
                <style>
                    ul#smartmenus > a:before{
                        color: #FFF!important;
                    }

                    #smartmenus ul {
                        width: 12em;
                    }
                    div.smartnavmenu{
                        width: 19em;
                    }
                    .sm-blue a {
                        color: #FFF;
                </style>
                <script type="text/javascript">
                    $(function () {
                        $('#smartmenus').smartmenus({
                            mainMenuSubOffsetX: 1,
                            mainMenuSubOffsetY: -8,
                            subMenusSubOffsetX: 1,
                            subMenusSubOffsetY: -8
                        });
                    });
                </script>