<?php
//debug print post data
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//======================================
$resultCrescents=array();
//======================================
//Start Load Crescent 1 Array
//$crescentOne=array();
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('SELECT DISTINCT panela.panel_UID AS PANEL_A, panela.position AS PANEL_A_POSITION, panelb.panel_UID AS PANEL_B, panelb.position AS PANEL_B_POSITION, cabineta.cabinet_UID AS CABINET_A, cabineta.label AS CABINET_A_LABEL, cabinetb.cabinet_UID AS CABINET_B, cabinetb.label AS CABINET_B_LABEL, storageunita.storageunit_UID AS STORAGEUNIT_A, storageunita.label AS STORAGEUNIT_A_LABEL, storageunitb.storageunit_UID AS STORAGEUNIT_B, storageunitb.label AS STORAGEUNIT_B_LABEL, locationa.location_UID AS LOCATION_A, locationa.description AS LOCATION_A_DESC, locationa.level AS LOCATION_A_LEVEL, locationb.location_UID AS LOCATION_B, locationb.description AS LOCATION_B_DESC, locationb.level AS LOCATION_B_LEVEL, buildinga.building_UID AS BUILDING_A, buildinga.name AS BUILDING_A_NAME, buildingb.building_UID AS BUILDING_B, buildingb.name AS BUILDING_B_NAME FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE ((buildinga.building_UID=:buildingA OR buildingb.building_UID=:buildingA) OR (buildinga.building_UID=:buildingB OR buildingb.building_UID=:buildingB))');

	$stmt->bindParam(':buildingA', $_POST['sourceBuilding_UID']);
	$stmt->bindParam(':buildingB', $_POST['destinationBuilding_UID']);
	$stmt->execute();


	$directConnectionsTable='';

	//table head
	$directConnectionsTable.='
	<div class="panel panel-default">
	<table class="table display table-striped table-hover table-responsive table-bordered">
		<thead>
			<tr>
				<th class="text-center">Path</th>

				<th class="text-center" colspan="5">Source Details</th>

				<th class="text-center">Strands</th>

				<th class="text-center" colspan="5">Destination Details</th>
			</tr>
			<tr>
				<th class="text-center">Counter</th>

				<th class="text-center">Building</th>
				<th class="text-center">Location</th>
				<th class="text-center">Storage Unit</th>
				<th class="text-center">Cabinet</th>
				<th class="text-center">Panel</th>

				<th class="text-center"><i class="fa fa-fw fa-exchange"></i></th>

				<th class="text-center">Panel</th>
				<th class="text-center">Cabinet</th>
				<th class="text-center">Storage Unit</th>
				<th class="text-center">Location</th>
				<th class="text-center">Building</th>
			</tr>
		</thead>
		<tbody>';
			$pathCounter=0;
			$directConnectionFound=0;
			foreach($stmt as $row){
				//if we found a connection mark it down.
				if (($row['BUILDING_A']==$_POST['sourceBuilding_UID'] && $row['BUILDING_B']==$_POST['destinationBuilding_UID']) || ($row['BUILDING_A']==$_POST['destinationBuilding_UID'] && $row['BUILDING_B']==$_POST['sourceBuilding_UID'])) {

				$pathCounter++;
				$directConnectionFound=1;
				$directConnectionsTable.='
				<tr>
				<td class="text-center">'.$pathCounter.'</td>

				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'">'.$row['BUILDING_A_NAME'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'&le='.$row['LOCATION_A_LEVEL'].'&lo='.$row['LOCATION_A'].'">'.$row['LOCATION_A_DESC'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'&le='.$row['LOCATION_A_LEVEL'].'&lo='.$row['LOCATION_A'].'&sto='.$row['STORAGEUNIT_A'].'">'.$row['STORAGEUNIT_A_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="manageCabinet.php?uid='.$row['CABINET_A'].'">'.$row['CABINET_A_LABEL'].'</a></td>
				<td class="text-center">'.$row['PANEL_A_POSITION'].'<br />('.$row['PANEL_A'].')</td>

				<th class="text-center"><i class="fa fa-fw fa-exchange"></i></th>

				<td class="text-center">'.$row['PANEL_B_POSITION'].'<br />('.$row['PANEL_B'].')</td>
				<td class="text-center"><a target="_blank" href="manageCabinet.php?uid='.$row['CABINET_B'].'">'.$row['CABINET_B_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'&le='.$row['LOCATION_B_LEVEL'].'&lo='.$row['LOCATION_B'].'&sto='.$row['STORAGEUNIT_B'].'">'.$row['STORAGEUNIT_B_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'&le='.$row['LOCATION_B_LEVEL'].'&lo='.$row['LOCATION_B'].'">'.$row['LOCATION_B_DESC'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'">'.$row['BUILDING_B_NAME'].'</a></td>
				</tr>
				';

				$x_source_building_uid=$row['BUILDING_A'];
				$x_source_location_uid=$row['LOCATION_A'];
				$x_source_storageunit_uid=$row['STORAGEUNIT_A'];
				$x_source_cabinet_uid=$row['CABINET_A'];
				$x_source_panel_uid=$row['PANEL_A'];
				$x_source_building_name=$row['BUILDING_A_NAME'];
				$x_source_location_desc=$row['LOCATION_A_DESC'];
				$x_source_storageunit_label=$row['STORAGEUNIT_A_LABEL'];
				$x_source_cabinet_label=$row['CABINET_A_LABEL'];
				$x_source_panel_position=$row['PANEL_A_POSITION'];

				$x_destination_building_uid=$row['BUILDING_B'];
				$x_destination_location_uid=$row['LOCATION_B'];
				$x_destination_storageunit_uid=$row['STORAGEUNIT_B'];
				$x_destination_cabinet_uid=$row['CABINET_B'];
				$x_destination_panel_uid=$row['PANEL_B'];
				$x_destination_building_name=$row['BUILDING_B_NAME'];
				$x_destination_location_desc=$row['LOCATION_B_DESC'];
				$x_destination_storageunit_label=$row['STORAGEUNIT_B_LABEL'];
				$x_destination_cabinet_label=$row['CABINET_B_LABEL'];
				$x_destination_panel_position=$row['PANEL_B_POSITION'];

				}

			}
	//end table etc
	$directConnectionsTable.='      
	</tbody>
	</table>
	</div>
	';
	$totalPaths=$stmt->rowCount();
$directConnectionError=0;

}
catch(PDOException $e){
	$directConnectionError=1;
	$directConnectionErrorMessage='';
	$directConnectionErrorMessage.='
    <div class="alert alert-danger">
	<h3>Error! - (Query Failed.)</h3>
    <p>An error occurred while trying to find direct connections.<br />'.$e.'.</p>
    </div>
    ';
}
//End Load Crescent 1 Array
//======================================

/*==============================================================+
|                    DIRECT CONNECTIONS                         |
+==============================================================*/

$checkForSecondaryConnections=0;

	//print direct connections
	if ($directConnectionFound) {
		echo '
		<div class="well well-lg">
		<h3>Direct Connections</h3>
		<div class="alert alert-success">';
		if ($pathCounter<2) {
			echo '<p>The system found only '.$pathCounter.' direct connection between the source and destination building.</p>';
		}
		else{
			echo '<p>The system found '.$pathCounter.' direct connections between the source and destination building.</p>';
		}

		echo '</div>
		<div class="alert alert-info">
		<ul>
		<li>Click on any cabinet label to open it in the <b>Manage Cabinet Contents</b> module.</li>
		<li>Click on any other value to open it in the <b>Guided CRUD</b> module.</li>
		</ul>
		</div>
		'.$directConnectionsTable.'
		</div>
		';
	}
	else{

		if ($directConnectionError) {
			echo $directConnectionErrorMessage;
		}
		else{
		$checkForSecondaryConnections=1;
		echo '
		<div class="well well-lg">
		<h3>Direct Connections</h3>
		<div class="alert alert-danger">
		<p>The system could not find any direct connections between the source and destination building.</p>
		</div>
		</div>
		';
		}

	}
	
if ($checkForSecondaryConnections) {
	//start secondary connection message
	$secondaryConnectionMessage='';

	//print user intro secondary connection message
	$secondaryConnectionMessage.='
	<div class="well well-lg">
	<h3>Secondary Connections</h3>';
	/*

	echo '
	<div class="alert alert-info">
	<p>Since we could not find any direct connections we will now look for secondary connections...</p>
	</div>';
*/
//get list of all buildings connected to the source building
//save as array of uid only
	$sourceSecondaryConnections=array();
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$stmt = $db->prepare('SELECT DISTINCT panela.panel_UID AS PANEL_A, panela.position AS PANEL_A_POSITION, panelb.panel_UID AS PANEL_B, panelb.position AS PANEL_B_POSITION, cabineta.cabinet_UID AS CABINET_A, cabineta.label AS CABINET_A_LABEL, cabinetb.cabinet_UID AS CABINET_B, cabinetb.label AS CABINET_B_LABEL, storageunita.storageunit_UID AS STORAGEUNIT_A, storageunita.label AS STORAGEUNIT_A_LABEL, storageunitb.storageunit_UID AS STORAGEUNIT_B, storageunitb.label AS STORAGEUNIT_B_LABEL, locationa.location_UID AS LOCATION_A, locationa.description AS LOCATION_A_DESC, locationa.level AS LOCATION_A_LEVEL, locationb.location_UID AS LOCATION_B, locationb.description AS LOCATION_B_DESC, locationb.level AS LOCATION_B_LEVEL, buildinga.building_UID AS BUILDING_A, buildinga.name AS BUILDING_A_NAME, buildingb.building_UID AS BUILDING_B, buildingb.name AS BUILDING_B_NAME FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID=:buildingA OR buildingb.building_UID=:buildingA');

		$stmt->bindParam(':buildingA', $_POST['sourceBuilding_UID']);
		$stmt->execute();
		$i=0;
		foreach($stmt as $row){
			$i++;
			if ($row['BUILDING_B']==$_POST['sourceBuilding_UID']) {
			$sourceSecondaryConnections[$i]['building_uid']=$row['BUILDING_A'];
			$sourceSecondaryConnections[$i]['building_name']=$row['BUILDING_A_NAME'];
			}
			if ($row['BUILDING_A']==$_POST['sourceBuilding_UID']) {
			$sourceSecondaryConnections[$i]['building_uid']=$row['BUILDING_B'];
			$sourceSecondaryConnections[$i]['building_name']=$row['BUILDING_B_NAME'];
			}
		}
	}
	catch(PDOException $e){
		$directConnectionError=1;
		$directConnectionErrorMessage='';
		$directConnectionErrorMessage.='
	    <div class="alert alert-danger">
		<h3>Error! - (Query Failed.)</h3>
	    <p>An error occurred while trying to find direct connections.<br />'.$e.'.</p>
	    </div>
	    ';
	}

	/*
	$secondaryConnectionMessage.='
	<div class="alert alert-info">
	<p>The source building is directly connected to the following buildings:</p>
	';
	foreach ($sourceSecondaryConnections as $key => $value) {
		$secondaryConnectionMessage.=' <span class="label label-primary">'.$value['building_name'].'</span> ';
	}
	$secondaryConnectionMessage.='
	</div>
	';
	*/


//get list of all buildings connected to the destination building
//save as array of uid only
	$destinationSecondaryConnections=array();
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$stmt = $db->prepare('SELECT DISTINCT panela.panel_UID AS PANEL_A, panela.position AS PANEL_A_POSITION, panelb.panel_UID AS PANEL_B, panelb.position AS PANEL_B_POSITION, cabineta.cabinet_UID AS CABINET_A, cabineta.label AS CABINET_A_LABEL, cabinetb.cabinet_UID AS CABINET_B, cabinetb.label AS CABINET_B_LABEL, storageunita.storageunit_UID AS STORAGEUNIT_A, storageunita.label AS STORAGEUNIT_A_LABEL, storageunitb.storageunit_UID AS STORAGEUNIT_B, storageunitb.label AS STORAGEUNIT_B_LABEL, locationa.location_UID AS LOCATION_A, locationa.description AS LOCATION_A_DESC, locationa.level AS LOCATION_A_LEVEL, locationb.location_UID AS LOCATION_B, locationb.description AS LOCATION_B_DESC, locationb.level AS LOCATION_B_LEVEL, buildinga.building_UID AS BUILDING_A, buildinga.name AS BUILDING_A_NAME, buildingb.building_UID AS BUILDING_B, buildingb.name AS BUILDING_B_NAME FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID=:buildingA OR buildingb.building_UID=:buildingA');

		$stmt->bindParam(':buildingA', $_POST['destinationBuilding_UID']);
		$stmt->execute();
		$i=0;
		foreach($stmt as $row){
			$i++;
			if ($row['BUILDING_B']==$_POST['destinationBuilding_UID']) {
			$destinationSecondaryConnections[$i]['building_uid']=$row['BUILDING_A'];
			$destinationSecondaryConnections[$i]['building_name']=$row['BUILDING_A_NAME'];
			}
			if ($row['BUILDING_A']==$_POST['destinationBuilding_UID']) {
			$destinationSecondaryConnections[$i]['building_uid']=$row['BUILDING_B'];
			$destinationSecondaryConnections[$i]['building_name']=$row['BUILDING_B_NAME'];
			}
		}
	}
	catch(PDOException $e){
		$directConnectionError=1;
		$directConnectionErrorMessage='';
		$directConnectionErrorMessage.='
	    <div class="alert alert-danger">
		<h3>Error! - (Query Failed.)</h3>
	    <p>An error occurred while trying to find direct connections.<br />'.$e.'.</p>
	    </div>
	    ';
	}
	/*

	$secondaryConnectionMessage.='
	<div class="alert alert-info">
	<p>The destination building is directly connected to the following buildings:</p>
	';

	foreach ($destinationSecondaryConnections as $key => $value) {
		if ($value['building_uid']!=$_POST['destinationBuilding_UID']) {
		$secondaryConnectionMessage.=' <span class="label label-primary">'.$value['building_name'].'</span> ';
		}
	}
	$secondaryConnectionMessage.='
	</div>
	';
*/
	//scan for duplicates / matches
	$secondaryConnectionFound=0;

	foreach ($sourceSecondaryConnections as $skey => $svalue) {
		foreach ($destinationSecondaryConnections as $dkey => $dvalue) {
			if ($svalue['building_uid']==$dvalue['building_uid']) {
				$secondaryConnectionFound=1;
				$secondaryConnectionValue['uid']=$svalue['building_uid'];
				$secondaryConnectionValue['name']=$svalue['building_name'];
			}
		}
	}

	if ($secondaryConnectionFound) {
		$secondaryConnectionMessage.='
		<div class="alert alert-success">
		<p>The system found secondary connections that link the source and destination building.</p>
		</div>
		<div class="alert alert-info">
		<ul>
		<li>Click on any cabinet label to open it in the <b>Manage Cabinet Contents</b> module.</li>
		<li>Click on any other value to open it in the <b>Guided CRUD</b> module.</li>
		</ul>
		</div>';

		//get details where building A and B are either
//===============
$secondaryConnections=array();
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
		$stmt = $db->prepare('SELECT DISTINCT panela.panel_UID AS PANEL_A, panela.position AS PANEL_A_POSITION, panelb.panel_UID AS PANEL_B, panelb.position AS PANEL_B_POSITION, cabineta.cabinet_UID AS CABINET_A, cabineta.label AS CABINET_A_LABEL, cabinetb.cabinet_UID AS CABINET_B, cabinetb.label AS CABINET_B_LABEL, storageunita.storageunit_UID AS STORAGEUNIT_A, storageunita.label AS STORAGEUNIT_A_LABEL, storageunitb.storageunit_UID AS STORAGEUNIT_B, storageunitb.label AS STORAGEUNIT_B_LABEL, locationa.location_UID AS LOCATION_A, locationa.description AS LOCATION_A_DESC, locationa.level AS LOCATION_A_LEVEL, locationb.location_UID AS LOCATION_B, locationb.description AS LOCATION_B_DESC, locationb.level AS LOCATION_B_LEVEL, buildinga.building_UID AS BUILDING_A, buildinga.name AS BUILDING_A_NAME, buildingb.building_UID AS BUILDING_B, buildingb.name AS BUILDING_B_NAME FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID=:buildingA OR buildingb.building_UID=:buildingA');

		$stmt->bindParam(':buildingA', $_POST['destinationBuilding_UID']);
		$stmt->execute();
		$i=0;
		foreach($stmt as $row){
			$i++;
			if ($row['BUILDING_B']==$_POST['destinationBuilding_UID']) {
			$destinationSecondaryConnections[$i]['building_uid']=$row['BUILDING_A'];
			$destinationSecondaryConnections[$i]['building_name']=$row['BUILDING_A_NAME'];
			}
			if ($row['BUILDING_A']==$_POST['destinationBuilding_UID']) {
			$destinationSecondaryConnections[$i]['building_uid']=$row['BUILDING_B'];
			$destinationSecondaryConnections[$i]['building_name']=$row['BUILDING_B_NAME'];
			}
		}
	}
	catch(PDOException $e){
		$directConnectionError=1;
		$directConnectionErrorMessage='';
		$directConnectionErrorMessage.='
	    <div class="alert alert-danger">
		<h3>Error! - (Query Failed.)</h3>
	    <p>An error occurred while trying to find direct connections.<br />'.$e.'.</p>
	    </div>
	    ';
	}

//===============
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('SELECT DISTINCT panela.panel_UID AS PANEL_A, panela.position AS PANEL_A_POSITION, panelb.panel_UID AS PANEL_B, panelb.position AS PANEL_B_POSITION, cabineta.cabinet_UID AS CABINET_A, cabineta.label AS CABINET_A_LABEL, cabinetb.cabinet_UID AS CABINET_B, cabinetb.label AS CABINET_B_LABEL, storageunita.storageunit_UID AS STORAGEUNIT_A, storageunita.label AS STORAGEUNIT_A_LABEL, storageunitb.storageunit_UID AS STORAGEUNIT_B, storageunitb.label AS STORAGEUNIT_B_LABEL, locationa.location_UID AS LOCATION_A, locationa.description AS LOCATION_A_DESC, locationa.level AS LOCATION_A_LEVEL, locationb.location_UID AS LOCATION_B, locationb.description AS LOCATION_B_DESC, locationb.level AS LOCATION_B_LEVEL, buildinga.building_UID AS BUILDING_A, buildinga.name AS BUILDING_A_NAME, buildingb.building_UID AS BUILDING_B, buildingb.name AS BUILDING_B_NAME FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE ((buildinga.building_UID=:buildingA AND buildingb.building_UID=:buildingB ) OR (buildinga.building_UID=:buildingB AND buildingb.building_UID=:buildingA ) ) OR ((buildinga.building_UID=:buildingB AND buildingb.building_UID=:buildingC ) OR (buildinga.building_UID=:buildingC AND buildingb.building_UID=:buildingB ) )');
	//source
	$stmt->bindParam(':buildingA', $_POST['sourceBuilding_UID']);
	//shared
	$stmt->bindParam(':buildingB', $secondaryConnectionValue['uid']);
	//destination
	$stmt->bindParam(':buildingC', $_POST['destinationBuilding_UID']);
	//execute
	$stmt->execute();


	$secondaryConnectionsTable='';

	//table head
	$secondaryConnectionsTable.='
	<div class="panel panel-default">
	<table class="table display table-striped table-hover table-responsive table-bordered">
		<thead>
			<tr>
				<th class="text-center">Path</th>

				<th class="text-center" colspan="5">Source Details</th>

				<th class="text-center">Strands</th>

				<th class="text-center" colspan="5">Destination Details</th>
			</tr>
			<tr>
				<th class="text-center">Counter</th>

				<th class="text-center">Building</th>
				<th class="text-center">Location</th>
				<th class="text-center">Storage Unit</th>
				<th class="text-center">Cabinet</th>
				<th class="text-center">Panel</th>

				<th class="text-center"><i class="fa fa-fw fa-exchange"></i></th>

				<th class="text-center">Panel</th>
				<th class="text-center">Cabinet</th>
				<th class="text-center">Storage Unit</th>
				<th class="text-center">Location</th>
				<th class="text-center">Building</th>
			</tr>
		</thead>
		<tbody>';
			$secondaryPathCounter=0;
			$secondaryConnectionFound=0;
			foreach($stmt as $row){
				$secondaryPathCounter++;
				
				/*
				$secondaryConnectionsTable.='
				<tr>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
					<td>Test</td>
				</tr>
				';   
*/
				$secondaryConnectionsTable.='
				<tr>
				<td class="text-center">'.$secondaryPathCounter.'</td>

				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'">'.$row['BUILDING_A_NAME'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'&le='.$row['LOCATION_A_LEVEL'].'&lo='.$row['LOCATION_A'].'">'.$row['LOCATION_A_DESC'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_A'].'&le='.$row['LOCATION_A_LEVEL'].'&lo='.$row['LOCATION_A'].'&sto='.$row['STORAGEUNIT_A'].'">'.$row['STORAGEUNIT_A_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="manageCabinet.php?uid='.$row['CABINET_A'].'">'.$row['CABINET_A_LABEL'].'</a></td>
				<td class="text-center">'.$row['PANEL_A_POSITION'].'<br />('.$row['PANEL_A'].')</td>

				<th class="text-center"><i class="fa fa-fw fa-exchange"></i></th>

				<td class="text-center">'.$row['PANEL_B_POSITION'].'<br />('.$row['PANEL_B'].')</td>
				<td class="text-center"><a target="_blank" href="manageCabinet.php?uid='.$row['CABINET_B'].'">'.$row['CABINET_B_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'&le='.$row['LOCATION_B_LEVEL'].'&lo='.$row['LOCATION_B'].'&sto='.$row['STORAGEUNIT_B'].'">'.$row['STORAGEUNIT_B_LABEL'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'&le='.$row['LOCATION_B_LEVEL'].'&lo='.$row['LOCATION_B'].'">'.$row['LOCATION_B_DESC'].'</a></td>
				<td class="text-center"><a target="_blank" href="guidedRecordCreation.php?b='.$row['BUILDING_B'].'">'.$row['BUILDING_B_NAME'].'</a></td>
				</tr>
				';


			}


	//end table etc
	$secondaryConnectionsTable.='      
	</tbody>
	</table>
	</div>
	';
	$totalSecondaryPaths=$stmt->rowCount();
	$secondaryConnectionError=0;

}
catch(PDOException $e){
	$secondaryConnectionError=1;
	$secondaryConnectionErrorMessage='';
	$secondaryConnectionErrorMessage.='
    <div class="alert alert-danger">
	<h3>Error! - (Query Failed.)</h3>
    <p>An error occurred while trying to find secondary connections.<br />'.$e.'.</p>
    </div>
    ';
}

if ($secondaryConnectionError) {
		$secondaryConnectionMessage.=$secondaryConnectionErrorMessage;
}

		$secondaryConnectionMessage.=$secondaryConnectionsTable;



		//make the table
/*
		$secondaryConnectionMessage.='
		<hr>
		<p>Get paths that fit one of these cases:</p>
		<p>
		<span class="label label-primary">Source ('.$_POST['sourceBuilding_UID'].')</span> 
		<i class="fa fa-fw fa-exchange"></i> 
		<span class="label label-primary">Shared ('.$secondaryConnectionValue['uid'].')</span>
		</p>
		<p>
		<span class="label label-primary">Shared ('.$secondaryConnectionValue['uid'].')</span>
		<i class="fa fa-fw fa-exchange"></i> 
		<span class="label label-primary">Source ('.$_POST['sourceBuilding_UID'].')</span> 
		</p>
		<p>
		<span class="label label-primary">Destination ('.$_POST['sourceBuilding_UID'].')</span> 
		<i class="fa fa-fw fa-exchange"></i> 
		<span class="label label-primary">Shared ('.$secondaryConnectionValue['uid'].')</span>
		</p>
		<p>
		<span class="label label-primary">Shared ('.$secondaryConnectionValue['uid'].')</span>
		<i class="fa fa-fw fa-exchange"></i> 
		<span class="label label-primary">Destination ('.$_POST['sourceBuilding_UID'].')</span> 
		</p>
		</div>
		';
*/

	}
	else{	
		$secondaryConnectionMessage.='
		<div class="well well-lg">
		<h3>Secondary Connections</h3>
		<div class="alert alert-danger">
		<p>The system could not find any secondary connections between the source and destination building.</p>
		</div>
		</div>
		';
	}





/*
$secondaryConnectionFound=0;
foreach ($sourceSecondaryConnections as $skey => $svalue) {
	$tempSourceValue='';
	$tempSourceValue=$svalue['building_uid'];
	foreach ($destinationSecondaryConnections as $dkey => $dvalue) {
		$tempDestinationValue='';
		$tempDestinationValue=$dvalue['building_uid'];

		//match?
		if ($svalue['building_uid']==$dvalue['building_uid']) {
			$secondaryConnectionFound=1;
			$secondaryConnectionValue['building_uid']=$dvalue['building_uid'];
			$secondaryConnectionValue['building_name']=$dvalue['building_uidname'];
		}
	}
}

//match = secondary connection
if ($secondaryConnectionFound) {
	$secondaryConnectionMessage.='<div class="alert alert-success"><p>The system found direct connections between the source and destination building.</p>';
	$secondaryConnectionMessage.='</div>
		<div class="alert alert-info">
		<ul>
		<li>Click on any cabinet label to open it in the <b>Manage Cabinet Contents</b> module.</li>
		<li>Click on any other value to open it in the <b>Guided CRUD</b> module.</li>
		</ul>
		</div>';
	//echo $secondaryConnectionsTable;

	$secondaryConnectionMessage.='<div class="alert alert-success">';
	$secondaryConnectionMessage.='<span class="label label-primary">'.$secondaryConnectionValue['building_name'].'</span>';
	$secondaryConnectionMessage.='</div>';

}
else{

}
*/

//no match = merge the lists

//use new merged uid list to find tertiary connections


	//close secondary connection message
	$secondaryConnectionMessage.='
	</div>
	';

	//print secondary connection message
	echo $secondaryConnectionMessage;
}







/*
PSEUDO-CODE FOR THIS PAGE: 
	check for a direct connection			
		check if source connected to destination 
		check if destination connected to source 
		if match - direct connection found
		if no direct connection exists...

	check for a secondary connection			
		get all building uids connected to source building 
		get all building uids connected to destination building 
		scan these for a match (common building uid) 
		if match - secondary connection found
		if no secondary connection exists...

	check for tertiary connection
		get all building uids connected to source
		save these as crescent 1A array

		get all building uids connected to destination
		save these as crescent 1B array

		foreach building (x) in 1A array
			get all uids connected to x
			save these as 1A_children[0] array
			save these as 1A_children[1] array

		foreach building (x) in 1B array
			get all uids connected to x
			save these as 1B_children[0] array
			save these as 1B_children[1] array

		foreach building in 1A_children[x] array
			get all uids connected to x
				foreach check through every single uid in 1B_children[]





*/



/*==============================================================+
|  GET ALL BUILDING UIDS CONNECTED TO THE DESTINATION BUILDING  |
+==============================================================*/

/*==============================================================+
|  GET ALL BUILDING UIDS CONNECTED TO THE DESTINATION BUILDING  |
+==============================================================*/

?>