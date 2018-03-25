<?php
	include_once '../config/database.php';
	include_once '../login/login.php';
	//include_once '../config/header.php';
	include_once '../config/const.php';

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp, secret');
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

	// Database & contact
	$database = new Database();
	$db = $database->connect();

	$login 	= new Login();
	$method = $_SERVER['REQUEST_METHOD'];


	$getlogin = $login->checkLogin($db);

	echo $getlogin;



?>
