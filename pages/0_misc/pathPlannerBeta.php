<!DOCTYPE html>
<html lang="en">
<?php 
include('snippets/sharedHead.php');

//get totals from database.
$totalBuildings=getTotalFromDatabase('buildings');
$totalLocations=getTotalFromDatabase('locations');
$totalStorageUnits=getTotalFromDatabase('storageUnits');
$totalCabinets=getTotalFromDatabase('cabinets');
$totalPanels=getTotalFromDatabase('panels');
$totalPorts=getTotalFromDatabase('ports');
$totalStrands=getTotalFromDatabase('strands');
$totalPaths=getTotalFromDatabase('paths');

//======================================
//Start Load Building Array
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('SELECT * FROM building order by number asc');
$stmt->execute();
$i=0;
foreach($stmt as $row) {
$i++;
$buildingDetails[$i]['UID']=$row['building_UID'];
$buildingDetails[$i]['number']=$row['number'];
$buildingDetails[$i]['name']=$row['name'];
$buildingDetails[$i]['levels']=$row['levels'];
$buildingDetails[$i]['notes']=$row['notes'];
$buildingDetails[$i]['lastMod']=$row['lastmodified'];
}
$totalBuildings=$stmt->rowCount();
}
catch(PDOException $e){
}

//End Load Building Array
//======================================
?>

<body>
<div id="wrapper">
<?php
$thisPage='pathPlanner';
?>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
<?php include('snippets/sharedTopNav.php');?>
<?php include('snippets/sharedSideNav.php');?>
</nav>
<div id="page-wrapper">
<div class="container-fluid animated fadeIn">
<?php include('snippets/sharedBreadcrumbs.php');?>
<!-- ======================================== -->
<!-- START Page Content -->
<!-- ======================================== -->

<?php
/*=================================================+
|           DISPLAY PATH PLANNER FORM              |
+=================================================*/
?>

<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>Path Planner</h1>
        </div>
        <div class="panel-body">

            <?php
            /*
            <hr>
            <h3>User Directions:</h3>
            <p>Enter a source and destination building below to generate a list of viable paths.</p>


            <!-- Start Path Planner Form -->
            <form id="pathPlannerForm" name="pathPlannerForm" method="POST">
                <h4>Set Source:</h4>

                <!-- Set Source Building -->
                <div class="col-xs-5">
                    <div class="form-group">
                        <label for="sourceBuilding_UID">Source:</label>
                        <select class="form-control" id="sourceBuilding_UID" name="sourceBuilding_UID">
                        <option value='' selected>Select a Building...</option>
                        <?php
                        foreach ($buildingDetails as $key => $value) {
                            if ($value['UID']==$_POST['sourceBuilding_UID']) {
                                echo '<option selected value="'.$value['UID'].'">'.$value['number'].' - '.$value['name'].'</option>';
                            }
                            else{
                                echo '<option value="'.$value['UID'].'">'.$value['number'].' - '.$value['name'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <!-- Path Icon -->
                <div class="col-xs-2">
                    <div class="form-group">
                        <label for=""><br /></label>
                        <button disabled class="form-control btn btn-primary"> <i class="fa fa-fw fa-exchange"></i> </button> 
                    </div>
                </div>

                <!-- Set Destination Building -->
                <div class="col-xs-5">
                    <div class="form-group">
                        <label for="destinationBuilding_UID">Destination:</label>
                        <select class="form-control" id="destinationBuilding_UID" name="destinationBuilding_UID">
                        <option value='' selected>Select a Building...</option>
                        <?php
                        foreach ($buildingDetails as $key => $value) {
                            if ($value['UID']==$_POST['destinationBuilding_UID']) {
                                echo '<option selected value="'.$value['UID'].'">'.$value['number'].' - '.$value['name'].'</option>';
                            }
                            else{
                                echo '<option value="'.$value['UID'].'">'.$value['number'].' - '.$value['name'].'</option>';
                            }
                        }
                        ?>
                        </select>
                    </div>
                </div>

                <!-- Process Switch & Submit Form Button -->
                <div class="col-xs-12">
                    <div class="form-group">
                        <input type="hidden" id="processPaths" name="processPaths" value="1" />
                        <input class="form-control btn btn-primary" type="submit" value="Find Viable Paths" />
                    </div>
                </div>

            </form>
 <!-- End Path Planner Form -->
        </div>
        <hr>

*/
?>
       
<?php
/*=================================================+
|           DISPLAY PATH PLANNER RESULTS           |
+=================================================*/
?>
        <div class="panel-body">
        	<?php 
        	//Result Fetcher
        	//01	Frazier Hall	0206
        	//04	Liberal Arts	0209
        	//08	Pharmacy		0212
        	//10	Admin			0202
        	//14	Pond SUB		0216

        	//05	Business Admin	0003
        	//18	M & O 			0220
        	//20	Heat Plant		0222
        	//19	Grounds			0221

        	//33	Fueling Sta		0234
        	//16	Haz Mat Sto		0218

        	//Primary
        	//$sourceBuilding_UID='0206';
        	//$destinationBuilding_UID='0003';

        	//SuperConnected
        	$sourceBuilding_UID='0206';
        	$destinationBuilding_UID='0218';


        	//echo '<br />';
        	//echo 'Source Building: '.$sourceBuilding_UID;
        	//echo '<br />';
        	//echo 'Destination Building: '.$destinationBuilding_UID;
        	//echo '<br />';


//SQL STUFF
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
$stmt = $db->prepare('SELECT * FROM building');
//execute query
$stmt->execute();
//store variables
$buildingCounter=0;
$thisUID='';
	foreach($stmt as $row) {
		$thisUID=$row['building_UID'];
		$buildings[$thisUID]['UID']=$row['building_UID'];
		$buildings[$thisUID]['number']=$row['number'];
		$buildings[$thisUID]['name']=$row['name'];
		$buildings[$thisUID]['levels']=$row['levels'];
		$buildings[$thisUID]['notes']=$row['notes'];
		$buildings[$thisUID]['address']=$row['address'];
		$buildings[$thisUID]['long']=$row['long'];
		$buildings[$thisUID]['lat']=$row['lat'];
		$buildingCounter++;
	}
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
}
//echo '<pre>';
//print_r($buildings);
//echo '</pre>';



//SQL STUFF
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
  $stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID GROUP BY INTERPANEL_ID"); 
//execute query
$stmt->execute();
//store variables
$connectionCounter=0;
	foreach($stmt as $row) {
		//link stregnth - strand count per panel
		$ibConnections[$connectionCounter]['linkStrength']=$row['linkStrength'];

		//A details
		$ibConnections[$connectionCounter]['A']['building_UID']=$row['A_BUID'];
		$ibConnections[$connectionCounter]['A']['building_name']=$row['A_NAME'];
		$ibConnections[$connectionCounter]['A']['building_number']=$row['A_NUMBER'];
		$ibConnections[$connectionCounter]['A']['storageunit_label']=$row['A_SU'];
		$ibConnections[$connectionCounter]['A']['cabinet_label']=$row['A_CAB'];
		$ibConnections[$connectionCounter]['A']['panel_position']=$row['A_PAN'];

		//B details
		$ibConnections[$connectionCounter]['B']['building_UID']=$row['B_BUID'];
		$ibConnections[$connectionCounter]['B']['building_name']=$row['B_NAME'];
		$ibConnections[$connectionCounter]['B']['building_number']=$row['B_NUMBER'];
		$ibConnections[$connectionCounter]['B']['storageunit_label']=$row['B_SU'];
		$ibConnections[$connectionCounter]['B']['cabinet_label']=$row['B_CAB'];
		$ibConnections[$connectionCounter]['B']['panel_position']=$row['B_PAN'];

		//inc counter
		$connectionCounter++;
	}
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
}
//echo '<pre>';
//print_r($ibConnections);
//echo '</pre>';



/*
[linkStrength] => 12
[A] => Array
    (
        [building_name] => Frazier Hall
        [building_number] => 001
        [storageunit_label] => 001-00-01
        [cabinet_label] => 001
        [panel_position] => 1
    )

[B] => Array
    (
        [building_name] => Business Administration
        [building_number] => 005
        [storageunit_label] => 005-00-01
        [cabinet_label] => 006
        [panel_position] => 7
    )
*/


//$sourceBuilding_UID='0206';
//$destinationBuilding_UID='0218';


//print Source and Destination deets
foreach ($ibConnections as $key => $value) {
	//if source == A building
	if ($sourceBuilding_UID==$value['A']['building_UID']) {
		$sourceName=$value['A']['building_name'];
	}
	//if source == A building
	if ($sourceBuilding_UID==$value['B']['building_UID']) {
		$sourceName=$value['B']['building_name'];
	}

	//if destination == A BUILDING
	if ($destinationBuilding_UID==$value['A']['building_UID']){
		$destinationName=$value['A']['building_name'];
	}
	//if destination == A BUILDING
	if ($destinationBuilding_UID==$value['B']['building_UID']){
		$destinationName=$value['B']['building_name'];
	}
}

echo 'Source Building:'.$sourceName;
echo '<br />';
echo 'Destination Building:'.$destinationName;
echo '<br />';


echo '<hr>';
//Check for primary connections
$primaryConnectionFound=0;
$primaryConnectionMsg='';
foreach ($ibConnections as $key => $value) {
//matched (source or dest) to A
if (($sourceBuilding_UID==$value['A']['building_UID'] && $destinationBuilding_UID==$value['B']['building_UID']) || ($sourceBuilding_UID==$value['B']['building_UID'] && $destinationBuilding_UID==$value['A']['building_UID'])){
	//if found
	$primaryConnectionFound=1;
	//primary connection found
	$primaryConnectionMsg.='
	<div class="col-xs-12 well">
		<!-- ALERT -->
		<div class="alert alert-success">
		<p class="text-center">Primary Connection Found:</p>
		</div>
		<!-- Column Left -->
		<div class="col-xs-6">
		<p class="text-center">
		'.$value['A']['building_number'].' - '.$value['A']['building_name'].'<br />
		'.$value['A']['storageunit_label'].'<br />
		'.$value['A']['cabinet_label'].'<br />
		'.$value['A']['panel_position'].'<br />
		</p>
		</div>
		<!-- Column Right -->
		<div class="col-xs-6">
		<p class="text-center">
		'.$value['B']['building_number'].' - '.$value['B']['building_name'].'<br />
		'.$value['B']['storageunit_label'].'<br />
		'.$value['B']['cabinet_label'].'<br />
		'.$value['B']['panel_position'].'<br />
		</p>
		</div>
	</div>
	';
}
}
if ($primaryConnectionFound) {
	echo $primaryConnectionMsg;
}
else{

	//primaryConnections
	$primaryNonConnections=array();
	foreach ($ibConnections as $key => $value) {
		if ($sourceBuilding_UID==$value['A']['building_UID']) {
		array_push($primaryNonConnections, $value['B']['building_UID']);
		}
		if ($sourceBuilding_UID==$value['B']['building_UID']) {
		array_push($primaryNonConnections, $value['A']['building_UID']);
		}
	}

	echo '
		<!-- ALERT -->
		<div class="alert alert-danger">
		<p class="text-center">No Primary Connections Found between <span class="label label-primary">'.$buildings[$sourceBuilding_UID]['name'].'</span> AND <span class="label label-primary">'.$buildings[$destinationBuilding_UID]['name'].'</span>.</p>
		<p class="text-center">We will continue to look for less direct connections.</p>
		</div>
	';


echo '<hr>';
//Check for secondary connections
echo '
<div class="alert alert-info">
<p class="text-center">The following buildings are connected to <span class="label label-primary">'.$buildings[$sourceBuilding_UID]['name'].'</span>:</p>
<p class="text-center">';
foreach ($primaryNonConnections as $key => $value) {
echo '<span class="label label-primary">'.$buildings[$value]['name'].'</span> ';
}
echo '
</p>
</div>
';


//check each primary non connection for a secondary connection












echo '<hr>';
//Check for tertiary connections




}//if no primary connections



//print all debug
echo '<hr>';
echo '<pre>';
print_r($ibConnections);
echo '</pre>';

        	?>
        </div>
    </div>
</div>

<!-- ======================================== -->
<!-- END Page Content -->
<!-- ======================================== -->
</div><!-- /.container-fluid -->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- jQuery + Misc Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
<script src="../bower_components/raphael/raphael-min.js"></script>
</body>
</html>
