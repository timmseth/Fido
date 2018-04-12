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
function writeAlert($alertType,$alertText){
	//switch between alert types
	switch ($alertType) {
		case 'success':
			echo '<div class="alert alert-success">';
			echo '<strong>Success!</strong> ';
			break;
		case 'info':
			echo '<div class="alert alert-info">';
			echo '<strong>Info!</strong> ';
			break;
		case 'warning':
			echo '<div class="alert alert-warning">';
			echo '<strong>Warning!</strong> ';
			break;
		case 'danger':
			echo '<div class="alert alert-danger">';
			echo '<strong>Error!</strong> ';
			break;
		default:
			break;
	}
	//write alert text & dismissal
	echo $alertText;
	echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	echo '</div>';
}

function writeHeader($textString){
    echo '<div class="row">';
    echo '<div class="col-lg-12">';
    echo '<h1 class="page-header">';
    echo $textString;
    echo '</h1>';
    echo '</div>';
    echo '<!-- /.col-lg-12 -->';
    echo '</div>';
    echo '<!-- /.row -->';
}

function generatePageStartHtml($pageName){
    $thisPage=$pageName;
	echo '
	<body>
	<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	'; 
	include('snippets/sharedTopNav.php');
	include('snippets/sharedSideNav.php');
	echo '</nav>
	<div id="page-wrapper">
	<div class="container-fluid animated fadeIn">';
	include('snippets/sharedBreadcrumbs.php');
}

function writeJumbo($header,$text,$btext,$bhref){
	echo '<div class="row">';
	echo '<div class="col-xs-12">';
	echo '<div class="jumbotron">';
	echo '<h1>'.$header.'</h1>';
	echo '<p>'.$text.'</p>';
	echo '<div class="col-xs-12">';
	echo '<p><a class="btn btn-primary btn-lg" href="'.$bhref.'" role="button">'.$btext.'</a></p>';
	echo '<br />';
	echo '</div>';
	echo '<br />';
	echo '</div>';
	echo '</div>';
	echo '</div>';                      
}
                                           
function getTotalElementCounts(){
	$totalBuildings=getTotalFromDatabase('buildings');
	$totalLocations=getTotalFromDatabase('locations');
	$totalStorageUnits=getTotalFromDatabase('storageUnits');
	$totalCabinets=getTotalFromDatabase('cabinets');
	$totalPanels=getTotalFromDatabase('panels');
	$totalPorts=getTotalFromDatabase('ports');
	$totalStrands=getTotalFromDatabase('strands');
	$totalJumpers=getTotalFromDatabase('jumpers');
}               

//Debug Print POST/GET data function
function getTotalFromDatabase($elementToCount){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $temp_foundRecords=0;
    switch($elementToCount){
        //COUNT BUILDINGS
        case 'buildings':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT locations
        case 'locations':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM location');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT storageUnits
        case 'storageUnits':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM storageunit');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT cabinets
        case 'cabinets':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM cabinet');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT panels
        case 'panels':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM panel');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT ports
        case 'ports':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM port');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT STRANDS
        case 'strands':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM strand');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT JUMPERS
        case 'jumpers':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM jumper');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT PATHS
        case 'paths':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM path');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        default:
            echo '<div class="alert alert-danger">Error!<br />An unhandeled exception has occurred.</div>';
            break;
    }
    return $temp_foundRecords;
}



//+=========================================+
//|  Shared PHP Functions                   |
//+=========================================+

//Debug Print POST/GET data function
function debugPrintData($input_dataType){
    echo "<pre>";
    print_r($input_dataType);
    echo "</pre>";
}

//Print Navigation
function printNavigation(){

}

//get breadcrumb details for a cabinet uid
function getBreadcrumbsForCabinet($cabinet){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $breadcrumbDetails=array();
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT building.building_UID AS "buildingUID", building.name AS "buildingName", location.level AS "buildingLevel", location.location_UID AS "locationUID", location.description AS "locationDesc", storageunit.storageUnit_UID AS "storageUnitUID", storageunit.label AS "storageUnitLabel", cabinet.cabinet_UID AS "cabinetUID", cabinet.label AS "cabinetLabel"FROM building INNER JOIN location ON building.building_UID=location.fk_building_UID INNER JOIN storageunit ON location.location_UID=storageunit.fk_location_UID INNER JOIN cabinet ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID WHERE cabinet.cabinet_UID=:thisCabinet');
        $stmt->bindParam(':thisCabinet', $cabinet);
        $stmt->execute();
        foreach($stmt as $row) {
            $breadcrumbDetails['buildingUID']=$row['buildingUID'];
            $breadcrumbDetails['buildingName']=$row['buildingName'];
            $breadcrumbDetails['buildingLevel']=$row['buildingLevel'];
            $breadcrumbDetails['locationUID']=$row['locationUID'];
            $breadcrumbDetails['locationDesc']=$row['locationDesc'];
            $breadcrumbDetails['storageUnitUID']=$row['storageUnitUID'];
            $breadcrumbDetails['storageUnitLabel']=$row['storageUnitLabel'];
            $breadcrumbDetails['cabinetUID']=$row['cabinetUID'];
            $breadcrumbDetails['cabinetLabel']=$row['cabinetLabel'];
        }
        }catch(PDOException $e){}
    //return the breadcrumbs array
    return $breadcrumbDetails;
}

//Get building details list
function getBuildingDetailsArray(){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $buildingDetails=array();
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
            $buildingDetails[$i]['status']=$row['status'];
            $buildingDetails[$i]['lastMod']=$row['lastmodified'];
        }
    }catch(PDOException $e){}
    return $buildingDetails;
}


//Get details array
function getDetailsFromDatabase($elementToSearch){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $detailsArray=array();
    switch ($elementToSearch) {
        case 'building':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            $i=0;
            foreach($stmt as $row) {
                $i++;
                $detailsArray[$i]['UID']=$row['building_UID'];
                $detailsArray[$i]['number']=$row['number'];
                $detailsArray[$i]['name']=$row['name'];
                $detailsArray[$i]['levels']=$row['levels'];
                $detailsArray[$i]['notes']=$row['notes'];
                $detailsArray[$i]['status']=$row['status'];
                $detailsArray[$i]['lastMod']=$row['lastmodified'];
            }
        }
        catch(PDOException $e){}
            break;
        
        default:
            break;
    }
    //return the details array
    return $detailsArray;
}


//Get Parents From Database
function getParentsFromDatabase($child,$childElement){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $temp_parentDetails=array();
    switch ($childElement) {
        case 'port':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT port.port_UID,panel.panel_UID,panel.position AS "panel_position", cabinet.label AS "cabinet_label", storageunit.label AS "storageunit_label", location.description AS "location_description", location.level AS "location_level", building.name AS "building_name", building.number AS "building_number" FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE port.port_UID=:child');
        $stmt->bindParam(':child', $child);
        $stmt->execute();
            foreach($stmt as $row) {
                $temp_parentDetails['child']=$child;
                $temp_parentDetails['panel_UID']=$row['panel_UID'];
                $temp_parentDetails['panel_position']=$row['panel_position'];
                $temp_parentDetails['cabinet_label']=$row['cabinet_label'];
                $temp_parentDetails['storageunit_label']=$row['storageunit_label'];
                $temp_parentDetails['location_description']=$row['location_description'];
                $temp_parentDetails['location_level']=$row['location_level'];
                $temp_parentDetails['building_name']=$row['building_name'];
                $temp_parentDetails['building_number']=$row['building_number'];
            }
        }catch(PDOException $e){}
            break;
        default:
            break;
    }
    return $temp_parentDetails;
}

/*
//Debug Print POST/GET data function
function getTotalFromDatabase($elementToCount){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $temp_foundRecords=0;
    switch($elementToCount){
        //COUNT BUILDINGS
        case 'buildings':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT locations
        case 'locations':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM location');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT storageUnits
        case 'storageUnits':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM storageunit');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT cabinets
        case 'cabinets':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM cabinet');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT panels
        case 'panels':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM panel');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT ports
        case 'ports':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM port');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT STRANDS
        case 'strands':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM strand');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT JUMPERS
        case 'jumpers':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM jumper');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT PATHS
        case 'paths':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM path');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        default:
            echo '<div class="alert alert-danger">Error!<br />An unhandeled exception has occurred.</div>';
            break;
    }
    return $temp_foundRecords;
}
*/

//Debug Print POST/GET data function
function getSomeFromDatabase($elementToCount,$columnToFilter,$filterBy){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $temp_foundRecords=0;
    switch($elementToCount){
    //COUNT within ports
        case 'ports':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM port WHERE '.$columnToFilter.'=:filterBy');
            $stmt->bindParam(':filterBy', $filterBy);
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
    //no selection
        default:
            echo '<div class="alert alert-danger">Error!<br />An unhandeled exception has occurred.</div>';
            break;
    }
    return $temp_foundRecords;
}

function generateAlert($alertType,$alertMsg){
    echo '<div class="alert alert-'.$alertType.' alert-dismissable">';
    //print dismiss icon
    echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
    //print alert type
    switch ($alertType) {
        case 'success':
            echo '<strong>Success!</strong> - ';
            break;
        case 'warning':
            echo '<strong>Warning!</strong> - ';
            break;
        case 'info':
            echo '<strong>Info!</strong> - ';
            break;
        case 'danger':
            echo '<strong>Alert!</strong> - ';
            break;
        default:
            # code...
            break;
    }
    //print alert text
    echo $alertMsg;
    //end the alert
    echo '</div>';
}

function getCabinetDetails($thisCabinetUID){
    //get global connection details
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    //get array to store details
    $tempCabinetDetails=array();
    
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        //prepare query
        $stmt = $db->prepare('SELECT * FROM cabinet where cabinet_UID=:cabinet_UID');
        //bind parameters
        $stmt->bindParam(':cabinet_UID', $thisCabinetUID);
        //execute query
        $stmt->execute();
        //store values
        foreach($stmt as $row) {
            $tempCabinetDetails['cabinet_UID']=$row['cabinet_UID'];
            $tempCabinetDetails['fk_storageUnit_UID']=$row['fk_storageUnit_UID'];
            $tempCabinetDetails['label']=$row['label'];
            $tempCabinetDetails['panelCapacity']=$row['panelCapacity'];
            $tempCabinetDetails['notes']=$row['notes'];
            $tempCabinetDetails['lastmodified']=$row['lastmodified'];
        }
        return $tempCabinetDetails;
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
        //Report Error Message(s)
        generateAlert('danger','Could not find the ports in panel B.<br />'.$e->getMessage());
    }
}

function updateCabinetDetails($thisCabinetUID,$thisCabinetLabel,$thisPanelCapacity,$thisCabinetNotes){
    //get global connection details
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    //get array to store details
    //$tempCabinetDetails=array();
    
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        //prepare query
        $stmt = $db->prepare('UPDATE cabinet SET label=:label, panelCapacity=:panelCapacity, notes=:notes WHERE cabinet_UID=:cabinet_UID');
        //bind parameters
        $stmt->bindParam(':label', $thisCabinetLabel);
        $stmt->bindParam(':panelCapacity', $thisPanelCapacity);
        $stmt->bindParam(':notes', $thisCabinetNotes);
        $stmt->bindParam(':cabinet_UID', $thisCabinetUID);
        //execute query
        $stmt->execute();
        //display user confirmation
        generateAlert('success','Cabinet Successfully Updated With The Following Details:<br /><strong>Label:</strong> '.$thisCabinetLabel.'<br /><strong>Panel Capacity:</strong>: '.$thisPanelCapacity.'<br /><strong>Notes:</strong>: '.$thisCabinetNotes.'');
        //return $tempCabinetDetails;
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
        //Report Error Message(s)
        generateAlert('danger','Could not find the ports in panel B.<br />'.$e->getMessage());
    }
}

function generateEditCabinetForm($formAction,$formMethod,$thisCabinetUID,$thisCabinetLabel,$thisPanelCapacity,$thisCabinetNotes){
echo '
<form method="'.$formMethod.'" action="'.$formAction.'">
    <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="updateCabinet" />

    <input type="hidden" name="thisCabinetUID" id="thisCabinetUID" value="'.$thisCabinetUID.'" />

    <div class="form-group">
        <label for="cabinetLabel">Cabinet Label:</label>
        <input class="form-control text-center" type="text" name="cabinetLabel" id="cabinetLabel" value="'.$thisCabinetLabel.'">
    </div>

    <div class="form-group">
        <label for="cabinetPanelCapacity">Panel Capacity:</label>
        <input class="form-control text-center" type="text" name="cabinetPanelCapacity" id="cabinetPanelCapacity" value="'.$thisPanelCapacity.'">
    </div>

    <div class="form-group">
        <label for="cabinetNotes">Cabinet Notes:</label>
        <input class="form-control text-center" type="text" name="cabinetNotes" id="cabinetNotes" value="'.$thisCabinetNotes.'">
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">Update Cabinet Details</button>
    </div>
</form>
';
}

function generateAddPanelForm($formAction,$formMethod,$panelNdx,$thisCabinetUID){
global $config;

echo '
<form method="'.$formMethod.'" action="'.$formAction.'" id="addPanelForm'.$panelNdx.'" name="addPanelForm'.$panelNdx.'"> 
';
?>
<input type="hidden" id="manageCabinetAction" name="manageCabinetAction" value="addPanel"> 
<?php 
echo '
<input type="hidden" id="addPanelSlot" name="addPanelSlot" value="'.$panelNdx.'"> 
<input type="hidden" id="fk_cabinet_x" name="fk_cabinet_x" value="'.$thisCabinetUID.'">

<div class="form-group"> 
    <label for="panelType">Panel Type</label> 
    <select id="panelType" name="panelType" class="form-control"> 
    <option selected value="'.$config['panelTypes']['default'].'">'.strtoupper($config['panelTypes']['default']).'</option>';

    foreach ($config['panelTypes'] as $key => $value) {
        if ($value!=$config['panelTypes']['default']) {
            echo '<option value="'.$value.'">'.strtoupper($value).'</option>';
        }
    }
    echo '
    </select> 
</div>

<div class="form-group"> 
<label for="portCapacity">Port Capacity</label> 
<input max="24" type="number" class="form-control" id="portCapacity" name="portCapacity"> 
</div> 

<button type="submit" class="text-right btn btn-primary">Submit</button> 

</form> ';
}

?>