<?php
Namespace App\objects;

Use App\datastore\menu_store;
Use Exception;

class menu_populate extends menu_store
{
    private $dataMenu = array();
    public $parentMenu = array();
    public $subMenu = array();


    public function __construct() 
    {
        if (isset($_SESSION["user"])) {
            $this->setFileName(STORAGE . $_SESSION["user"] . "_menu.dat");
        } else {
            $this->setFileName(STORAGE . OPERATION_MODE . "_menu.dat");
        }
        $this->setReadMode('r');
        $this->setDataMenu($this->readFile());
    }

    public function doAction($action) 
    {
        try {
            if ("select" == $action) {
                return $this->populateMenu();
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    private function populateMenu() 
    {
        $listPrev = $this->getDataMenu();

        $list = array();
        if (isset($listPrev) && ! empty($listPrev)) {
            $list = $this->buildMasterMenu($listPrev);
        }

        return $list;
    }

    protected function buildMasterMenu($arrList = array()) 
    {
        $masterMenu = "";
        $masterMenu .= '<div class="sidebar-menu">';
        $masterMenu .= '<ul class="list list-unstyled list-scrollbar" id="populateMenu">';
        $masterMenu .= $this->buildModule($arrList);
        $masterMenu .= '</ul>';
        $masterMenu .= '</div>';

        return $masterMenu;
    }

    protected function buildModule($arrList = array()) 
    {
        $moduleColumn = array("prefix", "name");
        $moduleName = $this->getModuleName($moduleColumn);
        $moduleMenu = "";
        foreach ($moduleName as $key => $value) {
            $list = array();
            $childlist = array();
            foreach ($arrList as $arrkey => $arrvalue) {
                if ($arrvalue["module"] == $value[$moduleColumn[0]]) {
                    $list[] = $arrvalue;
                    if ("-" != $arrvalue["parent"]) {
                        $childlist[] = $arrvalue;
                    }
                }
            }

            if (isset($childlist) && ! empty($childlist)) {
                $moduleMenu .= '<li class="list-item">';
                $moduleMenu .= '<p class="list-title text-uppercase">' . $value[$moduleColumn[1]] . '</p>';
                $moduleMenu .= '<ul class="list-unstyled">';
                $moduleMenu .= $this->buildFirstLvlMenu($list);
                $moduleMenu .= '</ul>';
                $moduleMenu .= '</li>';
            }
        }

        return $moduleMenu;
    }

    protected function buildFirstLvlMenu($arrList = array()) 
    {
        $listParent = array();
        $listChildLvl = array();
        foreach ($arrList as $arrkey => $arrvalue) {
            if ("-" == $arrvalue["parent"]) {
                $listParent[] = $arrvalue;
            } else {
                $listChildLvl[$arrvalue["parent"]][] = $arrvalue;
            }
        }

        $firstLvlMenu = "";
        foreach ($listParent as $parentkey => $parentvalue) {
            if ("-" == $parentvalue["url"]) {
                $firstLvlMenu .= '<li>';
                $firstLvlMenu .= '<a href="" class="list-link" id="' . $parentvalue["idname"] . '">';
                $firstLvlMenu .= '<i class="' . $parentvalue["icon"] . '" aria-hidden="true"></i> ' . $parentvalue["name"];
                $firstLvlMenu .= '</a>';
                $firstLvlMenu .= '</li>';

            } else if ("--" == $parentvalue["url"]) {
                if (isset($listChildLvl) && ! empty($listChildLvl)) {
                    $listChild = array();
                    $listChildMenu = array();
                    foreach ($listChildLvl as $childkey => $childvalue) {
                        if ($parentvalue["id"] == $childkey) {
                            foreach ($childvalue as $subkey => $subvalue) {
                                $listChild[] = $subvalue['id'];
                            }
                            $listChildMenu[$childkey] = $childvalue;
                        } 
                    }
                    foreach ($listChildLvl as $childkey => $childvalue) {
                        if (in_array($childkey, $listChild)) {
                            $listChildMenu[$childkey] = $childvalue;
                        }
                    }

                    if (isset($listChildMenu) && ! empty($listChildMenu)) {
                        $firstLvlMenu .= '<li>';
                        $firstLvlMenu .= '<a href="/" onclick="return false;" class="list-link link-arrow" id="' . $parentvalue["idname"] . '">';
                        $firstLvlMenu .= '<i class="' . $parentvalue["icon"] . '" aria-hidden="true"></i> ' . $parentvalue["name"];
                        $firstLvlMenu .= '</a>';
                        $firstLvlMenu .= '<ul class="list-unstyled list-hidden" id="' . $parentvalue["idname"] . '_child">';
                        $firstLvlMenu .= $this->buildChildMenu($listChildMenu);
                        $firstLvlMenu .= '</ul>';
                        $firstLvlMenu .= '</li>';
                    }
                }
            }
        }

        return $firstLvlMenu;
    }

    protected function buildSecondLvlMenu($arrList = array()) 
    {
        $secondLvlMenu = "";
        foreach ($arrList as $key => $value) {
            $listChild = array();
            foreach ($arrList as $subkey => $subvalue) {
                if ($value["id"] == $subvalue["parent"]) {
                    $listChild[] = $subvalue;
                }
            }

            if ("--" == $value["url"]) {
                $secondLvlMenu .= '<li>';
                $secondLvlMenu .= '<a href="#" class="list-link link-arrow">';
                $secondLvlMenu .= '<i class="' . $value["icon"] . '" aria-hidden="true"></i> ' . $value["name"];
                $secondLvlMenu .= '</a>';
                $secondLvlMenu .= '<ul class="list-unstyled list-hidden" id="' . $value["idname"] . '">';
                $secondLvlMenu .= $this->buildChildMenu($listChild, true);
                $secondLvlMenu .= '</ul>';
                $secondLvlMenu .= '</li>';
            }
        }

        return $secondLvlMenu;
    }

    protected function buildChildMenu($arrList = array(), $isChild = false) 
    {
        $listFirstLvl = array();
        foreach ($arrList as $key => $value) {
            $listFirstLvl[] = $value;
        }

        if ($isChild) {
            $listFirstLvlMenu = $listFirstLvl;
        } else {
            $listFirstLvlMenu = $listFirstLvl[0];
        }

        $childLvlMenu = "";
        $populatedArrId = array();
        foreach ($listFirstLvlMenu as $key => $value) {
            if ("--" == $value["url"]) {
                $listNext = array();
                $listNext[] = $value;
                foreach ($arrList[$value["id"]] as $arrkey => $arrvalue) {
                    if ($value["id"] == $arrvalue["parent"]) {
                        $listNext[] = $arrvalue;
                        $populatedArrId[] = $arrvalue["id"];
                    }
                }

                $populatedArrId[] = $value["id"];
                $childLvlMenu .= $this->buildSecondLvlMenu($listNext);
            } else {
                if (! in_array($value["id"], $populatedArrId)) {
                    $childLvlMenu .= '<li id="li_' . $value["idname"] . '">';
                    if ("-" != $value["url"]) {
                        $childLvlMenu .= '<a href="' . $value["url"] . '" target="contentFrame" class="list-link" id="' . $value["idname"] . '">';
                    } else {
                        $childLvlMenu .= '<a href="#" class="list-link" id="' . $value["idname"] . '">';
                    }
                    $childLvlMenu .= '<i class="' . $value["icon"] . '" aria-hidden="true"></i> ' . $value["name"];
                    $childLvlMenu .= '</a>';
                    $childLvlMenu .= '</li>';
                    $populatedArrId[] = $value["id"];
                }
            }  
        }

        return $childLvlMenu;
    }


#region -------------------- Settings --------------------
    protected function setDataMenu($arrlist)
    {
        $arrlist = $this->sortList($arrlist);
        $this->dataMenu = $arrlist;
    }

    public function getDataMenu() 
    {    
        return $this->dataMenu;
    }
#endregion
}
?>
