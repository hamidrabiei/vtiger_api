<?php



Class Login {

public function checkLogin($db){

			header("Access-Control-Allow-Origin: *");
			header('Access-Control-Allow-Headers: Cache-Control, Pragma, Origin, Authorization, Content-Type, X-Requested-With, timestamp, secret');
			header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');

				$content 		= file_get_contents("php://input");
        $decoded    = json_decode($content, true);
        $header     =   getallheaders();
        $time1      =   $header['timestamp'];
        $key        =   explode(':', $content );
        $user       =   $key[0];
        $tokenz     =   $key[1];
				$ifsecret   =   $header['secret'];

        $stmt       =   "SELECT id,user_hash FROM vtiger_users WHERE user_name = ?";
        $stmt       =   $db->prepare($stmt);
        $stmt       ->  bind_param('s', $user);
        $stmt       ->  bind_result($result,$result1);
        $stmt       ->  execute();
        $time 			= time();


		while($stmt->fetch()) {


            $result3    =   $result1.$user.$time1;
            $result3    =   sha1($result3);

			if($result3 == $tokenz)
			{

                $data_arr = array();
                include  MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';
                $include = MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';


                if(isset($apiauth) && $ifsecret == 0) {


                    if($public['expirestime'] < $time ) {


                        include  MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';
                        $publictoken = openssl_random_pseudo_bytes(8);
                        $publictoken = bin2hex($publictoken);
                        $apitoken = '$public';
                        $expires = time() + TICKET_TIME;
                        $replacement = $apitoken." = array('id' => '".$result."','public' => '".$publictoken."', 'createdtime' => '".$time."', 'expirestime' => '".$expires."'); ?>";
                        $contents = file($include, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $size = count($contents);
                        $contents[$size-1] = $replacement;
                        $temp = implode("\n", $contents);
                        file_put_contents($include, $temp);


                        $data_arr = array();
                        $data_arr["userData"] = array();
                        $login_item1 = array(
                            "id" => $result,
                            "token" => $publictoken,
                            "expires" => $expires
                        );
                        array_push($data_arr["userData"], $login_item1);



                    } else {

                        $data_arr = array();
                        $data_arr["userData"] = array();
                        $login_item2 = array(
                            "id" => $result,
                            "token" => $public['public'],
                            "expires" => $public['expirestime']
                        );
                        array_push($data_arr["userData"], $login_item2);
                    }




                }


                elseif(isset($apiauth) && $ifsecret == 1) {


                        include  MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';

                        if($public['expirestime'] < $time ) {


                        $publictoken = openssl_random_pseudo_bytes(8);
                        $publictoken = bin2hex($publictoken);
                        $apitoken = '$public';
                        $expires = time() + TICKET_TIME;
                        $replacement = $apitoken." = array('id' => '".$result."','public' => '".$publictoken."', 'createdtime' => '".$time."', 'expirestime' => '".$expires."'); ?>";
                        $contents = file($include, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $size = count($contents);
                        $contents[$size-1] = $replacement;
                        $temp = implode("\n", $contents);
                        file_put_contents($include, $temp);


                        $data_arr = array();
                        $data_arr["userData"] = array();
                        $login_item1 = array(
                            "id" => $result,
                            "secret" => $apiauth['secret'],
                            "token" => $publictoken,
                            "expires" => $expires

                        );
                        array_push($data_arr["userData"], $login_item1);

                    } else {

                        $data_arr = array();
                        $data_arr["userData"] = array();
                        $login_item2 = array(
                            "id" => $result,
                            "token" => $public['public'],
                            "secret" => $apiauth['secret'],
                            "expires" => $public['expirestime']
                        );
                        array_push($data_arr["userData"], $login_item2);
                    }

                }

                    elseif(!isset($apiauth)) {
                        $include = MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';
                        $tokensecret = openssl_random_pseudo_bytes(16);
                        $secrettoken = bin2hex($tokensecret);
                        $apisecret = '$apiauth';
                        $replacementsecret = $apisecret . " = array('id' => '" . $result . "','secret' => '" . $secrettoken . "');";
                        $contentssecret = file($include, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $sizesecret = count($contentssecret);
                        $contentssecret[$sizesecret-1] = $replacementsecret;
                        $tempsecret = implode("\n", $contentssecret);
                        file_put_contents($include, $tempsecret);


                    if(!isset($public)) {
                        $include = MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';
                        $publictoken = openssl_random_pseudo_bytes(8);
                        $publictoken = bin2hex($publictoken);
                        $apitoken = '$public';
                        $expires = time() + TICKET_TIME;
                        $replacement = $apitoken." = array('id' => '".$result."','public' => '".$publictoken."', 'createdtime' => '".$time."', 'expirestime' => '".$expires."'); ?>";
                        $contents = file($include, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $size = count($contents);
                        $contents[$size] = $replacement;
                        $temp = implode("\n", $contents);
                        file_put_contents($include, $temp);
                    }

                    include MAIN_DIR.'/user_privileges/user_privileges_' . $result . '.php';


                    $data_arr = array();
                    $data_arr["userData"] = array();
                    $login_item3 = array(
                        "id" => $result,
                        "token" => $publictoken,
                        "secret" => $secrettoken
                    );
                    array_push($data_arr["userData"], $login_item3);


                }
			}

		}


        if(empty($result)) {
            $text = 'Wrong username or password!';
            $data_arr["error"] = array(
                "text" => $text
            );


        }
        elseif($result3 != $tokenz) {
            $text = 'Wrong username or password!';
            $data_arr["error"] = array(
                "text" => $text
            );
        }

		return json_encode($data_arr);

	}

	public function token($password, $user)
	{
		$salt = substr($user, 0, 2);
		$salt = '$1$' . str_pad($salt, 9, '0');
		$encrypted_password = crypt($password, $salt);

       return $encrypted_password;

   }
    public function check_input_login($input)
    {
        $input = trim($input);
        $input = stripcslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }
}
?>
