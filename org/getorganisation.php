<?php

Class getOrganisation {

public function getOrganisations($db)
	{
		$stmt = "SELECT DISTINCT accountid, account_no, accountname, parentid, account_type, industry, annualrevenue, rating, ownership, siccode, tickersymbol, phone, otherphone, email1, email2, website, fax, employees, emailoptout, notify_owner, isconvertedfromlead, bill_city, bill_code, bill_country, bill_state, bill_street, bill_pobox
		FROM vtiger_account
		INNER JOIN vtiger_accountbillads ON accountid = accountaddressid
		INNER JOIN vtiger_crmentity ON accountid = crmid 
		WHERE deleted = '0'";

		if($result = $db->query($stmt))
		{
			$organisations_arr = array();
			$organisations_arr["organisations"] = array();

			while($obj = $result->fetch_array())
			{
				extract($obj);
				
				$organisation_item = array(

					"accountid" 			=> $accountid,
					"account_no" 			=> $account_no,
					"accountname"			=> $accountname,
					"parentid"				=> $parentid,
					"account_type"			=> $account_type,
					"industry"				=> $industry,
					"annualrevenue"			=> $annualrevenue,
					"rating"				=> $rating,
					"ownership"				=> $ownership,
					"siccode"				=> $siccode,
					"tickersymbol"			=> $tickersymbol,
					"phone"					=> $phone,
					"otherphone"			=> $otherphone,
					"email1"				=> $email1,
					"email2"				=> $email2,
					"website"				=> $website,
					"fax"					=> $fax,
					"employees"				=> $employees,
					"emailoptout"			=> $emailoptout,
					"notify_owner"			=> $notify_owner,
					"isconvertedfromlead"	=> $isconvertedfromlead,
					"bill_city"				=> $bill_city,
					"bill_code"				=> $bill_code,
					"bill_country"			=> $bill_country,
					"bill_state"			=> $bill_state,
					"bill_street"			=> $bill_street,
					"bill_pobox"			=> $bill_pobox
					

					);
				array_push($organisations_arr["organisations"],$organisation_item);
			}
		}
			return json_encode($organisations_arr); 
	}	



}

?>