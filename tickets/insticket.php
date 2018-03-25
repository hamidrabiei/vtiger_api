<?php
error_reporting(0);
Class insertTicket {


	public function changeToName($db,$method) {


		if($method == 'PUT') {

			$content 	= parse_str(file_get_contents('php://input'), $_PUT);

			$priority 	= $_PUT['priority'];
			$severity 	= $_PUT['severity'];
			$status 	= $_PUT['status'];
			$category	= $_PUT['category'];


		} else {

			$content 	= file_get_contents("php://input");
			$decoded 	= json_decode($content, true);

			$priority 	= $decoded['priority'];
			$severity 	= $decoded['severity'];
			$status 	= $decoded['status'];
			$category 	= $decoded['category'];

		}


			$sql = "SELECT MAX(crmid)+1 FROM vtiger_crmentity;";

			$sql .= "SELECT CONCAT('TT',MAX(CAST((SUBSTRING(ticket_no,3)+1) as UNSIGNED))) FROM vtiger_troubletickets;";

			$sql .= "SELECT IFNULL( (SELECT ticketpriorities FROM vtiger_ticketpriorities WHERE ticketpriorities_id = '$priority') ,'');";

			$sql .="SELECT IFNULL( (SELECT ticketseverities FROM vtiger_ticketseverities WHERE ticketseverities_id = '$severity') ,'');";

			$sql .= "SELECT IFNULL( (SELECT ticketstatus FROM vtiger_ticketstatus WHERE ticketstatus_id = '$status') ,'');";

			$sql .= "SELECT IFNULL( (SELECT ticketcategories FROM vtiger_ticketcategories WHERE ticketcategories_id = '$category') ,'');";




			if ($db->multi_query($sql)) {
		    	do {

		        if ($result = $db->store_result()) {
		            while ($row = $result->fetch_row()) {


		                $d[] = $row;

		            }
		            	$result->free();
		        }

		        if ($db->more_results()) {

		        	}

		    	} while ($db->next_result());
			}



	return $d;

	}

	public function insertTickets($db,$d) {

		$content 			= file_get_contents("php://input");
		$decoded 			= json_decode($content, true);

		$groupname 		= $this->check_input($decoded['groupname']);
		$parent_id 		= $decoded['parent_id'];
		$product_id 	= $decoded['product_id'];
		$last_name		= $decoded['last_name'];	// Assigned To
		if($last_name == '') {$last_name = 1;}
		$title 				= $this->check_input($decoded['title']);
		$solution 		= $this->check_input($decoded['solution']);
		$hours 				= $decoded['hours'];
		$days 				= $decoded['days'];
		$contact_id 	= $decoded['contact_id'];
		$description 	= $this->check_input($decoded['description']);
		$createdtime 	= gmdate('Y-m-d h:i:s \G\M\T');




		if(!is_numeric($parent_id) && !empty($parent_id) || !is_numeric($product_id) && !empty($product_id) || !is_numeric($hours) && !empty($hours) || !is_numeric($days) && !empty($days) || !is_numeric($contact_id) && !empty($contact_id)) {

			echo '{ "error": "should be number" }';

		} else {




			$ticketid 		= $d[0][0];
			$ticket_no 		= $d[1][0];
			$priority 		= $d[2][0];
			$severity 		= $d[3][0];
			$status 			= $d[4][0];
			$category 		= $d[5][0];


		 	if($last_name)
		 	{


				$stmt = "UPDATE vtiger_crmentity_seq SET id = id + 1;";


				$stmt .= "INSERT INTO vtiger_crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime,presence,deleted,label)
				VALUES ('$ticketid', '$last_name', '$last_name', '$last_name', 'HelpDesk', '$description','$createdtime', '$createdtime', '1', '0', '$title');";

				$stmt .= "INSERT INTO vtiger_ticketcf (ticketid, from_portal) VALUES ('$ticketid', '0');";

				$stmt .= "INSERT INTO vtiger_troubletickets (ticketid, ticket_no, groupname, parent_id, product_id, priority, severity,status,category,title,solution,hours,days,contact_id)
				VALUES ('$ticketid','$ticket_no', '$groupname', '$parent_id','$product_id','" .mysqli_real_escape_string($db,$priority). "','" .mysqli_real_escape_string($db,$severity). "','" .mysqli_real_escape_string($db,$status). "','" .mysqli_real_escape_string($db,$category). "','$title','$solution','$hours','$days','$contact_id');";



				if ($db->multi_query($stmt)) {
			    	do {

			        if ($result = $db->store_result()) {
			            while ($row = $result->fetch_row()) {


			            }
			            	$result->free();
			        }

			        if ($db->more_results()) {

			        	}

			    	} while ($db->next_result());
				}

				//echo ' {"success": "id": "'.$ticketid.'", "title": "'.$title.'"}';
				$data_arr = array();
				$data_arr["tickets"] = array();
				$ticket_item 	=	array(
					"id"=>$ticketid,
					"title"=>$title
					);
				array_push($data_arr["tickets"],$ticket_item);
			} else {

				echo ' {"error": "something went wrong"}';
			}
		}
		return json_encode($data_arr);
	}

	public function check_input($input) {

		if(!empty($input))
		{
			$input = filter_var($input, FILTER_SANITIZE_STRING);
		} else {
			$input = "";
		}
		return $input;
	}

}

?>
