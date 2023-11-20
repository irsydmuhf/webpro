<?php

class M_Kontak extends CI_Model
{
    public function fetch_all()
    {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('telepon');
        return $query->result_array();
    }

    public function fetch_single_data($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get('telepon');
        return $query->row();
    }

    public function insert_api($data)
    {
        $this->db->insert('telepon', $data);
        return $this->db->insert_id();
    }

    public function update_data($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->update("telepon", $data);
    }

    public function check_data($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('telepon');
        return $query->row() ? true : false;
    }
    function delete_data($id)
    {
        $this->db->where("id", $id);
        $this->db->delete("telepon");

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
?>
