<!DOCTYPE html>
<html>
<head>
    <title>Property Data Listing</title>
   <!-- data table -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- data table -->
</head>
<body>
<h1 class="text-center">Property Data Listing</h1>
<h4 class="text-left">Filters</h4>
    <div class="container">
    <div class="row">
        <div class="col">

            <div class="row g-2">

                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Agency Name</div>
                        <select class="form-select" id="agencyDropdown" name="agency">
                            <option value="">Choose...</option>
                           
                            <?php if(isset($agency)){ foreach ($agency as $agencies) { ?>
                                <option value="<?php echo $agencies['agency_name']; ?>"><?php echo $agencies['agency_name']; ?></option>
                            <?php } } ?>

                            
                        </select>
                    </div>
                </div>
                <!-- get ajax call -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Borough</div>
                        <select class="form-select" id="boroughDropdown" name="agency">
                            <option value="">No Data Found</option>
                        </select>
                    </div>
                </div>

                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Zipcode</div>
                        <select class="form-select" id="zipcodeDropdown">
                            <option value="">No Data Found</option>
                        </select>
                    </div>
                </div>
                <!-- dependent dropdown -->
            </div>
        </div>
    </div>
</div>

<div class="result_data_table table-responsive ">
    <h6 id="toptotalCount"></h6>
        <table id="property-datatable" class="display data_tble display table table-striped nowrap dataTable no-footer dtr-inline collapsed  custom_class" style="width:100%">
            <thead class="main_thead">
            <tr class="main_thead1">
                <th>unique_key</th>
                <th>created_date</th>
                <th>agency</th>
                <th>agency_name</th>
                <th>borough</th>
                <th>incident_zip</th>
                <th>complaint_type</th>
                <th>descriptor</th>
                <th>location_type</th>
                <th>incident_address</th>
                <th>street_name</th>
                <th>cross_street_1</th>
                <th>cross_street_2</th>
                <th>address_type</th>
                <th>city</th>
                <th>status</th>
                <th>resolution_description</th>
                <th>resolution_action_updated_date</th>
                <th>community_board</th>
                <th>bbl</th>
                <th>x_coordinate_state_plane</th>
                <th>y_coordinate_state_plane</th>
                <th>open_data_channel_type</th>
                <th>park_facility_name</th>
                <th>park_borough</th>
                <th>latitude</th>
                <th>longitude</th>
            </tr>
            </thead>

        </table>
</div>
</body>
</html>
<script>
    $(document).ready(function() {
       //FOR AGENCY FILTER
        $('#agencyDropdown').on('change', function() {
        var selectedAgency = $(this).val(); 
         //console.log("Selected agency: " + selectedAgency);

         if(selectedAgency == ''){
            dt.draw();
         }
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('/PropertyController/get_borough_by_agency'); ?>",
            data: { agency: selectedAgency },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                var dropdown  = $('#boroughDropdown');
                var dropdown2 = $('#zipcodeDropdown');
                dropdown.empty();
                dropdown2.empty();
                dropdown.append('<option value="">Choose...</option>');
                dropdown2.append('<option value="">Choose...</option>');
                $.each(response, function(index, value) {
                    dropdown.append('<option value="' + value.borough + '">' + value.borough + '</option>');
                }); 
                dt.draw();
            },
            error: function(xhr, status, error) {
                console.log("AJAX error: " + error);
            }

            // handle the dropdown change event
          
        });

    });

        $('#boroughDropdown').on('change', function() {
        var selectedborough = $(this).val(); 
        var selectedAgency = $("#agencyDropdown").val(); 
        
        // console.log(selectedborough);

        if(selectedborough == ''){
            dt.draw();
         }
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('/PropertyController/get_zipcodes_by_borough'); ?>",
            data: { borough: selectedborough, agency: selectedAgency },
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                var dropdown = $('#zipcodeDropdown');
                dropdown.empty();
                dropdown.append('<option value="">Choose...</option>');
                $.each(response, function(index, value) {
                    dropdown.append('<option value="' + value.incident_zip + '">' + value.incident_zip + '</option>');
              
                });
                dt.draw();

            },
            error: function(xhr, status, error) {
                console.log("AJAX error: " + error);
            }
        });

           
        });
        
        $('#zipcodeDropdown').on('change', function() {
            dt.draw();
        });
      
        
        var dt = $('#property-datatable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "sortable": true,
            'stateSave': true,
            "pageLength": 60,
            "searching": false,
            "lengthChange": false,
            "fixedHeader": true,
            'pagingType': 'full_numbers',
            "infoCallback": function(settings, start, end, max, total, pre) {
                            $("#toptotalCount").html("Total Records "+ total);
                            // return total;
                            return "Showing" + start +" to "+end+" of "+max+" entries";;
                             
                       },
            'responsive': true,
            "ajax": {
                "url": "<?php echo site_url(); ?>/PropertyController/ajax_property_show",
                "type": "POST",
                "data": function(data) {

                var agency = [];
                $('#agencyDropdown option:selected').each(function() {
                    agency.push($(this).val());
                });

                var borough = [];  
                $('#boroughDropdown option:selected').each(function() {
                    borough.push($(this).val());
                });

                var zipcode = [];
                $('#zipcodeDropdown option:selected').each(function() {
                    zipcode.push($(this).val());
                });

                
                data.agencyInput  = agency;
                data.boroughInput = borough;
                data.zipcodeInput = zipcode;

                }
            },

            "columns": [
                
                {
                    "data": "unique_key"
                },
               
                {
                    "data": "created_date"
                },
                {
                    "data": "agency"
                },
                {
                    "data": "agency_name"
                },
                {
                    "data": "borough"
                },
                {
                    "data": "incident_zip"
                },
                {
                    "data": "complaint_type"
                },
                {
                    "data": "descriptor"
                },
                {
                    "data": "location_type"
                },
                
                {
                    "data": "incident_address"
                },
                {
                    "data": "street_name"
                },
                {
                    "data": "cross_street_1"
                },
                {
                    "data": "cross_street_2"
                },
                {
                    "data": "address_type"
                },
                {
                    "data": "city"
                },
                {
                    "data": "status"
                },
                {
                    "data": "resolution_description"
                },
                {
                    "data": "resolution_action_updated_date"
                },
                {

                    "data": 'community_board'
                },
                {
                    "data": "bbl"
                },
                
                {
                    "data": "x_coordinate_state_plane"
                },
                {
                    "data": "y_coordinate_state_plane"
                },
                {
                    "data": "open_data_channel_type"
                },
                {
                    "data": "park_facility_name"
                },
                {
                    "data": "park_borough"
                },
                {
                    "data": "latitude"
                },
                {
                    "data": "longitude"
                }
            ],
            "order": [
                [0, "ASC"]
            ],
            'columnDefs': [{
               
                "targets": [26],
                "orderable": false
            }],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
               

            }

        });

    });

</script>