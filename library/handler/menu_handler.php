<?php
Namespace App\handler;

Use App\worker\menu_worker;
Use Exception;

	$menu_worker = new menu_worker();
    $result = array();
    
    if (isset($_POST["menu_action"]) || isset($_POST["menu_action_new"])) {
        $result['menu'] = $menu_worker->doAction($_POST);
    } else {
        $arrParams['menu_action'] = "select";
        $result['menu'] = $menu_worker->doAction($arrParams);
    }

    $result['optModule'] = $menu_worker->doPopulate("module");
    $result['optParent'] = $menu_worker->doPopulate("parent");
?>