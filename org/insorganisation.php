<?php

Class insertOrganisation {

	public function insertOrganisations($db,$d)
	{

		$content 				=	file_get_contents("php://input");
		$decoded 				= 	json_decode($content, true);
		$accountname		=	$this->check_input($decoded['accountname']);
		$parentid				=	$this->check_input($decoded['parentid']);
		$annualrevenue	=	$this->check_input($decoded['annualrevenue']);
		$ownership			=	$this->check_input($decoded['ownership']);
		$siccode				=	$this->check_input($decoded['siccode']);
		$tickersymbol		=	$this->check_input($decoded['tickersymbol']);
		$phone					=	$this->check_input($decoded['phone']);
		$otherphone			=	$this->check_input($decoded['otherphone']);
		$email1					=	$this->check_input($decoded['email1']);
		$email2					=	$this->check_input($decoded['email2']);
		$website				=	$this->check_input($decoded['website']);
		$fax						=	$this->check_input($decoded['fax']);
		$employees			=	$this->check_input($decoded['employees']);
		$emailoptout		=	$this->check_input($decoded['emailoptout']);
		$notify_owner		=	$this->check_input($decoded['notify_owner']);
		$isconvertedfromlead	=	$this->check_input($decoded['isconvertedfromlead']);
		$bill_city			=	$this->check_input($decoded['bill_city']);
		$bill_code			=	$this->check_input($decoded['bill_code']);
		$bill_country		=	$this->check_input($decoded['bill_country']);
		$bill_state			=	$this->check_input($decoded['bill_state']);
		$bill_street		=	$this->check_input($decoded['bill_street']);
		$bill_pobox			=	$this->check_input($decoded['bill_pobox']);

		$accountid 			= $d[0][0];
		$account_no 		=	$d[1][0];
		$account_type		=	$d[2][0];
		$industry 			=	$d[3][0];
		$rating 				=	$d[4][0];
		$createdtime		=	gmdate('Y-m-d h:i:s \G\M\T');
		$label					=	$accountname;


		if($accountname) {


			$stmt  = "UPDATE vtiger_crmentity_seq SET id = id + 1;";

			$stmt .= "INSERT INTO vtiger_crmentity
			(crmid, smcreatorid, smownerid, modifiedby, setype, description, createdtime, modifiedtime, presence, deleted, label)
			VALUES ('$accountid', '1', '1', '1', 'Accounts', '','$createdtime', '$createdtime', '1', '0', '$label');";

			$stmt .= "INSERT INTO vtiger_accountscf (accountid) VALUES ('$accountid');";

			$stmt .= "INSERT INTO vtiger_account
			(accountid, account_no, accountname, parentid, account_type, industry, annualrevenue, rating, ownership, siccode,
			tickersymbol, phone, otherphone, email1, email2, website, fax, employees, emailoptout, notify_owner, isconvertedfromlead)
			VALUES ('$accountid', '$account_no', '$accountname', '$parentid', '$account_type', '$industry', '$annualrevenue', '$rating', '$ownership', '$siccode',
			'$tickersymbol', '$phone', '$otherphone', '$email1', '$email2', '$website', '$fax', '$employees', '$emailoptout', '$notify_owner', '$isconvertedfromlead');";

			$stmt .="INSERT INTO vtiger_accountbillads
			(accountaddressid, bill_city, bill_code, bill_country, bill_state, bill_street, bill_pobox)
			VALUES ('$accountid', '$bill_city', '$bill_code', '$bill_country', '$bill_state', '$bill_street', '$bill_pobox');";

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
			//echo ' {"success": "id": "'.$accountid.'", "accountname": "'.$accountname.'"}';
			$data_arr = array();
			$data_arr["organisations"] = array();
			$ticket_item 	=	array(
				"id"=>$accountid,
				"accountname"=>$accountname
				);
			array_push($data_arr["organisations"],$ticket_item);

		} else {
			echo ' {"error": "something went wrong"}';
		}

	}

	public function getData($db, $method)
	{
		if($method == 'PUT') {

			$content = parse_str(file_get_contents('php://input'), $_PUT);

			$account_type 	= $_PUT['account_type'];
			$industry 		= $_PUT['industry'];
			$rating			= $_PUT['rating'];

		} else {

			$content = file_get_contents("php://input");

			$account_type 	= $_POST['account_type'];
			$industry 		= $_POST['industry'];
			$rating			= $_POST['rating'];

		}

		$stmt = "SELECT MAX(crmid)+1 FROM vtiger_crmentity;";


		$stmt.= "SELECT CONCAT('ACC',MAX(CAST((SUBSTRING(account_no,4)+1) as UNSIGNED))) FROM vtiger_account;";

		$stmt.= "SELECT IFNULL( (SELECT accounttype FROM vtiger_accounttype WHERE accounttypeid = '$account_type') ,'');";

		$stmt.= "SELECT IFNULL( (SELECT industry FROM vtiger_industry WHERE industryid = '$industry') ,'');";

		$stmt.= "SELECT IFNULL( (SELECT rating FROM vtiger_rating WHERE rating_id = '$rating') ,'');";

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

	public function check_input($input)
	{
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);

		return $input;
	}

}

?>
