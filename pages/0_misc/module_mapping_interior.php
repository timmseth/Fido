<!DOCTYPE html>
<html lang="en">

<?php include('snippets/sharedHead.php');?>

<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    $thisPage='mapping';
    ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
        <?php include('snippets/sharedTopNav.php');?>
        <?php include('snippets/sharedSideNav.php');?>
    </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                 <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Maps &amp; Paths<small> (Document Paths.)</small></h1>
                    </div>
                    <!-- /.col-lg-12 -->

                    <div class="col-lg-12">



                    <?php
                        //if there was a building passed list the levels available.
                        if (isset($_POST['b'])){

                            //get details from building uid
    $buildingDetails=array("UID","number","name","levels","notes","lastMod");
    //PDO Connect
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
      //Prepare Query
      $stmt = $db->prepare('SELECT * FROM building WHERE building_UID="'.$_POST["b"].'" order by number asc LIMIT 1');
      // Run Query
      $stmt->execute();
      //Store in multidimensional array
      $i=0;
      foreach($stmt as $row) {
      $buildingDetails['UID']=$row['building_UID'];
      $buildingDetails['number']=$row['number'];
      $buildingDetails['name']=$row['name'];
      $buildingDetails['levels']=$row['levels'];
      $buildingDetails['notes']=$row['notes'];
      $buildingDetails['status']=$row['status'];
      $buildingDetails['lastMod']=$row['lastmodified'];
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

                            echo '
                            <!-- Accepted! -->
                                <div class="col-lg-12">
                                    <h1>Building: '.$buildingDetails['name'].'.</h1>
                                    <p>The following levels were found in this building:</p>
                                    ';

                                    for($i=$buildingDetails['levels'];$i>=0;$i--){

                                        echo '
                                        <div class="panel panel-default">
                                        <div class="panel-heading">
                                        ';
                                        //if there is a levelmap in the database show a link
                                        echo '<h3>';
                                            echo 'Level '.$i;
                                        echo '</h3>';
                                        echo '
                                        </div>

                                        <div class="panel-body">';


                                            

                                        //allow upload of a map
                                        echo '
                                        <form method="POST" action="#">

                                          <div class="form-group">
                                            <!-- Pass Building Name -->
                                            <input type="hidden" id="uploadMap_buildingName" name="uploadMap_buildingName" value="'.$buildingDetails['name'].'" />
                                            <!-- Pass Building Level -->
                                            <input type="hidden" id="uploadMap_buildingLevel" name="uploadMap_buildingLevel" value="'.$i.'" />
                                            <!-- Map Image -->
                                            <label for="uploadedMap">Map Image</label>
                                            <input type="file" id="uploadedMap" name="uploadedMap">
                                            <p class="help-block">This should be an image of the floor/level (facility maps usually).</p>
                                          </div>

                                          <button type="submit" class="btn btn-default">Submit</button>

                                        </form>
                                        ';

                                        //else show a link to the editable level-based path map.
                                        echo '
                                          <a href="module_mapping_interior.php?b='.$buildingDetails['name'].'&l='.$i.'">Open Interactive Level Path Map</a>  
                                        ';




                                        echo'
                                        </div>
                                        </div>
                                        ';
                                       


                                        echo '
                                        <hr>
                                        ';

                                    }

                                    echo '
                                </div>';

                        }


/*
                    //if there is a building and level passed
                    //load the proper map straightaway
                        if (isset($_GET['b']) && isset($_GET['l'])) {
                            echo '
                            <!-- Accepted! -->
                                <div class="col-lg-12">
                                    <h1>Building '.$_GET['b'].' - Level '.$_GET['l'].'.</h1>
                                    <p>There are also maps for the following levels:</p>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>        
                                </div>

                            <!-- Map Here -->
                                <div class="col-lg-12">
                                    <h1>Interior Building Map</h1> 

                                </div>
                            ';
                            
                            
                        }

                        //if there is a building passed but no level
                        //load the proper map options straightaway
                        elseif(isset($_GET['b']) && !isset($_GET['l'])){
                         echo '
                            <!-- Kinda Accepted! - Pick A Level-->
                                <div class="col-lg-12">
                                    <h1>Building '.$_GET['b'].'</h1>
                                    <p>There are maps for the following levels in this building:</p>
                                    <ul>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>        
                                </div>
                            ';
                        }
                        */

                        //if any other situation presents itself
                        //present user with a list of buildings to choose from
                        else{
                            echo '
                            <!-- REJECTED!-->
                                <div class="col-lg-12">
                                        <h2>Select a Building and Level</h2>';


/**
SHOW ALL BUILDINGS
*/
//IF there is no pending action other than an allowed building action...
//THEN Show the list of all buildings.
if((!$_POST['locationAction'] && !$_POST['cabinetAction']) && ($_POST['buildingAction']!='edit' && $_POST['buildingAction']!='view')){
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
                                <b>Click on a building to drill down</b> and view or edit the fiber paths on each level.<br />
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
                <table class="table table-striped table-bordered table-hover" id="dataTables-building-maps">
                    <thead>
                        <tr>
                            <th>UID</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Notes</th>
                            <th class="center">View Maps</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>UID</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Notes</th>
                            <th class="center">View Maps</th>
                        </tr>
                    </tfoot>
                    <tbody>
            <?php
            //Generate Full List Of Buildings.
            $i=1;while($i<=$totalBuildings){
                //print the row even/odd class:
                if ($i % 2 == 0) {
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
                echo "<td>".$buildingDetails[$i]['notes']."</td>";

                //print drill down button
                echo "
                <td>
                    <form action='module_mapping_interior.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value=".$buildingDetails[$i]['UID']." />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingMapsAction' id='buildingMapsAction' value='view' />
                        <input type='hidden' name='b' id='b' value='".$buildingDetails[$i]['UID']."'/>
                        

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

                                     
                                echo '</div>
                            ';
                        }
                    ?>





                    </div>
                    <!-- /.col-lg-12 -->

                </div>
                <!-- /.row -->
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
            "targets": [0],
            "visible": false
        },
        {
            "targets": [4,5,6],
            "orderable": false
        }
    ]
    });
//END BUILDINGS DATATABLE

//BUILDING MAPS DATATABLE
    //init datatable and set options
    $('#dataTables-building-maps').DataTable({
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
            "targets": [0],
            "visible": false
        },
        {
            "targets": [4],
            "orderable": false
        }
    ]
    });
//END BUILDING MAPS DATATABLE
});

</script>

</body>

</html>
