<?php
Namespace App\handler;

Use App\worker\module_worker;
Use Exception;

	$module_worker = new module_worker();
    $result = array();
   
    if (isset($_POST["module_action"]) || isset($_POST["module_action_new"])) {
        $result['module'] = $module_worker->doAction($_POST);
    } else {
        $arrParams['module_action'] = "select";
        $result['module'] = $module_worker->doAction($arrParams);
    }
?>