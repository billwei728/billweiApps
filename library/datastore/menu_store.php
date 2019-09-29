<?php
Namespace App\datastore;

Use App\base;
Use Exception;

class menu_store extends filedata_store
{
    protected $filename;
    protected $readmode;
    protected $clearmode;
    protected $insertmode;


    public function __construct()
    {
    }

    protected function sortList($listMenu)
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

        return $list;
    }

    private function array_msort($array, $cols)
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
                if (! isset($ret[$k])) {
                    $ret[$k] = $array[$k];
                }
                $ret[$k][$col] = $array[$k][$col];
            }
        }

        return $ret;
    }

    private function moduleName($arrlist)
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

    protected function getModuleName($moduleColumn = array("prefix")) 
    {
        $moduleName = $this->moduleName($moduleColumn);
        return $moduleName;
    }
    

#region -------------------- Settings --------------------
    protected function getListTitle() 
    {
        return array("id", "module", "parent", "rank", "name", "url", "icon", "idname", "ver");
    }

    protected function setFileName($file = "") 
    {
        $this->filename = $file;
    }

    public function getFileName()
    {
        return $this->filename;
    }

    public function setReadMode($mode = "") 
    {
        $this->readmode = $mode;
    }

    public function getReadMode() 
    {
        return $this->readmode;
    }

    protected function setClearMode($mode = "") 
    {
        $this->clearmode = $mode;
    }

    public function getClearMode() 
    {
        return $this->clearmode;
    }

    protected function setInsertMode($mode = "") 
    {
        $this->insertmode = $mode;
    }

    public function getInsertMode() 
    {
        return $this->insertmode;
    }
#endregion
}
?>