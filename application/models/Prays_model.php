<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prays_model extends CI_Model
{
    public function __construct() { 
        parent::__construct();
    }
    private $_table = "Prays";

    public function getTopPrays(){
        $query=$this->db->query("select * from prays order by inputdate desc LIMIT 3");
	    return $query->result();
    }

    public function input_data($data){
        $this->db->insert('prays', $data);
		return $this->db->insert_id();
    }
}