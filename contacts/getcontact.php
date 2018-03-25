<?php

Class getContact {

	public function getContacts($db)
	{

		$stmt = "SELECT DISTINCT contactid, contact_no, accountid, firstname, lastname, email, phone, mobile, title,
		department, fax, reportsto, otheremail, secondaryemail, donotcall, emailoptout, reference, notify_owner,
		isconvertedfromlead, mailingstreet, mailingstate, mailingpobox, othercity, otherstate, mailingzip, otherzip,
		otherstate, otherpobox
		FROM vtiger_contactdetails
		INNER JOIN vtiger_contactaddress ON contactid = contactaddressid
		INNER JOIN vtiger_crmentity ON contactid = crmid
		WHERE deleted = '0'";

		if($result = $db->query($stmt))
		{
			$contacts_arr = array();
			$contacts_arr["contacts"] = array();


			while($obj = $result->fetch_array())
			{
				extract($obj);

				$contact_item = array(
					"contactid" 					=> $contactid,
					"contact_no" 					=> $contact_no,
					"accountid"						=> $accountid,
					"firstname"						=> $firstname,
					"lastname"						=> $lastname,
					"email"								=> $email,
					"phone"								=> $phone,
					"mobile"							=> $mobile,
					"fax"									=> $fax,
					"department"					=> $department,
					"reportsto"						=> $reportsto,
					"otheremail"					=> $otheremail,
					"secondaryemail"			=> $secondaryemail,
					"donotcall"						=> $donotcall,
					"emailoptout"					=> $emailoptout,
					"reference"						=> $reference,
					"notify_owner"				=> $notify_owner,
					"title"								=> $title,
					"mailingcity"					=> $mailingcity,
					"mailingcountry"			=> $mailingcountry,
					"isconvertedfromlead" => $isconvertedfromlead,
					"mailingstreet"				=> $mailingstreet,
					"mailingstate"				=> $mailingstate,
					"mailingpobox"				=> $mailingpobox,
					"othercity"						=> $othercity,
					"otherstate"					=> $otherstate,
					"mailingzip"					=> $mailingzip,
					"otherzip"						=> $otherzip,
					"otherstreet"					=> $otherstate,
					"otherpobox"					=> $otherpobox
					);
				array_push($contacts_arr["contacts"],$contact_item);
			}

		}
	return json_encode($contacts_arr);
	}

}

?>
