<?php
Namespace App\datastore;

Use App\base;
Use Exception;

class account_store extends filedata_store
{
    protected $filename;
    protected $readmode;
    protected $clearmode;
    protected $insertmode;


    public function __construct()
    {
    }

#region -------------------- Settings --------------------
    protected function getListTitle() 
    {
        return array("id", "name", "userid", "password", "rank", "remark", "ver");
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