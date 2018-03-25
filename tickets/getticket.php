<?php

Class getTicket {

	public function getId($db) {

		$stmt = 'SELECT DISTINCT vtiger_crmentity.crmid as ticketid FROM vtiger_crmentity WHERE vtiger_crmentity.setype = "HelpDesk" AND vtiger_crmentity.deleted = "0"';

		$query = mysqli_query($db,$stmt);

		$tick_id_arr = array();

		while($record = mysqli_fetch_array($query)) {
			extract($record);

			$tick_id_arr[] = $ticketid;
		}

		return $tick_id_arr;
	}

	public function getTickets($db,$comment,$id) {

	if($id == null) {

		$stmt = 'SELECT DISTINCT vtiger_troubletickets.ticketid,vtiger_troubletickets.ticket_no,vtiger_troubletickets.groupname,vtiger_products.productname, vtiger_troubletickets.title,
		vtiger_troubletickets.solution,vtiger_contactdetails.lastname,crm2.deleted,vtiger_account.accountname, vtiger_troubletickets.priority,vtiger_troubletickets.severity,crm1.smownerid,
		vtiger_troubletickets.status,vtiger_troubletickets.category,vtiger_troubletickets.hours, vtiger_troubletickets.days,crm1.description,vtiger_users.last_name
		FROM vtiger_troubletickets
		LEFT JOIN vtiger_crmentity AS crm1 ON vtiger_troubletickets.ticketid = crm1.crmid
		LEFT JOIN vtiger_account ON vtiger_troubletickets.parent_id = vtiger_account.accountid
		LEFT JOIN vtiger_contactdetails ON vtiger_troubletickets.contact_id = vtiger_contactdetails.contactid
		LEFT JOIN vtiger_products ON vtiger_troubletickets.product_id = vtiger_products.productid
        LEFT JOIN vtiger_users ON crm1.smownerid = vtiger_users.id
		LEFT JOIN vtiger_crmentity AS crm2 ON vtiger_troubletickets.title = crm2.label WHERE crm2.deleted = "0" AND crm1.deleted = "0"';
		$query = mysqli_query($db,$stmt);

	} else {
		$stmt = 'SELECT DISTINCT vtiger_troubletickets.ticketid,vtiger_troubletickets.ticket_no,vtiger_troubletickets.groupname,vtiger_products.productname, vtiger_troubletickets.title,
		vtiger_troubletickets.solution,vtiger_contactdetails.lastname,crm2.deleted,vtiger_account.accountname, vtiger_troubletickets.priority,vtiger_troubletickets.severity,crm1.smownerid,
		vtiger_troubletickets.status,vtiger_troubletickets.category,vtiger_troubletickets.hours, vtiger_troubletickets.days,crm1.description,vtiger_users.last_name
		FROM vtiger_troubletickets
		LEFT JOIN vtiger_crmentity AS crm1 ON vtiger_troubletickets.ticketid = crm1.crmid
		LEFT JOIN vtiger_account ON vtiger_troubletickets.parent_id = vtiger_account.accountid
		LEFT JOIN vtiger_contactdetails ON vtiger_troubletickets.contact_id = vtiger_contactdetails.contactid
		LEFT JOIN vtiger_products ON vtiger_troubletickets.product_id = vtiger_products.productid
        LEFT JOIN vtiger_users ON crm1.smownerid = vtiger_users.id
		LEFT JOIN vtiger_crmentity AS crm2 ON vtiger_troubletickets.title = crm2.label WHERE crm2.deleted = "0" AND crm1.deleted = "0" AND crm1.smownerid = "'.$id.'"';
		$query = mysqli_query($db,$stmt);
	}


		if($query) {
			$tickets_arr = array();
			$tickets_arr["tickets"] = array();

			$i=0;

		while($record = mysqli_fetch_array($query)){

			extract($record);

			$ticket_item			=  array(

				"ticketid" 			=> $ticketid,
				"ticket_no" 		=> $ticket_no,
				"title" 			=> $title,
				"lastname" 			=> $lastname,
				"groupname" 		=> $groupname,
				"productname" 		=> $productname,
				"organisationname" 	=> $accountname,
				"priority" 			=> $priority,
				"severity" 			=> $severity,
				"status" 			=> $status,
				"category" 			=> $category,
				"hours" 			=> $hours,
				"days" 				=> $days,
				"description" 		=> $description,
				"solution" 			=> $solution,
				"last_name"			=> $last_name

				);

			array_push($tickets_arr["tickets"],$ticket_item);

			$i++;
		}


	}
		return json_encode($tickets_arr);
	}



	public function getComments($db,$tick_id_arr) {


		$count = count($tick_id_arr);

		$com = array();

		for($i=0; $i < $count; $i++) {

		$id = $tick_id_arr[$i];

		$stmt = "SELECT vtiger_modcomments.commentcontent,vtiger_modcomments.modcommentsid,vtiger_users.user_name FROM vtiger_modcomments
		INNER JOIN vtiger_users ON vtiger_modcomments.userid = vtiger_users.id
		WHERE vtiger_modcomments.related_to = '$id'";

		$query = mysqli_query($db,$stmt);

		$comments_arr["data"] = array();

		while($record = mysqli_fetch_array($query)) {

			extract($record);

			$comment_item 			=  array(
				"id" 				=> $modcommentsid,
				"user"				=> $user_name,
				"commentcontent" 	=> $commentcontent
				);


			array_push($comments_arr["data"],$comment_item);

		}

		$com[$i] = $comments_arr;

		}

		return $com;

	}




}




?>
