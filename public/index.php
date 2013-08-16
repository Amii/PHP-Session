<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function autoload($className) {
	$className = str_replace('\\', '/', $className);
	include dirname(dirname(__DIR__)) . '/' . $className . '.php';
};

spl_autoload_register('autoload');

include dirname(__DIR__) . '/config/constants.php';

if (!isset($_POST['page']) || !in_array($_POST['page'], unserialize(PAGES))) {
	$_POST['page'] = 'login';
}

$controller = new session\controller\Controller($_POST['page']);
$controller->main($_POST);
