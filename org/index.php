<?php
	error_reporting(E_PARSE);

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp');
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

	include_once '../config/database.php';
	include_once '../org/getorganisation.php';
	include_once '../org/insorganisation.php';
	include_once '../org/delorganisation.php';
	include_once '../org/editorganisation.php';
	include_once '../login/checklogin.php';


	$database = new Database();
	$db 	  = $database->connect();

	$getorganisation  =	 new getOrganisation();
	$insorganisation  =	 new insertOrganisation();
	$editorganisation =	 new editOrganisation();
	$delorganisation  =  new deleteOrganisation();




	$method = $_SERVER['REQUEST_METHOD'];



            switch ($method) {
                case 'GET':
                    $read = $getorganisation->getOrganisations($db);
                    echo $read;
                    break;
                case 'POST':
                    $data = $insorganisation->getData($db, $method);
                    $post = $insorganisation->insertOrganisations($db, $data);
                    echo $post;
                case 'PUT':
                    $id = $_GET['id'];
                    $data = $editorganisation->getData($db, $method);
                    $edit = $editorganisation->editContact($db, $id, $data);
                    break;
                case 'DELETE':
                    $id = $_GET['id'];
                    $delete = $delorganisation->deleteOrganisations($db, $id);
                    break;
                default:
                    echo "Klaida";
                    break;

									}

?>
