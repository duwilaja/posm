<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}
	
	public function index()
	{
		$data['lat'] = $this->input->get("lat");
		$data['lng'] = $this->input->get("lng");
		$data['latfld'] = $this->input->get("latfld");
		$data['lngfld'] = $this->input->get("lngfld");
		$this->load->view('map',$data);
	}
	public function route()
	{
		$data['route'] = $this->input->get("route");
		$this->load->view('maproute',$data);
	}
	public function view()
	{
		$data['lat'] = $this->input->get("lat");
		$data['lng'] = $this->input->get("lng");
		$this->load->view('mapview',$data);
	}
}