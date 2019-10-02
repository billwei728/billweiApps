<?php
Namespace App\worker;

Use App\datastore\account_store;
Use Exception;

class account_worker extends account_store
{
    public function __construct()
    {
        if (isset($_SESSION["user"])) {
            $this->setFileName(STORAGE . $_SESSION["user"] . "_account.dat");
        } else {
            $this->setFileName(STORAGE . OPERATION_MODE . "_account.dat");
        }
        $this->setReadMode('r');
        $this->setClearMode("w");
        $this->setInsertMode("a+");
    }

    public function doAction($arrParams = array()) 
    {
        try {
            if ("update" == $arrParams['account_action']) {
                return $this->update($arrParams);
            } else if ("delete" == $arrParams['account_action']) {
                return $this->delete($arrParams);
            } else if ("insert" == $arrParams['account_action']) {
                return $this->insert($arrParams);
            } else if ("updRank" == $arrParams['account_action']) {
                return $this->update_rank($arrParams);
            } else if ("clear" == $arrParams['account_action']) {
                return $this->clear($arrParams);
            } else if ("select" == $arrParams['account_action']) {
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
                $arrLength = count($arrParams["account_no"]);
                for ($x = 0; $x < $arrLength; $x++) {
                    if (in_array($arrParams["account_no"][$x], $arrSelected)) {
                        $version = (int)$listPrev[$x]["ver"];
                        $version = ++$version;
                        $list[] = $arrParams["account_no"][$x] . '||' . $arrParams["account_name"][$x] . '||' . $arrParams["account_id"][$x] . '||' . $arrParams["account_pass"][$x] . '||' . $arrParams["account_rank"][$x] . '||' . $arrParams["account_remark"][$x] . '||' . $version . "\r\n";
                    } else {
                        $list[] = $listPrev[$x]["id"] . '||' . $listPrev[$x]["name"] . '||' . $listPrev[$x]["userid"] . '||' . $listPrev[$x]["password"] . '||' . $listPrev[$x]["rank"] . '||' . $listPrev[$x]["remark"] . '||' . $listPrev[$x]["ver"];
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
                    if (! in_array($arrParams["account_no"][$key], $arrSelected)) {
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
                    $list[$key] = $index . '||' . $listRemain[$key]["name"] . '||' . $listRemain[$key]["userid"] . '||' . $listRemain[$key]["password"] . '||' . $index . '||' . $listRemain[$key]["remark"] . '||' . $listRemain[$key]["ver"];
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
        echo '<pre>'; print_r($arrParams); echo '</pre>';
        try {
            $list = array();
            $list[] = $this->getNewIndex() . '||' . $arrParams['account_type_new'] . '||' . $arrParams['account_id_new'] . '||' . $arrParams['account_pass_new'] . '||' . $this->getNewIndex() . '||' . $arrParams['account_remark_new'] . '||' . 1 . "\r\n";
            if ($this->insertFile($list)) {
                $this->returnPage(1, __FUNCTION__, "success");
            } else {
                $this->returnPage(1, __FUNCTION__, "error");
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
                    if ($currentRankId == $arrParams["account_no"][$key]) {
                        $arrParams["account_no"][$key] = $updateRankId;
                        $arrParams["account_rank"][$key] = $updateRankId;
                        $version = (int)$listPrev[$key]["ver"];
                        $version = ++$version;
                    } else if ($updateRankId == $arrParams["account_no"][$key]) {
                        $arrParams["account_no"][$key] = $currentRankId;
                        $arrParams["account_rank"][$key] = $currentRankId;
                        $version = (int)$listPrev[$key]["ver"];
                    } else {
                        $version = (int)$listPrev[$key]["ver"];
                    }
                    
                    $list[] = $arrParams["account_no"][$key] . '||' . $arrParams["account_name"][$key] . '||' . $arrParams["account_id"][$key] . '||' . $arrParams["account_pass"][$key] . '||' . $arrParams["account_rank"][$key] . '||' . $arrParams["account_remark"][$key] . '||' . $version . "\r\n";
                }
                usort($list, function($a, $b) {
                    return $a['account_rank'] - $b['account_rank'];
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
        $page = "account_list";
        header("Location: " . $homeURL . $sessionURL . "?url=" . $_SERVER['PHP_SELF'] . "&page=" . $page . "&total=" . $total . "&result=" . $result . "&action=" . $action);
        die();
    }

    private function getNewIndex()  
    {
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