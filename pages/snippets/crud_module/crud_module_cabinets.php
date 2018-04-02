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
/**
==START===================
*/
//DEBUG
//debugPrintData($_POST);
/*
Array Example:
//$_POST['locationUID'] => 			0172
//$_POST['locationDescription'] => 	Room A503
//$_POST['locationLevel'] => 		4
//$_POST['parentBuildingUID'] => 	0001
//$_POST['locationAction'] => 		view
*/

//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try {
    //Prepare Query
    $stmt = $db->prepare("SELECT cabinet.cabinet_UID AS cabinet_UID, cabinet.label AS cabinet_label, cabinet.notes AS cabinet_notes, storageunit.storageUnit_UID AS su_uid, storageunit.label AS storageUnit_label FROM storageunit LEFT JOIN cabinet ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID LEFT JOIN location ON storageunit.fk_location_UID=location.location_UID WHERE storageunit.storageUnit_UID=:storageUnitUID");


    //bind values (values are not interpolated into the query)
    $stmt->bindParam(':storageUnitUID', $_POST['storageUnitUID']);

    // Run Query
    $stmt->execute();
    //Store in multidimensional array
    $iCabinet=0;
    $storageUnitLabel='';
    foreach($stmt as $row) {
        $storageUnitLabel=$row['storageUnit_label'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['storageUnit_label']=$row['storageUnit_label'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['storageUnit_UID']=$row['storageUnit_UID'];
    if ($row['cabinet_UID']==null) {
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['uid']=$row['cabinet_UID'];
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['uid']=$row['cabinet_UID'];
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['label']=$row['cabinet_label'];
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['notes']=$row['cabinet_notes'];
    }
    else{
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['uid']=$row['cabinet_UID'];
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['label']=$row['cabinet_label'];
        $cabinetArray[$row['storageUnit_label']][$iCabinet]['notes']=$row['cabinet_notes'];
    }
  
    $iCabinet++;
  }
  //Report Total Rows
  $totalCabinets=$stmt->rowCount();
  }
  //Report Error (if error)
  catch(PDOException $e){
    echo '<div class="alert-box alert noMarginBottomAlert">Error: ' . $e->getMessage() . '</div>';
  }

//debugPrintData($cabinetArray);

//get building name from building uid
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
                            <b>Click on a cabinet to drill down</b> and see each panel and port.<br />
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
            Contents of Storage Unit <b>'.$storageUnitLabel.'</b>.
        </div>
        <!-- .panel-heading -->
        <div class="panel-body">';
//START PANEL BODY

echo '
        <input class="form-control" type="text" id="cabinetTableSearch" name="cabinetTableSearch" placeholder="Type to filter the list below in real-time.">
';

//DT Version
echo '
 <div class="dataTable_wrapper">
    <table class="table table-striped table-bordered table-hover" id="dataTables-cabinets">
        <thead>
            <tr>
                <th class="center" colspan="7">Cabinet Records</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>Cabinet</th>
                <th>Storage Unit</th>
                <th>Notes</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>UID</th>
                <th>Cabinet</th>
                <th>Storage Unit</th>
                <th>Notes</th>
                <th class="alert-danger" style="width:1%;">Delete</th>
                <th class="center alert-info" style="width:1%;">Update</th>
                <th class="center">Drill Down</th>
            </tr>
        </tfoot>
 
        <tbody>
';
//for each storage unit
foreach ($cabinetArray as $key => $value) {

	//for each cabinet in each storage unit
	for ($iCabinetNdx=0; $iCabinetNdx <= sizeof($value); $iCabinetNdx++) { 
		//print on not null...
		if($value[$iCabinetNdx]['storageUnit_label']){
			//echo "DEBUG ".$value[$iCabinetNdx]['storageUnit_label']."<br />";
			echo '<tr>';
			echo '<td>'.$value[$iCabinetNdx]['uid'].'</td>';
			echo '<td>'.$value[$iCabinetNdx]['label'].'</td>';
			echo '<td>'.$value[$iCabinetNdx]['storageUnit_label'].'</td>';
			if ($value[$iCabinetNdx]['notes']!='') {
				echo '<td>'.$value[$iCabinetNdx]['notes'].'</td>';
			}
			else{
				echo '<td>No notes have been entered for this cabinet.</td>';
			}

			//delete button
			echo "
			<td>
			    <form action='module_crud.php' method='POST'>
                <!-- pass UID -->
                    <input type='hidden' name='cabinetUID' id='cabinetUID' value='".$value[$iCabinetNdx]['uid']."' />
                <!-- pass description -->
                    <input type='hidden' name='cabinetLabel' id='cabinetLabel' value='".$value[$iCabinetNdx]['label']."' />
                <!-- pass level -->
                    <input type='hidden' name='cabinetStorageUnitLabel' id='cabinetStorageUnitLabel' value='".$value[$iCabinetNdx]['storageUnit_label']."' />
                <!-- pass action -->
			        <input type='hidden' name='cabinetAction' id='cabinetAction' value='delete' />
			        <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this Cabinet will also destroy everything inside this cabinet. This is irreversible.')\"><i class='fa fa-times'></i></button>
			        </form>
			</td>";

			//edit button
			echo "
			<td>
			    <form action='module_crud.php' method='POST'>
                <!-- pass UID -->
                    <input type='hidden' name='cabinetUID' id='cabinetUID' value='".$value[$iCabinetNdx]['uid']."' />
                <!-- pass description -->
                    <input type='hidden' name='cabinetLabel' id='cabinetLabel' value='".$value[$iCabinetNdx]['label']."' />
                <!-- pass description -->
                    <input type='hidden' name='cabinetNotes' id='cabinetNotes' value='".$value[$iCabinetNdx]['notes']."' />
                <!-- pass level -->
                    <input type='hidden' name='cabinetStorageUnitLabel' id='cabinetStorageUnitLabel' value='".$value[$iCabinetNdx]['storageUnit_label']."' />
                <!-- pass action -->
			        <input type='hidden' name='cabinetAction' id='cabinetAction' value='edit' />
			        <button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
			    </form>
			</td>";

			//drill down button
			echo "
			<td>
			    <form action='manageCabinet.php' method='GET'>
			    <!-- pass UID -->
			        <input type='hidden' name='uid' id='uid' value='".$value[$iCabinetNdx]['uid']."' />
                    <button type='submit' class='btn btn btn-primary btn-lg btn-block'><i class='fa fa-chevron-circle-right'></i></button>
			    </form>
			</td>";

			echo '</tr>';



		}
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


<script type="text/javascript">
    
//START custom search fields
//Building Search
buildingTable    = $('#dataTables-buildings').DataTable();  
$('#dataTables-buildings_filter').hide();
$('#buildingTableSearch').keyup(function(){
    buildingTable.search($(this).val()).draw() ;
})

//Location Search
locationTable    = $('#dataTables-locations').DataTable();  
$('#dataTables-locations_filter').hide();
$('#locationTableSearch').keyup(function(){
    locationTable.search($(this).val()).draw() ;
})

//Storage Unit Search
storageUnitTable = $('#dataTables-storageUnits').DataTable();  
$('#dataTables-storageUnits_filter').hide();
$('#storageUnitTableSearch').keyup(function(){
    storageUnitTable.search($(this).val()).draw() ;
})

//Cabinet Search
cabinetTable     = $('#dataTables-cabinets').DataTable();  
$('#dataTables-cabinets_filter').hide();
$('#cabinetTableSearch').keyup(function(){
    cabinetTable.search($(this).val()).draw() ;
})
//END custom search fields

</script>

