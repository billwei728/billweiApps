<?php
Namespace App\datastore;

Use App\base;
Use Exception;

class filedata_store extends base
{
    public function __construct()
    {
    }

    protected function readFile()  
    {
        $filename = $this->getFileName();
        $mode = $this->getReadMode();
        $list = array();

        try {
            if (! file_exists($filename)) {
                throw new Exception($filename . " Not Found.");
            }
            $fHandler = fopen($filename, $mode);
            if ($fHandler) {
                while (! feof($fHandler)) {
                    $line = fgets($fHandler);
                    $list[] = explode("||", $line);
                }
                $this->log('[' . get_parent_class($this) . ' - ' . __FUNCTION__ . '] ' . $filename . " Reading File.", M_LOG_INFO);
                fclose($fHandler);
            } else {
                throw new Exception($filename . " Open Failed.");
            }
            if (end($list)) array_pop($list); // Check Last Array
        } catch (Exception $errMsg) {
            $this->log('[' . get_parent_class($this) . ' - ' . __FUNCTION__ . '] ' . $filename . " Read Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }

        return $this->dataAllocate($list);
    }

    protected function clearFile()  
    {
        $filename = $this->getFileName();
        $mode = $this->getClearMode();

        try {
            if (! file_exists($filename)) {
                throw new Exception($filename . " Not Found.");
            }
            $fHandler = fopen($filename, $mode);
            if (! $fHandler) {
                throw new Exception($filename . " Open Failed.");
            }
            fclose($fHandler);
        } catch (Exception $errMsg) {
            $this->log('[' . get_parent_class($this) . ' - ' . __FUNCTION__ . '] ' . $filename . " Clear Failed." . "Message : " . $errMsg, M_LOG_ERROR);
        }

        return true;
    }

    protected function insertFile($arrlist = array())
    {
        $filename = $this->getFileName();
        $mode = $this->getInsertMode();

        try {
            foreach ($arrlist as $key => $value) {
                if (! file_exists($filename)) {
                    throw new Exception($filename . " Not Found.");
                }
                $fHandler = fopen($filename, $mode);
                if ($fHandler) {
                    $fwrite = fwrite($fHandler, $value);
                    fclose($fHandler);
                    if ($fwrite === false) {
                        $this->log('[' . get_parent_class($this) . ' - ' . __FUNCTION__ . '] ' . "File Write Failed : " . json_encode($value), M_LOG_ERROR);
                        return false;
                    }
                } else {
                    throw new Exception($filename . " Open Failed.");
                }
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_parent_class($this) . ' - ' . __FUNCTION__ . '] ' . $filename . " Insert Failed." . "Message : " . $errMsg, M_LOG_ERROR);
            return false;
        }

        return true;
    }
    
    protected function dataAllocate($arrlist = array())  
    {
        $list = array();
        $listTitle = $this->getListTitle();

        foreach ($arrlist as $key => $value) {
            foreach ($value as $subKey => $subvalue) {
                $list[$key][$listTitle[$subKey]] = $subvalue;
            }
        }

        return $list;
    }
}
?>