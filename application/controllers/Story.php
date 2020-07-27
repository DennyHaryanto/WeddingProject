<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Story extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->helper('url');
	}
	
	public function index()
	{
		$this->load->model('prays_model');

		$result['data']=$this->prays_model->getTopPrays();
		$this->load->view('story', $result);
	}

	
	function tambah_aksi(){
		$this->load->model('prays_model');
		
		$nama = $this->input->post('Nama');
		$prays = $this->input->post('Prays');
 
		$data = array(
			'nama' => $nama,
			'prays' => $prays,
			'inputdate' => date('Y-m-d H:i:s')
			);
		$this->prays_model->input_data($data);
		redirect('story');
	}
}
