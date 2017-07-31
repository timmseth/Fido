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
                            <b>Click on a level to drill down</b> into that level and view or edit contents.<br />
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
            Contents of Building '.$_POST['buildingNumber'].' - '.$_POST['buildingName'].'
        </div>
        <!-- .panel-heading -->
        <div class="panel-body">';
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

for ($iFillArrayNdx=0; $iFillArrayNdx < $_POST['buildingLevels']; $iFillArrayNdx++) { 



//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try {
    //Prepare Query
    $stmt = $db->prepare("SELECT DISTINCT t1.location_UID AS location_UID, t1.level AS location_level, t1.description AS location_description, t2.building_UID AS building_UID FROM location AS t1 INNER JOIN building AS t2 WHERE t1.fk_building_UID=t2.building_UID AND t1.fk_building_UID=:building_UID AND t1.level=:level ORDER BY t2.number+1 asc, t1.description asc");
    
    //bind values (values are not interpolated into the query)
    $stmt->bindParam(':building_UID', $_POST['buildingUID']);
    $stmt->bindParam(':level', $iFillArrayNdx);

    // Run Query
    $stmt->execute();
    //Store in multidimensional array
    $iLocation=0;
    foreach($stmt as $row) {
    $locationArray[$row['location_level']][$iLocation]['building_uid']=$row['building_UID'];
    //$locationArray[$iLocation]['level']=$row['location_level'];
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
for ($iLevels=$_POST['buildingLevels']; $iLevels >= 0; $iLevels--){
    //for each location on that level
    for ($iLocationsOnLevel=0; $iLocationsOnLevel < sizeof($locationArray[$iLevels]); $iLocationsOnLevel++) { 
        echo '<tr>';
                echo '<td>'.$locationArray[$iLevels][$iLocationsOnLevel]["uid"].'</td>';
                echo '<td>'.$locationArray[$iLevels][$iLocationsOnLevel]["description"].'</td>';
                echo '<td>'.$iLevels.'</td>';
                
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