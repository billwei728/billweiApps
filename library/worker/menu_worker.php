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
        } else $this->setFileName(STORAGE . OPERATION_MODE . "_menu.dat");
        $this->setReadMode('r');
        $this->setClearMode("w");
        $this->setInsertMode("a+");
    }

    public function doAction($arrParams = array()) 
    {
        try {
            if ("update" == $arrParams['menu_action']) return $this->update($arrParams);
            elseif ("delete" == $arrParams['menu_action']) return $this->delete($arrParams);
            elseif ("insert" == $arrParams['menu_action']) return $this->insert($arrParams);
            elseif ("updRank" == $arrParams['menu_action']) return $this->update_rank($arrParams);
            elseif ("clear" == $arrParams['menu_action']) return $this->clear($arrParams);
            else return $this->select();
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    private function select()  
    {
        $list = $this->readFile();
        if (isset($list) && ! empty($list)) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . " Reading File... : " . json_encode($list), M_LOG_INFO); 
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
                        $list[] = $arrParams["menu_id_new"] . '||' . $arrParams["module_name_new"] . '||' . $arrParams["parent_node_new"] . '||' . $arrParams["menu_rank_new"] . '||' . $arrParams["menu_name_new"] . '||' . $arrParams["menu_url_new"] . '||' . $arrParams["menu_icon_new"] . '||' . $arrParams["menu_idname_new"] . '||' . 1 . "\r\n";
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
// echo '<pre>'; print_r($arrParams); echo '</pre>';
// Array
// (
//     [tblMenu_length] => 10
//     [row_check] => Array
//         (
//             [0] => 3
//         )

//     [menu_action] => delete
//     [updRank_action] => 
//     [updRank_id] => 
// )

            // if ($this->clearFile()) {
                $listRemain = array();
                $arrSelected = $arrParams["row_check"];
                // $index = 0;
                // foreach ($listPrev as $key => $value) {
                //     if (! in_array($listPrev[$key]["id"], $arrSelected)) {
                //         $listRemain[$index] = $listPrev[$key];
                //         $index++;
                //     }
                // }

                // usort($listRemain, function($a, $b) {
                //     return $a['rank'] - $b['rank'];
                // });

                // $list = array();
                // $index = 1;
                // foreach ($listRemain as $key => $value) {
                //     $list[$key] = $index . '||' . $listRemain[$key]["prefix"] . '||' . $index . '||' . $listRemain[$key]["name"];
                //     $index++;
                // }

                // if ($this->insertFile($list)) {
                //     $this->returnPage(count($arrSelected), "deleted", "success");
                // }
            // }  
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Delete Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }   
    }

    private function insert($arrParams = array())  
    {
        try {
            $list = array();
            //define lastest rank
            $menuNewRank = $this->getNewRank($arrMenu["module_name_new"], $arrMenu["parent_node_new"]);
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
            
            $list = array();
            $currentRankId = $arrParams['updRank_id'];
            $updateRankId;
            if ($this->clearFile()) {
                if ("rank_up" == $arrParams['updRank_action']) {
                    $updateRankId = $arrParams['updRank_id'] - 1;
                } elseif ("rank_down" == $arrParams['updRank_action']) {
                    $updateRankId = $arrParams['updRank_id'] + 1;
                }
                foreach ($listPrev as $key => $value) {
                    if ($currentRankId == $arrParams["menu_id"][$key]) {
                        $arrParams["menu_id"][$key] = $updateRankId;
                        $arrParams["menu_rank"][$key] = $updateRankId;
                    } elseif ($updateRankId == $arrParams["menu_id"][$key]) {
                        $arrParams["menu_id"][$key] = $currentRankId;
                        $arrParams["menu_rank"][$key] = $currentRankId;
                    }
                    $list[] = $arrParams["menu_id"][$key] . '||' . $arrParams["menu_prefix"][$key] . '||' . $arrParams["menu_rank"][$key] . '||' . $arrParams["menu_name"][$key] . "\r\n";
                }
                usort($list, function($a, $b) {
                    return $a['menu_rank'] - $b['menu_rank'];
                });
            }

            if ($this->insertFile($list)) {
                $this->returnPage(__FUNCTION__);
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Ranking Update Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function clear($arrParams = array())  
    {
        if ($this->readFile()) return $this->clearFile();
        else {
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
            if ($module == $listPrev[$key]['module']) {
                if ($parentNode == $listPrev[$key]['parent']) {
                    $list[] = $listPrev[$key];
                }
            }
        }       

        $countMenus = count($list);
        $init = 0;
        $lastRank = 0;
        foreach ($list as $key => $value) {
            if (++$init === $countMenus) {
                $lastRank = $list[$key]['rank'];
            }
        }

        return ++$lastRank;
    }
}
?>