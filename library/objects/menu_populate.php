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
    public function moduleName($arrlist) //ModuleName($prefix) 
    {
        $module_worker = $this->create("App\worker\module_worker");
        $arrParams['module_action'] = "select";
        $moduleName = $module_worker->doAction($arrParams);
        
        $list = array();
        foreach ($moduleName as $key => $value) {
            $list[$key][$arrlist[0]] = str_replace(array("\r", "\n"), '',  $value["prefix"]);
            $list[$key][$arrlist[1]] = str_replace(array("\r", "\n"), '',  $value["name"]);
        }

        return $list;
        // return array(array("prefix" => "ds", "name" => "Dashboard"), array("prefix" => "st", "name" => "Staging"), array("prefix" => "pr", "name" => "Production"));
    }

    protected function setDataMenu($arrlist)
    {
        $this->dataMenu = $arrlist;
    }

    public function getDataMenu() 
    {
        return $this->dataMenu;
    }
#endregion
}

/** ----------------------------------------------------------------------------------------------- */

    $menu_populate = new menu_populate();
    $result = $menu_populate->doAction("select"); 

    // echo '<pre>'; print_r($result); echo '</pre>';

/** ----------------------------------------------------------------------------------------------- */
?>
