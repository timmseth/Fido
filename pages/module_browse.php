<?php
/*
+==============================+
|                              |
| ███████╗██╗██████╗  ██████╗  |
| ██╔════╝██║██╔══██╗██╔═══██╗ |
| █████╗  ██║██║  ██║██║   ██║ |
| ██╔══╝  ██║██║  ██║██║   ██║ |
| ██║     ██║██████╔╝╚██████╔╝ |
| ╚═╝     ╚═╝╚═════╝  ╚═════╝  |
+==============================+=========================================+
| Description: FiDo is a web app used to manage fiber optic resources.   |
| Author: Seth Timmons                                                   |
+==============================+=========================================+
| This file is part of FiDo.                                             |
|                                                                        |
| FiDo is free software: you can redistribute it and/or modify           |
| it under the terms of the GNU General Public License as published by   |
| the Free Software Foundation, either version 3 of the License, or      |
| (at your option) any later version.                                    |
|                                                                        |
| FiDo is distributed in the hope that it will be useful,                |
| but WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
| GNU General Public License for more details.                           |
|                                                                        |
| You should have received a copy of the GNU General Public License      |
| along with FiDo.  If not, see <http://www.gnu.org/licenses/>.          |
|                                                                        |
+========================================================================+
*/
?>
<!DOCTYPE html>
<html lang="en">

<?php 
//require common php functions
include('snippets/commonFunctions.php');
//include snippet - shared head html
include('snippets/sharedHead.php');
//write page start snippet
$thisPage='browseAll';
generatePageStartHtml($thisPage);





//include('snippets/sharedBreadcrumbs.php');
writeHeader('Direct Browse <small>(Select a Category)</small>');



generateAlert('info','This page is currently under development and currently only contains storage units.<br />Once completed it will allow direct browsing of all buildings, locations, storage units, etc in a tabbed panels format.<br />This page is intended to allow for looking up specific information very quickly.');




//check for override
if(isset($_GET['list'])){
    switch ($_GET['list']){
        case 'buildings':
            $list='buildings';
            break;
        
        case 'locations':
            $list='locations';
            break;
        
        case 'storageUnits':
            $list='storageUnits';
            break;
        
        case 'cabinets':
            $list='cabinets';
            break;
        
        //catch-other default to storage units
        default:
            $list='storageUnits';
            break;
    }
}
//absolute default to storage units
else{
    $list='storageUnits';
}



generateBrowseAllHtml($list);












function generateBrowseAllHtml($requested){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;

    switch ($requested) {
        case 'buildings':
            # code...
            break;

        case 'locations':
            # code...
            break;

        case 'cabinets':
            # code...
            break;

        //default to storageunits as catchall
        default:
            # code...
            break;
    }

    //Get a list of all current buildings in the database (store in array).
    //Initialize Multidimensional array to hold building list...
    $storageUnitDetails=array("UID" => array(),"type" => array(),"label" => array(),"lastMod" => array());
    //PDO Connect
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
      //Prepare Query
      $stmt = $db->prepare('SELECT * FROM storageunit order by label asc');
      // Run Query
      $stmt->execute();
      //Store in multidimensional array
      $i=0;
      foreach($stmt as $row) {
      $i++;
      $storageUnitDetails[$i]['UID']=$row['storageUnit_UID'];
      $storageUnitDetails[$i]['type']=$row['type'];
      $storageUnitDetails[$i]['label']=$row['label'];
      $storageUnitDetails[$i]['lastMod']=$row['lastMod'];
      }
      //Save Total Rows
      $totalStorageUnits=$stmt->rowCount();
      //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Results Returned. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
      }
    //Catch Errors (if errors)
    catch(PDOException $e){
    //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
    }
    //End Load Building Array

    ?>


    <!-- User Direction -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    User Direction
                </div>
                <div class="panel-body">
                    <p>
                    Click on any entry to drill down.<br />
                    Search any column by typing in the search box.<br />
                    The table will update to reflect your search criteria automatically.
                    </p>
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->


    <!-- List of all top level (building) records. -->
    <div class="row">
    <div class="col-lg-12">

    <!-- display building list -->
    <div class="panel panel-primary">
    <div class="panel-heading">
    Storage Unit Records

    </div>
    <div class="panel-body">

    <input type="text" class="form-control" id="searchStorageUnits" name="searchStorageUnits" placeholder="Start typing to filter results...">


    <div class="dataTable_wrapper">
        <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-storageUnits">
            <thead>
                <tr>
                    <th class="center" colspan="6">Storage Unit Records</th>
                </tr>
                <tr>
                    
                    <th>UID</th>
                    <th>Type</th>
                    <th>Label</th>
                    <th class="alert-danger" style="width:1%;">Delete</th>
                    <th class="center alert-info" style="width:1%;">Update</th>
                    <th class="center">Drill Down</th>

                </tr>
            </thead>
            <tfoot>
                <tr>
                    
                    <th>UID</th>
                    <th>Type</th>
                    <th>Label</th>
                    <th class="alert-danger" style="width:1%;">Delete</th>
                    <th class="center alert-info" style="width:1%;">Update</th>
                    <th class="center">Drill Down</th>

                </tr>
            </tfoot>
            <tbody>
                <?php
                //Generate Full List Of Buildings.
                $i=1;while($i<=$totalStorageUnits){
                //print the row even/odd class:
                if ($i % 2 == 0) {
                    echo '<tr class="even ">';
                }
                else{
                    echo '<tr class="odd  dt-body-center">';
                }


                //print hidden UID
                echo "<td>".$storageUnitDetails[$i]['UID']."</td>";

                //print the rest of the row:
                echo "<td>".$storageUnitDetails[$i]['type']."</td>";
                echo "<td>".$storageUnitDetails[$i]['label']."</td>";

                //delete button

                echo "
                <td>
                ";
                /*
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$storageUnitDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$storageUnitDetails[$i]['label']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='delete' />
                        <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this building will also destroy everything inside this building. This is irreversible.')\"><i class='fa fa-times'></i></button>
                        </form>

                        */
                       echo "
                </td>";


                //edit button
                echo "
                <td>
                ";
                /*
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$buildingDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='edit' />
                        <button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
                    </form>
                    */
                       echo "
                </td>";


                //print drill down button
                echo "
                <td>

                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='storageUnitUID' id='storageUnitUID' value=".$storageUnitDetails[$i]['UID']." />
                        <input type='hidden' name='storageUnitType' id='storageUnitType' value='".$storageUnitDetails[$i]['type']."' />
                        <input type='hidden' name='storageUnitLabel' id='storageUnitLabel' value='".$storageUnitDetails[$i]['label']."' />
                        <input type='hidden' name='storageUnitAction' id='storageUnitAction' value='view' />
                        <button type='submit' class='btn btn btn-primary btn-lg btn-block'><i class='fa fa-chevron-circle-right'></i></button>
                    </form>
                 
                </td>";





                echo "</tr>";
                //increment counter
                $i++;
                }
                ?>
            </tbody>
        </table>
    </div>

</div><!-- /.panel-body -->
</div><!-- /.panel -->
</div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<?php
}
?>





            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
/*
//BUILDINGS DATATABLE
    //init datatable and set options
    $('#dataTables-buildings').DataTable({
        searching: true,
        "search": {
            "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
        {
            "targets": [0,3],
            "visible": false
        },
        {
            "targets": [4,5,6],
            "orderable": false
        }
    ]
    });




//END BUILDINGS DATATABLE
*/
//
//
/*
//LOCATIONS DATATABLE
    //init datatable and set options
    $('#dataTables-locations').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var elementHiddenBuildingPass = $('#elementHiddenBuildingPass').val();
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before('<tr class="group"><td colspan="5">Level '+group+' - <a target="_blank" href="module_mapping_interior.php?b='+elementHiddenBuildingPass+'&l='+group+'">view map</a></td></tr>');
        last = group;
        }});
        },
        "order": [[ 2, "desc" ]],
        "columnDefs": [
        {
        "targets": [0],
        "visible": false
        },
        {
        "targets": [3,4,5],
        "orderable": false
        }]
    });
// Order by the grouping
    $('#dataTables-locations tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
//END LOCATIONS DATATABLE
*/

//STORAGEUNITS DATATABLE
    //init datatable and set options
    $('#dataTables-storageUnits').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        "order": [[ 2, "asc" ]],
        "columnDefs": [
        {
        "targets": [0],
        "visible": false
        },
        {
        "targets": [3,4,5],
        "orderable": false
        }]
        /*
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var elementHiddenBuildingPass = $('#elementHiddenBuildingPass').val();
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before('<tr class="group"><td colspan="4">'+group.toUpperCase()+'</td></tr>');
        last = group;
        }});
        },
        */
       /*
        */
        
    });
// Order by the grouping
 /*
    $('#dataTables-storageUnits tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
    */
//END STORAGEUNITS DATATABLE
/*

//CABINETS DATATABLE
    //init datatable and set options
    $('#dataTables-cabinets').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before(
            '<tr class="group"><td colspan="5">Storage Unit '+group+'</td></tr>'
        );
        last = group;
        }});
        },
        "order": [[ 2, "asc" ]],
        "columnDefs": [
        {
        "targets": [0,2],
        "visible": false
        },
        {
        "targets": [4,5,6],
        "orderable": false
        }]
    });
// Order by the grouping
    $('#dataTables-cabinets tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
//END CABINETS DATATABLE
*/
//START panelDisplay DATATABLE
    //init datatable and set options
    $('.dataTables-portsDisplay').DataTable({
        searching: false,
        info: false,
        paging: false,
        ordering: false
    });
//END panelDisplay DATATABLE



});//END ON READY RUN SCRIPTS
</script>

</body>

</html>


?>
