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
//get config file
require_once('../../configPointer.php');
// This is the master page.
// All Cabinet Contents Can Be Managed Through This Page.



//DELETE A PANEL FROM THE CABINET
if (isset($_POST['panelAction']) && $_POST['panelAction']=="removePanel") {
    if (isset($_POST['removePanelUID']) && $_POST['removePanelUID']!="") {
        echo "I should be deleting panel ".$_POST['removePanelUID']." now.";
    }
}
else{
    
}



?>







<?php
if (isset($_GET['uid']) && $_GET['uid']!="") {
	//Get the cabinet UID
	$cabinet_uid=$_GET['uid'];
}
if (isset($_POST['uid']) && $_POST['uid']!="") {
	//Get the cabinet UID
	$cabinet_uid=$_POST['uid'];
}

if (isset($cabinet_uid) && $cabinet_uid!="") {
	//Init arrays
	$cabinetDetails = array();
	$panelDetails = array();
	$portDetails = array();
	//set panel details
	$panelDetails['panel_UID'] = array();
	$panelDetails['position'] = array();
	$panelDetails['portCapacity'] = array();
	$panelDetails['type'] = array();
	//set port details
	$portDetails['uid'] = array();
	$portDetails['status'] = array();



	//Get the parent cabinet information
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * from cabinet where cabinet_UID='.$cabinet_uid);
	$stmt->execute();
	foreach($stmt as $row) {
		$cabinetDetails['fk_storageUnit_UID']=$row['fk_storageUnit_UID'];
		$cabinetDetails['cabinet_UID']=$row['cabinet_UID'];
		$cabinetDetails['label']=$row['label'];
		$cabinetDetails['panelCapacity']=$row['panelCapacity'];
		$cabinetDetails['notes']=$row['notes'];
	}
	$totalCabinets=$stmt->rowCount();
	}catch(PDOException $e){}

	//Get the child panels information
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * from panel where fk_cabinet_UID='.$cabinet_uid);
	$stmt->execute();
	$i=0;
	foreach($stmt as $row) {
		$panelDetails['panel_UID'][]=$row['panel_UID'];
		$panelDetails['position'][]=$row['position'];
		$panelDetails['portCapacity'][]=$row['portCapacity'];
		$panelDetails['type'][]=$row['type'];
		//set positiondetails
		$panelPositionDetails[$row['position']]['panel_UID']=$row['panel_UID'];
		$panelPositionDetails[$row['position']]['portCapacity']=$row['portCapacity'];
		$panelPositionDetails[$row['position']]['type']=$row['type'];


//=======================
	//Get the child ports information
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * from port where fk_panel_UID='.$row["panel_UID"].'');
	$stmt->execute();
	foreach($stmt as $rowx) {
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['port_UID']=$rowx['port_UID'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['number']=$rowx['number'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['strandStatus']=$rowx['strandStatus'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['fk_strand_UID']=$rowx['fk_strand_UID'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['jumperStatus']=$rowx['jumperStatus'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['fk_jumper_UID']=$rowx['fk_jumper_UID'];
		$panelPortDetails[$row['panel_UID']][$rowx['number']]['lastModified']=$rowx['lastModified'];
	}
	}catch(PDOException $e){}

//=======================


	}
	$totalPanels=$stmt->rowCount();
	}catch(PDOException $e){}

	$panelCount = sizeof($panelDetails['panel_UID']);
	

	//CREATE cabinet representation
	//print each panel (module) slot (even empty ones)
	for ($iPanelCapCounter=1; $iPanelCapCounter <= $cabinetDetails['panelCapacity']; $iPanelCapCounter++) {
		


		if ($iPanelCapCounter % 3==0) {
			echo '<div class="row">';
		}
		if (in_array($iPanelCapCounter, $panelDetails['position'])) {
			$thisPanelUID='';
			$thisPanelPosition='';
			$thisPanelPortCap='';
			$thisPanelType='';

			foreach ($panelPositionDetails as $key => $value) {
				//echo "<pre>";
				//print_r($panelPortDetails);
				//echo "<pre>";

				if ($key==$iPanelCapCounter) {

					$thisPanelUID=$panelPositionDetails[$iPanelCapCounter]['panel_UID'];
					$thisPanelPosition=$iPanelCapCounter;
					$thisPanelPortCap=$panelPositionDetails[$iPanelCapCounter]['portCapacity'];
					$thisPanelType=$panelPositionDetails[$iPanelCapCounter]['type']; 

					echo "
					<div class='col-xs-12 col-lg-4'>
						<div class='panel panel-default'>
							<div class='panel-heading'>
								";

								//Delete the panel.
								echo "
								<form action='guidedRecordCreation.php' method='POST' class='form-inline'>
									<input type='hidden' id='manageCabinetAction' name='manageCabinetAction' value='removePanel'>
									<input type='hidden' id='removePanelUID' name='removePanelUID' value='".$thisPanelUID."'>
									<button title='Delete this panel.' type='submit' class='inline-block text-right btn btn-danger'><i class='fa fa-fw fa-times'></i>
									</button>
								</form>

								<a title='UID: ".$thisPanelUID."' class='btn btn-default form-inline'><i class='fa fa-fw fa-info-circle'></i></a>

								<h4 class='text-center'>Panel ".$thisPanelPosition."<br />Panel Type: ".strtoupper($thisPanelType)."</h4>

								";

									//thisPanelUID

									//<a title="Delete this panel." class="btn btn-danger">
											//<i class="fa fa-fw fa-times"></i>
									//</a>
									


								echo "
							</div>
							<div class='panel-body'>
<div class='text-center'>														
	<span class='label'><i class='fa fa-square text-muted'> Port Not Active</i></span>
	<span class='label'><i class='fa fa-square text-success'> Port Active</i></span>
	<br />
	<span class='label'><i class='fa fa-question text-muted'> Attenuation Unknown</i></span>
	<span class='label'><i class='fa fa-check-square-o text-success'> Attenuation Pass</i></span>
	<span class='label'><i class='fa fa-warning text-danger'> Attenuation Fail</i></span>
</div>
							";

							?>


							<table class='display table table-striped table-bordered table-hover'>
								<thead>
									<tr>
									<?php
										echo '
										<!-- Column 1 -->
										<th colspan="2" class="text-center">Column 1</th>
										<!-- Column 2 -->
										<th colspan="2" class="text-center">Column 2</th>
										';
									
									?>
									</tr>
								</thead>
								<tbody>
							<?php

							for ($iPorts=1; $iPorts <= $thisPanelPortCap; $iPorts++) {
								//first port 
								if ($iPorts==1) {
									echo '<tr>';
								}

								echo '
								<td class="text-center border-right">';

								//Check for and display strand (if)	
								echo "Strand ".$iPorts."<br />";
								if ($panelPortDetails[$thisPanelUID][$iPorts]['strandStatus']=='active') {
									echo "<i class='fa fa-square text-success'></i><br />";	
								}
								else{
									echo "<i class='fa fa-square text-muted'></i><br />";	
								}

								echo '
								</td>
								<td class="text-center border-left">';

								//Check for and display jumper (if)
								echo "Jumper ".$iPorts."<br />";	
								if ($panelPortDetails[$thisPanelUID][$iPorts]['jumperStatus']=='active') {
									echo "<i class='fa fa-square text-success'></i><br />";	
								}
								else{
									echo "<i class='fa fa-square text-muted'></i><br />";	
								}
								echo '
								</td>
								';


								//last port 
								if ($iPorts==$thisPanelPortCap) {
									echo '<tr>';
								}
								//end column every 2
								else if ($iPorts % 2 == 0) {
									echo '</tr>';
									echo '<tr>';
								}
								//do nothing
								else{

								}

							}
							
							?>

								</tbody>
							</table>

							<?php								
				}
			}
					
						//echo '
						//<!-- Display -->
						//<p>Needle found in the haystack.</p>
						//';


					echo '
					</div>
				</div>
			</div>
			';	
		}
		else{
			echo '
			<div class="col-xs-12 col-lg-4">
				<div class="panel panel-warning">
					<div class="panel-heading">
						<h4 class="text-center">Empty Slot ('.$iPanelCapCounter.')</h4>
						<p class="text-center">Add a panel by clicking on the button below.</p>
					</div>
					<div class="panel-body">
						<a title="Add a panel here." class="btn btn-primary btn-block" data-toggle="collapse" data-target="#addPanel'.$iPanelCapCounter.'">
							<i class="fa fa-fw fa-align-left fa-plus-circle"></i> Add Panel Here
						</a>
						<br />
						<div id="addPanel'.$iPanelCapCounter.'" class="panel panel-default collapse">
							<div class="panel-body">
							<!-- Form -->
								<form method="POST" action="guidedRecordCreation.php" id="addPanelForm'.$iPanelCapCounter.'" name="addPanelForm'.$iPanelCapCounter.'">
								
								<input type="hidden" id="manageCabinetAction" name="manageCabinetAction" value="addPanel">
								<input type="hidden" id="addPanelSlot" name="addPanelSlot" value="'.$iPanelCapCounter.'">
								<input type="hidden" id="fk_cabinet_x" name="fk_cabinet_x" value="'.$cabinet_uid.'">

								<div class="form-group">
									<label for="panelType">Panel Type</label>
									<select id="panelType" name="panelType" class="form-control">
									  <option selected value="st">ST</option>
									  <option value="sc">SC</option>
									  <option value="lc">LC</option>
									  <option value="mtrj">MTRJ</option>
									</select>
								</div>

								<div class="form-group">
									<label for="portCapacity">Port Capacity</label>
									<input type="number" class="form-control" id="portCapacity" name="portCapacity">
								</div>

								<button type="submit" class="text-right btn btn-primary">Submit</button>
								<br /><br />

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			';
		}
		if ($iPanelCapCounter % 3==0) {
			echo '</div>';
		}
	
	}

}



?>