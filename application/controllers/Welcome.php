<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
		//$this->load->view('welcome_message');
		$this->load->view('login');
	}
	public function home(){
		$usr=$this->session->userdata('user_data');
		if(isset($usr)){
			$data["session"]=$usr;
			$this->load->view('home',$data);
		}else{
			redirect(base_url()."sign/out/1");
		}
	}
	public function tot(){
		$bts=$this->db->from("t_bts")->count_all_results();
		$con=$this->db->from("t_states")->count_all_results();
		$on=$this->db->where(array("pln"=>"on","pwr"=>"on"))->from("t_states")->count_all_results();
		$data=array(
			"bts"=>$bts,
			"controller"=>$con,
			"on"=>$on,
			"off"=>($con-$on)
		);
		
		echo json_encode($data);
	}
	public function map(){
		$data=array();
		$usr=$this->session->userdata('user_data');
		if(isset($usr)){
			$data=$this->db->get("v_maps")->result_array();
		}
		echo json_encode($data);
	}
}
