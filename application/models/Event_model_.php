<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model
{
    public function __construct() { parent::__construct(); }
    private $_table = "tiket";

    public $id;
    public $event_name;
    public $price;
    public $event_time;
    public $event_date;
    public $note;

    public function getAllevent($show=null, $start=null, $cari=null){
        $this->db->select("*");
        $this->db->from("event");
        $this->db->where("isactive = '1'");
        $this->db->where("(event_name  LIKE '%".$cari."%' or note  LIKE '%".$cari."%')");
        $this->db->order_by("create_date");
        if ($show == null && $start == null) {
        } else {
            $this->db->limit($show, $start);
        }
        return $this->db->get();
    }
        
    public function get_count_event($cari = null){
            $count = array();
            
            $this->db->select(" COUNT(id) as recordsFiltered ");
            $this->db->from("event");
            $this->db->where("isactive = '1'");
            $this->db->where("(event_name  LIKE '%".$cari."%' or note  LIKE '%".$cari."%')");
            $count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
            
            $this->db->select(" COUNT(id) as recordsTotal ");
            $this->db->from("event");
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
        $this->eventname = $post["eventname"];
        $this->password = $post["password"];
        $this->email = $post["email"];
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id)
    {
        return $this->db->delete($this->_table, array("id" => $id));
    }
}