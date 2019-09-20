<?php

class menustore
{
    public $menuFile;
    public $modes;

    public function __construct()
    {
        $this->menuFile = MENU . "menu.log";
        $this->modes = "r";
    }

    public function menuActions($arrParams) 
    {
        $action = $arrParams['menuAction'];
        // echo 'Input Name - ' . htmlspecialchars($params);

        if ('save' == $action) {
            try {
                $this->save($arrParams);
            } catch (Exception $errMsg) {
                echo $errMsg;
                // return $errMsg;$errMsg;
            }
        }

        // $this->save($params, $arMsgType);
        // $result['supportGateway'] = $supportGateway;
        // $result['selectedGateway'] = $gatewayReq;
        // $result['xrefId'] = $xrefId;

        // return $result;
    }

    function save($arrMenu)  
    {
        //define empty string                                 
        $stEntry = "";
        //define lastest rank
        $menuNewRank = $this->getNewRank($arrMenu['inputEnv'], $arrMenu['inputType']);

        $stEntry .= $this->getNewIndex() . "||" . $arrMenu['inputEnv'] . "||" . $arrMenu['inputType'] . "||" . $menuNewRank . "||" . $arrMenu['inputMName'] . "||" . $arrMenu['inputMAddrs'] . "||" . 1 . "\r\n";  

        $stCurLogFileName = $this->menuFile;
        //open the file append mode,dats the log file will create day wise  
        $fHandler = fopen($stCurLogFileName, 'a+');  
        //write the info into the file  
        fwrite($fHandler, $stEntry);  
        //close handler  
        fclose($fHandler);
    }

    function getNewIndex()  
    {
        $file = escapeshellarg($this->menuFile); // for the security concious (should be everyone!)
        $line = `tail -n 1 $file`;
        $array = explode("||",$line);

        return ++$array[0];
    }

    function getNewRank($menuTitle, $menuSubTitle)  
    {
        $arrMenuList = $this->menuAllocate();
        $menuList = array();
        foreach ($arrMenuList as $key => $value) {
            if ($menuTitle == $arrMenuList[$key]['env']) {
                if ($menuSubTitle == $arrMenuList[$key]['type']) {
                    $menuList[] = $arrMenuList[$key];
                }
            }
        }       

        $countMenus = count($menuList);
        $init = 0;
        $lastRank = 0;
        foreach ($menuList as $key => $value) {
            if (++$init === $countMenus) {
                $lastRank = $menuList[$key]['rank'];
            }
        }

        return ++$lastRank;
    }

    function readFile()  
    {
        if ($fh = fopen($this->menuFile, $this->modes)) {
            while (! feof($fh)) {
                $line = fgets($fh);
                $menuListPrev[] = explode("||", $line);
            }
            fclose($fh);
        }
        // Check Last Array
        if (end($menuListPrev)) {
            array_pop($menuListPrev); 
        }

        foreach ($menuListPrev as $key =>  $value) {
            foreach ($value as $subKey => $subvalue) {
                $menuList[$key][$subKey] = $subvalue;
            }
        }

        return $menuList;
    }

    function menuAllocate()  
    {
        $arrMenu = $this->readFile();
        $arrMenuList = array();
        $arrMenuTitle = array('id', 'env', 'type', 'rank', 'name', 'url', 'ver');

        // Relocate name to each array
        foreach ($arrMenu as $key => $value) {
            foreach ($value as $subKey => $subvalue) {
                $arrMenuList[$key][$arrMenuTitle[$subKey]] = $subvalue;
            }
        }

        return $arrMenuList;
    }
}

/** ----------------------------------------------------------------------------------------------------------------- */


    $menustore = new menustore();

    if (isset($_POST["menuAction"])) {
        $menustore->menuActions($_POST);
    }


/** ----------------------------------------------------------------------------------------------------------------- */
?>