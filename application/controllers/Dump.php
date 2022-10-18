<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
		if(json_last_error == JSON_ERROR_NONE){
			$sid=$data->sid;
			$this->db->update("t_states",$data,"sid='$sid'");
			if($this->db->affected_rows()>0)	{
				$ret="OK";
			}else{
				$ret="NoUpdate";
			}
		}else{
			$ret=json_last_error_msg();
		}
		echo $ret;
	}
	
}
