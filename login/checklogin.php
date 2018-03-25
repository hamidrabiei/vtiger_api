<?php
//include_once '../config/const.php';
Class checkAccess {

	public function getToken()
	{

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == 'POST') {
            $header = getallheaders();
            $login  = $header['Authorization'];
            $login  = str_replace('Basic ', '', $login);
            $time   = $header['timestamp'];
            $login  = base64_decode($login);
            $login_arr = array();
            array_push($login_arr,$login,$time);
        }
        elseif($method == 'PUT') {

            $header = getallheaders();
            $login  = $header['Authorization'];
            $login  = str_replace('Basic ', '', $login);
            $time   = $header['timestamp'];
            $login  = base64_decode($login);
            $login_arr = array();
            array_push($login_arr,$login,$time);

        }
        elseif($method == 'DELETE') {

            $header = getallheaders();
            $login  = $header['Authorization'];
            $login  = str_replace('Basic ', '', $login);
            $time   = $header['timestamp'];
            $login  = base64_decode($login);
            $login_arr = array();
            array_push($login_arr,$login,$time);
        }
        elseif($method == 'GET') 	{
            $header = getallheaders();
            $login  = $header['Authorization'];
            $login  = str_replace('Basic ', '', $login);
            $time   = $header['timestamp'];
            $login  = base64_decode($login);
            $login_arr = array();
            array_push($login_arr,$login,$time);
        }

        return $login_arr;
	}


	public function giveAccess($db)
    {
        $auth = 0;
        $content 	= 	$this->getToken();
        $login      =   $content[0];
        $time       =   $content[1];
 		$login 		= 	explode(':', $login );

 		$id_ticket	=	$login[0];
 		$token 		=	$login[1];

        if($id_ticket == "") {

        } else {

        include_once  dirname(dirname(dirname(__FILE__))).'/user_privileges/user_privileges_' . $id_ticket . '.php';

 		$password   =   sha1($public['public'].$time.$apiauth['secret']);


 		$now = time();
 		$createdtime = $public['createdtime'];
 		$endtime     = $public['expirestime'];


 		if($password == $token && $now-1 <= $time &&  $time <= $now+1 && $createdtime < $time && $endtime > $time) {
 		    $auth = 1;
    } else {
 		    $auth = 0;
    }

    }

		return $auth;
    }
}




?>
