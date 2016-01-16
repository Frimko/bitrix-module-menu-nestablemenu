<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

class CNestablemenuComponent extends CBitrixComponent
{
    public $arFullMenu=array();
    public function uncode($item){
        return htmlspecialchars_decode($item);
    }

    public function constructMenu($arChildMenu=array(),$FullMenu=false,$copy=false,$level=1)
    {
        foreach ($arChildMenu as $row) {
            $r = array();
            if (!$row->hide) {
                if ($row->children) {
                    $r = array(
                        'TEXT' => $this->uncode($row->text),
                        'LINK' => $this->uncode($row->link),
                        'ADDITIONAL_LINKS' => $this->uncode($row->additional_links),
                        'PARAMS' => $this->uncode($row->params),
                        'PERMISSION' => "R",
                        'IS_PARENT' => true,
                        'DEPTH_LEVEL' => $level,
                        'HIDE' => $this->uncode($row->hide),
                    );
                    $this->arFullMenu[] = $r;
                    if ($copy != $row->link) {
                        $copy = $row->link;
                        $this->constructMenu($row->children, $this->arFullMenu, $copy, $level + 1);
                    } else {
                        $this->arFullMenu[count($this->arFullMenu) - 1]['IS_PARENT'] = false;
                    }
                } else {
                    $r = array(
                        'TEXT' => $this->uncode($row->text),
                        'LINK' => $this->uncode($row->link),
                        'ADDITIONAL_LINKS' => $this->uncode($row->additional_links),
                        'PARAMS' => $this->uncode($row->params),
                        'PERMISSION' => "R",
                        'IS_PARENT' => false,
                        'DEPTH_LEVEL' => $level,
                        'HIDE' => $this->uncode($row->hide),
                    );
                    $this->arFullMenu[] = $r;
                }
            }
        }
    }
}
