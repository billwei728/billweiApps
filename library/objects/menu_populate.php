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
        } else $this->setFileName(STORAGE . OPERATION_MODE . "_menu.dat");
        $this->setReadMode('r');
        $this->setDataMenu($this->readFile());
    }

    public function doAction($action) 
    {
        try {
            if ("select" == $action) return $this->populateMenu();
            // else return $this->select();
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
        $moduleName = $this->moduleName($moduleColumn);

        $moduleMenu = "";
        foreach ($moduleName as $key => $value) {
            $list = array();
            $childlist = array();
            foreach ($arrList as $arrkey => $arrvalue) {
                if ($arrvalue["module"] == $value[$moduleColumn[0]]) {
                    $list[] = $arrvalue;
                    if ($arrvalue["parent"] != "-") {
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
        $listPrev = array();
        $list = array();
        foreach ($arrList as $arrkey => $arrvalue) {
            if ("-" == $arrvalue["parent"]) {
                $listPrev[] = $arrvalue;
            } else {
                $list[] = $arrvalue;
            }
        }

        $firstLvlMenu = "";
        foreach ($listPrev as $key => $value) {
            if ("-" == $value["url"]) {
                $firstLvlMenu .= '<li>';
                $firstLvlMenu .= '<a href="" class="list-link" id="' . $value["idname"] . '">';
                $firstLvlMenu .= '<i class="' . $value["icon"] . '" aria-hidden="true"></i> ' . $value["name"];
                $firstLvlMenu .= '</a>';
                $firstLvlMenu .= '</li>';

            } elseif ("--" == $value["url"]) {
                // $firstLvlMenu .= $this->buildSecondLvlMenu($list);
                if (isset($list) && ! empty($list)) {
                    $firstLvlMenu .= '<li>';
                    $firstLvlMenu .= '<a href="#" class="list-link link-arrow" id="' . $value["idname"] . '">';
                    $firstLvlMenu .= '<i class="' . $value["icon"] . '" aria-hidden="true"></i> ' . $value["name"];
                    $firstLvlMenu .= '</a>';
                    $firstLvlMenu .= '<ul class="list-unstyled list-hidden" id="' . $value["idname"] . '_child">';
                    $firstLvlMenu .= $this->buildChildMenu($list);
                    $firstLvlMenu .= '</ul>';
                    $firstLvlMenu .= '</li>';
                }
            }
        }

        return $firstLvlMenu;
    }

    protected function buildSecondLvlMenu($arrList = array()) 
    {
        $secondLvlMenu = "";
        foreach ($arrList as $key => $value) {
            $listPrev = array();
            foreach ($arrList as $arrkey => $arrvalue) {
                if ($value["id"] == $arrvalue["parent"]) {
                    $listPrev[] = $arrvalue;
                }
            }

            if ("--" == $value["url"]) {
                $secondLvlMenu .= '<li>';
                $secondLvlMenu .= '<a href="#" class="list-link link-arrow">';
                $secondLvlMenu .= '<i class="' . $value["icon"] . '" aria-hidden="true"></i> ' . $value["name"];
                $secondLvlMenu .= '</a>';
                $secondLvlMenu .= '<ul class="list-unstyled list-hidden" id="' . $value["idname"] . '">';
                $secondLvlMenu .= $this->buildChildMenu($listPrev);
                $secondLvlMenu .= '</ul>';
                $secondLvlMenu .= '</li>';
            }
        }

        return $secondLvlMenu;
    }

    protected function buildChildMenu($arrList = array()) 
    {
        $childLvlMenu = "";
        $populatedArrId = array();
        foreach ($arrList as $key => $value) {
            if ("--" == $value["url"]) {
                $listPrev = array();
                $listPrev[] = $value;
                foreach ($arrList as $arrkey => $arrvalue) {
                    if ($value["id"] == $arrvalue["parent"]) {
                        $listPrev[] = $arrvalue;
                        $populatedArrId[] = $arrvalue["id"];
                    }
                }
                $populatedArrId[] = $value["id"];
                $childLvlMenu .= $this->buildSecondLvlMenu($listPrev);
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
                }
            }  
        }

        return $childLvlMenu;
    }


#region -------------------- Settings --------------------
    public function moduleName($arrlist)
    {
        $module_worker = $this->create("App\worker\module_worker");
        $arrParams['module_action'] = "select";
        $moduleName = $module_worker->doAction($arrParams);
        
        $list = array();
        foreach ($arrlist as $arrkey => $arrvalue) {
            foreach ($moduleName as $key => $value) {
                $list[$key][$arrlist[$arrkey]] = str_replace(array("\r", "\n"), '',  $value[$arrlist[$arrkey]]);
            }
        }

        return $list;
    }

    protected function getModuleName() 
    {
        $moduleColumn = array("prefix", "name");
        $moduleName = $this->moduleName($moduleColumn);
        return $moduleName;
    }

    protected function setDataMenu($arrlist)
    {
        $arrlist = $this->listSort($arrlist);
        $this->dataMenu = $arrlist;
    }

    public function getDataMenu() 
    {    
        return $this->dataMenu;
    }

    private function listSort($listMenu)
    {
        $listPrev = array();
        $moduleName = $this->getModuleName();
        foreach ($moduleName as $key => $value) {
            foreach ($listMenu as $arrkey => $arrvalue) {
                if ($moduleName[$key]["prefix"] == $listMenu[$arrkey]["module"]) {
                    $listPrev[$key][] = $listMenu[$arrkey];  
                }
            }
        }

        $listSort = array();
        foreach ($listPrev as $key => $value) {
            $listSort[] = $this->array_msort($listPrev[$key], array('parent'=>SORT_ASC, 'rank'=>SORT_ASC));
        }

        $list = array();
        foreach ($listSort as $key => $value) {
            foreach ($value as $subkey => $subvalue) {
                $list[] = $subvalue;
            }
        }
        // echo '<pre>'; print_r($list); echo '</pre>';
        return $list;
    }

    protected function array_msort($array, $cols)
    {
        $colarr = array();
        foreach ($cols as $col => $order) {
            $colarr[$col] = array();
            foreach ($array as $k => $row) { 
                $colarr[$col]['_'.$k] = strtolower($row[$col]); 
            }
        }
        $eval = 'array_multisort(';
        foreach ($cols as $col => $order) {
            $eval .= '$colarr[\'' . $col . '\'],' . $order . ',';
        }
        $eval = substr($eval, 0, -1) . ');';
        eval($eval);
        $ret = array();
        foreach ($colarr as $col => $arr) {
            foreach ($arr as $k => $v) {
                $k = substr($k, 1);
                if (! isset($ret[$k])) $ret[$k] = $array[$k];
                $ret[$k][$col] = $array[$k][$col];
            }
        }
        return $ret;

    }
#endregion
}

/** ----------------------------------------------------------------------------------------------- */

    $menu_populate = new menu_populate();
    $result = $menu_populate->doAction("select"); 

    // echo '<pre>'; print_r($result); echo '</pre>';

/** ----------------------------------------------------------------------------------------------- */
?>
