<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md extends CI_Controller {

	public function index()
	{
		$usr=$this->session->userdata('user_data');
		if(isset($usr)){
			$view=$this->input->get("p");
			$data["session"]=$usr;
			if($usr["uaccess"]!='ADM') $view='unauthorize';
			$this->load->view($view,$data);
		}else{
			redirect(base_url()."sign/out/1");
		}
	}
	
	public function datatable()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();
		if(isset($usr)){
			$sql=base64_decode($this->input->post("s"));
			$res=$this->db->query($sql)->result_array();
			//$this->load->model("myodbc");
			//$res=$this->myodbc->query($sql)->result_array();
			for($i=0;$i<count($res);$i++){
				$dum=array_values($res[$i]);
				$rowid=$res[$i]['rowid'];
				$dum[0]='<a href="#" onclick="openf('.$rowid.')">'.$dum[0].' </a>';
				$data[]=$dum;
			}
		}
		$out=array('data'=>$data);
		echo json_encode($out);
	}
	
	public function get()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();$cod='500';
		if(isset($usr)){
			$c=base64_decode($this->input->post("c"));
			$c=$c==''?'*':$c;
			$sql="select $c from ".base64_decode($this->input->post("t"))." where rowid=".$this->input->post("id");
			$data=$this->db->query($sql)->result_array();
			$cod='200';
		}
		$ret=array('code'=>$cod,'data'=>$data);
		echo json_encode($ret);
	}
	
	public function gets()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();
		if(isset($usr)){
			$c=base64_decode($this->input->post("c"));
			$w=base64_decode($this->input->post("w"));
			$t=base64_decode($this->input->post("t"));
			$c=$c==''?'*':$c;
			$sql="select $c from $t where $w";
			$data=$this->db->query($sql)->result_array();
		}
		$ret=array('data'=>$data);
		echo json_encode($ret);
	}
	
	public function lov()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();
		if(isset($usr)){
			$c=base64_decode($this->input->post("c"));
			$w=base64_decode($this->input->post("w"));
			$t=base64_decode($this->input->post("t"));
			$onclick=base64_decode($this->input->post("o"));
			$sql="select $c from $t where $w";
			$data=$this->db->query($sql)->result_array();
			for($i=0;$i<count($res);$i++){
				$dum=array_values($res[$i]);
				$dum[0]='<input type="radio" name="pilih" onclick="'.$onclick.'" value="'.$dum[0].'"> '.$dum[0];
				$data[]=$dum;
			}
		}
		$ret=array('data'=>$data);
		echo json_encode($ret);
	}
	
	public function sv()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();$msgs='Failed'; $typ="error";
		if(isset($usr)){
			//$this->load->model("mydb");
			$c=base64_decode($this->input->post("cols"));
			$t=base64_decode($this->input->post("table"));
			$rowid=$this->input->post("rowid");
			$flag=$this->input->post("flag");
			$where="rowid=$rowid";
			
			$data=$this->input->post(explode(",",$c));
			$data["lastupd"]=date('Y-m-d H:i:s');
			
			//specific user pwd
			if(isset($_POST['upwd'])){
				if($_POST['upwd']!='') $data["upwd"]=md5($this->input->post("upwd"));
			}
			if(isset($_POST['updby'])){
				$data["updby"]=$usr["uid"];
			}
			
			if($rowid==0){
				$this->db->insert($t, $data);
			}else{
				if($flag=='DEL') {
					$this->db->delete($t,$where);
				}else{
					$this->db->update($t, $data, $where);
				}
			}
			
			//$this->db->query($sql);
			if($this->db->affected_rows()>0) {
				$msgs='Success'; $typ="success";
			}else{
				$err=$this->db->error();
				$msgs="Error ".$err["code"]." : ".$err['message'];
			}
			
		}else{
			$msgs="Session closed, please login";
		}
		$ret=array('msgs'=>$msgs,'type'=>$typ);
		echo json_encode($ret);
	}
	
	//files
	public function filetable(){
		$this->load->helper('directory');
		$map = directory_map("./files/",1);
		$data=array();
		foreach($map as $d){
			$btn='<a title="Delete this file" href="#" onclick="delf(\''.base64_encode($d).'\');"><i class="fas fa-trash"></i></a>';
			$data[]=array($d,$btn);
		}
		$ret=array('data'=>$data);
		echo json_encode($ret);
	}
	public function svf()
	{
		$usr=$this->session->userdata('user_data');
		$data=array();$msgs='Failed'; $typ="error";
		if(isset($usr)){
			$config['upload_path']          = './files/';
			$config['allowed_types']        = '*';
			//$config['max_size']             = 100;
			//$config['max_width']            = 1024;
			//$config['max_height']           = 768;
			$config['file_ext_tolower'] = true;
			$config['overwrite'] = true;
			

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('file'))
			{
				$msgs = $this->upload->display_errors('','');
			}
			else
			{
				$msgs = "File uploaded.";
				$typ="success";
			}
		}else{
			$msgs="Session closed, please login";
		}
		$ret=array('msgs'=>$msgs,'type'=>$typ);
		echo json_encode($ret);
	}
	
	public function dlf(){
		$usr=$this->session->userdata('user_data');
		$data=array();$msgs='Failed'; $typ="error";
		if(isset($usr)){
			$f=base64_decode($this->input->post("f"));
			if(unlink('./files/'.$f)){
				$msgs='File '.$f.' deleted';
				$typ='success';
			}
		}else{
			$msgs="Session closed, please login";
		}
		$ret=array('msgs'=>$msgs,'type'=>$typ);
		echo json_encode($ret);
	}
 
}
