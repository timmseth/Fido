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
<?php
//debug POST data
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";





//set vars from previous page
if($_POST['locationUID']) {
    $locationUID=$_POST['locationUID'];
    $locationLevel=$_POST['locationLevel'];
    $parentBuildingUID=$_POST['parentBuildingUID'];
    $locationDescription=$_POST['locationDescription'];
}





//get vars from building
if($_POST['parentBuildingUID']){
    $buildingUID=$_POST['parentBuildingUID'];
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            //Prepare Query
            $stmt = $db->prepare('SELECT * from building WHERE building_UID=:fk_building_UID');
            //bind values (values are not interpolated into the query)
            $stmt->bindParam(':fk_building_UID', $parentBuildingUID);
            // Run Query
            $stmt->execute();
            //Save Total Rows
            foreach($stmt as $row) {
                $buildingName=$row['name'];
                $buildingNumber=$row['number'];
                $buildingLevels=$row['levels'];
                $buildingNotes=$row['notes'];

            }
            //Report Success
            //echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
            //Report Error Message(s)
            //echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        } 
}





//Print User Direction
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
                <b>Select a Storage-Unit to drill down</b> and see cabinets.<br />
                <b>Search any column by typing in the search box</b> on the right. The table will update to reflect your search criteria automatically.
                </p>
            </div>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
';












/**










 

echo '     
<div class="row">
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            Contents of Building '.$buildingNumber.' - '.$buildingName.'
        </div>
        <!-- .panel-heading -->
        <div class="panel-body">
        <div class="alert alert-info">
        <p>Note:<br />Items such as mounts, racks, cabinets, etc are NOT considered locations and should be entered as a "storage unit" within a "location".
        </p>
        <p>
        For the "description" field try using:
        </p>
        <ul>
        <li>Specific room names - "Rose dining room."</li>
            <li>Exact room numbers - "Room 242A."</li>
            <li>Hallway + nearest room number - "Hallway near room 242B."</li>
            <li>Cardinal Descriptions - "West Stairwell."</li>
        </ul>
        </div>';

echo '
 <!-- START create new location-->


                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#createLocationAccordion"><i class="fa fa-plus-square"></i> Create New Location</a>
                            </h4>
                        </div>
                        <div id="createLocationAccordion" class="panel-collapse collapse">
                            <div class="panel-body">

<!-- START FORM HERE -->
                    <form role="form" action="module_crud.php" method="POST"> 
                        <input type="hidden" id="locationAction" name="locationAction" value="create" />
                        <!-- Building Details -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>* </small>Building</label>
                                <select class="form-control" id="createLocationParentBuilding" name="createLocationParentBuilding" required >
                                    <option selected disabled>Select building...</option>';

                                    for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 

                                      echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';

                                    }

                                echo '
                                </select>
                                <p class="help-block">The parent building to this location. <small>(Where is this place?)</small></p>
                            </div>
                        </div>

                        <div class="col-lg-3" id="createLocationDependentLevelsContainer" name="createLocationDependentLevelsContainer">
                            <div class="form-group">
                                <label><small>* </small>Level:</label>
                                <input class="form-control" id="createLocationParentLevel" name="createLocationParentLevel">
                                <p class="help-block">Try to be as specific as possible.</p>

                            </div>
                        </div>

                        <!--

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="text-muted">Address</label>
                                <input class="form-control" id="createBuildingAddress" name="createBuildingAddress">
                                <p class="help-block">Optional Field. <small>("1111 East Martin Luther King Jr Way")</small></p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="text-muted">Long</label>
                                <input class="form-control" id="createBuildingLong" name="createBuildingLong">
                                <p class="help-block">Optional Field. <small>("42.863161")</small></p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="text-muted">Lat</label>
                                <input class="form-control" id="createBuildingLat" name="createBuildingLat">
                                <p class="help-block">Optional Field. <small>("-112.431484")</small></p>
                            </div>
                        </div>        -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><small>* </small>Description</label>
                                <input class="form-control" id="createLocationDesc" name="createLocationDesc">
                                <p class="help-block">Try to be as specific as possible.</p>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-default">Submit</button>
                                <button type="reset" class="btn btn-lg btn-default">Reset</button>
                            </div>
                        </div>
                    </form>
                <!-- END FORM HERE -->

                            ';

                            /*

<!-- ==================================================================== -->
<!-- START CREATE LOCATION STANDALONE FORM -->
    <div role="tabpanel" class="tab-pane" id="location">
        <?php
        //========
            echo '
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-fw fa-plus-circle"></i> Add Location<br />
                    <small>To An Existing Building</small></h1>

                                                    <div class="alert alert-info">
                                <p>Note:<br />Items such as mounts, racks, cabinets, etc are NOT considered locations and should be entered as a "storage unit" within a "location".
                                </p>
                                <p>
                                For the "description" field try using:
                                </p>
                                <ul>
                                <li>Specific room names - "Rose dining room."</li>
                                    <li>Exact room numbers - "Room 242A."</li>
                                    <li>Hallway + nearest room number - "Hallway near room 242B."</li>
                                    <li>Cardinal Descriptions - "West Stairwell."</li>
                                </ul>
                                </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">
                
                </div>
            </div>
            ';
            //========

                            echo '
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END create new building -->
';


*/




//====







//init storageunit array
$storageUnitArray=array();

//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try {
    //Prepare Query
    //$stmt = $db->prepare("SELECT DISTINCT t1.storageUnit_UID AS storageUnit_UID, t1.t2.number+1, t1.level AS location_level, t1.description AS location_description, t2.building_UID AS building_UID FROM location AS t1 INNER JOIN building AS t2 WHERE t1.fk_building_UID=t2.building_UID AND t1.fk_building_UID=:building_UID AND t1.level=:level ORDER BY t2.number+1 asc, t1.description asc");
    $stmt = $db->prepare("SELECT t1.storageUnit_UID, t1.type, t1.label FROM storageunit AS t1 WHERE t1.fk_location_UID=:location_UID");
    
    //bind values (values are not interpolated into the query)
    //$stmt->bindParam(':building_UID', $buildingUID);
    $stmt->bindParam(':location_UID', $locationUID);

    // Run Query
    $stmt->execute();
    //Store in multidimensional array
    $iStorageUnit=0;
    foreach($stmt as $row) {
    $storageUnitArray[$iStorageUnit]['storageUnit_UID'] =   $row['storageUnit_UID'];
    $storageUnitArray[$iStorageUnit]['label']           =   $row['label'];
    $storageUnitArray[$iStorageUnit]['type']            =   $row['type'];
    $iStorageUnit++;
  }

//echo "<pre>";
//print_r($storageUnitArray);
//echo "</pre>";

  //Report Total Rows
  $totalStorageUnits=$stmt->rowCount();
  }
  //Report Error (if error)
  catch(PDOException $e){
    echo '<div class="alert-box alert noMarginBottomAlert">Error: ' . $e->getMessage() . '</div>';
  }
  //End Generate Location Array

//DT Version
echo '

<div class="row">
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
        </div>
        <div class="panel-body">


 <div class="dataTable_wrapper">
    <table class="table table-striped table-bordered table-hover" id="dataTables-storageUnits">
        <thead>
                <tr>
                    <th class="center" colspan="6">Storage-Unit Records</th>
                </tr>
                <tr>
                <th>UID</th>
                <th>Label</th>
                <th>Type</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>UID</th>
                <th>Label</th>
                <th>Type</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </tfoot>
 
        <tbody>
';




foreach ($storageUnitArray as $key => $value) {
 echo '<tr>';
                echo '<td>'.$value['storageUnit_UID'].'</td>';
                echo '<td>'.$value['label'].'</td>';
                echo '<td>'.$value['type'].'</td>';
                
                //delete button
                echo "
                <td>
<form action='module_crud.php' method='POST'>
<!-- pass storageUnitUID -->
    <input type='hidden' name='storageUnitUID' id='storageUnitUID' value=".$value['storageUnit_UID']." />
<!-- pass action -->
    <input type='hidden' name='storageUnitAction' id='storageUnitAction' value='delete' />
    <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this Storage Unit will also destroy everything inside this Storage Unit. This is irreversible.')\"><i class='fa fa-times'></i></button>
    </form>
                </td>";

                //edit button
                echo "
                <td>

<form action='module_crud.php' method='POST'>
<!-- pass UID -->
    <input type='hidden' name='storageUnitUID' id='storageUnitUID' value=".$value['storageUnit_UID']." />
<!-- pass storageUnitLabel -->
    <input type='hidden' name='storageUnitLabel' id='storageUnitLabel' value='".$value['label']."' />
<!-- pass storageUnitType -->
    <input type='hidden' name='storageUnitType' id='storageUnitType' value='".$value['type']."' />
<!-- pass action -->
    <input type='hidden' name='storageUnitAction' id='storageUnitAction' value='edit' />
<button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
    </form>

                </td>";

                //drill down button
                echo "
                <td>

<form action='module_crud.php' method='POST'>
<!-- pass UID -->
    <input type='hidden' name='storageUnitUID' id='storageUnitUID' value=".$value['storageUnit_UID']." />
<!-- pass level -->
    <input type='hidden' name='parentLocationUID' id='parentLocationUID' value='".$locationUID."' />
<!-- pass parent building-->
    <input type='hidden' name='parentBuildingUID' id='parentBuildingUID' value='".$value['storageUnit_UID']."' />
<!-- pass action -->
    <input type='hidden' name='storageUnitAction' id='storageUnitAction' value='view' />
    <button type='submit' class='btn btn btn-primary btn-lg btn-block'><i class='fa fa-chevron-circle-right'></i></button>
    </form>

                </td>";

            echo '</tr>';


}



//for each level
//for ($iLevels=$buildingLevels; $iLevels >= 0; $iLevels--){
    //for each location on that level
/*
    for ($iStorageUnitsInLocation=0; $iStorageUnitsInLocation < sizeof($storageUnitArray[$iLevels]); $iStorageUnitsInLocation++) { 
       
    }
    */
//}
echo '
    </tbody>
</table>
';

   
echo '</div>';







/*
//START PANEL BODY

//Get all locations in building and group by level.

//Initialize Multidimensional array to hold query results...


//for ($iFillArrayNdx=0; $iFillArrayNdx < $buildingLevels; $iFillArrayNdx++) { 

//}//end fill array loop


*/


//END PANEL BODY
        echo '</div>
        <!-- .panel-body -->
    </div>
    <!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->';
?>