<?php
Namespace App\worker;

Use App\datastore\clearlog_store;
Use Exception;

class clearlog_worker extends clearlog_store
{
    public function __construct()
    {
        $this->setFileName(LOG . "gtlog.log");
        $this->setClearMode("w");
    }

    public function doAction($action) 
    {
        try {
            if ("clear" == $action) {
                return $this->clear();
            }
        } catch (Exception $errMsg) {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . "Message : " . $errMsg, M_LOG_ERROR);
            throw new Exception($errMsg);
        }
    }

    private function clear()  
    {
        if ($this->clearFile()) {
            // return $this->returnPage(1, "deleted", "success");
        } else {
            $this->log('[' . get_called_class() . ' - ' . __FUNCTION__ . '] ' . $this->getFileName() . " Clear Failed.", M_LOG_ERROR);
            throw new Exception($this->getFileName() . " Clear Failed.");
        }
    }
}
?>