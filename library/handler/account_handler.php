<?php
Namespace App\handler;

Use App\worker\account_worker;
Use Exception;

	$account_worker = new account_worker();
    $result = array();
   
    if (isset($_POST["account_action"]) || isset($_POST["account_action_new"])) {
        $result['account'] = $account_worker->doAction($_POST);
    } else {
        $arrParams['account_action'] = "select";
        $result['account'] = $account_worker->doAction($arrParams);
    }

    // echo '<pre>'; print_r($_POST); echo '</pre>';
?>