<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PropertyController extends CI_Controller {

    public function __construct() { 
        //construct controller
        parent::__construct();
        // Load the model
        $this->load->database();
        $this->load->model('PropertyModel');
    }

    // get data from url and insert in db 
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

//get borough from agency
function get_borough_by_agency(){

        $agency = $this->input->post('agency');
        $addressess = $this->PropertyModel->get_borough_byAgency($agency);
        echo json_encode($addressess);
    }

//get zipcode from borough and agency
function get_zipcodes_by_borough(){

    $borough = $this->input->post('borough');
    $agency  = $this->input->post('agency');
    
    $zipcodes = $this->PropertyModel->get_zipcodes_byborough($borough,$agency);
    echo json_encode($zipcodes);
}

//property list
function property_view(){

    $data['property_view_count'] = $this->PropertyModel->getProperty_count(); //data count
    $data['agency'] = $this->PropertyModel->get_agency(); //get agency
    $this->load->view('propertyList', $data);
        
}

//get ajax data listing 
function ajax_property_show(){

      
        $start  = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $col    = $_REQUEST['order'][0]['column'];
        $dir    = $_REQUEST['order'][0]['dir'];
        $data['products'] = '';
        $extraval = '';
        $data['products'] = $this->PropertyModel->get_data_property($_REQUEST, $start, $length, $col, $dir, 1, $extraval);
    
        if ($data['products']) {
          
          foreach ($data['products'] as $pro) {
            // $pro->rap = round($pro->rap_price);
           
          }
        }
    
       
        $json_data = array(
          "draw" => intval($_REQUEST['draw']),
          "recordsTotal"    => $this->PropertyModel->getProperty_count(), 
          "recordsFiltered" => $this->PropertyModel->get_data_property($_REQUEST, 0, 0, $col, $dir, 0, $extraval),
          "data" => $data['products']
        );
        
        echo json_encode($json_data);

    }
    
}
?>