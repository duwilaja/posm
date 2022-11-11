<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Dump extends CI_Controller {

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
			date_default_timezone_set('Asia/Jakarta');
			$data->lastupd=date('Y-m-d H:i:s');
			$this->db->update("t_states",$data,"sid='$sid'");
			if($this->db->affected_rows()>0)	{
				$ret="OK";
			}else{
				$this->db->insert("t_states",$data);
				$ret="Inserted";
			}
			//if($data->pln=='off'||$data->pwr=='off'){
				$pic=$this->db->where('controllerid',$sid)->get("t_bts")->row();
				if(isset($pic)){
					$ret.=' ' .$this->notify($pic,$data);
				}
			//}
		}else{
			if(json_last_error() == JSON_ERROR_NONE) {$ret="Blank";} else{
			$ret=json_last_error_msg();}
		}
		echo $ret;
	}
	
	private function notify($rec,$data,$br='<br />'){
		$pic=$rec->pic; $mail=$rec->mail; $bts=$rec->btsname; 
		$pln=$data->pln; $pwr=$data->pwr; $t=$data->t; $d=$data->d; $batt=$data->batt;
		$onoff=($pln=='on'&&$pwr=='on')?'ON':'OFF'; 
		$sub="POSM Notification BTS $bts : $onoff"; 
		$msg="Dear $pic, $br $br FYI, $br Status BTS $bts $br PLN : $pln $br Power : $pwr $br Temperature : $t $br Door : $d $br Battery : $batt $br $br Regards,$br POSM Admin";
		if(trim($mail)!=''){
			return $this->sendmail($mail,$sub,$msg);
		}
	}
	
	private function sendmail($tos,$sub,$msg,$debug=false){
		require (APPPATH.'third_party/phpmailer/Exception.php');
		require (APPPATH.'third_party/phpmailer/PHPMailer.php');
		require (APPPATH.'third_party/phpmailer/SMTP.php');
		
		date_default_timezone_set('Etc/UTC');

		$mail = new PHPMailer(true);

		try {
			//Server settings
			if($debug) $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = 'poweroutagemonitoringsystem@gmail.com';                     //SMTP username
			$mail->Password   = 'ausgoykmgssvnnye';//'PoSM-#-01';//;                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
			$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			
			//Recipients
			$mail->setFrom($mail->Username, 'POSM Notification');
			//$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
			$toa=explode(";",$tos);
			foreach($toa as $to){
				$mail->addAddress(trim($to));               //Name is optional
			}
			//Content
			$mail->isHTML(true);                                  //Set email format to HTML
			$mail->Subject = $sub; //'Here is the subject';
			$mail->Body    = $msg; //'This is the HTML message body <b>in bold!</b>';
			$mail->AltBody = $msg; //'This is the body in plain text for non-HTML mail clients';

			$mail->send();
			return 'Message has been sent';
		} catch (Exception $e) {
			return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
	
}
