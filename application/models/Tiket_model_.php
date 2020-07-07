<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tiket_model extends CI_Model
{
    public function __construct() { parent::__construct(); }
    private $_table = "tr_order";

    public $id;
    public $event_name;
    public $price;
    public $event_time;
    public $event_date;
    public $note;

    public function getAllevent()
        {
           	$events = $this->db->query( "select * from event where isactive = '1'");
            return $events;
        }

    public function getAllpesan_tiket($show=null, $start=null, $cari=null){
        $this->db->select("b.event_name, c.username, c.email, sum(a.qty) as jml_pesanan, max(c.last_active) as last_active");
        $this->db->from("tr_order a");
        $this->db->join("event b", "a.id = b.id","left");
        $this->db->join("user c", "a.id = c.id","left");
        $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%')");
        if ($show == null && $start == null) {
        } else {
            $this->db->limit($show, $start);
        }
        return $this->db->get();
    }
        
    public function get_count_pesan_tiket($cari = null){
            $count = array();
            
            $this->db->select(" COUNT(a.id) as recordsFiltered ");
            $this->db->from("tr_order a");
            $this->db->join("event b", "a.id = b.id","left");
            $this->db->join("user c", "a.id = c.id","left");
            $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%')");
			$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
			
			$this->db->select(" COUNT(id) as recordsTotal ");
			$this->db->from("tr_order");
			$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
            
            return $count;
    }

    public function insert_event($data){
        $this->db->insert('event', $data);
		return $this->db->insert_id();
    }

    public function update_event($data){
        $this->db->where('id', $data['id']);
        $this->db->update('event', $data);
        return $data['id'];
    }

    public function delete_event($data){
            $this->db->where('id', $data['id']);
            $this->db->update('event', array('isactive' => '0'));
			return $data['id'];
    }

    public function get_event_by_id($id){
        if(empty($id)){
            return array();
        }
        else{
            $this->db->select("*");
            $this->db->from("event");
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
    }

    public function get_code_tiket($id_user){
        $this->db->select("a.username, b.code");
        $this->db->from("user a, tr_order b");
        $this->db->where('a.id = b.id_user');
        $this->db->where('b.id_user', $id_user);
        $this->db->order_by('b.create_date', 'desc');
        $this->db->limit('1');
        return $this->db->get()->row();
    }

    public function get_detail_event($id_event){
        if(empty($id_event)){
            return array();
        } else {
            $session = $this->session->userdata('login');
            $this->db->select("*");
            $this->db->from("event");
            $this->db->where('id', $id_event);
            return $this->db->get()->row_array();
            }
        }

    public function combobox_event(){
            $this->db->select("*");
            $this->db->from("event");
            //$this->db->where("event_date <= current_date()");
            return $this->db->get();
        }
    
    // public function insert_order($data){
    //         $this->db->insert('tr_order', $data);
    //         return $this->db->insert_id();
    // }
    public function insert_order($data){
        $this->db->insert('tr_order', $data);
        return $this->db->insert_id();
}
    
    public function update_order($data){
            $this->db->where('id', $data['id']);
            $this->db->update('tr_order', $data);
            return $data['id'];
        }
    
    public function list_event($event_date){
            $this->db->from("event");
            $this->db->where('event_date', $event_date);
            $this->db->order_by('event_name');
            
            return $this->db->get();
        }

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["id" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->event_name = $post["event_name"];
        $this->price = $post["price"];
        $this->event_time = $post["event_time"];
        $this->event_date = $post["event_date"];
        $this->note = $post["note"];
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->id = $post["id"];
        $this->username = $post["username"];
        $this->password = $post["password"];
        $this->email = $post["email"];
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id)
    {
        return $this->db->delete($this->_table, array("id" => $id));
    }
}