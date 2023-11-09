<?php
class MyModel extends CI_Model {

    public function get_agency() {
        $this->db->select('*');
        $this->db->from('property_data');
        $this->db->group_by('agency');
        $this->db->order_by('agency','asc');
        $query = $this->db->get();

        return $query->result_array();
    }


    public function get_zipcodes_byborough($borough) {
        $this->db->select('incident_zip');
        $this->db->from('property_data');
        $this->db->group_by('incident_zip');
        $this->db->where('borough', $borough);
        $this->db->where("incident_zip !=", "");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_borough_byAgency($agency) {
        $this->db->select('borough');
        $this->db->from('property_data');
        $this->db->group_by('borough');
        $this->db->where('agency', $agency);
        // $this->db->where("borough IS NOT NULL");
        $query = $this->db->get();
        return $query->result_array();
    }



    

    
}
?>
