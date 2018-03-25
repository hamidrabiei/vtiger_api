<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

Class deleteTicket {

	public function deleteTickets($db,$id)
	{

		if(is_numeric($id)) {

			$stmt = "UPDATE vtiger_crmentity SET deleted = '1' WHERE crmid = ? AND vtiger_crmentity.setype = 'HelpDesk'";
			$stmt 		= $db->prepare($stmt);
			$stmt		->bind_param('i', $id);
			$stmt		->execute();


			if($stmt->execute()) {
				echo '{"success":"record with id '.$id.' was deleted."}';
			} else {
				echo '{"error":"record with id '.$id.' was not found."}';
			}


		} else {

			echo '{"warning":"please insert id"}';
		}

	}

}

?>
