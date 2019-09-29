<?php
Namespace App\worker;

Use App\datastore\module_store;
Use Exception;

class module_worker extends module_store
{
    public function __construct()
    {
        if (isset($_SESSION["user"])) {
            $this->setFileName(STORAGE . $_SESSION["user"] . "_module.dat");
        } else {
            $this->setFileName(STORAGE . OPERATION_MODE . "_module.dat");
        }
        $this->setReadMode('r');
        $this->setClearMode("w");
        $this->setInsertMode("a+");
    }

    public function doAction($arrParams = array()) 
    {
        try {
            if ("update" == $arrParams['module_action']) {
                return $this->update($arrParams);
            } else if ("delete" == $arrParams['module_action']) {
                return $this->delete($arrParams);
            } else if ("insert" == $arrParams['module_action']) {
                return $this->insert($arrParams);
            } else if ("updRank" == $arrParams['module_action']) {
                return $this->update_rank($arrParams);
            } else if ("clear" == $arrParams['module_action']) {
                return $this->clear($arrParams);
            } else if ("select" == $arrParams['module_action']) {
                return $this->select();
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    private function select()  
    {
        $list = $this->sortList();
        if (isset($list) && ! empty($list)) {
            return $list;
        } else {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . $this->getFileName() . " Read Failed.", M_LOG_ERROR);
            throw new Exception($this->getFileName() . " Read Failed.");
        }
    }

    private function update($arrParams = array())  
    {
        try {
            $listPrev = $this->sortList();

            if ($this->clearFile()) {
                $list = array();
                $arrSelected = $arrParams["row_check"];
                $arrLength = count($arrParams["module_id"]);
                for ($x = 0; $x < $arrLength; $x++) {
                    if (in_array($arrParams["module_id"][$x], $arrSelected)) {
                        $list[] = $arrParams["module_id"][$x] . '||' . $arrParams["module_prefix"][$x] . '||' . $arrParams["module_rank"][$x] . '||' . $arrParams["module_name"][$x] . "\r\n";
                    } else {
                        $list[] = $listPrev[$x]["id"] . '||' . $listPrev[$x]["prefix"] . '||' . $listPrev[$x]["rank"] . '||' . $listPrev[$x]["name"];
                    }
                }

                if ($this->insertFile($list)) {
                    $this->returnPage(count($arrSelected), __FUNCTION__, "success");
                }
            }  
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Update Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function delete($arrParams = array())  
    {
        try {
            $listPrev = $this->sortList();

            if ($this->clearFile()) {
                $listRemain = array();
                $arrSelected = $arrParams["row_check"];
                $index = 0;
                foreach ($listPrev as $key => $value) {
                    if (! in_array($arrParams["module_id"][$key], $arrSelected)) {
                        $listRemain[$index] = $listPrev[$key];
                        $index++;
                    }
                }

                usort($listRemain, function($a, $b) {
                    return $a['rank'] - $b['rank'];
                });

                $list = array();
                $index = 1;
                foreach ($listRemain as $key => $value) {
                    $list[$key] = $index . '||' . $listRemain[$key]["prefix"] . '||' . $index . '||' . $listRemain[$key]["name"];
                    $index++;
                }

                if ($this->insertFile($list)) {
                    $this->returnPage(count($arrSelected), __FUNCTION__, "success");
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
            $list[] = $this->getNewIndex() . '||' . $arrParams['module_prefix_new'] . '||' . $this->getNewIndex() . '||' . $arrParams['module_name_new'] . "\r\n";

            if ($this->insertFile($list)) {
                $this->returnPage(1, __FUNCTION__, "success");
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Record(s) Insert Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }
    }

    private function update_rank($arrParams = array())  
    {
        try {
            $listPrev = $this->sortList();
            
            $list = array();
            $currentRankId = $arrParams['updRank_id'];
            $updateRankId;
            if ($this->clearFile()) {
                if ("rank_up" == $arrParams['updRank_action']) {
                    $updateRankId = $arrParams['updRank_id'] - 1;
                } else if ("rank_down" == $arrParams['updRank_action']) {
                    $updateRankId = $arrParams['updRank_id'] + 1;
                }
                foreach ($listPrev as $key => $value) {
                    if ($currentRankId == $arrParams["module_id"][$key]) {
                        $arrParams["module_id"][$key] = $updateRankId;
                        $arrParams["module_rank"][$key] = $updateRankId;
                    } else if ($updateRankId == $arrParams["module_id"][$key]) {
                        $arrParams["module_id"][$key] = $currentRankId;
                        $arrParams["module_rank"][$key] = $currentRankId;
                    }
                    $list[] = $arrParams["module_id"][$key] . '||' . $arrParams["module_prefix"][$key] . '||' . $arrParams["module_rank"][$key] . '||' . $arrParams["module_name"][$key] . "\r\n";
                }
                usort($list, function($a, $b) {
                    return $a['module_rank'] - $b['module_rank'];
                });
            }

            if ($this->insertFile($list)) {
                $this->returnPage(1, __FUNCTION__, "success");
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
        $page = "module_list";
        header("Location: " . $homeURL . $sessionURL . "?url=" . $_SERVER['PHP_SELF'] . "&page=" . $page . "&total=" . $total . "&result=" . $result . "&action=" . $action);
        die();
    }

    private function getNewIndex()  
    {
        // $file = escapeshellarg($this->getFileName()); // for the security concious (should be everyone!)
        // $line = `tail -n 1 $file`;
        // $array = explode("||",$line);
        $listPrev = $this->readFile();
        $listCount = count($listPrev);
        
        return ++$listCount;
    }

    private function sortList()  
    {
        $listPrev = $this->readFile();
        usort($listPrev, function($a, $b) {
            return $a['rank'] - $b['rank'];
        });
        return $listPrev;
    }
}
?>