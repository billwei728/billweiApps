<?php
Namespace App\datastore;

Use App\base;
Use Exception;

class clearlog_store extends filedata_store
{
    protected $filename;
    protected $clearmode;


    public function __construct()
    {
    }


#region -------------------- Settings --------------------
    protected function setFileName($file = "") 
    {
        $this->filename = $file;
    }

    public function getFileName()
    {
        return $this->filename;
    }

    protected function setClearMode($mode = "") 
    {
        $this->clearmode = $mode;
    }

    public function getClearMode() 
    {
        return $this->clearmode;
    }
#endregion
}
?>