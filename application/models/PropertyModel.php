<?php
class PropertyModel extends CI_Model {

    //get agency name
    public function get_agency() {
       
        $this->db->select('agency_name');
        $this->db->from('property_data');
        $this->db->group_by('agency_name');
        $this->db->order_by('agency_name','asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    //get borough name
    public function get_borough_byAgency($agency) {
      
        $this->db->select('borough');
        $this->db->from('property_data');
        $this->db->group_by('borough');
        $this->db->where('agency_name', $agency);
        $query = $this->db->get();
        return $query->result_array();
    }


    // get zipcode
    public function get_zipcodes_byborough($borough,$agency) {
       
        $this->db->select('incident_zip');
        $this->db->from('property_data');
        $this->db->group_by('incident_zip');
        $this->db->where('borough', $borough);
        $this->db->where('agency_name', $agency);
        $this->db->where("incident_zip !=", "");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    //property data count
    public function getProperty_count() {
       
        $query = "SELECT * FROM property_data";
        $data  = $this->db->query($query);
        return $data->num_rows();
    }

    public function get_data_property($arr, $start, $table_length, $col, $dir, $count = 1,$extraval='') {
		
        $agency = '1=1';
        if (!empty($arr['agencyInput'][0])) {
         
           // $agency = [];
           
            // foreach ($arr['agencyInput'] as $p) {
            //     $agency[] = $p;
            // }
            // $agency = "'" . implode("','", $agency) . "'";
            $agency = 'agency_name IN("' . $arr['agencyInput'][0] . '") ';
        }

      
        $borough = '1=1';
        if (!empty($arr['boroughInput'][0])) {
           
            $borough = ' borough IN("' . $arr['boroughInput'][0] . '") ';
        }


        $zipcode = '1=1';
        if (!empty($arr['zipcodeInput'][0])) {
            $zipcode = ' incident_zip IN("' . $arr['zipcodeInput'][0] . '") ';
        }


		$data_arr = array(
            0 => 'unique_key',
            1 => 'created_date',
            2 => 'agency',
            3 => 'agency_name',
            4 => 'borough ',
            5 => 'incident_zip',
            6 => 'complaint_type',
            7 => 'descriptor',
            8 => 'location_type',
            9 => 'incident_address'
            // 10 => 'cross_street_1',
            // 11 => 'cross_street_2',
            // 12 => 'address_type',
            // 13 => 'city',
            // 14 => 'status',
            // 15 => 'resolution_description',
            // 16 => 'resolution_action_updated_date',
            // 17 => 'community_board',
            // 18 => 'bbl',
            // 19 => 'location_type',
            // 20 => 'x_coordinate_state_plane',
            // 21 => 'y_coordinate_state_plane',
            // 22 => 'open_data_channel_type',
            // 23 => 'park_facility_name',
            // 24 => 'park_borough',
            // 25 => 'latitude',
            // 26 => 'longitude'
        );

        $get_col_name = $data_arr[$col];
        $type = $dir;
       
		
		$limit = "";
		if($table_length){
        $limit = " LIMIT " . $start . "," . $table_length . " ";
		}
        

        $q = $this->db->query("SELECT * FROM property_data WHERE $agency AND $borough AND $zipcode ORDER BY ". $get_col_name ."   ".$type." ".$limit);
        // print_r($this->db->last_query());

        if ($count == 1) {
            if ($q->num_rows() > 0) {
                return $q->result();
            }
            return array();
        } else {
            return $q->num_rows();
        }

    }
    
}
?>
