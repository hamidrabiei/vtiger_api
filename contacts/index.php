<?php
	error_reporting(E_PARSE);
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp');
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
	// Include
	include_once '../contacts/getcontact.php';
	include_once '../contacts/editcontact.php';
	include_once '../contacts/delcontact.php';
	include_once '../contacts/insertcontact.php';
	include_once '../config/database.php';
	include_once '../login/checklogin.php';


	// Database & contact
	$database = new Database();
	$db = $database->connect();

	$getcontact		= new getContact();
	$editcontact 	= new editContact();
	$delcontact 	= new deleteContact();
	$inscontact 	= new insertContact();
	$check				= new checkAccess();


	$method = $_SERVER['REQUEST_METHOD'];

	$check1 = $check->giveAccess($db);

		if($check1) {
            switch ($method) {
                case 'GET': // skirtas nuskaityti ticket'us
                    $getcontacts = $getcontact->getContacts($db);
                    echo $getcontacts;
                    break;
                case 'POST': // skirtas sukurti nauja ticket'a
                    $data = $inscontact->getData($db);
                    $inscontacts = $inscontact->insertContacts($db, $data);

                case 'PUT': // skirtas modifikuoti ticket'a
                    $id = $_GET['id'];
                    $editcontacts = $editcontact->editContacts($db, $id);
                    echo $editcontacts;
                    break;
                case 'DELETE': // skirtas istrinti ticket'a
                    $id = $_GET['id'];
                    $delcontacts = $delcontact->deleteContacts($db, $id);
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
