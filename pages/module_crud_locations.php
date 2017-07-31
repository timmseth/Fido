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

<?php include('snippets/sharedHead.php');?>

<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    $thisPage='crud';
    ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
        <?php include('snippets/sharedTopNav.php');?>
        <?php include('snippets/sharedSideNav.php');?>
    </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Database<small> (<b>C</b>reate. <b>R</b>ead. <b>U</b>pdate. <b>D</b>elete.)</small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->


<?php
/**
//Pre-load operations go here. (this includes any changes to the database that we want to show up on load.)
*/
/*==================
Pre-Load Operations
==================*/

//Check for pending building actions...
if($_POST['buildingAction']){

    //check for a specific building...
    if($_POST['buildingUID']){
        //decide on a specific building action...
        switch($_POST['buildingAction']) {
        //Edit An Existing Building. (Get Edited Values From User.)
        case 'edit':

        //give user direction
        echo '                
        <!-- User Direction -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            User Direction
                        </div>
                        <div class="panel-body">
                            <p>
                            <b>Edit the fields below</b> to set new values.<br />
                            <b>Hit update record</b> to save your changes.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->';

        //echo "Edit building information here for update...";
        echo "<form action='module_crud.php' method='POST'>
        <input type='hidden' name='preload' id='preload' value='1' />
        <input type='hidden' name='buildingUID' id='buildingUID' value='".$_POST['buildingUID']."' />
        <input type='hidden' name='buildingAction' id='buildingAction' value='update' />";
        echo '
        <div class="dataTable_wrapper">
            <table class="table table-striped table-bordered table-hover" id="dataTables-updateBuilding">
                <thead>
                    <tr>
                        <th class="center" colspan="6">Building Record</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <th>Number</th>
                        <th>Levels</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd  dt-body-center">';

                        echo "<!-- Name -->
                        <td>
                            <div class='form-group-lg'>
                                <input class='form-control' id='buildingName' name='buildingName' value='".$_POST['buildingName']."'>
                            </div>
                        </td>
                        ";
                
                        echo "<!-- Number -->
                        <td>
                            <div class='form-group-lg'>
                                <input class='form-control' id='buildingNumber' name='buildingNumber' value='".$_POST['buildingNumber']."'>
                            </div>
                        </td>
                        ";

                        echo "<!-- Levels -->
                        <td>
                            <div class='form-group-lg'>
                                <input class='form-control' id='buildingLevels' name='buildingLevels' value='".$_POST['buildingLevels']."'>
                            </div>
                        </td>
                        ";

                        echo "<!-- Notes -->
                        <td>
                            <div class='form-group-lg'>
                                <textarea class='form-control' id='buildingNotes' name='buildingNotes'>".$_POST['buildingNotes']."</textarea>
                            </div>
                        </td>
                        ";


                        echo '<!-- Update Button -->
                        <td>
                        
                        </td>
                    </tr>
                </tbody>
            </table>';
        echo " <button type='submit' class='btn btn-primary btn-lg btn-block'><i class='fa fa-edit'></i> Update Record</button>";
        echo "</form>";
        break;

        //Update An Existing Building. (Send Edited Values From User to Database.)
        case 'update':
        //check for building to update
        if ($_POST['buildingUID']) {
            echo "got building UID";
            //check for required fields
            if ($_POST['buildingName'] && $_POST['buildingNumber'] && $_POST['buildingLevels']) {
                //check for optional fields
                //update with notes
                if ($_POST['buildingNotes']) {
                //echo "Run mysql job to update all columns here.";
                //PDO Connect
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                  //Prepare Query
                  $stmt = $db->prepare('UPDATE building SET name=:name, number=:number, levels=:levels, notes=:notes WHERE building_UID=:building_UID');

                  //bind values (values are not interpolated into the query)
                  $stmt->bindParam(':name', $_POST['buildingName']);
                  $stmt->bindParam(':number', $_POST['buildingNumber']);
                  $stmt->bindParam(':levels', $_POST['buildingLevels']);
                  $stmt->bindParam(':notes', $_POST['buildingNotes']);
                  $stmt->bindParam(':building_UID', $_POST['buildingUID']);

                  // Run Query
                  $stmt->execute();

                  //Save Total Rows
                  //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Rows Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }

                }
                //update without notes
                else{
                //echo "Run mysql job to update all columns except notes here.";
                //PDO Connect
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                  //Prepare Query
                  $stmt = $db->prepare('UPDATE building SET name=:name, number=:number, levels=:levels WHERE building_UID=:building_UID');

                  //bind values (values are not interpolated into the query)
                  $stmt->bindParam(':name', $_POST['buildingName']);
                  $stmt->bindParam(':number', $_POST['buildingNumber']);
                  $stmt->bindParam(':levels', $_POST['buildingLevels']);
                  $stmt->bindParam(':building_UID', $_POST['buildingUID']);

                  // Run Query
                  $stmt->execute();

                  //Save Total Rows
                  //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }



                }
                //display dialog to redirect to main page.
            }
        }
        break;

        //Delete An Existing Building.
        case 'delete':
        if ($_POST['buildingUID']) {
            //echo "Delete building action here...";
            //PDO Connect
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
              //Prepare Query
              $stmt = $db->prepare('DELETE FROM building WHERE building_UID=:building_UID AND name=:buildingName AND number=:buildingNumber LIMIT 1');

              //bind values (values are not interpolated into the query)
              $stmt->bindParam(':building_UID', $_POST['buildingUID']);
              $stmt->bindParam(':buildingName', $_POST['buildingName']);
              $stmt->bindParam(':buildingNumber', $_POST['buildingNumber']);

              // Run Query
              $stmt->execute();

              //Save Total Rows
              //Report Success
                echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Deleted.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            //Catch Errors (if errors)
            catch(PDOException $e){
            //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
        }       
        break;

        //View An Existing Building. (default)
        default:
            //generate full navigatable list of building contents.
            include('snippets/debug_mockSelectedBuilding.php');
        break;
        }//end switch
    }

    //create a new one?
    if ($_POST['buildingAction']=='create') {
        # code...
    }
}

/**
SHOW ALL BUILDINGS
*/
//IF there is no pending action other than an allowed building action...
//THEN Show the list of all buildings.
if((!$_POST['locationAction']) && ($_POST['buildingAction']!='edit' && $_POST['buildingAction']!='view')){
    //Get a list of all current buildings in the database (store in array).
    //Initialize Multidimensional array to hold building list...
    $buildingDetails=array("UID" => array(),"number" => array(),"name" => array(),"levels" => array(),"notes" => array(),"lastMod" => array());
    //PDO Connect
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
      //Prepare Query
      $stmt = $db->prepare('SELECT * FROM building order by number asc');
      // Run Query
      $stmt->execute();
      //Store in multidimensional array
      $i=0;
      foreach($stmt as $row) {
      $i++;
      $buildingDetails[$i]['UID']=$row['building_UID'];
      $buildingDetails[$i]['number']=$row['number'];
      $buildingDetails[$i]['name']=$row['name'];
      $buildingDetails[$i]['levels']=$row['levels'];
      $buildingDetails[$i]['notes']=$row['notes'];
      $buildingDetails[$i]['status']=$row['status'];
      $buildingDetails[$i]['lastMod']=$row['lastmodified'];
      }
      //Save Total Rows
      $totalBuildings=$stmt->rowCount();
      //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Results Returned.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
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
                                <b>Click on a building to drill down</b> and view or edit contents.<br />
                                <b>Search any column by typing in the search box</b> on the right. The table will update to reflect your search criteria automatically.
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
        Building Records
        </div>
        <div class="panel-body">
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="dataTables-buildings">
                    <thead>
                        <tr>
                            <th class="center" colspan="6">Building Records</th>
                        </tr>
                        <tr>
                            
                            <th>UID</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th class="alert-danger" style="width:1%;">Delete</th>
                            <th class="center alert-info" style="width:1%;">Update</th>
                            <th class="center">Drill Down</th>

                        </tr>
                    </thead>
                    <tbody>
            <?php
            //Generate Full List Of Buildings.
            $i=1;while($i<=$totalBuildings){
                //print the row even/odd class:
                
                if ($i%2 == 0){
                    echo '<tr class="even ">';
                }
                else{
                    echo '<tr class="odd  dt-body-center">';
                }


                //print hidden UID
                echo "<td>".$buildingDetails[$i]['UID']."</td>";

                //print the rest of the row:
                echo "<td>".$buildingDetails[$i]['name']."</td>";
                echo "<td>".$buildingDetails[$i]['number']."</td>";

                //delete button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$buildingDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingStatus' id='buildingStatus' value='".$buildingDetails[$i]['status']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='delete' />
                        <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this building will also destroy everything inside this building. This is irreversible.')\"><i class='fa fa-times'></i></button>
                        </form>
                </td>";


                //edit button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$buildingDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingStatus' id='buildingStatus' value='".$buildingDetails[$i]['status']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='edit' />
                        <button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
                    </form>
                </td>";


                //print drill down button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value=".$buildingDetails[$i]['UID']." />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingStatus' id='buildingStatus' value='".$buildingDetails[$i]['status']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='view' />
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
        </div>
        </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
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
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before(
            '<tr class="group"><td colspan="5">Level '+group+'</td></tr>'
        );
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

});//END ON READY RUN SCRIPTS
</script>

</body>

</html>