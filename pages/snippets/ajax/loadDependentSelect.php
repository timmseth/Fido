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
require_once('../configPointer.php');

if (isset($_GET['form']) && isset($_GET['load'])) {

	//Which form are we in?
	switch ($_GET['form']) {
			



//===============================================================
//===============================================================
//===============================================================					

	//ManageCabinet
		case 'manageCabinet':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					# code...
					//echo '
					//	<span class="text-warning">Adding Dependent Levels.</span>
					//';

					//check for parent building
					if (isset($_GET['parentBuilding'])) {
					//DEBUG
					//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each building in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

					$stmt->execute();
					//Store in multidimensional array
					foreach($stmt as $row) {
					$buildingDetails['parentBuilding_uid']=$row['building_uid'];
					$buildingDetails['parentBuilding_level']=$row['levels'];
					}
					//Get Total
					$totalLevels=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}

					//output the actual options in the proper select field.
					echo '
					<!-- Level Details -->
					<div class="form-group">
					<label><small>* </small>Level</label>
					<select class="form-control" id="manageCabinetParentLevel" name="manageCabinetParentLevel" required >
					<option selected disabled>Select level...</option>';

					$levelsOneLess=$buildingDetails['parentBuilding_level'];
					$levelsOneLess--;



					for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
						echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
						if ($iLevelSelectGen==0) {
						echo ' (lowest possible)';
						}
						echo '</option>';
					}

					echo '</select>
					</div>';
					//=================
					}
					else{
					echo '<div class="alert alert-danger">';
					echo 'Error!<br />';
					echo 'Couldn\'t load levels based on the selected building...';
					echo '</div>';
					}
					//Fake js each time to enable the next dependency.
					?>
					<!-- Enable Dependent Locations -->
					<script>
					    $("#manageCabinetParentLevel").change(function(event) {
					        //get the user selections
					        var selectedBuilding = $("#manageCabinetParentBuilding").val();
					        var selectedLevel = $("#manageCabinetParentLevel").val();
					        var targetDiv = document.getElementById( "locationBreadcrumbField" );
					        //load the child levels based on user selections
					        $(targetDiv).html('');
						        $('#storageUnitBreadcrumbField').html('');
						        $('#cabinetBreadcrumbField').html('');
					        $(targetDiv).addClass('animated fadeInDown');
					        $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel);
					        $(targetDiv).show("slow");


					    });
					</script>
					<?php
					break;

				//load dependent locations
				case 'locations':
					# code...
					//echo '
					//<span class="text-warning">Adding Dependent Locations.</span>
					//';
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
					//DEBUG
					//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');
					//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$locationDetails[$i]['parentBuilding_name']=$row['name'];
					$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$locationDetails[$i]['parentLocation_level']=$row['level'];
					$locationDetails[$i]['parentLocation_description']=$row['description'];
					}
					//Get Total
					$totalLocations=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Location</label>
					<select class="form-control" id="manageCabinetParentLocation" name="manageCabinetParentLocation" required >
					<option selected disabled>Select a Location...</option>';

					for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
					echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
					}

					echo '
					</select>
					</div>';

					//=================
					}
					else{
					echo '<div class="alert alert-danger">';
					echo 'Error!<br />';
					echo 'Couldn\'t load levels based on the selected building...';
					echo '</div>';
					}
					//Fake js each time to enable the next dependency.
					?>
					<!-- Enable Dependent Locations -->
					<script>
					    $("#manageCabinetParentLocation").change(function(event) {
					        //get the user selections
					        var selectedBuilding = $("#manageCabinetParentBuilding").val();
					        var selectedLevel = $("#manageCabinetParentLevel").val();
					        var selectedLocation = $("#manageCabinetParentLocation").val();
					        var targetDiv = document.getElementById( "storageUnitBreadcrumbField" );
					        //load the child levels based on user selections
					        $(targetDiv).html('');
						        $('#cabinetBreadcrumbField').html('');
					        $(targetDiv).addClass('animated fadeInDown');
					        $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=storageUnits&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation);
					        $(targetDiv).show("slow");
					    });
					</script>
					<?php


					break;

				//load dependent storageUnits
				case 'storageUnits':
//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$StorageUnitDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$StorageUnitDetails[$i]['parentBuilding_name']=$row['name'];
					$StorageUnitDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$StorageUnitDetails[$i]['parentLocation_level']=$row['level'];
					$StorageUnitDetails[$i]['parentLocation_description']=$row['description'];
					$StorageUnitDetails[$i]['parentStorageUnit_label']=$row['label'];
					$StorageUnitDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
					}
					//Get Total
					$totalStorageUnits=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Storage Unit Details -->
					<div class="form-group">
					<label><small>* </small>Storage Unit</label>
					<select class="form-control" id="manageCabinetParentStorageUnit" name="manageCabinetParentStorageUnit" required >
					<option selected disabled>Select a Storage Unit...</option>';

					for ($iStorageUnitsSelectGen=1; $iStorageUnitsSelectGen <= $totalStorageUnits; $iStorageUnitsSelectGen++) { 
					echo '<option value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';
					}

					echo '
					</select>
					</div>';
						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load storage units based on the selected building, level, and location...';
			            echo '</div>';
					}
					//Fake js each time to enable the next dependency.
					?>
					<!-- Enable Dependent Locations -->
					<script>
					    $("#manageCabinetParentStorageUnit").change(function(event) {
					        //get the user selections
					        var selectedBuilding = $("#manageCabinetParentBuilding").val();
					        var selectedLevel = $("#manageCabinetParentLevel").val();
					        var selectedLocation = $("#manageCabinetParentLocation").val();
					        var selectedStorageUnit = $("#manageCabinetParentStorageUnit").val();
					        var targetDiv = document.getElementById("cabinetBreadcrumbField");
					        //load the child levels based on user selections
					        $(targetDiv).html('');
					        $(targetDiv).addClass('animated fadeInDown');
					        $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=cabinets&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit);
					        $(targetDiv).show("slow");
					    });
					</script>
					<?php
					break;




				//load dependent cabinets
				case 'cabinets':
					# code...
					//echo '
						//<span class="text-warning">Adding Dependent Cabinets.</span>
					//';		
//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentStorageUnit'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label,cabinet.label AS "cabinetLabel",cabinet.cabinet_UID FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet ON cabinet.fk_storageUnit_UID=storageunit.storageUnit_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
						$i++;
						$CabinetDetails[$i]['parentBuilding_uid']=$row['building_uid'];
						$CabinetDetails[$i]['parentBuilding_name']=$row['name'];
						$CabinetDetails[$i]['parentLocation_uid']=$row['location_uid'];
						$CabinetDetails[$i]['parentLocation_level']=$row['level'];
						$CabinetDetails[$i]['parentLocation_description']=$row['description'];
						$CabinetDetails[$i]['parentStorageUnit_label']=$row['label'];
						$CabinetDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
						$CabinetDetails[$i]['parentCabinet_label']=$row['cabinetLabel'];
						$CabinetDetails[$i]['parentCabinet_UID']=$row['cabinet_UID'];
					}
					
					//Get Total
					$totalCabinets=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Cabinet Details -->
					<div class="form-group">
					<label><small>* </small>Cabinet</label>
					<select class="form-control" id="manageCabinetUID" name="manageCabinetUID" required >
					<option selected disabled>Select a Cabinet...</option>';

					for ($iCabinetsSelectGen=1; $iCabinetsSelectGen <= $totalCabinets; $iCabinetsSelectGen++) { 
					echo '<option value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';
					}

					echo '
					</select>
					</div>';
						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load storage units based on the selected building, level, and location...';
			            echo '</div>';
					}


	//Fake js each time to enable the next dependency.
					?>
					<!-- Enable Dependent Locations -->
					<script>
					    $("#manageCabinetUID").change(function(event) {
					        //get the user selections
					        var selectedBuilding = $("#manageCabinetParentBuilding").val();
					        var selectedLevel = $("#manageCabinetParentLevel").val();
					        var selectedLocation = $("#manageCabinetParentLocation").val();
					        var selectedStorageUnit = $("#manageCabinetParentStorageUnit").val();
					        var selectedCabinet = $("#manageCabinetUID").val();
					        var targetDiv = document.getElementById( "cabinetRepresentationDiv" );
					        //load the child levels based on user selections
					        $(targetDiv).html('');
					        $(targetDiv).addClass('animated fadeInDown');
					        $(targetDiv).load("snippets/ajax/manageCabinetContents.php?uid="+selectedCabinet);
						    window.location = './manageCabinet.php?uid='+selectedCabinet;
					    });
					</script>
					<?php



					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			







		//ADD LOCATION
		case 'addLocation':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					//check for parent building
					if (isset($_GET['parentBuilding'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {
						$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

						$stmt->execute();
						//Store in multidimensional array
						foreach($stmt as $row) {
						$buildingDetails['parentBuilding_uid']=$row['building_uid'];
						$buildingDetails['parentBuilding_level']=$row['levels'];
						}
						//Get Total
						$totalLevels=$stmt->rowCount();
						}    
						//Catch Errors (if errors)
						catch(PDOException $e){}

						//output the actual options in the proper select field.
						echo '
						<!-- Level Details -->
						<div class="form-group">
						<label><small>* </small>Level</label>
						<select class="form-control" id="createLocationParentLevel" name="createLocationParentLevel" required >
						<option selected disabled>Select level...</option>';

				$levelsOneLess=$buildingDetails['parentBuilding_level'];
				$levelsOneLess--;



						for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
							echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
							if ($iLevelSelectGen==0) {
							echo ' (lowest possible)';
							}
							echo '</option>';
						}

						echo '</select>
						<p class="help-block">The level where this location can be found.</p>
						</div>';
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			
		//ADD STORAGEUNIT
		case 'addStorageUnit':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					//check for parent building
					if (isset($_GET['parentBuilding'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';

					//=================
						
						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {
						$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

						$stmt->execute();
						//Store in multidimensional array
						foreach($stmt as $row) {
						$buildingDetails['parentBuilding_uid']=$row['building_uid'];
						$buildingDetails['parentBuilding_level']=$row['levels'];
						}
						//Get Total
						$totalLevels=$stmt->rowCount();
						}    
						//Catch Errors (if errors)
						catch(PDOException $e){}

						//output the actual options in the proper select field.
						echo '
						<!-- Level Details -->
						<div class="form-group">
						<label><small>* </small>Level</label>
						<select class="form-control" id="createStorageUnitParentLevel" name="createStorageUnitParentLevel" required >
						<option selected disabled>Select level...</option>';

				$levelsOneLess=$buildingDetails['parentBuilding_level'];
				$levelsOneLess--;



						for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
							echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
							if ($iLevelSelectGen==0) {
							echo ' (lowest possible)';
							}
							echo '</option>';
						}
						echo '</select>
						<p class="help-block">The level where this location can be found.</p>
						</div>';

//Fake js each time to enable the next dependency.
?>
<!-- Enable Dependent Locations -->
<script>
$("#createStorageUnitParentLevel").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createStorageUnitParentBuilding").val();
    var selectedLevel = $("#createStorageUnitParentLevel").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createStorageUnitDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createStorageUnitDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //if both building and level are not empty
    if ((selectedBuilding!='') && (selectedLevel!='')){
        //load the child levels based on user selections
        $("#createStorageUnitDependentLocationsContainer").html('');
        $("#createStorageUnitDependentLocationsContainer").load('snippets/ajax/loadDependentSelect.php?form=addStorageUnit&load=locations&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel);

        //get the numerical value of building not the uid
        var selectedBuildingDisplay=$("#createStorageUnitParentBuilding option:selected").text();
        var selectedBuildingNumber = selectedBuildingDisplay.substring(0,3);
        var selectedLevelZeroFill= pad(selectedLevel, 2);
        //spit it out
        $("#selectedBuildingNumber").html(selectedBuildingNumber);
        $("#selectedBuildingLevel").html(selectedLevelZeroFill);
        //add guide for inc value
        //$("#arbitIncValue").html('ZZ');
        $("#afterGeneratedLabelHint").html('The "zz" value in the Storage-Unit Label must be found by a physical examination of the room. If you cannot do this then do NOT proceed.');


    }

});

function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

</script>
<?php
/*
//Change PARENT LEVEL field in the STORAGE UNIT FORM
//LOAD LOCATIONS
$('#createStorageUnitParentLevel').change(function(event) {
//get the user selections
var selectedBuilding = $('#createStorageUnitParentBuilding').val();
var selectedLevel = $('#createStorageUnitParentLevel').val();

//no building selected
if (selectedBuilding==''){
//load alert
$('#createStorageUnitDependentLocationsContainer').html('<div class=\"alert alert-info\">Please select a building.</div>');
}

//no level selected
if (selectedLevel==''){
//load alert
$('#createStorageUnitDependentLocationsContainer').html('<div class=\"alert alert-info\">Please select a level.</div>');
}

//if both building and level are not empty
if ((selectedBuilding!='') && (selectedLevel!='')){
//load the child levels based on user selections
$('#createStorageUnitDependentLocationsContainer').html('');
$('#createStorageUnitDependentLocationsContainer').load('snippets/ajax/loadDependentSelect.php?form=addStorageUnit&load=locations&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel);
}
});
*/

						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;

				//load dependent locations
				case 'locations':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');
					//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$locationDetails[$i]['parentBuilding_name']=$row['name'];
					$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$locationDetails[$i]['parentLocation_level']=$row['level'];
					$locationDetails[$i]['parentLocation_description']=$row['description'];
					}
					//Get Total
					$totalLocations=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Location</label>
					<select class="form-control" id="createStorageUnitParentLocation" name="createStorageUnitParentLocation" required >
					<option selected disabled>Select a Location...</option>';

					for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
					echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
					}

					echo '
					</select>
				                                <p class="help-block">The building level/floor where this location is found.<small>("Level 0 (lowest possible)")</small></p>
					</div>';
						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			
//ADD CABINET
		case 'addCabinet':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					//check for parent building
					if (isset($_GET['parentBuilding'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';

					//=================
						
						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {
						$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

						$stmt->execute();
						//Store in multidimensional array
						foreach($stmt as $row) {
						$buildingDetails['parentBuilding_uid']=$row['building_uid'];
						$buildingDetails['parentBuilding_level']=$row['levels'];
						}
						//Get Total
						$totalLevels=$stmt->rowCount();
						}    
						//Catch Errors (if errors)
						catch(PDOException $e){}

						//output the actual options in the proper select field.
						echo '
						<!-- Level Details -->
						<div class="form-group">
						<label><small>* </small>Level</label>
						<select class="form-control" id="createCabinetParentLevel" name="createCabinetParentLevel" required >
						<option selected disabled>Select level...</option>';
							
				$levelsOneLess=$buildingDetails['parentBuilding_level'];
				$levelsOneLess--;



						for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
							echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
							if ($iLevelSelectGen==0) {
							echo ' (lowest possible)';
							}
							echo '</option>';
						}
						echo '</select>
						<p class="help-block">The level where this location can be found.</p>
						</div>';

//Fake js each time to enable the next dependency.
?>
<!-- Enable Dependent Locations -->
<script>
$("#createCabinetParentLevel").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createCabinetParentBuilding").val();
    var selectedLevel = $("#createCabinetParentLevel").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //if both building and level are not empty
    if ((selectedBuilding!='') && (selectedLevel!='')){
        //load the child levels based on user selections
        $("#createCabinetDependentLocationsContainer").html('');
        $("#createCabinetDependentLocationsContainer").load('snippets/ajax/loadDependentSelect.php?form=addCabinet&load=locations&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel);

    }

});



function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

</script>
<?php
/*
//Change PARENT LEVEL field in the STORAGE UNIT FORM
//LOAD LOCATIONS
$('#createStorageUnitParentLevel').change(function(event) {
//get the user selections
var selectedBuilding = $('#createStorageUnitParentBuilding').val();
var selectedLevel = $('#createStorageUnitParentLevel').val();

//no building selected
if (selectedBuilding==''){
//load alert
$('#createStorageUnitDependentLocationsContainer').html('<div class=\"alert alert-info\">Please select a building.</div>');
}

//no level selected
if (selectedLevel==''){
//load alert
$('#createStorageUnitDependentLocationsContainer').html('<div class=\"alert alert-info\">Please select a level.</div>');
}

//if both building and level are not empty
if ((selectedBuilding!='') && (selectedLevel!='')){
//load the child levels based on user selections
$('#createStorageUnitDependentLocationsContainer').html('');
$('#createStorageUnitDependentLocationsContainer').load('snippets/ajax/loadDependentSelect.php?form=addStorageUnit&load=locations&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel);
}
});
*/

						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;

				//load dependent locations
				case 'locations':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');
					//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$locationDetails[$i]['parentBuilding_name']=$row['name'];
					$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$locationDetails[$i]['parentLocation_level']=$row['level'];
					$locationDetails[$i]['parentLocation_description']=$row['description'];
					}
					//Get Total
					$totalLocations=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Location</label>
					<select class="form-control" id="createCabinetParentLocation" name="createCabinetParentLocation" required >
					<option selected disabled>Select a Location...</option>';

					for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
					echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
					}

					echo '
					</select>
				    <p class="help-block">The building level/floor where this location is found.<small>("Level 0 (lowest possible)")</small></p>
					</div>';
					


//======================================================================
?>

<script>
$("#createCabinetParentLocation").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createCabinetParentBuilding").val();
    var selectedLevel = $("#createCabinetParentLevel").val();
    var selectedLocation = $("#createCabinetParentLocation").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //no location selected
    if (selectedLocation==''){
        //load alert
        $("#createCabinetDependentStorageUnitsContainer").html('<div class="alert alert-info">Please Select a Location.</div>');
    }

    //if both building and level and location are not empty
    if ((selectedBuilding!='') && (selectedLevel!='') && (selectedLocation!='')){
        //load the child levels based on user selections
        $("#createCabinetDependentStorageUnitsContainer").html('');
        $("#createCabinetDependentStorageUnitsContainer").load('snippets/ajax/loadDependentSelect.php?form=addCabinet&load=storageUnits&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel+'&parentLocation='+selectedLocation);
    }

});
</script>
<?php


					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;





	case 'storageUnits':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$StorageUnitDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$StorageUnitDetails[$i]['parentBuilding_name']=$row['name'];
					$StorageUnitDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$StorageUnitDetails[$i]['parentLocation_level']=$row['level'];
					$StorageUnitDetails[$i]['parentLocation_description']=$row['description'];
					$StorageUnitDetails[$i]['parentStorageUnit_label']=$row['label'];
					$StorageUnitDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
					}
					//Get Total
					$totalStorageUnits=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Storage Unit Details -->
					<div class="form-group">
					<label><small>* </small>Storage Unit</label>
					<select class="form-control" id="createCabinetParentStorageUnit" name="createCabinetParentStorageUnit" required >
					<option selected disabled>Select a Storage Unit...</option>';

					for ($iStorageUnitsSelectGen=1; $iStorageUnitsSelectGen <= $totalStorageUnits; $iStorageUnitsSelectGen++) { 
					echo '<option value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';
					}

					echo '
					</select>
				       <p class="help-block">The Storage Unit where this cabinet is found.<small>("xxx - yy - zz")</small></p>
					</div>';
						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load storage units based on the selected building, level, and location...';
			            echo '</div>';
					}
					break;










				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
		


































		//ADD PANEL
		case 'addPanel':
			//What are we populating
			switch ($_GET['load']) {
							//load dependent levels

/* ==========================
=============================PANEL INSERTS
========================== */

case 'levels':
	//check for parent building
	if (isset($_GET['parentBuilding'])) {
		//DEBUG
		//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';

	//=================
		
		//get a detailed list of each building in the database
		$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
		$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

		$stmt->execute();
		//Store in multidimensional array
		foreach($stmt as $row) {
		$buildingDetails['parentBuilding_uid']=$row['building_uid'];
		$buildingDetails['parentBuilding_level']=$row['levels'];
		}
		//Get Total
		$totalLevels=$stmt->rowCount();
		}    
		//Catch Errors (if errors)
		catch(PDOException $e){}

		//output the actual options in the proper select field.
		echo '
		<!-- Level Details -->
		<div class="form-group">
		<label><small>* </small>Level</label>
		<select class="form-control" id="createPanelParentLevel" name="createPanelParentLevel" required >
		<option selected disabled>Select level...</option>';
			
$levelsOneLess=$buildingDetails['parentBuilding_level'];
$levelsOneLess--;

		for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
			echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
			if ($iLevelSelectGen==0) {
			echo ' (lowest possible)';
			}
			echo '</option>';
		}
		echo '</select>
		<p class="help-block">The level where this location can be found.</p>
		</div>';

//Fake js each time to enable the next dependency.
?>
<!-- Enable Dependent Locations -->
<script>
$("#createPanelParentLevel").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createPanelParentBuilding").val();
    var selectedLevel = $("#createPanelParentLevel").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //if both building and level are not empty
    if ((selectedBuilding!='') && (selectedLevel!='')){
        //load the child levels based on user selections
        $("#createPanelDependentLocationsContainer").html('');
        $("#createPanelDependentLocationsContainer").load('snippets/ajax/loadDependentSelect.php?form=addPanel&load=locations&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel);

    }

});



function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

</script>
<?php

						
					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;

				//load dependent locations
				case 'locations':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');
					//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$locationDetails[$i]['parentBuilding_name']=$row['name'];
					$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$locationDetails[$i]['parentLocation_level']=$row['level'];
					$locationDetails[$i]['parentLocation_description']=$row['description'];
					}
					//Get Total
					$totalLocations=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Location</label>
					<select class="form-control" id="createPanelParentLocation" name="createPanelParentLocation" required >
					<option selected disabled>Select a Location...</option>';

					for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
					echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
					}

					echo '
					</select>
				    <p class="help-block">The building level/floor where this location is found.<small>("Level 0 (lowest possible)")</small></p>
					</div>';
					


//======================================================================
?>

<script>
$("#createPanelParentLocation").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createPanelParentBuilding").val();
    var selectedLevel = $("#createPanelParentLevel").val();
    var selectedLocation = $("#createPanelParentLocation").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //no location selected
    if (selectedLocation==''){
        //load alert
        $("#createPanelDependentStorageUnitsContainer").html('<div class="alert alert-info">Please Select a Location.</div>');
    }

    //if both building and level and location are not empty
    if ((selectedBuilding!='') && (selectedLevel!='') && (selectedLocation!='')){
        //load the child levels based on user selections
        $("#createPanelDependentStorageUnitsContainer").html('');
        $("#createPanelDependentStorageUnitsContainer").load('snippets/ajax/loadDependentSelect.php?form=addPanel&load=storageUnits&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel+'&parentLocation='+selectedLocation);
    }

});
</script>
<?php


					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load levels based on the selected building...';
			            echo '</div>';
					}
					break;



	case 'storageUnits':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$StorageUnitDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$StorageUnitDetails[$i]['parentBuilding_name']=$row['name'];
					$StorageUnitDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$StorageUnitDetails[$i]['parentLocation_level']=$row['level'];
					$StorageUnitDetails[$i]['parentLocation_description']=$row['description'];
					$StorageUnitDetails[$i]['parentStorageUnit_label']=$row['label'];
					$StorageUnitDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
					}
					//Get Total
					$totalStorageUnits=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Storage Unit Details -->
					<div class="form-group">
					<label><small>* </small>Storage Unit</label>
					<select class="form-control" id="createPanelParentStorageUnit" name="createPanelParentStorageUnit" required >
					<option selected disabled>Select a Storage Unit...</option>';

					for ($iStorageUnitsSelectGen=1; $iStorageUnitsSelectGen <= $totalStorageUnits; $iStorageUnitsSelectGen++) { 
					echo '<option value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';
					}

					echo '
					</select>
				       <p class="help-block">The Storage Unit where this cabinet is found.<small>("xxx - yy - zz")</small></p>
					</div>';
						




?>

<script>
$("#createPanelParentStorageUnit").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createPanelParentBuilding").val();
    var selectedLevel = $("#createPanelParentLevel").val();
    var selectedLocation = $("#createPanelParentLocation").val();
    var selectedStorageUnit = $("#createPanelParentStorageUnit").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //no location selected
    if (selectedLocation==''){
        //load alert
        $("#createPanelDependentStorageUnitsContainer").html('<div class="alert alert-info">Please Select a Location.</div>');
    }

    //no storageUnit selected
    if (selectedStorageUnit==''){
        //load alert
        $("#createPanelDependentStorageUnitsContainer").html('<div class="alert alert-info">Please Select a Storage Unit.</div>');
    }

    //if both building and level and location are not empty
    if ((selectedBuilding!='') && (selectedLevel!='') && (selectedLocation!='') && (selectedStorageUnit!='')){
        //load the child levels based on user selections
        $("#createPanelDependentCabinetsContainer").html('');
        $("#createPanelDependentCabinetsContainer").load('snippets/ajax/loadDependentSelect.php?form=addPanel&load=cabinets&parentBuilding='+selectedBuilding+'&parentLevel='+selectedLevel+'&parentLocation='+selectedLocation+'&parentStorageUnit='+selectedStorageUnit);
    }
});
</script>
<?php



					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load cabinets based on the selected building, level, location, and storage unit...';
			            echo '</div>';
					}
					break;























































//======================================================================



/*
	case 'cabinets':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])&& isset($_GET['parentStorageUnit'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label,cabinet.label AS "cabinetLabel",cabinet.cabinet_UID FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet ON cabinet.fk_location_UID=storageUnit.storageUnit_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$CabinetDetails[$i]['parentBuilding_uid']=$row['building_uid'];
					$CabinetDetails[$i]['parentBuilding_name']=$row['name'];
					$CabinetDetails[$i]['parentLocation_uid']=$row['location_uid'];
					$CabinetDetails[$i]['parentLocation_level']=$row['level'];
					$CabinetDetails[$i]['parentLocation_description']=$row['description'];
					$CabinetDetails[$i]['parentStorageUnit_label']=$row['label'];
					$CabinetDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
					$CabinetDetails[$i]['parentCabinet_label']=$row['cabinetLabel'];
					$CabinetDetails[$i]['parentCabinet_UID']=$row['cabinet_UID'];
					}
					//Get Total
					$totalCabinets=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Cabinet Details -->
					<div class="form-group">
					<label><small>* </small>Storage Unit</label>
					<select class="form-control" id="createPanelParentCabinet" name="createPanelParentCabinet" required >
					<option selected disabled>Select a Cabinet...</option>';

					for ($iCabinetsSelectGen=1; $iCabinetsSelectGen <= $totalCabinets; $iCabinetsSelectGen++) { 
					echo '<option value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';
					}

					echo '
					</select>
				       <p class="help-block">The Cabinet where this cabinet is found.<small>("xxx - yy - zz")</small></p>
					</div>';
						


*/
//======================================================================




	case 'cabinets':
					//check for parent building
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentStorageUnit'])) {
						//DEBUG
						//echo '<div class="alert alert-success">Success!<br />Loading dependent elements based off the selected item...</div>';
					//=================
					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label,cabinet.label AS "cabinetLabel",cabinet.cabinet_UID FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet ON cabinet.fk_storageUnit_UID=storageunit.storageUnit_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'" AND location.location_uid="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'"');

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
						$i++;
						$CabinetDetails[$i]['parentBuilding_uid']=$row['building_uid'];
						$CabinetDetails[$i]['parentBuilding_name']=$row['name'];
						$CabinetDetails[$i]['parentLocation_uid']=$row['location_uid'];
						$CabinetDetails[$i]['parentLocation_level']=$row['level'];
						$CabinetDetails[$i]['parentLocation_description']=$row['description'];
						$CabinetDetails[$i]['parentStorageUnit_label']=$row['label'];
						$CabinetDetails[$i]['parentStorageUnit_UID']=$row['storageUnit_UID'];
						$CabinetDetails[$i]['parentCabinet_label']=$row['cabinetLabel'];
						$CabinetDetails[$i]['parentCabinet_UID']=$row['cabinet_UID'];
					}
					
					//Get Total
					$totalCabinets=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Cabinet Details -->
					<div class="form-group">
					<label><small>* </small>Cabinet</label>
					<select class="form-control" id="createPanelParentCabinet" name="createPanelParentCabinet" required >
					<option selected disabled>Select a Cabinet...</option>';

					for ($iCabinetsSelectGen=1; $iCabinetsSelectGen <= $totalCabinets; $iCabinetsSelectGen++) { 
					echo '<option value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';
					}

					echo '
					</select>
				       <p class="help-block">The Cabinet where this panel is found.</p>
					</div>';
						



//======================================================================
?>

<script>
$("#createPanelParentCabinet").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createPanelParentBuilding").val();
    var selectedLevel = $("#createPanelParentLevel").val();
    var selectedLocation = $("#createPanelParentLocation").val();
    var selectedStorageUnit = $("#createPanelParentStorageUnit").val();
    var selectedCabinet = $("#createPanelParentCabinet").val();

    //no building selected
    if (selectedBuilding==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Building.</div>');
    }

    //no level selected
    if (selectedLevel==''){
        //load alert
        $("#createPanelDependentLocationsContainer").html('<div class="alert alert-info">Please Select a Level.</div>');
    }

    //no location selected
    if (selectedLocation==''){
        //load alert
        $("#createPanelDependentStorageUnitsContainer").html('<div class="alert alert-info">Please Select a Location.</div>');
    }

    //no storageUnit selected
    if (selectedStorageUnit==''){
        //load alert
        $("#createPanelDependentCabinetsContainer").html('<div class="alert alert-info">Please Select a Storage Unit.</div>');
    }

        //no storageUnit selected
    if (selectedCabinet==''){
        //load alert
        $("#createPanelDependentPanelsContainer").html('<div class="alert alert-info">Please Select a Cabinet.</div>');
    }


});
</script>
<?php



					//=================
					}
					else{
			            echo '<div class="alert alert-danger">';
			            echo 'Error!<br />';
			            echo 'Couldn\'t load storage units based on the selected building, level, and location...';
			            echo '</div>';
					}
					break;










/* ==========================
=============================
========================== */

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			
		//ADD PORT
		case 'addPort':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					# code...
					break;

				//load dependent locations
				case 'locations':
					# code...
					break;

				//load dependent storageUnits
				case 'storageUnits':
					# code...
					break;

				//load dependent cabinets
				case 'cabinets':
					# code...
					break;

				//load dependent panels
				case 'panels':
					# code...
					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			
		//ADD STRAND (This is triggered mainly for the manage cabinet contents page when a strand is being added to a page)
		case 'addStrand':
			//What are we populating
			switch ($_GET['load']) {

//This will display to the user a list of buildings when they want to add a strand on the cabinet management page.
				case 'buildings':
					# code...
					if (isset($_GET['checkJumpers'])) {
						echo '
<!-- Destination Details -->
<div class="form-group">
<div class="help-block">
	<p>Select one of the following options first:</p>
	</div>
	<div class="radio">
	  <label>
	    <input type="radio" name="portConnectsToOption" id="portConnectsToPort" value="port" checked>
	    This jumper connects one port to another port.
	  </label>
	</div>
	<div class="radio">
	  <label>
	    <input type="radio" name="portConnectsToOption" id="portConnectsToEquipment" value="equipment">
	    This jumper connects one port to a new piece of equipment.
	  </label>
	</div>
	<!--
	<div class="radio">
	  <label>
	    <input type="radio" name="portConnectsToOption" id="portConnectsToEquipment" value="equipment">
	    This jumper connects one port to an existing piece of equipment.
	  </label>
	</div>
	-->
</div>';

//mapPanels

					}
					else{
						//echo "Did Not Find CheckJumper.";
					}

					//get a detailed list of each location in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT * FROM building ORDER BY number ASC');
					//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

					$stmt->execute();
					//Store in multidimensional array
					$i=0;
					foreach($stmt as $row) {
					$i++;
					$buildingDetails[$i]['parentBuilding_uid']=$row['building_UID'];
					$buildingDetails[$i]['parentBuilding_name']=$row['name'];
					$buildingDetails[$i]['parentBuilding_number']=$row['number'];
					}
					//Get Total
					$totalBuildings=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					

					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Destination Building</label>
					<select class="form-control" id="addStrandDestinationBuilding" name="addStrandDestinationBuilding" required >
					<option selected disabled>Select a Building...</option>';

					for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 
					echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['parentBuilding_uid'].'">'.$buildingDetails[$iBuildingSelectGen]['parentBuilding_number'].' - '.$buildingDetails[$iBuildingSelectGen]['parentBuilding_name'].'</option>';
					}

					echo '
					</select>
				    <p class="help-block">The building to which this strand connects.</p>
					</div>';
		


					if (isset($_GET['checkJumpers'])) {

					echo '
					<script>
					$("#addStrandDestinationBuilding").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var portConnectsToSelection = $("input[name=portConnectsToOption]:checked").val();
					    //alert(selectedBuilding);
					    var loadLevelsHereDiv = document.getElementById("workSpaceDestinationLevel");
					    $(loadLevelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=levels&parentBuilding="+selectedBuilding+"&checkJumpers=yes&portConnectsTo="+portConnectsToSelection);
					});
					</script>
					';
					echo '
					<script src="../js/attenuationCalculator.js"></script>
					';
					}
					elseif (isset($_GET['mapPanels'])) {
					echo '
					<script>
					$("#addStrandDestinationBuilding").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    //alert(selectedBuilding);
					    var loadLevelsHereDiv = document.getElementById("workSpaceDestinationLevel");
					    $(loadLevelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=levels&parentBuilding="+selectedBuilding+"&mapPanels=yes");
					});
					</script>
					<script src="../js/attenuationCalculator.js"></script>
					';
					}
					else{
					echo '
					<script>
					$("#addStrandDestinationBuilding").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    //alert(selectedBuilding);
					    var loadLevelsHereDiv = document.getElementById("workSpaceDestinationLevel");
					    $(loadLevelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=levels&parentBuilding="+selectedBuilding);
					});
					</script>
					<script src="../js/attenuationCalculator.js"></script>
					';
					}





					break;

//This will load up the level options when a building is selected.

				//load dependent levels
				case 'levels':

					if (isset($_GET['parentBuilding'])) {

					if (isset($_GET['checkJumpers'])) {
						if (isset($_GET['portConnectsTo'])) {
							//echo "Selection: ".$_GET['portConnectsTo'];
						}
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}

					//get a detailed list of each building in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

					$stmt->execute();
					//Store in multidimensional array
					foreach($stmt as $row) {
					$buildingDetails['parentBuilding_uid']=$row['building_uid'];
					$buildingDetails['parentBuilding_level']=$row['levels'];
					}
					//Get Total
					$totalLevels=$stmt->rowCount();
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					}

					echo '
					<!-- Level Details -->
					<div class="form-group">
					<label><small>* </small>Destination Level</label>
					<select class="form-control" id="addStrandDestinationLevel" name="addStrandDestinationLevel" required >';

					echo '
					<option selected disabled>Select level...</option>';

					for ($iLevelSelectGen=($buildingDetails['parentBuilding_level']); $iLevelSelectGen >= 0; $iLevelSelectGen--) { 

					echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
					if ($iLevelSelectGen==0) {
					echo ' (lowest possible)';
					}
					echo '</option>';

					}
					echo '</select>
					<p class="help-block">The level where this strand is going.</p>
					</div>';


					if (isset($_GET['checkJumpers'])) {
						if ($_GET['portConnectsTo']=='equipment') {
							echo '
							<script>
							$("#addStrandDestinationLevel").change(function(event) {
						    var portConnectsToSelection = $("input[name=portConnectsToOption]:checked").val();
							    var selectedBuilding = $("#addStrandDestinationBuilding").val();
							    var selectedLevel = $("#addStrandDestinationLevel").val();
							    var loadLocationsHereDiv = document.getElementById("workSpaceDestinationLocation");
							    $(loadLocationsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&checkJumpers=yes&portConnectsTo="+portConnectsToSelection);
							});
							</script>
							';
						}
						else{
							echo '
							<script>
							$("#addStrandDestinationLevel").change(function(event) {
							    var selectedBuilding = $("#addStrandDestinationBuilding").val();
							    var selectedLevel = $("#addStrandDestinationLevel").val();
							    var loadLocationsHereDiv = document.getElementById("workSpaceDestinationLocation");
							    $(loadLocationsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&checkJumpers=yes");
							});
							</script>
							';
						}
					}
					elseif (isset($_GET['mapPanels'])) {
					echo '
					<script>
					$("#addStrandDestinationLevel").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var loadLocationsHereDiv = document.getElementById("workSpaceDestinationLocation");
					    $(loadLocationsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&mapPanels=yes");
					});
					</script>
					';
					}
					else{
					echo '
					<script>
					$("#addStrandDestinationLevel").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var loadLocationsHereDiv = document.getElementById("workSpaceDestinationLocation");
					    $(loadLocationsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel);
					});
					</script>
					';
					}




					# code...
					break;
//This will load up the location options when a level of a building is selected.

				//load dependent locations
				case 'locations':
					# code...
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
					if (isset($_GET['checkJumpers'])) {
						if (isset($_GET['portConnectsTo'])) {
							//echo "Selection: ".$_GET['portConnectsTo'];
						}
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}
					//get a detailed list of each building in the database
					$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					try {
					$stmt = $db->prepare('SELECT location.description,location.location_UID FROM location INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');

					$stmt->execute();


					echo '
					<!-- Location Details -->
					<div class="form-group">
					<label><small>* </small>Destination Location</label>
					<select class="form-control" id="addStrandDestinationLocation" name="addStrandDestinationLocation" required >';

					echo '
					<option selected disabled>Select location...</option>';

					//Store in multidimensional array
					foreach($stmt as $row) {
						$locationDetails['parentLocation_uid']=$row['location_UID'];
						$locationDetails['parentLocation_description']=$row['description'];
						echo '<option value="'.$locationDetails['parentLocation_uid'].'">'.$locationDetails['parentLocation_description'].'</option>';
					}

					//Get Total
					$totalLocations=$stmt->rowCount();
					
echo '</select>
					<p class="help-block">The location where this strand is going.</p>
					</div>';
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					}

					if (isset($_GET['checkJumpers'])) {
						if (isset($_GET['portConnectsTo'])) {
							# code...
							//workSpaceDestinationEquipment
							echo '
							<script>
							$("#addStrandDestinationLocation").change(function(event) {
							    var selectedBuilding = $("#addStrandDestinationBuilding").val();
							    var selectedLevel = $("#addStrandDestinationLevel").val();
							    var selectedLocation = $("#addStrandDestinationLocation").val();
							    var loadMakesHereDiv = document.getElementById("workSpaceDestinationMake");
							    $(loadMakesHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=makes&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&checkJumpers=yes");
							});
							</script>

							';
						}
						else{
							echo '
					<script>
					$("#addStrandDestinationLocation").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var loadStorageUnitsHereDiv = document.getElementById("workSpaceDestinationStorageUnit");
					    $(loadStorageUnitsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=storageUnits&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&checkJumpers=yes");
					});
					</script>

					';
						}
					
					}
					elseif (isset($_GET['mapPanels'])) {

					echo '
					<script>
					$("#addStrandDestinationLocation").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var loadStorageUnitsHereDiv = document.getElementById("workSpaceDestinationStorageUnit");
					    $(loadStorageUnitsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=storageUnits&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&mapPanels=yes");
					});
					</script>

					';
					}
					else{
					
					echo '
					<script>
					$("#addStrandDestinationLocation").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var loadStorageUnitsHereDiv = document.getElementById("workSpaceDestinationStorageUnit");
					    $(loadStorageUnitsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=storageUnits&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation);
					});
					</script>

					';
					}
					break;




/*=====================
=======================
=====================*/

//load dependent equipment stuff
case 'makes':
if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])){

	//check jumpers
	if (isset($_GET['checkJumpers'])) {
		if (isset($_GET['portConnectsTo'])) {
			//echo "Selection: ".$_GET['portConnectsTo'];
		}
	}
	else{}

	//set make array
	$makeDetails=array();
	//get list from database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * FROM eq_make');
	$stmt->execute();
	foreach($stmt as $row) {
		$makeDetails[$row['make_UID']]['UID']=$row['make_UID'];
		$makeDetails[$row['make_UID']]['name']=$row['name'];
		$makeDetails[$row['make_UID']]['description']=$row['description'];
		$makeDetails[$row['make_UID']]['lastModified']=$row['lastModified'];
	}


	echo '
	<!-- Make Details -->
	<div class="form-group">
	<label><small>* </small>Equipment Make</label>
	<select class="form-control" id="addStrandDestinationEqMake" name="addStrandDestinationEqMake" required>';

	echo '
	<option selected disabled>Select Make...</option>';
	foreach ($makeDetails as $key => $value) {
		echo '<option value="'.$value['UID'].'">'.$value['name'].'</option>';
	}
	echo '</select>
	<p class="help-block">The make of the equipment to which this jumper is connecting.</p>
	</div>';
	}    
	catch(PDOException $e){}
}


	echo '
	<script>
	$("#addStrandDestinationEqMake").change(function(event) {
	var selectedBuilding = $("#addStrandDestinationBuilding").val();
	var selectedLevel = $("#addStrandDestinationLevel").val();
	var selectedLocation = $("#addStrandDestinationLocation").val();
	var selectedMake = $("#addStrandDestinationEqMake").val();
	var loadStorageUnitsHereDiv = document.getElementById("workSpaceDestinationModel");
	$(loadStorageUnitsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=models&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentMake="+selectedMake+"&checkJumpers=yes");
	});
	</script>
	';

break;

/*=====================
=======================
=====================*/


//load dependent equipment stuff
case 'models':
if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentMake'])){

	//check jumpers
	if (isset($_GET['checkJumpers'])) {
		if (isset($_GET['portConnectsTo'])) {
			//echo "Selection: ".$_GET['portConnectsTo'];
		}
	}
	else{}

	//set make array
	$modelDetails=array();
	//get list from database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * FROM eq_model');
	$stmt->execute();
	foreach($stmt as $row) {
		$modelDetails[$row['model_UID']]['UID']=$row['model_UID'];
		$modelDetails[$row['model_UID']]['make']=$row['fk_make_UID'];
		$modelDetails[$row['model_UID']]['name']=$row['name'];
		$modelDetails[$row['model_UID']]['description']=$row['description'];
		$modelDetails[$row['model_UID']]['lastModified']=$row['lastModified'];
	}


	echo '
	<!-- Model Details -->
	<div class="form-group">
	<label><small>* </small>Equipment Model</label>
	<select class="form-control" id="addStrandDestinationEqModel" name="addStrandDestinationEqModel" required >';

	echo '
	<option selected disabled>Select Model...</option>';
	foreach ($modelDetails as $key => $value) {
		if ($value['make']==$_GET['parentMake']) {
			echo '<option value="'.$value['UID'].'">'.$value['name'].'</option>';
		}
		else{
			echo '<div class="alert alert-danger"><p>There are no models of the selected make.</p></div>';
		}
	}
	echo '</select>
	<p class="help-block">The model of the equipment to which this jumper is connecting.</p>
	</div>';
	}    
	catch(PDOException $e){}
}


	echo '
	<script>
	$("#addStrandDestinationEqModel").change(function(event) {
	var selectedBuilding = $("#addStrandDestinationBuilding").val();
	var selectedLevel = $("#addStrandDestinationLevel").val();
	var selectedLocation = $("#addStrandDestinationLocation").val();
	var selectedMake = $("#addStrandDestinationEqMake").val();
	var selectedModel = $("#addStrandDestinationEqModel").val();
	var loadDepartmentsHereDiv = document.getElementById("workSpaceDestinationDepartment");
	$(loadDepartmentsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=departments&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentMake="+selectedMake+"&parentModel="+selectedModel+"&checkJumpers=yes");
	});
	</script>
	';

break;

/*=====================
=======================
=====================*/


//load dependent equipment stuff
case 'departments':
if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentMake']) && isset($_GET['parentModel'])){

	//check jumpers
	if (isset($_GET['checkJumpers'])) {
		if (isset($_GET['portConnectsTo'])) {
			//echo "Selection: ".$_GET['portConnectsTo'];
		}
	}
	else{}

	//set make array
	$departmentDetails=array();
	//get list from database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT * FROM department');
	$stmt->execute();
	foreach($stmt as $row) {
		$departmentDetails[$row['department_UID']]['UID']=$row['department_UID'];
		$departmentDetails[$row['department_UID']]['name']=$row['name'];
	}


	echo '
	<!-- Department Details -->
	<div class="form-group">
	<label><small>* </small>Department (Owner)</label>
	<select class="form-control" id="addStrandDestinationDepartment" name="addStrandDestinationDepartment" required >';

	echo '
	<option selected disabled>Select Department...</option>';
	foreach ($departmentDetails as $key => $value) {
		echo '<option value="'.$value['UID'].'">'.$value['name'].'</option>';
		
	}
	echo '</select>
	<p class="help-block">The department that owns the equipment to which this jumper is connecting.</p>
	</div>';
	}    
	catch(PDOException $e){}
}

//New Equipment
echo '
	<!-- Equipment Name -->
	<div class="form-group">
	<label><small>* </small>Equipment Name</label>
	<input type="text" class="form-control" id="addStrandEquipmentName" name="addStrandEquipmentName" required placeholder="(Required field.)" />
	<p class="help-block">This field should contain a unique label for the equipment. This can be a hostname, IP address, MAC address, or otherwise as necessary.</p>
	</div>

	<!-- Equipment Description -->
	<div class="form-group">
	<label><small>* </small>Equipment Description</label>
	<textarea class="form-control" id="addStrandEquipmentDesc" name="addStrandEquipmentDesc" placeholder="(Not a Required field.)"></textarea>
	<p class="help-block">This description should contain as much detail as possible about what this equipment is and what purpose it serves.</p>
	</div>

	<!-- Jumper Notes -->
	<div class="form-group">
	<label><small>* </small>Jumper Notes</label>
	<textarea class="form-control" id="addStrandJumperDesc" name="addStrandJumperDesc"  placeholder="(Required field.)"></textarea>
	<p class="help-block">Jumper destination, port, or any special information should be recorded here.</p>
	</div>
';




break;

/*=====================
=======================
=====================*/

//This will load up the storage unit options when a location on a level is selected.
				//load dependent storageUnits
				case 'storageUnits':
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation'])) {
					if (isset($_GET['checkJumpers'])) {
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}
						//echo "test.";

						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {
						$stmt = $db->prepare('SELECT storageunit.label,storageunit.storageUnit_UID FROM storageunit INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.location_UID="'.$_GET['parentLocation'].'"');

						$stmt->execute();


						echo '
						<!-- Storage Unit Details -->
						<div class="form-group">
						<label><small>* </small>Destination Storage Unit</label>
						<select class="form-control" id="addStrandDestinationStorageUnit" name="addStrandDestinationStorageUnit" required >';

						echo '
						<option selected disabled>Select Storage Unit...</option>';

						//Store in multidimensional array
						foreach($stmt as $row) {
							$storageUnitDetails['parentStorageUnit_uid']=$row['storageUnit_UID'];
							$storageUnitDetails['parentStorageUnit_label']=$row['label'];
							echo '<option value="'.$storageUnitDetails['parentStorageUnit_uid'].'">'.$storageUnitDetails['parentStorageUnit_label'].'</option>';
						}

						//Get Total
						$totalStorageUnits=$stmt->rowCount();

						echo '</select>
						<p class="help-block">The Storage Unit where this strand is going.</p>
						</div>';
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}



					}

					if (isset($_GET['checkJumpers'])) {
					echo '
					<script>
					$("#addStrandDestinationStorageUnit").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var loadCabinetsHereDiv = document.getElementById("workSpaceDestinationCabinet");
					    $(loadCabinetsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=cabinets&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&checkJumpers=yes");
					});
					</script>

					';
					}
					elseif (isset($_GET['mapPanels'])) {
	echo '
					<script>
					$("#addStrandDestinationStorageUnit").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var loadCabinetsHereDiv = document.getElementById("workSpaceDestinationCabinet");
					    $(loadCabinetsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=cabinets&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&mapPanels=yes");
					});
					</script>

					';
					}
					else{
					
					echo '
					<script>
					$("#addStrandDestinationStorageUnit").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var loadCabinetsHereDiv = document.getElementById("workSpaceDestinationCabinet");
					    $(loadCabinetsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=cabinets&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit);
					});
					</script>

					';
					}

					

					# code...
					break;

//This will load up the Cabinet options when a storage Unit in a location is selected.
				//load dependent cabinets
				case 'cabinets':
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentStorageUnit'])) {
						//echo "test.";
					if (isset($_GET['checkJumpers'])) {
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}
						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {
						$stmt = $db->prepare('SELECT cabinet.label,cabinet.cabinet_UID FROM cabinet INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.location_UID="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'"');

						$stmt->execute();


						echo '
						<!-- Cabinet Details -->
						<div class="form-group">
						<label><small>* </small>Destination Cabinet</label>
						<select class="form-control" id="addStrandDestinationCabinet" name="addStrandDestinationCabinet" required >';

						echo '
						<option selected disabled>Select Cabinet...</option>';

						//Store in multidimensional array
						foreach($stmt as $row) {
							$cabinetDetails['parentCabinet_uid']=$row['cabinet_UID'];
							$cabinetDetails['parentCabinet_label']=$row['label'];
							echo '<option value="'.$cabinetDetails['parentCabinet_uid'].'">'.$cabinetDetails['parentCabinet_label'].'</option>';
						}

						//Get Total
						$totalCabinets=$stmt->rowCount();

						echo '</select>
						<p class="help-block">The Cabinet where this strand is going.</p>
						</div>';
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}

					}


					if (isset($_GET['checkJumpers'])) {
					
					echo '
					<script>
					$("#addStrandDestinationCabinet").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var selectedCabinet = $("#addStrandDestinationCabinet").val();
					    var loadPanelsHereDiv = document.getElementById("workSpaceDestinationPanel");
					    $(loadPanelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=panels&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&parentCabinet="+selectedCabinet+"&checkJumpers=yes");
					});
					</script>

					';

					}
					elseif (isset($_GET['mapPanels'])) {
					echo '
					<script>
					$("#addStrandDestinationCabinet").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var selectedCabinet = $("#addStrandDestinationCabinet").val();
					    var loadPanelsHereDiv = document.getElementById("workSpaceDestinationPanel");
					    $(loadPanelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=panels&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&parentCabinet="+selectedCabinet+"&mapPanels=yes");
					});
					</script>

					';
					}
					else{
					
					echo '
					<script>
					$("#addStrandDestinationCabinet").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var selectedCabinet = $("#addStrandDestinationCabinet").val();
					    var loadPanelsHereDiv = document.getElementById("workSpaceDestinationPanel");
					    $(loadPanelsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=panels&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&parentCabinet="+selectedCabinet);
					});
					</script>

					';

					}




					# code...
					break;

				//load dependent panels
				case 'panels':
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentStorageUnit']) && isset($_GET['parentCabinet'])) {

								if (isset($_GET['checkJumpers'])) {
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}

						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {

					if (isset($_GET['mapPanels'])) {
						

						$stmt = $db->prepare('SELECT panel.position, panel.panel_UID FROM panel INNER JOIN port ON panel.panel_UID=port.fk_panel_UID INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE cabinet.cabinet_UID="'.$_GET['parentCabinet'].'" AND (SELECT sum(case port.strandStatus when "available" then 1 else null end) FROM port WHERE fk_panel_UID=panel.panel_UID AND port.strandStatus="available") > 0 GROUP BY panel.position');
					}
					else{ 


						$stmt = $db->prepare('SELECT panel.position,panel.panel_UID FROM panel INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.location_UID="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'" AND cabinet.cabinet_UID="'.$_GET['parentCabinet'].'"');

					}
						

						$stmt->execute();


						echo '
						<!-- Panel Details -->
						<div class="form-group">
						<label><small>* </small>Destination Panel</label>
						<select class="form-control" id="addStrandDestinationPanel" name="addStrandDestinationPanel" required >';

						echo '
						<option selected disabled>Select Panel...</option>';

						//Store in multidimensional array
						foreach($stmt as $row) {
							$panelDetails['parentPanel_uid']=$row['panel_UID'];
							$panelDetails['parentPanel_position']=$row['position'];
							echo '<option value="'.$panelDetails['parentPanel_uid'].'">'.$panelDetails['parentPanel_position'].'</option>';
						}

						//Get Total
						$totalPanels=$stmt->rowCount();

						echo '</select>
						<p class="help-block">Only Panels which are actually installed are shown. </p>';

					if (isset($_GET['mapPanels'])) {
					echo '<p class="help-block">Only Panels which have no ports with active strands connected are shown. </p>';
					}

						echo '
						</div>';
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}

					
					}	
					if (isset($_GET['checkJumpers'])) {
					
					echo '
					<script>
					$("#addStrandDestinationPanel").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var selectedCabinet = $("#addStrandDestinationCabinet").val();
					    var selectedPanel = $("#addStrandDestinationPanel").val();
					    var loadPortsHereDiv = document.getElementById("workSpaceDestinationPort");
					    $(loadPortsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=ports&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&parentCabinet="+selectedCabinet+"&parentPanel="+selectedPanel+"&checkJumpers=yes");
					});
					</script>

					';

					}
					else{
					echo '
					<script>
					$("#addStrandDestinationPanel").change(function(event) {
					    var selectedBuilding = $("#addStrandDestinationBuilding").val();
					    var selectedLevel = $("#addStrandDestinationLevel").val();
					    var selectedLocation = $("#addStrandDestinationLocation").val();
					    var selectedStorageUnit = $("#addStrandDestinationStorageUnit").val();
					    var selectedCabinet = $("#addStrandDestinationCabinet").val();
					    var selectedPanel = $("#addStrandDestinationPanel").val();
					    var loadPortsHereDiv = document.getElementById("workSpaceDestinationPort");
					    $(loadPortsHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=ports&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit+"&parentCabinet="+selectedCabinet+"&parentPanel="+selectedPanel);
					});
					</script>

					';

					}



					# code...
					break;

				//load dependent ports
				case 'ports':
					if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel']) && isset($_GET['parentLocation']) && isset($_GET['parentStorageUnit']) && isset($_GET['parentCabinet']) && isset($_GET['parentPanel'])) {

					if (isset($_GET['checkJumpers'])) {
						//echo "Found CheckJumper.";
					}
					else{
						//echo "Did Not Find CheckJumper.";
					}
						//get a detailed list of each building in the database
						$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						try {

						if (isset($_GET['checkJumpers']) && $_GET['checkJumpers']=='yes') {
							$stmt = $db->prepare('SELECT port.number,port.port_UID FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.location_UID="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'" AND cabinet.cabinet_UID="'.$_GET['parentCabinet'].'" AND panel.panel_UID="'.$_GET['parentPanel'].'" AND port.jumperStatus="available"');
						}
						else{
							$stmt = $db->prepare('SELECT port.number,port.port_UID FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.location_UID="'.$_GET['parentLocation'].'" AND storageunit.storageUnit_UID="'.$_GET['parentStorageUnit'].'" AND cabinet.cabinet_UID="'.$_GET['parentCabinet'].'" AND panel.panel_UID="'.$_GET['parentPanel'].'" AND port.strandStatus="available"');
						}



						$stmt->execute();


						echo '
						<!-- Port Details -->
						<div class="form-group">
						<label><small>* </small>Destination Port</label>
						<select class="form-control" id="addStrandDestinationPort" name="addStrandDestinationPort" required >';

						echo '
						<option selected disabled>Select Port...</option>';

						//Store in multidimensional array
						foreach($stmt as $row) {
							$portDetails['parentPort_uid']=$row['port_UID'];
							$portDetails['parentPort_number']=$row['number'];
							echo '<option value="'.$portDetails['parentPort_uid'].'">'.$portDetails['parentPort_number'].'</option>';
						}

						//Get Total
						$totalPanels=$stmt->rowCount();

						echo '</select>
						<p class="help-block">Only ports that are available for connection to your strand (empty boot) are shown.</p>
						</div>';
					}    
					//Catch Errors (if errors)
					catch(PDOException $e){}
					}	

					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			
//Copy entire addStrand here and rename / revar it to addJumper



		//ADD PATH
		case 'addPath':
			//What are we populating
			switch ($_GET['load']) {
				//load dependent levels
				case 'levels':
					# code...
					break;

				//load dependent locations
				case 'locations':
					# code...
					break;

				//load dependent storageUnits
				case 'storageUnits':
					# code...
					break;

				//load dependent cabinets
				case 'cabinets':
					# code...
					break;

				//load dependent panels
				case 'panels':
					# code...
					break;

				//load dependent ports
				case 'ports':
					# code...
					break;

				//load dependent strands
				case 'strands':
					# code...
					break;

				//DEFAULT
				default:
		            echo '<div class="alert alert-danger">';
		            echo 'Error!<br />';
		            echo 'An unhandeled exception has occurred.';
		            echo '</div>';
					break;
			}
			break;
			

	



		//DEFAULT
		default:
			# code...
            echo '<div class="alert alert-danger">Error!<br />An unhandeled exception has occurred.</div>';
			break;
	}

}



?>






<?php
	/*
	switch ($_GET['load']) {
//========================================
//LOAD LEVELS
//========================================
case 'levels':
	if (isset($_GET['parentBuilding'])) {
	//get a detailed list of each building in the database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid="'.$_GET['parentBuilding'].'"');

	$stmt->execute();
	//Store in multidimensional array
	foreach($stmt as $row) {
	$buildingDetails['parentBuilding_uid']=$row['building_uid'];
	$buildingDetails['parentBuilding_level']=$row['levels'];
	}
	//Get Total
	$totalLevels=$stmt->rowCount();
	}    
	//Catch Errors (if errors)
	catch(PDOException $e){}
	}

	echo '
	<!-- Level Details -->
	<div class="form-group">
	<label><small>* </small>Level</label>
	<select class="form-control" id="createStorageUnitParentLevel" name="createStorageUnitParentLevel" required >';

	echo '
	<option selected disabled>Select level...</option>';

for ($iLevelSelectGen=($buildingDetails['parentBuilding_level']); $iLevelSelectGen >= 0; $iLevelSelectGen--) { 

echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
if ($iLevelSelectGen==0) {
echo ' (lowest possible)';
}
echo '</option>';

}
	echo '</select>
	<p class="help-block">The level where this location can be found.</p>
	</div>';
	break;
//========================================
//LOAD LOCATIONS
//========================================
case 'locations':
	if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
	//get a detailed list of each building in the database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');

	$stmt->execute();
	//Store in multidimensional array
	foreach($stmt as $row) {
	$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
	$locationDetails[$i]['parentBuilding_name']=$row['name'];
	$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
	$locationDetails[$i]['parentLocation_level']=$row['level'];
	$locationDetails[$i]['parentLocation_description']=$row['description'];
	}
	//Get Total
	$totalLocations=$stmt->rowCount();
	}    
	//Catch Errors (if errors)
	catch(PDOException $e){}
	
		echo '
	<!-- Level Details -->
	<div class="form-group">
	<label><small>* </small>Level</label>
	<select class="form-control" id="createStorageUnitParentLevel" name="createStorageUnitParentLevel" required >';

	echo '
	<option selected disabled>Select level...</option>';

for ($iLevelSelectGen=($buildingDetails['parentBuilding_level']); $iLevelSelectGen >= 0; $iLevelSelectGen--) { 

echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
if ($iLevelSelectGen==0) {
echo ' (lowest possible)';
}
echo '</option>';

}
	echo '</select>
	<p class="help-block">The level where this location can be found.</p>
	</div>';


?>

<?php

	}


	break;



	if (isset($_GET['parentBuilding']) && isset($_GET['parentLevel'])) {
	//get a detailed list of each building in the database
	$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try {
	$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level="'.$_GET['parentLevel'].'"');
	//$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['parentBuilding'].'" AND location.level='.$_GET['parentLevel']);

	$stmt->execute();
	//Store in multidimensional array
	$i=0;
	foreach($stmt as $row) {
	$i++;
	$locationDetails[$i]['parentBuilding_uid']=$row['building_uid'];
	$locationDetails[$i]['parentBuilding_name']=$row['name'];
	$locationDetails[$i]['parentLocation_uid']=$row['location_uid'];
	$locationDetails[$i]['parentLocation_level']=$row['level'];
	$locationDetails[$i]['parentLocation_description']=$row['description'];
	}
	//Get Total
	$totalLocations=$stmt->rowCount();
	}    
	//Catch Errors (if errors)
	catch(PDOException $e){}
	}

	echo '
	<!-- Location Details -->
	<div class="form-group">
	<label><small>* </small>Location</label>
	<select class="form-control" id="createStorageUnitParentLocation" name="createStorageUnitParentLocation" required >
	<option selected disabled>Select a location...</option>';

	for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
	echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
	}

	echo '
	</select>
                                <p class="help-block">The building level/floor where this location is found.<small>("Level 0 (lowest possible)")</small></p>
	</div>';
	
//========================================
//Default Action
//========================================
default:
	//display error to user
	echo '
	<div class="alert alert-danger">
	<h2>Error!</h2>
	<p>An unhandled exception has occurred.</p>
	</div>';
	break;
	}
}



 $("#dependentLevelsContainer").load("snippets/ajax/loadDependentSelect.php?load=levels&parentBuilding="+selectedBuilding);
*/
?>

