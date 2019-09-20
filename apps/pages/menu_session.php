<?php
ini_set('display_errors', 1);
session_start();
$_SESSION['page'] = $_GET["page"];
if ($_GET["page"]) {
	setcookie('page', $_GET["page"], time() + (3), "/");
}
if ($_GET["total"]) {
	setcookie('total', $_GET["total"], time() + (3), "/");
}
if ($_GET["result"]) {
	setcookie('result', $_GET["result"], time() + (3), "/");
}
if ($_GET["action"]) {
	setcookie('action', $_GET["action"], time() + (3), "/");
}
header("Location: " . $_GET["url"]);
?>