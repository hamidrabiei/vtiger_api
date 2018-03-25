<?php
	error_reporting(E_ALL);

	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp');
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

	include_once '../config/database.php';
	include_once '../tickets/getticket.php';
	include_once '../tickets/delticket.php';
	include_once '../tickets/editticket.php';
	include_once '../tickets/insticket.php';
	include_once '../login/checklogin.php';



	$database = new Database();
	$db = $database->connect();

	$getticket 	 = 	new getTicket();
	$delticket 	 =	new deleteTicket();
	$editticket  =  new editTicket();
	$insticket 	 =	new insertTicket();
	$check  	 =  new checkAccess();


	$method = $_SERVER['REQUEST_METHOD'];

	$check1 = $check->giveAccess($db);



            if($check1) {


                switch ($method) {

                    case 'GET':
                        $id    = $_GET['id'];
                        $getid = $getticket->getId($db);
                        $comment = $getticket->getComments($db, $getid);
                        $read = $getticket->getTickets($db, $comment,$id);
                        echo $read;
                        break;

                    case 'POST':
                        $data = $insticket->changeToName($db, $method);
                        $add = $insticket->insertTickets($db, $data);
                        echo $add;
                        break;

                    case 'PUT':
                        $ids = $_GET['id'];
                        $data = $editticket->changeToName($db, $method);
                        $edit = $editticket->editTickets($db, $ids, $data);
                        echo $edit;
                        break;

                    case 'DELETE': 
                        $ids = $_GET['id'];
                        $delete = $delticket->deleteTickets($db, $ids);
                        echo $delete;
                        break;

                    default:
                        echo "Klaida";
                        break;
                }

       } else {
            $text = 'wrong id/token or module is turned off';
            $error_arr = array();
            $error_arr["error"] = array();
            $error_item            =  array(

                "message"          => $text,
                );

            array_push($error_arr["error"],$error_item);
            echo json_encode($error_arr);
       }






?>
