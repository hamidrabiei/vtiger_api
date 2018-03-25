<?php

Class editContact {

	public function editContacts($db, $id)
	{
		if(is_numeric($id)){

			$content 					= 	file_get_contents("php://input");
      $decoded 					= 	json_decode($content, true);
			$firstname 				=	$this->check_input($decoded['firstname']);
			$lastname 				=	$this->check_input($decoded['lastname']);
			$email						=	$this->check_input($decoded['email']);
			$phone						=	$this->check_input($decoded['phone']);
			$title						=	$this->check_input($decoded['title']);
			$mailingcity			=	$this->check_input($decoded['mailingcity']);
			$mailingcountry 	= 	$this->check_input($decoded['mailingcountry']);
			$mobile 					=	$this->check_input($decoded['mobile']);
			$fax							=	$this->check_input($decoded['fax']);
			$department 			= 	$this->check_input($decoded['departament']);
			$reportsto				=	$this->check_input($decoded['reportsto']);
			$otheremail 			=	$this->check_input($decoded['otheremail']);
			$secondaryemail 	= 	$this->check_input($decoded['secondaryemail']);
			$donotcall				=	$this->check_input($decoded['donotcall']);
			$emailoptout			=	$this->check_input($decoded['emailoptout']);
			$reference  			=	$this->check_input($decoded['reference']);
			$notify_owner 		=	$this->check_input($decoded['notify_owner']);
			$isconvertedfromlead	=	$this->check_input($decoded['isconvertedfromlead']);
			$mailingstreet		=	$this->check_input($decoded['mailingstreet']);
			$mailingstate			=	$this->check_input($decoded['mailingstate']);
			$mailingpobox			=	$this->check_input($decoded['mailingpobox']);
			$othercity				=	$this->check_input($decoded['othercity']);
			$otherstate				=	$this->check_input($decoded['otherstate']);
			$mailingzip				=	$this->check_input($decoded['mailingzip']);
			$otherzip					=	$this->check_input($decoded['otherzip']);
			$otherstreet			=	$this->check_input($decoded['otherstreet']);
			$otherpobox				=	$this->check_input($decoded['otherpobox']);


			$stmt = "UPDATE vtiger_contactdetails
			SET firstname  			= (case when '$firstname' = '' then firstname else '$firstname' end),
				lastname   			= (case when '$lastname' = '' then lastname else '$lastname' end),
				email      			= (case when '$email' = '' then email else '$email' end),
				phone      			= (case when '$phone' = '' then phone else '$phone' end),
				title      			= (case when '$title' = '' then title else '$title' end),
				mobile	   			= (case when '$mobile' = '' then mobile else '$mobile' end),
				department 			= (case when '$department' = '' then department else '$department' end),
				fax 	   			= (case when '$fax' = '' then fax else '$fax' end),
				reportsto  			= (case when '$reportsto' = '' then reportsto else '$reportsto' end),
				otheremail 			= (case when '$otheremail' = '' then otheremail else '$otheremail' end),
				secondaryemail		= (case when '$secondaryemail' = '' then secondaryemail else '$secondaryemail' end),
				donotcall			= (case when '$donotcall' = '' then donotcall else '$donotcall' end),
				emailoptout			= (case when '$emailoptout' = '' then emailoptout else '$emailoptout' end),
				reference 			= (case when '$reference' = '' then reference else '$reference' end),
				notify_owner		= (case when '$notify_owner' = '' then notify_owner else '$notify_owner' end),
				isconvertedfromlead	= (case when '$isconvertedfromlead' = '' then isconvertedfromlead else '$isconvertedfromlead' end)
			WHERE contactid = '$id';";

			$stmt.=	"UPDATE vtiger_contactaddress
			SET mailingcity			= (case when '$mailingcity' = '' then mailingcity else '$mailingcity' end),
				mailingcountry		= (case when '$mailingcountry' = '' then mailingcountry else '$mailingcountry' end),
				mailingstreet		= (case when '$mailingstreet' = '' then mailingstreet else '$mailingstreet' end),
				mailingstate		= (case when '$mailingstate' = '' then mailingstate else '$mailingstate' end),
				mailingpobox		= (case when '$mailingpobox' = '' then mailingpobox else '$mailingpobox' end),
				othercity			= (case when '$othercity' = '' then othercity else '$othercity' end),
				otherstate			= (case when '$otherstate' = '' then otherstate else '$otherstate' end),
				mailingzip			= (case when '$mailingzip' = '' then mailingzip else '$mailingzip' end),
				otherzip			= (case when '$otherzip' = '' then otherzip else '$otherzip' end),
				otherstreet			= (case when '$otherstate' = '' then otherstate else '$otherstate' end),
				otherpobox			= (case when '$otherpobox' = '' then otherpobox else '$otherpobox' end)
			WHERE contactaddressid = '$id';";

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

			$data_arr = array();
            $data_arr["contacts"] = array();
            $contact_edit_item 	=	array(
                "id"=>$id,
                "title"=>$lastname
                );
          	array_push($data_arr["contacts"],$contact_edit_item);

		} else {
			$data_arr = array();
            $data_arr["contacts"] = array();
            $contact_edit_item 	=	array(
                "error" => "something went wrong"
                );
          	array_push($data_arr["contacts"],$contact_edit_item);
		}


		return json_encode($data_arr);

	}

	public function check_input($input)
	{
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);

		return $input;
	}


}


?>
