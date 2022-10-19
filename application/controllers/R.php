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
			$tbl=base64_decode($this->input->post("t"));
			$df=$this->input->post("df");
			$dt=$this->input->post("dt");
			if($df!='') $this->db->where("lastupd>=","$df");
			if($dt!='') $this->db->where("lastupd<=","$dt 23:59:59");
			$res=$this->db->get($tbl)->result_array();
			for($i=0;$i<count($res);$i++){
				$data[]=array_values($res[$i]);
			}
		}
		$out=array('data'=>$data);
		echo json_encode($out);
	}
	
}