<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Web38\Nestablemenu\SettingsMenuTable;

class CNestablemenu extends CBitrixComponent
{
    public $arFullMenu=array();

	public function onPrepareComponentParams($arParams)
	{
		$arParams["CACHE_TYPE"] = $arParams["MENU_CACHE_TYPE"];
		$arParams["CACHE_TIME"] = $arParams["MENU_CACHE_TIME"];
		return $arParams;
	}

	public function getCacheID($additionalCacheID = false)
	{
		global $APPLICATION;
		global $USER;

		$strCacheID = "";
		if($this->arParams["MENU_CACHE_TIME"])
		{
			if($this->arParams["CACHE_SELECTED_ITEMS"])
				$strCacheID = $APPLICATION->GetCurPage();
			else
				$strCacheID = "";
			$strCacheID .=
				":".$this->arParams["USE_EXT"].
				":".$this->arParams["MAX_LEVEL"].
				":".$this->arParams["ROOT_MENU_TYPE"].
				":".$this->arParams["CHILD_MENU_TYPE"].
				":".LANGUAGE_ID.
				":".SITE_ID.
				""
			;

			if($this->arParams["MENU_CACHE_USE_GROUPS"] === "Y")
				$strCacheID .= ":".$USER->GetGroups();

			if($this->arParams["MENU_CACHE_USE_USERS"] === "Y")
				$strCacheID .= ":".$USER->GetID();

			if(is_array($this->arParams["MENU_CACHE_GET_VARS"]))
			{
				foreach($this->arParams["MENU_CACHE_GET_VARS"] as $name)
				{
					$name = trim($name);
					if($name != "" && array_key_exists($name, $_GET))
						$strCacheID .= ":".$name."=".$_GET[$name];
				}
			}

			$strCacheID = md5($strCacheID);
		}

		return $strCacheID;
	}

	public function getGenerationCachePath($id)
	{
		$hash = md5($id);
		$path = $this->getRelativePath()."/".substr($hash,-5,2)."/".substr($hash,-3);
		return $path;
	}

    /*создание массива для меню из оригинального меню*/
	public function getSiteMenuRecursive($arChildMenu=array(),$arFullMenu=false,$copy=false,$level=1){
        global $arFullMenu;
        foreach ($arChildMenu as $row) {
            $obChildren = new CMenu("left");
            $obChildren->Init($row[1],false,false,true);
            $arr=$obChildren->arMenu;
            if ($arr){
                $r=array(
                    'TEXT'=>$row[0],
                    'LINK'=>$row[1],
                    'ADDITIONAL_LINKS'=>$row[2],
                    'PARAMS'=>$row[3],
                    'PERMISSION'=>$row[4],
                    'IS_PARENT'=>true,
                    'DEPTH_LEVEL'=>$level,
                );
                $arFullMenu[]= $r;
                if($copy!=$row[1]) {
                    $copy = $row[1];
                    $this->getSiteMenuRecursive($arr, $arFullMenu, $copy,$level+1);
                }
                else{
                    $arFullMenu[count($arFullMenu)-1]['IS_PARENT']=false;
                }
            }
            else{
                $r=array(
                    'TEXT'=>$row[0],
                    'LINK'=>$row[1],
                    'ADDITIONAL_LINKS'=>$row[2],
                    'PARAMS'=>$row[3],
                    'PERMISSION'=>$row[4],
                    'IS_PARENT'=>false,
                    'DEPTH_LEVEL'=>$level
                );
                $arFullMenu[]= $r;
            }
        }
        return $arFullMenu;
    }

	public function getMenuString($type = "left")
	{

	}

	public function setSelectedItems($bMultiSelect = false)
    {

    }

    public static function compileTable(){
        $sql = SettingsMenuTable::getEntity()->compileDbTableStructureDump();
        $connection = Bitrix\Main\Application::getConnection();
        $connection->query($sql[0]);
        //frimko\nestablemenu\CreateTable::delete(1);
        $result = SettingsMenuTable::add(array(
            'NAME' => 'new_menu',
            'DATA' => '[{"id":1,"text":"Новый элемент","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":2,"text":"Новый элемент","link":"link","additional_links":"","params":"","permission":"R","hide":"","children":[{"id":1,"text":"Новый элемент","link":"link","additional_links":"","params":"","permission":"","hide":""}]}]}]'
        ));
        return $result->isSuccess();
    }

    public function uncode($item){
        return htmlspecialchars_decode($item);
    }

    /*надо же както все взятое из базы преобразовать*/
    public function constructMenu($arChildMenu=array(),$FullMenu=false,$copy=false,$level=1){
        foreach ($arChildMenu as $row) {
            $r=array();
            if ($row->children){
                $r=array(
                    'TEXT'=>$this->uncode($row->text),
                    'LINK'=>$this->uncode($row->link),
                    'ADDITIONAL_LINKS'=>$this->uncode($row->additional_links),
                    'PARAMS'=>$this->uncode($row->params),
                    'PERMISSION'=>"R",
                    'IS_PARENT'=>true,
                    'DEPTH_LEVEL'=>$level,
                    'HIDE' => $this->uncode($row->hide),
                );
                $this->arFullMenu[]= $r;
                    if($copy!=$row->link) {
                        $copy = $row->link;
                        $this->constructMenu($row->children, $this->arFullMenu, $copy,$level+1);
                    }
                    else{
                        $this->arFullMenu[count($this->arFullMenu)-1]['IS_PARENT']=false;
                    }
            }
            else{
                $r=array(
                    'TEXT'=>$this->uncode($row->text),
                    'LINK'=>$this->uncode($row->link),
                    'ADDITIONAL_LINKS'=>$this->uncode($row->additional_links),
                    'PARAMS'=>$this->uncode($row->params),
                    'PERMISSION'=>"R",
                    'IS_PARENT'=>false,
                    'DEPTH_LEVEL'=>$level,
                    'HIDE' => $this->uncode($row->hide),
                );
                $this->arFullMenu[]= $r;
            }
        }
    }


}

