<?php

//|=========================================|
//|  INTRODUCTION                           |
//|=========================================|
/*
    This config file is what makes FIDO work for your environment.
    It is automatically loaded in every page of the project.
    It is required and FIDO will NOT load if it is not available.

    Customize and manage the settings in this file based on your specific environment. As your environment changes, this file will hopefully allow FIDO to change with it.

    Ideally you will be able to change things (such as database credentials or a path to a specific resource) one time in this file rather than on every page of the project.

    System administrators MUST SECURE THIS FILE! Database credentials are in plain text and as such are only as secure as this file.

    See the readme (readme.php) for more information.
*/

//|=========================================|
//|  Configuration File Setup               |
//|=========================================|
/*
    In order for this file to do it's job, three things must happen:
    1. Move this file to a secure area and make sure it's permissions are locked down tight. 

    2. Find the FIDO/pages/configPointer.php file. Edit the variable "$path_to_config_file" should reference the location of "config.php" (This File.)

    3. Fill out the remainder of this file correctly.
*/

//|=========================================|
//|  Data Tables Setup                      |
//|=========================================|
/*
    Each page has individual control of it's data tables via an inline script.
    The DataTables jquery plugin is being used to handle tables in FIDO. 
    Check out datatables.net for documentation and customization options.
*/



//+=========================================+
//|  Database Credentials                   |
//+=========================================+

    //Error reporting.
    ini_set("error_reporting", "true");
    error_reporting(E_ALL & ~E_NOTICE);
    //error_reporting(E_ALL|E_STRCT);

    //DB Credentials
    $dbHost = "your_db_host";
    $dbName = "your_db_name";
    $dbUser = "your_db_user";
    $dbPassword = "your_db_password";

    //web directory
    $pathToWebDir='/var/www/html/projects/FiDo';

//+=========================================================+
//|  Configuration Array                                    |
//|  AKA:"The Holy Handgrenade of Configuration"            |
//|  An array with a bunch of your configuration settings   |
//|  in one variable. Try it in your next project.          |
//|  It works really well.                                  |
//+=========================================================+
    
    $config = array(
    //+==========================================+
    //|  SECTION 1 - Admin Contact Information   |
    //+==========================================+
        "administrator" => array(
            "name" => "admin_name",
            "phone" => "admin_number",
            "email" => "admin_email"
        ),
    //+==========================================+
    //|  SECTION 2 - Project Header Settings     |
    //+==========================================+
    //PROJECT HEADER - (and window title)
        "header" => array(
            "windowTitle" => "Fido | Fiber App.",
            "projectName" => "FiDo",
            "projectVersion" => "2.0",
            "projectTagline" => "<span class='linkColor'>Fi</span>ber <span class='linkColor'>Do</span>cumentation."
        ),
    //+==========================================+
    //|  SECTION 3 - Project Footer Settings     |
    //+==========================================+
        "footer" => array(
            "copyDate" => "&copy; ".date("Y"),
            "content" => " - org_name."
        ),
    //+==========================================+
    //|  SECTION 4 - StorageUnit Types           |
    //+==========================================+
        "storageUnitTypes" => array(
            "0" => "wallmount",
            "1" => "rack"
        ),
    //+==========================================+
    //|  SECTION 4.1 - Panel Types           |
    //+==========================================+
        "panelTypes" => array(
            "0" => "st",
            "1" => "sc",
            "2" => "lc",
            "3" => "mtrj",
            "default" => "st"
        ),
    //+==========================================+
    //|  SECTION 4.2 - Panel Sizes           |
    //+==========================================+
        "panelSizes" => array(
            "0" => "4",
            "1" => "6",
            "2" => "12"
        ),
    //+==========================================+
    //|  SECTION 5 - Strand Options           |
    //+==========================================+
        "strandOptions" => array(
            "mode" => array(
                "0" => "singlemode",
                "1" => "multimode"
            ),
            "wavelength" => array(
                "singlemode" => array(
                    "0" => "1310",  //0.4 loss per km
                    "1" => "1365",  //0.4 loss per km
                    "2" => "1550",  //0.3 loss per km
                    "3" => "1625"   //0.3 loss per km
                ),
                "multimode" => array(
                    "0" => "850",   //core size dependent
                    "1" => "1300"   //core size dependent
                )
            ),
            "coresize" => array(
                "singlemode" => array(
                    "0" => "8.3",  //dependent on wavelength
                ),
                "multimode" => array(
                    "0" => "50",    //3.0 loss per km
                    "1" => "62.5"   //3.4 loss per km
                )
            )
        ),
    //+==========================================+
    //|  SECTION 6 - Default Options           |
    //+==========================================+
        "defaultOptions" => array(
            "singlemodeWavelength" => '1550',
            "singlemodeCoreSize" => '8.3',
            "multimodeWavelength" => '1300',
            "multimodeCoreSize" => '50'
        )
    );





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
