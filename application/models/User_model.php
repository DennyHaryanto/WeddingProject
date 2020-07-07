<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct() { parent::__construct(); }
    private $_table = "user";

    public $id;
    public $username;
    public $password;
    public $email;
    public $isactive = 1;
    public $isagree = 1;
    public $isrole = 1;
    public $last_active;
    public $create_date;
    public $update_date;
    public $pesanan = 0;
    
    public function getAlluser($show=null, $start=null, $cari=null){
        $this->db->select("*");
        $this->db->from("user");
        $this->db->where("isactive = '1'");
        $this->db->where("(username  LIKE '%".$cari."%' or email  LIKE '%".$cari."%')");
        $this->db->order_by("last_active");
        if ($show == null && $start == null) {
        } else {
            $this->db->limit($show, $start);
        }
        return $this->db->get();
    }
        
    public function get_count_user($cari = null){
            $count = array();
            
            $this->db->select(" COUNT(id) as recordsFiltered ");
            $this->db->from("user");
            $this->db->where("isactive = '1'");
            $this->db->where("(username  LIKE '%".$cari."%' or email  LIKE '%".$cari."%')");
            $count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
            
            $this->db->select(" COUNT(id) as recordsTotal ");
            $this->db->from("user");
            $this->db->where("isactive = '1'");
            $count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
            
            return $count;
    }
    

    public function getAlluser_traffic($show=null, $start=null, $cari=null){
            $this->db->select("b.event_name, c.username, c.email, sum(a.qty) as jml_pesanan, max(c.last_active) as last_active");
            $this->db->from("tr_order a");
            $this->db->join("event b", "a.id_event = b.id","left");
            $this->db->join("user c", "a.id_user = c.id","left");
            $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%')");
            $this->db->group_by("c.id");
            if ($show == null && $start == null) {
            } else {
                $this->db->limit($show, $start);
            }
            return $this->db->get();
    }
		
	public function get_count_user_traffic($cari = null){
			$count = array();
			$session = $this->session->userdata('login');
			
			$this->db->select(" COUNT(a.id) as recordsFiltered ");
            $this->db->from("tr_order a");
            $this->db->join("event b", "a.id_event = b.id","left");
            $this->db->join("user c", "a.id_user = c.id","left");
            $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%')");
            $this->db->group_by("c.id");
			$count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
			
			$this->db->select(" COUNT(id) as recordsTotal ");
            $this->db->from("tr_order");
            $this->db->group_by("id");
			$count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
			
			return $count;
    }

    public function getAlllist_order($show=null, $start=null, $cari=null, $id_user, $role){
        $this->db->select("a.code, b.event_name, b.event_date, b.price, c.username, c.email, a.qty as jml_pesanan, sum(b.price*a.qty) as total");
        $this->db->from("tr_order a");
        $this->db->join("event b", "a.id_event = b.id","left");
        $this->db->join("user c", "a.id_user = c.id","left");
        if ($role == 1) {
            $this->db->where("a.id_user != ''");
        } else {
            $this->db->where("a.id_user = '$id_user'");
        }
        $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%' or a.code  LIKE '%".$cari."%')");
        $this->db->group_by('a.id');
        $this->db->order_by('a.create_date', 'DESC');
        if ($show == null && $start == null) {
        } else {
            $this->db->limit($show, $start);
        }
        return $this->db->get();
    }
        
    public function get_count_list_order($cari = null, $username, $role){
            $count = array();
            $session = $this->session->userdata('login');
            
            $this->db->select(" COUNT(a.id) as recordsFiltered ");
            $this->db->from("tr_order a");
            $this->db->join("event b", "a.id_event = b.id","left");
            $this->db->join("user c", "a.id_user = c.id","left");
            $this->db->where("(c.username  LIKE '%".$cari."%' or b.event_name  LIKE '%".$cari."%')");
            $count['recordsFiltered'] = $this->db->get()->row_array()['recordsFiltered'];
            
            $this->db->select(" COUNT(id) as recordsTotal ");
            $this->db->from("tr_order");
            $this->db->group_by("id");
            $count['recordsTotal'] = $this->db->get()->row_array()['recordsTotal'];
            
            return $count;
    }

    public function insert_user($data){
        $this->db->insert('user', $data);
		return $this->db->insert_id();
    }

    public function update_user($data){
        $this->db->where('id', $data['id']);
        $this->db->update('user', $data);
        return $data['id'];
    }

    public function delete_user($data){
            $this->db->where('id', $data['id']);
            $this->db->update('user', array('isactive' => '0'));
			return $data['id'];
    }

    public function get_user_by_id($id){
        if(empty($id)){
            return array();
        }
        else{
            $this->db->select("*");
            $this->db->from("user");
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

    public function saveadmin()
    {
        $post = $this->input->post();
        $this->username = $post["username"];
        $this->password = md5($post["password"]);
        $this->email = $post["email"];
        $this->isrole = $post["isrole"];
        $this->create_date = date('Y-m-d H:i:s');
        $this->db->insert($this->_table, $this);
    }

    public function saveuser()
    {
        $post = $this->input->post();
        $this->username = $post["username"];
        $this->password = md5($post["password"]);
        $this->email = $post["email"];
        $this->isrole = $post["isrole"];
        $this->handphone = $post["handphone"];
        $this->create_date = date('Y-m-d H:i:s');
        $this->db->insert($this->_table, $this);
    }

    public function updatepass()
    {
        $post = $this->input->post();
        $this->id = $post["id"];
        $this->password = $post["password"];
        $this->db->update($this->_table, $this, array('id' => $post['id']));
    }

    public function delete($id)
    {
        return $this->db->delete($this->_table, array("id" => $id));
    }

    function login_check($username, $password){
        // $this->load->database();
        $this->db->select('*');
		$this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);

		$data = $this->db->get();
	    if ($data -> num_rows() == 1) {
            $result = $data->result();
            return $result;
        } else {
            return false;
        }
    }
}