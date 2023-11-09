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
<style>
    /* .dropdowns{display:inline-block} */
</style>
<body>

<h1 class="text-center">Property Data Listing</h1>
<h4 class="text-left">Filters</h4>
    

    <div class="container">
    <div class="row">
        <div class="col">

            <div class="row g-2">

                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Agency</div>
                        <select class="form-select" id="agencyDropdown">
                            <option selected>Choose...</option>
                           
                            <?php if(isset($agency)){ foreach ($agency as $agencies) { ?>
                                <option value="<?php echo $agencies['agency']; ?>"><?php echo $agencies['agency']; ?></option>
                            <?php } } ?>

                            
                        </select>
                    </div>
                </div>
                <!-- get ajax call -->
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Borough</div>
                        <select class="form-select" id="boroughDropdown">
                            <option>No Data Found</option>
                        </select>
                    </div>
                </div>

                <div class="col">
                    <div class="input-group">
                        <div class="input-group-text">Select Zipcode</div>
                        <select class="form-select" id="zipcodeDropdown">
                            <option>No Data Found</option>
                        </select>
                    </div>
                </div>
                
                <!-- dependent dropdown -->
            </div>
        </div>
    </div>
</div>

  

<table id="data_table" class="table table-striped nowrap" style="width:100%">
        <thead>
            <tr>
            <?php
       
                foreach ($matching_columns as $col) {
                    
                echo "<th>".$col ."</th>";

                }
                    ?>
            </tr>
        </thead>
        <tbody>

           <?php foreach ($records as $row): ?>
                <tr>
                
                <?php
                    
                    foreach ($matching_columns as $col) {
                        
                    echo "<td>".$row[$col] ."</td>";

                    }
                ?>
                </tr>
           <?php endforeach; ?>
            
        </tbody>
    </table>
    

</body>
<script>
// datatable
$(document).ready(function() {

    new DataTable('#data_table', {
        pagingType: 'full_numbers',
        responsive: true
    });

});

</script>

<script>
    $(document).ready(function() {
    $('#agencyDropdown').change(function() {
        var selectedAgency = $(this).val(); 
        // console.log("Selected agency: " + selectedAgency);
        
        $.ajax({
            type: "POST",
            url: "<?php echo siter_url('MyController/get_borough_by_agency'); ?>",
            data: { agency: selectedAgency },
            dataType: 'json',
            success: function(response) {
                console.log("AJAX success");
                var dropdown = $('#boroughDropdown');
                dropdown.empty();
                $.each(response, function(index, value) {
                    dropdown.append('<option selected>Choose...</option>');
                    dropdown.append('<option value="' + value.borough + '">' + value.borough + '</option>');
                }); 
            },
            error: function(xhr, status, error) {
                console.log("AJAX error: " + error);
            }
        });
    });


    $('#boroughDropdown').change(function() {
        var selectedborough = $(this).val(); 
        // console.log("Selected agency: " + selectedzip);
        
        $.ajax({
            type: "POST",
            url: "<?php echo siter_url('MyController/get_zipcodes_by_borough'); ?>",
            data: { borough: selectedborough },
            dataType: 'json',
            success: function(response) {
                console.log("AJAX success");
                var dropdown = $('#zipcodeDropdown');
                dropdown.empty();
                $.each(response, function(index, value) {
                    // dropdown.append('<option selected>Choose...</option>');
                    dropdown.append('<option value="' + value.incident_zip + '">' + value.incident_zip + '</option>');
              
                });
            },
            error: function(xhr, status, error) {
                console.log("AJAX error: " + error);
            }
        });
    });

});
</script>


</html>
