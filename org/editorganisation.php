<?php
Class editOrganisation{

	public function getData($db, $method){
		if($method == 'PUT') {

			$content 		= file_get_contents("php://input");
      $decoded 		= json_decode($content, true);
			$account_type 	= $decoded['account_type'];
			$industry 		= $decoded['industry'];
			$rating			= $decoded['rating'];

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
	public function editContact($db, $id, $d)
	{
		if(is_numeric($id))
		{
				$this->getData($db, $method);
				$content 				= parse_str(file_get_contents('php://input'), $decoded);
				$accountname		=	$this->check_input_org($decoded['accountname']);
				$parentid				=	$this->check_input_org($decoded['parentid']);
				$annualrevenue 	=	$this->check_input_org($decoded['annualrevenue']);
				$ownership			=	$this->check_input_org($decoded['ownership']);
				$siccode				=	$this->check_input_org($decoded['siccode']);
				$tickersymbol		=	$this->check_input_org($decoded['tickersymbol']);
				$phone					=	$this->check_input_org($decoded['phone']);
				$otherphone			=	$this->check_input_org($decoded['otherphone']);
				$email1					=	$this->check_input_org($decoded['email1']);
				$email2					=	$this->check_input_org($decoded['email2']);
				$website				=	$this->check_input_org($decoded['website']);
				$fax						=	$this->check_input_org($decoded['fax']);
				$employees			=	$this->check_input_org($decoded['employees']);
				$emailoptout		=	$this->check_input_org($decoded['emailoptout']);
				$notify_owner		=	$this->check_input_org($decoded['notify_owner']);
				$isconvertedfromlead	=	$this->check_input_org($decoded['isconvertedfromlead']);
				$bill_city			=	$this->check_input_org($decoded['bill_city']);
				$bill_code			=	$this->check_input_org($decoded['bill_code']);
				$bill_country		=	$this->check_input_org($decoded['bill_country']);
				$bill_state			=	$this->check_input_org($decoded['bill_state']);
				$bill_street		=	$this->check_input_org($decoded['bill_street']);
				$bill_pobox			=	$this->check_input_org($decoded['bill_pobox']);

				$account_type		=	$d[2][0];
				$industry 			=	$d[3][0];
				$rating 			=	$d[4][0];
				$createdtime		=	gmdate('Y-m-d h:i:s \G\M\T');

				if($accountname)
				{
				$stmt = "UPDATE vtiger_account
				SET accountname 		= (case when '$accountname' = '' then accountname else '$accountname' end),
					parentid			= (case when '$parentid' = '' then parentid else '$parentid' end),
					account_type		= (case when '$account_type' = '' then account_type else '$account_type' end),
					industry 			= (case when '$industry' = '' then industry else '$industry' end),
					annualrevenue		= (case when '$annualrevenue' = '' then annualrevenue else '$annualrevenue' end),
					rating 				= (case when '$rating' = '' then rating else '$rating' end),
					ownership 			= (case when '$ownership' = '' then ownership else '$ownership' end),
					siccode				= (case when '$siccode' = '' then siccode else '$siccode' end),
					tickersymbol		= (case when '$tickersymbol' = '' then tickersymbol else '$tickersymbol' end),
					phone				= (case when '$phone' = '' then phone else '$phone' end),
					otherphone			= (case when '$otherphone' = '' then otherphone else '$otherphone' end),
					email1				= (case when '$email1' = '' then email1 else '$email1' end),
					email2				= (case when '$email2' = '' then email2 else '$email2' end),
					website				= (case when '$website' = '' then website else '$website' end),
					fax 				= (case when '$fax' = '' then fax else '$fax' end),
					employees 			= (case when '$employees' = '' then employees else '$employees' end),
					emailoptout 		= (case when '$emailoptout' = '' then emailoptout else '$emailoptout' end),
					notify_owner		= (case when '$notify_owner' = '' then notify_owner else '$notify_owner' end),
					isconvertedfromlead = (case when '$isconvertedfromlead' = '' then isconvertedfromlead else '$isconvertedfromlead' end)
				WHERE accountid 		= '$id';";

				$stmt.= "UPDATE vtiger_accountbillads
				SET bill_city 			= (case when '$bill_city' = '' then bill_city else '$bill_city' end),
					bill_code 			= (case when '$bill_code' = '' then bill_code else '$bill_code' end),
					bill_country		= (case when '$bill_country' = '' then bill_country else '$bill_country' end),
					bill_state			= (case when '$bill_state' = '' then bill_state else '$bill_state' end),
					bill_state			= (case when '$bill_street' = '' then bill_street else '$bill_street' end),
					bill_pobox			= (case when '$bill_pobox' = '' then bill_pobox else '$bill_pobox' end)
				WHERE accountaddressid	= '$id';";

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

				//echo ' {"success": "id": "'.$id.'", "lastname": "'.$accountname.'"}';
				$data_arr = array();
            	$data_arr["organisation"] = array();
            	$org_edit_item 	=	array(
                	"id"=>$id,
                	"accountname"=>$accountname
                	);

          		array_push($data_arr["organisation"],$org_edit_item);

			} else {
				$data_arr = array();
            	$data_arr["organisation"] = array();
            	$org_edit_item 	=	array(
                	"error"=>"Something went wrong"
                	);

          		array_push($data_arr["organisation"],$org_edit_item);
			}
		} else {
			echo '{"warning":"please insert id"}';
		}

	}

	public function check_input_org($input)
	{
		$input = trim($input);
		$input = stripcslashes($input);
		$input = htmlspecialchars($input);

		return $input;
	}
}
?>
