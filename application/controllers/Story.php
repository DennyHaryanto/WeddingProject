<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Prays_model');
	}
	
	public function index()
	{
		$result['data']=$this->Prays_model->getTopPrays();
		$this->load->view('story', $result);
	}

	
	function tambah_aksi(){
		$nama = $this->input->post('Nama');
		$prays = $this->input->post('Prays');
 
		$data = array(
			'nama' => $nama,
			'prays' => $prays,
			'inputdate' => date('Y-m-d H:i:s')
			);
		$this->Prays_model->input_data($data);
		redirect('story');
	}
}
