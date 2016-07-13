<?php

define("_LOGPATH_", "logs/");



require_once("classes/class.debug.php");
require_once("classes/class.controller.index.php");
require_once("classes/class.abstract.pdo.php");

session_start();

$myController = new IndexController();

$myController->handleRequest();