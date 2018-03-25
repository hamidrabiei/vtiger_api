<?php

Class editTicket {

	public function changeToName($db,$method) {

		if($method == 'PUT') {

      $content 	= file_get_contents("php://input");
      $decoded 	= json_decode($content, true);
			$priority 	= $decoded['priority'];
			$severity 	= $decoded['severity'];
			$status 	= $decoded['status'];
			$category	= $decoded['category'];


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

	public function editTickets($db, $id, $d)
	{

		if(is_numeric($id)) {
            $this->changeToName($db, $method);
            $content 		= file_get_contents("php://input");
            $decoded 		= json_decode($content, true);
            $groupname 	= $this->check_input($decoded['groupname']);
            $parent_id 	= $decoded['parent_id'];
            $product_id = $decoded['product_id'];
            $title 			= $this->check_input($decoded['title']);
            $solution 	= $this->check_input($decoded['solution']);
            $hours 			= $decoded['hours'];
            $days 			= $decoded['days'];
            $contact_id = $decoded['contact_id'];
            $description = $this->check_input($decoded['description']);
            $createdtime = gmdate('Y-m-d h:i:s \G\M\T');

            if (!is_numeric($parent_id) && !empty($parent_id) || !is_numeric($product_id) && !empty($product_id) || !is_numeric($hours) && !empty($hours) || !is_numeric($days) && !empty($days) || !is_numeric($contact_id) && !empty($contact_id)) {

                echo '{ "error": "should be number" }';

            } else {

                $priority = $d[2][0];
                $severity = $d[3][0];
                $status = $d[4][0];
                $category = $d[5][0];


                $stmt = "UPDATE vtiger_crmentity
					SET label = (case when '$title' = '' then label else '$title' end),
						description = (case when '$description' = '' then description else '$description' end)
					WHERE crmid = '$id' AND setype = 'HelpDesk';";

                $stmt .= "UPDATE vtiger_troubletickets
					SET groupname = (case when '$groupname' = '' then groupname else '$groupname' end),
						parent_id = (case when '$parent_id' = '' then parent_id else '$parent_id' end),
						product_id = (case when '$product_id' = '' then product_id else '$product_id' end),
						priority = (case when '$priority' = '' then priority else '$priority' end),
						severity = (case when '$severity' = '' then severity else '$severity' end),
						status = (case when '$status' = '' then status else '$status' end),
						category = (case when '$category' = '' then category else '$category' end),
						title = (case when '$title' = '' then title else '$title' end),
						solution = (case when '$solution' = '' then solution else '$solution' end),
						hours = (case when '$hours' = '' then hours else '$hours' end),
						days = (case when '$days' = '' then days else '$days' end),
						contact_id = (case when '$contact_id' = '' then contact_id else '$contact_id' end)
					WHERE ticketid = '$id';";


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
                //echo ' {"success": "id": "' . $id . '", "title": "' . $title . '"}';
                $data_arr = array();
                $data_arr["tickets"] = array();
                $ticket_edit_item 	=	array(
                    "id"=>$ticketid,
                    "title"=>$title
                );
                array_push($data_arr["tickets"],$ticket_edit_item);

								return json_encode($data_arr);

            }
        }
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
