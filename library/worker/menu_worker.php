<?php
Namespace App\worker;

Use App\datastore\menu_store;
Use Exception;

class menu_worker extends menu_store
{
    public function __construct()
    {
        if (isset($_SESSION["user"])) {
            $this->setFileName(STORAGE . $_SESSION["user"] . "_menu.dat");
        } else {
            $this->setFileName(STORAGE . OPERATION_MODE . "_menu.dat");
        }
        $this->setReadMode("r");
        $this->setClearMode("w");
        $this->setInsertMode("a+");
    }

    public function doAction($arrParams = array()) 
    {
        try {
            if ("update" == $arrParams["menu_action"]) {
                return $this->update($arrParams);
            } else if ("delete" == $arrParams["menu_action"]) {
                return $this->delete($arrParams);
            } else if ("insert" == $arrParams["menu_action"]) {
                return $this->insert($arrParams);
            } else if ("updRank" == $arrParams["menu_action"]) {
                return $this->update_rank($arrParams);
            } else if ("clear" == $arrParams["menu_action"]) {
                return $this->clear($arrParams);
            } else {
                return $this->select();
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    private function select()  
    {
        $list = $this->readFile();
        if (isset($list) && ! empty($list)) {
            // $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . " Reading File... : " . json_encode($list), M_LOG_INFO);
            $list = $this->sortList($list);
            $listModule = $this->getModuleName();        

            $listParent = array();
            foreach ($listModule as $moduleKey => $module) {
                $num = 0;
                foreach ($list as $menuKey => $menu) {
                    if ($list[$menuKey]["module"] == $module["prefix"]) {
                        if (empty($listParent[$module["prefix"]])) {
                            $listParent[$module["prefix"]][] = $list[$menuKey]["parent"];
                            $listCount[$module["prefix"]][$list[$menuKey]["parent"]] = ++$num;
                        } else {
                            if (! in_array($list[$menuKey]["parent"], $listParent[$module["prefix"]])) {
                                $num = 0;
                                $listParent[$module["prefix"]][] = $list[$menuKey]["parent"];
                            }
                            $listCount[$module["prefix"]][$list[$menuKey]["parent"]] = ++$num;
                        }
                    }
                }
            }

            $listMenu = array();
            foreach ($listCount as $moduleKey => $count) {
                foreach ($count as $parentKey => $subcount) {
                    foreach ($list as $menuKey => $menu) {
                        if ($menu["module"] == $moduleKey) {
                            if ($menu["parent"] == $parentKey) {
                                if (1 < $subcount) {
                                    if (1 == $menu["rank"]) {
                                        $list[$menuKey]["rank"]  = $menu["rank"] . "_" . "down";
                                    } else if ($subcount == $menu["rank"]) {
                                        $list[$menuKey]["rank"]  = $menu["rank"] . "_" . "up";
                                    } else {
                                        $list[$menuKey]["rank"]  = $menu["rank"] . "_" . "updown";
                                    }
                                } else {
                                    $list[$menuKey]["rank"] = $menu["rank"] . "_" . "no";
                                }
                            }
                        }
                    }
                }
            }

            $num = 0;
            foreach ($list as $key => $value) {
                $list[$key] = str_replace("\r\n", '', $list[$key]);
                $list[$key]["rowid"] = ++$num;
            }

            return $list;
        } else {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . $this->getFileName() . " Read Failed.", M_LOG_ERROR);
            throw new Exception($this->getFileName() . " Read Failed.");
        }
    }

    private function update($arrParams = array())  
    {
        try {
            $listPrev = $this->readFile();

            if ($this->clearFile()) {
                $list = array();
                $arrSelected[] = $arrParams["row_checked"];
                foreach ($listPrev as $key => $value) {
                    if (in_array($listPrev[$key]["id"], $arrSelected)) {
                        $list[] = $arrParams["menu_id_new"] . '||' . $arrParams["module_name_new"] . '||' . $arrParams["parent_node_new"] . '||' . $listPrev[$key]["rank"] . '||' . $arrParams["menu_name_new"] . '||' . $arrParams["menu_url_new"] . '||' . $arrParams["menu_icon_new"] . '||' . $arrParams["menu_idname_new"] . '||' . 1 . "\r\n";
                    } else {
                        $list[] = $listPrev[$key]["id"] . '||' . $listPrev[$key]["module"] . '||' . $listPrev[$key]["parent"] . '||' . $listPrev[$key]["rank"] . '||' . $listPrev[$key]["name"] . '||' . $listPrev[$key]["url"] . '||' . $listPrev[$key]["icon"] . '||' . $listPrev[$key]["idname"] . '||' . $listPrev[$key]["ver"];
                    }
                }
                if ($this->insertFile($list)) {
                    $this->returnPage(count($arrSelected), "updated", "success");
                }
            }  
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Update Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function delete($arrParams = array())  
    {
        try {
            $listPrev = $this->readFile();
            
            if ($this->clearFile()) {
                $arrSelected[] = $arrParams["action_id"];
                $listDeleteParent;
                $listRemain = array();
                foreach ($listPrev as $key => $value) {
                    if (in_array($listPrev[$key]["id"], $arrSelected)) {
                        $listDeleteParent = $listPrev[$key]["parent"];
                    } else {
                        $listRemain[] = $listPrev[$key];
                    }
                }

                $listSibling = array();
                foreach ($listRemain as $key => $value) {
                    if ($listRemain[$key]["parent"] == $listDeleteParent) {
                        $listSibling[] = $listRemain[$key];
                        unset($listRemain[$key]);
                    }
                }

                if (! empty($listSibling)) {
                    usort($listSibling, function($a, $b) {
                        return $a["rank"] - $b["rank"];
                    });

                    $rankNew = 1;
                    foreach ($listSibling as $key => $value) {
                        $listSibling[$key]["rank"] = $rankNew;
                        $rankNew++;
                    }
                }

                $listMerge = array_merge($listRemain, $listSibling);
                $listFinal = $this->sortList($listMerge);
                if (! empty($listFinal)) {
                    foreach ($listFinal as $key => $value) {
                        $list[] = $listFinal[$key]["id"] . '||' . $listFinal[$key]["module"] . '||' . $listFinal[$key]["parent"] . '||' . $listFinal[$key]["rank"] . '||' . $listFinal[$key]["name"] . '||' . $listFinal[$key]["url"] . '||' . $listFinal[$key]["icon"] . '||' . $listFinal[$key]["idname"] . '||' . $listFinal[$key]["ver"];
                    }

                    if ($this->insertFile($list)) {
                        $this->returnPage(count($arrSelected), "deleted", "success");
                    }
                } else {
                    $this->returnPage(count($arrSelected), "deleted", "error");
                }
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Delete Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }   
    }

    private function insert($arrParams = array())  
    {
        try {
            $list = array();
            //define lastest rank
            $menuNewRank = $this->getNewRank($arrParams["module_name_new"], $arrParams["parent_node_new"]);
            $list[] = $this->getNewIndex() . '||' . $arrParams["module_name_new"] . '||' . $arrParams["parent_node_new"] . '||' . $menuNewRank . '||' . $arrParams["menu_name_new"] . '||' . $arrParams["menu_url_new"] . '||' . $arrParams["menu_icon_new"] . '||' . $arrParams["menu_idname_new"] . '||' . 1 . "\r\n";

            if ($this->insertFile($list)) {
                $this->returnPage(1, "inserted", "success");
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Insert Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function update_rank($arrParams = array())  
    {
        try {
            $listPrev = $this->readFile();

            if ($this->clearFile()) {
                $arrSelected[] = $arrParams["action_id"];
                $listSelectedParent;
                $listSelectedRank = 0;
                $listSelectedNewRank = 0;
                foreach ($listPrev as $key => $value) {
                    if (in_array($listPrev[$key]["id"], $arrSelected)) {
                        $listSelectedParent = $listPrev[$key]["parent"];
                        if ("rank_up" == $arrParams["updRank_action"]) {
                            $listSelectedRank = $listPrev[$key]["rank"];
                            $listSelectedNewRank = $listPrev[$key]["rank"] - 1;
                        } else if ("rank_down" == $arrParams["updRank_action"]) {
                            $listSelectedRank = $listPrev[$key]["rank"];
                            $listSelectedNewRank = $listPrev[$key]["rank"] + 1;
                        }
                    }
                }

                $listSibling = array();
                $listRemain = array();
                foreach ($listPrev as $key => $value) {
                    if ($listPrev[$key]["parent"] == $listSelectedParent) {
                        if ($listPrev[$key]["rank"] == $listSelectedRank) {
                            $listPrev[$key]["rank"] = $listSelectedNewRank;
                        } else if ($listPrev[$key]["rank"] == $listSelectedNewRank) {
                            $listPrev[$key]["rank"] = $listSelectedRank;
                        }
                        $listSibling[] = $listPrev[$key];
                    } else {
                        $listRemain[] = $listPrev[$key];
                    }
                }

                $listMerge = array_merge($listRemain, $listSibling);
                $listFinal = $this->sortList($listMerge);
                if (! empty($listFinal)) {
                    foreach ($listFinal as $key => $value) {
                        $list[] = $listFinal[$key]["id"] . '||' . $listFinal[$key]["module"] . '||' . $listFinal[$key]["parent"] . '||' . $listFinal[$key]["rank"] . '||' . $listFinal[$key]["name"] . '||' . $listFinal[$key]["url"] . '||' . $listFinal[$key]["icon"] . '||' . $listFinal[$key]["idname"] . '||' . $listFinal[$key]["ver"];
                    }

                    if ($this->insertFile($list)) {
                        $this->returnPage(count($arrSelected), "updated", "success");
                    }
                } else {
                    $this->returnPage(count($arrSelected), "updated", "error");
                }
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Ranking Update Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function clear($arrParams = array())  
    {
        if ($this->readFile()) {
            return $this->clearFile();
        } else {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . $this->getFileName() . " Clear Failed.", M_LOG_ERROR);
            throw new Exception($this->getFileName() . " Clear Failed.");
        }
    }

    protected function returnPage($total = 1, $action = "", $result = "success")  
    {
        $homeURL = "//" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        $sessionURL = "/pages/menu_session.php";
        $page = "menu_list";
        header("Location: " . $homeURL . $sessionURL . "?url=" . $_SERVER['PHP_SELF'] . "&page=" . $page . "&total=" . $total . "&result=" . $result . "&action=" . $action);
        die();
    }

    private function getNewIndex()  
    {
        $file = escapeshellarg($this->getFileName()); // for the security concious (should be everyone!)
        $line = `tail -n 1 $file`;
        $array = explode("||",$line);

        return ++$array[0];
    }

    private function getNewRank($module, $parentNode)  
    {
        $listPrev = $this->readFile();
        $list = array();
        foreach ($listPrev as $key => $value) {
            if ($module == $listPrev[$key]["module"]) {
                if ($parentNode == $listPrev[$key]["parent"]) {
                    $list[] = $listPrev[$key];
                }
            }
        }

        $countMenus = count($list);
        $init = 0;
        $lastRank = 0;
        foreach ($list as $key => $value) {
            if (++$init === $countMenus) {
                $lastRank = $list[$key]["rank"];
            }
        }

        return ++$lastRank;
    }

#region -------------------- Select Option --------------------
    public function doPopulate($select) 
    {
        try {
            if ("parent" == $select) {
                return $this->selectOptVal_Parent();
            } else {
                return $this->selectOptVal_Module();
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    public function selectOptVal_Module() 
    {
        $moduleColumn = array("prefix", "name");
        $listPrev = $this->getModuleName($moduleColumn);
        
        $list = array();
        foreach ($listPrev as $key => $value) {
            $list[$listPrev[$key]['name']] = $listPrev[$key]['prefix'];
        }

        return $list;
    }

    public function selectOptVal_Parent() 
    {
        $listPrev = $this->readFile();
        
        $list = array();
        foreach ($listPrev as $key => $value) {
            if ("--" == $listPrev[$key]['url']) {
                $list[$listPrev[$key]['name']] = $listPrev[$key]['id'];
            }
        }
        $list["None"] = "-";

        return $list;
    }
#endregion
}
?>