<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class R extends CI_Controller {
	
	public function index()
	{
		$usr=$this->session->userdata('user_data');
		if(isset($usr)){
			$data["session"]=$usr;
			$this->load->view($this->input->get('v'),$data);
		}else{
			redirect(base_url()."sign/out/1");
		}
	}
	
	public function datatable()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();
		if(isset($usr)){
			$res=$this->db->select("btsid,btsname,controllerid,pln,pwr,d,t,batt,t_states.lastupd")->join("t_states","controllerid=sid","left")->get("t_bts")->result_array();
			for($i=0;$i<count($res);$i++){
				//switch($rpt){
				//	case "rmp": $res[$i]=$this->rmp($res[$i]); break;
				//}
				//$dum=array_values($res[$i]);
				$data[]=array_values($res[$i]);//$dum;
			}
		}
		$out=array('data'=>$data);
		echo json_encode($out);
	}
	
}