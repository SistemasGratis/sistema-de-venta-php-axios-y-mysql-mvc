<?php
require_once 'config.php';
require_once 'controllers/logincontroller.php';
$login = new Login();
$login->index();