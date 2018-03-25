<?php
Class Auth {


	public function getLogin()
	{
		$method = $_SERVER['REQUEST_METHOD'];

		if($method == 'POST') {
			$content 	= file_get_contents("php://input");
			$login 		=	$_POST['login'];
		}
		elseif($method == 'PUT') {
			$content 	= parse_str(file_get_contents('php://input'), $_PUT);
			$login 		=	$_PUT['login'];
		}
		elseif($method == 'DELETE') {
			$content 	= 	parse_str(file_get_contents('php://input'), $_DELETE);
			$login 		=	$_DELETE['login'];
		}
		elseif($method == 'GET') 	{
			$content 	= $_GET['login'];
			$login 		= $content;
		}

		return $login;
	}


	public function checkLogin($db)
	{

		$login 		= 	explode(':', $this->getLogin() );
		$user 		=	$login[0];
		$password	=	$login[1];

		$hash	=	strtolower(md5($password));

		$encrypted_password = $this->encrypt_password($password,$user);

		$stmt		= 	"SELECT 1 FROM vtiger_users WHERE user_name = ? AND user_password = ?";
		$stmt 		= 	$db->prepare($stmt);
		$stmt		->	bind_param('ss', $user, $encrypted_password);
		$stmt		->	execute();
		$stmt		->	bind_result($result);

		while($stmt->fetch()) {
			if(!empty($result)){

				return $check = true;

			} else {

				return $check = false;

			}
		}
	}


	public function encrypt_password($password, $user)
	{
		$salt = substr($user, 0, 2);
		$salt = '$1$' . str_pad($salt, 9, '0');
		$encrypted_password = crypt($password, $salt);

        return $encrypted_password;
	}




}

?>
