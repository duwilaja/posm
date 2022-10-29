<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
use League\OAuth2\Client\Provider\Google;

class Dump extends CI_Controller {
	
	private email = 'poweroutagemonitoringsytem@gmail.com';
	private clientId = '';
	private clientSecret = '';
	private refreshToken = '';
	private redirectUri  = 'http://omgdemo.website/posm/dump/callback';

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$input=file_get_contents("php://input");
		$data=json_decode(strtolower($input));
		if(json_last_error() == JSON_ERROR_NONE && $input!=""){
			$sid=$data->sid;
			$data->lastupd=date('Y-m-d H:i:s');
			$this->db->update("t_states",$data,"sid='$sid'");
			if($this->db->affected_rows()>0)	{
				$ret="OK";
			}else{
				$this->db->insert("t_states",$data);
				$ret="Inserted";
			}
		}else{
			if(json_last_error() == JSON_ERROR_NONE) {$ret="Blank";} else{
			$ret=json_last_error_msg();}
		}
		echo $ret;
	}
	public function testmail(){
		require (APPPATH.'third_party/phpmailer/Exception.php');
		require (APPPATH.'third_party/phpmailer/PHPMailer.php');
		require (APPPATH.'third_party/phpmailer/SMTP.php');
		
		require APPPATH . 'third_party/gugel/vendor/autoload.php';
		
		date_default_timezone_set('Etc/UTC');

		$mail = new PHPMailer(true);

		try {
			//Server settings
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			//$mail->Username   = 'poweroutagemonitoringsytem@gmail.com';                     //SMTP username
			//$mail->Password   = 'ausgoykmgssvnnye';//'PoSM-#-01';//;                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			
			$mail->AuthType = 'XOAUTH2';
			$provider = new Google(
				[
					'clientId' => $this->clientId,
					'clientSecret' => $this->clientSecret,
				]
			);
			$mail->setOAuth(
				new OAuth(
					[
						'provider' => $provider,
						'clientId' => $this->clientId,
						'clientSecret' => $this->clientSecret,
						'refreshToken' => $this->refreshToken,
						'userName' => $this->email,
					]
				)
			);

			//Recipients
			$mail->setFrom($this->email, 'POSM');
			//$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
			//$tos=explode(";",$to);
			//foreach($tos as $t){
				$mail->addAddress('smart.mgmt.mmt@gmail.com');               //Name is optional
			//}
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = 'Here is the subject';
			$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			echo 'Message has been sent';
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

	}
	
	public function callback(){
		require APPPATH . 'third_party/gugel/vendor/autoload.php';

		if (!empty($_GET['error'])) {

			// Got an error, probably user denied access
			exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

		} elseif (empty($_GET['code'])) {

			// If we don't have an authorization code then get one
			$authUrl = $provider->getAuthorizationUrl();
			$_SESSION['oauth2state'] = $provider->getState(); 
			header('Location: ' . $authUrl);
			exit;

		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

			// State is invalid, possible CSRF attack in progress
			unset($_SESSION['oauth2state']);
			exit('Invalid state');

		} else {

			// Try to get an access token (using the authorization code grant)
			$token = $provider->getAccessToken('authorization_code', [
				'code' => $_GET['code']
			]);

			$_SESSION['token'] = serialize($token);

			// Optional: Now you have a token you can look up a users profile data
			//header('Location: /user.php');
		}

	}
}
