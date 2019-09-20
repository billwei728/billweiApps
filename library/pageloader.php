<?php 
Namespace App;

Use App\worker\module_worker;

    $page = 'home';
    if (isset($_SESSION['page'])) {
        $page = $_SESSION['page'];
    }
    $module = explode("_", $page);

    if (isset($page)) {
        $filename = PAGES . $page . '.php';
        if (file_exists($filename)) {
            include_once(WORKER . $module[0] . "_worker.php");
            include_once(HANDLER . $module[0] . "_handler.php");
            include_once(PAGES . $page . ".php");
        } elseif ($page == "home") {
        	include_once(PAGES . $page . ".html");
        } else {
            include_once(PAGES . "404.html");
        }
    }
?>