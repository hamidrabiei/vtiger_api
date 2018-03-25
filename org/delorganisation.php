<?php
Class deleteOrganisation {

	public function deleteOrganisations($db,$id)
	{

		if(is_numeric($id)){
			$stmt = "UPDATE vtiger_crmentity SET deleted = '1' WHERE crmid = ? AND setype = 'Accounts'";
			$stmt 	 = $db->prepare($stmt);
			$stmt	->bind_param('i', $id);
			$stmt	->execute();

			if($stmt->execute())
			{
				echo '{"success":"record with id '.$id.' was deleted."}';
			}else{
				echo '{"error":"record with id '.$id.' was not found."}';
			}

		} else {
			echo '{"warning":"please insert id"}';
		}
		
	}
	
}
?>