<?php
Namespace App\handler;

Use App\worker\clearlog_worker;
Use Exception;

	$clearlog_worker = new clearlog_worker();
    $result = $clearlog_worker->doAction("clear"); 
?>