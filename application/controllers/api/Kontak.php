<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kontak extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database(); // optional
        $this->load->model('M_Kontak');
        $this->load->library('form_validation');
    }  

    function fetch_all()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('telepon');
        return $query->result_array();
    }

    function fetch_single_data($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get('telepon');
        return $query->row();
    }

    function index_get()
    {
        $id = $this->get('id');
        if ($id === NULL) {
            $data = $this->fetch_all();
        } else {
            $data = $this->fetch_single_data($id);
        }

        $this->response($data, 200);
    }

    function check_data($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('telepon');

        if ($query->row()) {
            return true;
        } else {
            return false;
        }
    }

    function insert_api($data)
    {
        $this->db->insert('telepon', $data);
        if ($this->db->affected_rows() > 0){
            return true;
        } else {
            return false;
        }
    }

    function index_post()
    {
        if ($this->post('nama') == ''){
            $response = array(
                'status' => 'fail',
                'field' => 'nama',
                'message' => 'Isian nama tidak boleh kosong!',
                'status_code' => 502
            );

            return $this->response($response);
        }
        if ($this->post('nomor') == '') {
            $response = array(
                'status' => 'fail',
                'field' => 'nomor',
                'message' => 'Isian nomor tidak boleh kosong!',
                'status_code' => 502
            );
            return $this->response($response);
        }

        $data = array(
            'name' => trim($this->post('nama')),
            'number' => trim($this->post('nomor'))
        );
        $this->insert_api($data);
        $last_row = $this->db->select('*')->order_by('id', 'desc')->limit(1)->get('telepon')->row();
        $response = array(
            'status' => 'success',
            'data' => $last_row,
            'status_code' => 201,
        );
        return $this->response($response);
    }

    function update_data($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->update("telepon", $data);
    }

    function index_put()
    {
        $id = $this->put('id');
        $check = $this->check_data($id); // Changed M_Kontak to $this->check_data
        if ($check == false) {
            $error = array(
                'status' => 'fail',
                'field' => 'id',
                'message' => 'Data tidak ditemukan!',
                'status_code'=> 502
            );

            return $this->response($error);
        }

        if ($this->put('nama') == ''){
            $response = array(
                'status' => 'fail',
                'field' => 'nama',
                'message' => 'Isian nama tidak boleh kosong!',
                'status_code' => 502
            );
            return $this->response($response);
        }

        if ($this->put('nomor') == ''){
            $response = array(
                'status' => 'fail',
                'field' => 'nomor',
                'message' => 'Isian nomor tidak boleh kosong!',
                'status_code' => 502
            );
            return $this->response($response);
        }

        $data = array(
            'name' => trim($this->put('nama')),
            'number' => trim($this->put('nomor'))
        );

        $this->update_data($id, $data);
        $newData = $this->fetch_single_data($id);
        $response = array(
            'status' => 'success',
            'data' => $newData,
            'status_code' => 200,
        );
        return $this->response($response);
    }
    function index_delete() {
        $id = $this->delete('id');
        
        $check = $this->M_Kontak->check_data($id);
    
        if (!$check) {
            $error = array(
                'status' => 'fail',
                'field' => 'id',
                'message' => 'Data tidak ditemukan!',
                'status_code' => 502
            );
            return $this->response($error);
        }
    
        $delete = $this->M_Kontak->delete_data($id);
    
        $response = array(
            'status' => 'success',
            'data' => null,
            'status_code' => 200,
        );
    
        return $this->response($response);
    }
    
}
?>
