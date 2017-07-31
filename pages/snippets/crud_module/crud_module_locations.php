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
//set vars from building page
if($_POST['buildingUID']) {
    $buildingUID=$_POST['buildingUID'];
    $buildingNumber=$_POST['buildingNumber'];
    $buildingName=$_POST['buildingName'];
    $buildingLevels=$_POST['buildingLevels'];
}
//set vars from location update/delete
if(isset($_POST['parentBuildingUID'])){
    $buildingUID=$_POST['parentBuildingUID'];
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            //Prepare Query
            $stmt = $db->prepare('SELECT * from building WHERE building_UID=:fk_building_UID');
            //bind values (values are not interpolated into the query)
            $stmt->bindParam(':fk_building_UID', $buildingUID);
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
                            <b>Select a location to drill down</b> and see storage units, cabinets, and more.<br />
                            <b>Search any column by typing in the search box</b> on the right. The table will update to reflect your search criteria automatically.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->';

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

//$buildingUID

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

    <option selected value="'.$buildingUID.'">'.$buildingNumber.' - '.$buildingName.'</option>

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
            <button type="reset"  class="btn btn-lg btn-default">Reset</button>
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
            

                            */


                            /*
                            <form role="form" action="module_crud.php" method="POST"> 
                            <input type="hidden" id="buildingAction" name="buildingAction" value="create" />
                            <!-- Building Details -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><small>* </small>Name</label>
                                    <input class="form-control" id="createBuildingName" name="createBuildingName" required >
                                    <p class="help-block">The official ISU name. <small>("Rendezvous")</small></p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label><small>* </small>Number</label>
                                    <input class="form-control" id="createBuildingNumber" name="createBuildingNumber" required >
                                    <p class="help-block">The official ISU number. <small>("38")</small></p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label><small>* </small>Levels</label>
                                    <input class="form-control" id="createBuildingLevels" name="createBuildingLevels" required >
                                    <p class="help-block">How many levels does this building have? <small>("5")</small></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="text-muted">Notes</label>
                                    <input class="form-control" id="createBuildingNotes" name="createBuildingNotes">
                                    <p class="help-block">Optional Field</p>
                                </div>
                            </div>
                    
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-lg btn-default">Submit</button>
                                    <button type="reset" class="btn btn-lg btn-default">Reset</button>
                                </div>
                            </div>

                            </form>
                            */

                            echo '
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END create new building -->
';


//START PANEL BODY

//Get all locations in building and group by level.
/*=====================
Generate Location Array
=====================*/
//Initialize Multidimensional array to hold query results...
/*
$locationArray=array(
  "building_uid" => array(), //parent building UID
  "level" => array(),        //level of parent building 
  "uid" => array(),          //location UID
  "description" => array()  //location description
  );
*/

$locationArray=array();

for ($iFillArrayNdx=0; $iFillArrayNdx < $buildingLevels; $iFillArrayNdx++) { 

//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try {
    //Prepare Query
    $stmt = $db->prepare("SELECT DISTINCT t1.location_UID AS location_UID, t1.level AS location_level, t1.description AS location_description, t2.number+1, t2.building_UID AS building_UID FROM location AS t1 INNER JOIN building AS t2 WHERE t1.fk_building_UID=t2.building_UID AND t1.fk_building_UID=:building_UID AND t1.level=:level ORDER BY t2.number+1 asc, t1.description asc");
    
    //bind values (values are not interpolated into the query)
    $stmt->bindParam(':building_UID', $buildingUID);
    $stmt->bindParam(':level', $iFillArrayNdx);

    // Run Query
    $stmt->execute();
    //Store in multidimensional array
    $iLocation=0;
    foreach($stmt as $row) {
    $locationArray[$row['location_level']][$iLocation]['building_uid']=$row['building_UID'];
    $locationArray[$row['location_level']][$iLocation]['uid']=$row['location_UID'];
    $locationArray[$row['location_level']][$iLocation]['description']=$row['location_description'];
    $iLocation++;
  }
  //Report Total Rows
  $totalLocations=$stmt->rowCount();
  }
  //Report Error (if error)
  catch(PDOException $e){
    echo '<div class="alert-box alert noMarginBottomAlert">Error: ' . $e->getMessage() . '</div>';
  }
  //End Generate Location Array
}//end fill array loop

//DT Version
echo '
 <div class="dataTable_wrapper">
    <table class="table table-striped table-bordered table-hover" id="dataTables-locations">
        <thead>
                <tr>
                    <th class="center" colspan="6">Location Records</th>
                </tr>
                <tr>
                <th>UID</th>
                <th>Description</th>
                <th>Level</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>UID</th>
                <th>Description</th>
                <th>Level</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </tfoot>
 
        <tbody>
';
//for each level
for ($iLevels=$buildingLevels; $iLevels >= 0; $iLevels--){
    //for each location on that level
    
    for ($iLocationsOnLevel=0; $iLocationsOnLevel < sizeof($locationArray[$iLevels]); $iLocationsOnLevel++) { 
        echo '<tr>
                <td>'.$locationArray[$iLevels][$iLocationsOnLevel]["uid"].'</td>
                <td>'.$locationArray[$iLevels][$iLocationsOnLevel]["description"].'</td>
                <td>'.$iLevels.'</td>';
                
                //delete button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                    <!-- pass UID -->
                        <input type='hidden' name='locationUID' id='locationUID' value=".$locationArray[$iLevels][$iLocationsOnLevel]["uid"]." />
                    <!-- pass description -->
                        <input type='hidden' name='locationDescription' id='locationDescription' value='".$locationArray[$iLevels][$iLocationsOnLevel]["description"]."' />
                    <!-- pass level -->
                        <input type='hidden' name='locationLevel' id='locationLevel' value='".$iLevels."' />
                    <!-- pass parent building-->
                        <input type='hidden' name='parentBuildingUID' id='parentBuildingUID' value='".$locationArray[$iLevels][$iLocationsOnLevel]["building_uid"]."' />
                    <!-- pass action -->
                        <input type='hidden' name='locationAction' id='locationAction' value='delete' />
                        <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this Location will also destroy everything inside this location. This is irreversible.')\"><i class='fa fa-times'></i></button>
                        </form>
                </td>";

                //edit button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                    <!-- pass UID -->
                        <input type='hidden' name='locationUID' id='locationUID' value=".$locationArray[$iLevels][$iLocationsOnLevel]["uid"]." />
                    <!-- pass description -->
                        <input type='hidden' name='locationDescription' id='locationDescription' value='".$locationArray[$iLevels][$iLocationsOnLevel]["description"]."' />
                    <!-- pass level -->
                        <input type='hidden' name='locationLevel' id='locationLevel' value='".$iLevels."' />
                    <!-- pass parent building-->
                        <input type='hidden' name='parentBuildingUID' id='parentBuildingUID' value='".$locationArray[$iLevels][$iLocationsOnLevel]["building_uid"]."' />
                    <!-- pass action -->
                        <input type='hidden' name='locationAction' id='locationAction' value='edit' />
                        <button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
                    </form>
                </td>";

                //drill down button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                    <!-- pass UID -->
                        <input type='hidden' name='locationUID' id='locationUID' value=".$locationArray[$iLevels][$iLocationsOnLevel]["uid"]." />
                    <!-- pass description -->
                        <input type='hidden' name='locationDescription' id='locationDescription' value='".$locationArray[$iLevels][$iLocationsOnLevel]["description"]."' />
                    <!-- pass level -->
                        <input type='hidden' name='locationLevel' id='locationLevel' value='".$iLevels."' />
                    <!-- pass parent building-->
                        <input type='hidden' name='parentBuildingUID' id='parentBuildingUID' value='".$locationArray[$iLevels][$iLocationsOnLevel]["building_uid"]."' />
                    <!-- pass action -->
                        <input type='hidden' name='locationAction' id='locationAction' value='view' />
                        <button type='submit' class='btn btn btn-primary btn-lg btn-block'><i class='fa fa-chevron-circle-right'></i></button>
                    </form>
                </td>";

            echo '</tr>';
    }
}
echo '
    </tbody>
</table>
';

        echo '</div>
    </div>
</div>';


echo '</div>';




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