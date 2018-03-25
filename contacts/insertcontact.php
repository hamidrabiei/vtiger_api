<?php

Class insertContact {


	public function getData($db)
	{

		$stmt = "SELECT MAX(crmid)+1 FROM vtiger_crmentity;";


		$stmt.= "SELECT CONCAT('CON',MAX(CAST((SUBSTRING(contact_no,4)+1) as UNSIGNED))) FROM vtiger_contactdetails;";

		if ($db->multi_query($stmt)) {
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


	public function insertContacts($db, $d)
	{

		$content 				=	file_get_contents("php://input");
		$decoded 				= 	json_decode($content, true);
		$firstname 			=	$this->check_input($decoded['firstname']);
		$lastname 			=	$this->check_input($decoded['lastname']);
		$email					=	$this->check_input($decoded['email']);
		$phone					=	$this->check_input($decoded['phone']);
		$title					=	$this->check_input($decoded['title']);
		$mailingcity		=	$this->check_input($decoded['mailingcity']);
		$mailingcountry = 	$this->check_input($decoded['mailingcountry']);
		$mobile 				=	$this->check_input($decoded['mobile']);
		$fax						=	$this->check_input($decoded['fax']);
		$department 		= 	$this->check_input($decoded['departament']);
		$reportsto			=	$this->check_input($decoded['reportsto']);
		$otheremail 		=	$this->check_input($decoded['otheremail']);
		$secondaryemail = 	$this->check_input($decoded['secondaryemail']);
		$donotcall			=	$this->check_input($decoded['donotcall']);
		$emailoptout		=	$this->check_input($decoded['emailoptout']);
		$reference  		=	$this->check_input($decoded['reference']);
		$notify_owner 	=	$this->check_input($decoded['notify_owner']);
		$isconvertedfromlead	=	$this->check_input($decoded['isconvertedfromlead']);
		$mailingstreet		=	$this->check_input($decoded['mailingstreet']);
		$mailingstate		=	$this->check_input($decoded['mailingstate']);
		$mailingpobox		=	$this->check_input($decoded['mailingpobox']);
		$othercity			=	$this->check_input($decoded['othercity']);
		$otherstate			=	$this->check_input($decoded['otherstate']);
		$mailingzip			=	$this->check_input($decoded['mailingzip']);
		$otherzip				=	$this->check_input($decoded['otherzip']);
		$otherstreet		=	$this->check_input($decoded['otherstreet']);
		$otherpobox			=	$this->check_input($decoded['otherpobox']);

		$contactid 			=	$d[0][0];
		$contact_no			=	$d[1][0];
		$createdtime 		= gmdate('Y-m-d h:i:s \G\M\T');
		$label 					=	$firstname .' '. $lastname;


		if($lastname)
		{

			$stmt  = "UPDATE vtiger_crmentity_seq SET id = id + 1;";


			$stmt .= "INSERT INTO vtiger_crmentity
			(crmid, smcreatorid, smownerid, modifiedby, setype, description, createdtime, modifiedtime, presence, deleted, label)
			VALUES ('$contactid', '1', '1', '1', 'Contacts', '','$createdtime', '$createdtime', '1', '0', '$label');";

			$stmt .= "INSERT INTO vtiger_contactscf (contactid) VALUES ('$contactid');";

			$stmt .= "INSERT INTO vtiger_contactaddress
			(contactaddressid, mailingcity, mailingstreet, mailingcountry, mailingstate, mailingpobox, othercity, otherstate,
			mailingzip, otherstreet, otherpobox)
			VALUES ('$contactid', '$mailingcity', '$mailingstreet', '$mailingcountry', '$mailingstate', '$mailingpobox',
			'$othercity','$otherstate', '$mailingzip', '$otherstreet', '$otherpobox');";

			$stmt .= "INSERT INTO vtiger_contactdetails
			(contactid, contact_no, accountid, firstname, lastname, email, phone, mobile, title, department, fax, reportsto,
			otheremail, secondaryemail, donotcall, emailoptout, reference, notify_owner, isconvertedfromlead)
			VALUES ('$contactid', '$contact_no', '$accountid', '$firstname', '$lastname', '$email', '$phone', '$mobile', '$title',
			'$department', '$fax', '$reportsto', '$otheremail', '$secondaryemail', '$donotcall', '$emailoptout', '$reference',
			'$notify_owner', '$isconvertedfromlead');";



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

			//echo ' {"success": "id": "'.$contactid.'", "lastname": "'.$lastname.'"}';
			$contact_add_arr = array();
				$contact_add_arr["contacts"] = array();
				$contact_item 	=	array(
					"id"=>$ticketid,
					"title"=>$title
					);
				array_push($contact_add_arr["contacts"],$contact_item);
		} else {
			echo ' {"error": "something went wrong"}';
		}

	return json_encode($contact_add_arr);

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
