<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyController extends CI_Controller {


    public function __construct() {
        parent::__construct();
        // Load the model
        $this->load->database();
        $this->load->model('MyModel');
    }


	public function index()
	{    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://data.cityofnewyork.us/resource/erm2-nwe9.json?$$app_token=hksqaTPIwHhlFHpuRbfdwjSbJ');
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result, true);

        if ($obj !== null) {
            $this->load->database();

            $db_columns = $this->db->list_fields('property_data');
            
            $api_columns = array_keys($obj[0]); 
            $matching_columns = array_intersect($api_columns, $db_columns);

            foreach ($obj as $row) {
                $data = array();
                foreach ($row as $key => $value) {
                    if (in_array($key, $matching_columns)) {
                        $data[$key] = $value;
                    }
                }

                if (!empty($data)) {
                    $this->db->insert('property_data', $data);
                }
            }

            echo "Data inserted successfully!";
        } else {
            echo "Failed to fetch or decode JSON data.";
        }
	}


    function datatable(){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://data.cityofnewyork.us/resource/erm2-nwe9.json?$$app_token=hksqaTPIwHhlFHpuRbfdwjSbJ');
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result, true);

        if ($obj !== null) {

        $this->load->database();
        $db_columns = $this->db->list_fields('property_data');
        $api_columns = array_keys($obj[0]); 
        $matching_columns = array_intersect($api_columns, $db_columns);
        
        $data['matching_columns'] = $matching_columns;
        $data['agency'] = $this->MyModel->get_agency(); 
        // $data['zipcode'] = $this->MyModel->get_zipcode(); 
        // $data['address'] = $this->MyModel->get_address(); 

        $data['records'] = $this->db->get('property_data')->result_array();
        $this->load->view('list', $data);
    } else {
        echo "Failed to fetch or decode JSON data.";
    }
    }

    function get_zipcodes_by_borough(){

        $borough = $this->input->post('borough');
        $zipcodes = $this->MyModel->get_zipcodes_byborough($borough);
        echo json_encode($zipcodes);
    }

    function get_borough_by_agency(){

        // $zip = $this->input->post('zip');
        $agency = $this->input->post('agency');
        $addressess = $this->MyModel->get_borough_byAgency($agency);
        echo json_encode($addressess);
    }


    
}
