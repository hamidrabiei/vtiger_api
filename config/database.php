<?php
// DB connection.

class Database {

	protected $db_name = 'vtigercrm1';
	protected $db_user = 'root';
	protected $db_pass = '';
	protected $db_host = 'localhost';


	public function connect() {

		$connect_db = new mysqli( $this->db_host, $this->db_user, $this->db_pass, $this->db_name );

		if ( mysqli_connect_errno() ) {
			printf("Cannot access database: %s\
		", mysqli_connect_error());
			exit();
		}
		return $connect_db;

	}

}






?>
