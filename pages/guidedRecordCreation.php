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
<!DOCTYPE html>
<html lang="en">

<?php 
//require common php functions
include('snippets/commonFunctions.php');
//include snippet - shared head html
include('snippets/sharedHead.php');
//write page start snippet
$thisPage='grc';
//
//generatePageStartHtml($thisPage);
//writeHeader('FiDo Guided Record Creation');





//get totals buildings from database.
$totalBuildings=getTotalFromDatabase('buildings');

//get totals locations from database.
$totalLocations=getTotalFromDatabase('locations');

//get totals storageUnits from database.
$totalStorageUnits=getTotalFromDatabase('storageUnits');

//get totals cabinets from database.
$totalCabinets=getTotalFromDatabase('cabinets');

//get totals panels from database.
$totalPanels=getTotalFromDatabase('panels');

//get totals ports from database.
$totalPorts=getTotalFromDatabase('ports');

//get totals strands from database.
$totalStrands=getTotalFromDatabase('strands');

//get totals paths from database.
$totalPaths=getTotalFromDatabase('paths');


//create a building
if (isset($_POST['buildingAction']) && $_POST['buildingAction']=='create') {
    # code...
    //debugPrintData($_POST);
    //check for required fields
    if(isset($_POST['createBuildingName']) && isset($_POST['createBuildingNumber']) && isset($_POST['createBuildingLevels'])){
          $createBuildingName=$_POST['createBuildingName'];
        $createBuildingNumber=$_POST['createBuildingNumber'];
        $createBuildingLevels=$_POST['createBuildingLevels'];

        //optional notes
        if($_POST['createBuildingNotes']){
            $createBuildingNotes=$_POST['createBuildingNotes'];
        }else{$createBuildingNotes='';}

        //create a building here.
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        //insert building with note.
        if($createBuildingNotes){
            $stmt = $db->prepare('INSERT INTO building SET name=:name, number=:number, levels=:levels, notes=:notes');
            $stmt->bindParam(':name', $createBuildingName);
            $stmt->bindParam(':number', $createBuildingNumber);
            $stmt->bindParam(':levels', $createBuildingLevels);
            $stmt->bindParam(':notes', $createBuildingNotes);
        }
        //insert building without note
        else{
            $stmt = $db->prepare('INSERT INTO building SET name=:name, number=:number, levels=:levels');
            $stmt->bindParam(':name', $createBuildingName);
            $stmt->bindParam(':number', $createBuildingNumber);
            $stmt->bindParam(':levels', $createBuildingLevels);
        }
        //execute query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
    }
}


//create a location
if (isset($_POST['locationAction']) && $_POST['locationAction']=='create') {
    # code...
    //debugPrintData($_POST);
    # code...
    //debugPrintData($_POST);
    //check for required fields
    if(isset($_POST['createLocationParentBuilding']) && isset($_POST['createLocationParentLevel']) && isset($_POST['createLocationDesc'])){
        
        $createLocationParentBuilding=$_POST['createLocationParentBuilding'];
        $createLocationParentLevel=$_POST['createLocationParentLevel'];
        $createLocationDesc=$_POST['createLocationDesc'];

        //create a building here.
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('INSERT INTO location SET fk_building_UID=:building, level=:level, description=:description');
            $stmt->bindParam(':building', $createLocationParentBuilding);
            $stmt->bindParam(':level', $createLocationParentLevel);
            $stmt->bindParam(':description', $createLocationDesc);

        //execute query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
    }
}


//create a storageUnit
if (isset($_POST['storageUnitAction']) && $_POST['storageUnitAction']=='create') {
    # code...
    //debugPrintData($_POST);
    # code...
    //debugPrintData($_POST);
    //check for required fields
    if(isset($_POST['createStorageUnitParentBuilding']) && isset($_POST['createStorageUnitParentLevel']) && isset($_POST['createStorageUnitParentLocation']) && isset($_POST['createStorageUnitType']) && isset($_POST['createStorageUnitLabel'])){
        
        $createStorageUnitParentBuilding=$_POST['createStorageUnitParentBuilding'];
        $createStorageUnitParentLevel=$_POST['createStorageUnitParentLevel'];
        $createStorageUnitParentLocation=$_POST['createStorageUnitParentLocation'];
        $createStorageUnitType=$_POST['createStorageUnitType'];
        $createStorageUnitLabel=$_POST['createStorageUnitLabel'];

        //create a building here.
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('INSERT INTO storageunit SET fk_location_UID=:location, type=:type, label=:label');
            $stmt->bindParam(':location', $createStorageUnitParentLocation);
            $stmt->bindParam(':type', $createStorageUnitType);
            $stmt->bindParam(':label', $createStorageUnitLabel);

        //execute query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
    }
}

if (isset($_POST['cabinetAction']) && $_POST['cabinetAction']=='create') {

    if(isset($_POST['createCabinetParentBuilding']) && isset($_POST['createCabinetParentLevel']) && isset($_POST['createCabinetParentLocation']) && isset($_POST['createCabinetParentStorageUnit']) && isset($_POST['createCabinetLabel']) && isset($_POST['createCabinetPanelCapacity']) && isset($_POST['createCabinetNotes'])){


    $createCabinetParentBuilding=$_POST['createCabinetParentBuilding'];
    $createCabinetParentLevel=$_POST['createCabinetParentLevel'];
    $createCabinetParentLocation=$_POST['createCabinetParentLocation'];
    $createCabinetParentStorageUnit=$_POST['createCabinetParentStorageUnit'];
    $createCabinetPanelCapacity=$_POST['createCabinetPanelCapacity'];
    $createCabinetLabel=$_POST['createCabinetLabel'];
    $createCabinetNotes=$_POST['createCabinetNotes'];




    //create a building here.
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('INSERT INTO cabinet (fk_storageUnit_UID, label, panelCapacity, notes) values (:storageUnit,:label,:panelCapacity,:notes)');
    $stmt->bindParam(':storageUnit', $createCabinetParentStorageUnit);
    $stmt->bindParam(':label', $createCabinetLabel);
    $stmt->bindParam(':panelCapacity', $createCabinetPanelCapacity);
    $stmt->bindParam(':notes', $createCabinetNotes);

    //execute query
    $stmt->execute();

    //Save Total Rows
    //Report Success
    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
    //Report Error Message(s)
    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
    }

    }
}




/*=================*/
/*=================*/
/*=================*/
//
//  createPanelPosition
//  createPanelPortCapacity
//  createPanelPortType
//  


//create a panel
if (isset($_POST['panelAction']) && $_POST['panelAction']=='create') {
    # code...
    //debugPrintData($_POST);
    # code...
    //debugPrintData($_POST);
    //check for required fields
    if(isset($_POST['createPanelParentBuilding']) && isset($_POST['createPanelParentLevel']) && isset($_POST['createPanelParentLocation']) && isset($_POST['createPanelParentStorageUnit']) && isset($_POST['createPanelParentCabinet']) && isset($_POST['createPanelLabel']) && isset($_POST['createPanelNotes'])){
        
        $createPanelParentBuilding=$_POST['createPanelParentBuilding'];
        $createPanelParentLevel=$_POST['createPanelParentLevel'];
        $createPanelParentLocation=$_POST['createPanelParentLocation'];
        $createPanelParentStorageUnit=$_POST['createPanelParentStorageUnit'];
        $createPanelParentCabinet=$_POST['createPanelParentCabinet'];
        $createPanelLabel=$_POST['createPanelLabel'];
        $createPanelNotes=$_POST['createPanelNotes'];

        //create a building here.
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('INSERT INTO panel SET fk_cabinet_UID=:fk_cabinet_UID, portCapacity=:portCapacity, type=:type, notes=:notes');
            $stmt->bindParam(':fk_cabinet_UID', $createPanelParentCabinet);
            $stmt->bindParam(':portCapacity', $createLocationParentLevel);
            $stmt->bindParam(':portCapacity', $createLocationDesc);
            $stmt->bindParam(':type', $createLocationDesc);
            $stmt->bindParam(':notes', $createLocationDesc);

        //execute query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
    }
}

/*=================*/
/*=================*/
/*=================*/

?>

<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    $thisPage='guidedCrud';
    ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
        <?php include('snippets/sharedTopNav.php');?>
        <?php include('snippets/sharedSideNav.php');?>
    </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid animated fadeIn">
            
        <?php include('snippets/sharedBreadcrumbs.php');?>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Guided CRUD <small> (<b>C</b>reate. <b>R</b>ead. <b>U</b>pdate. <b>D</b>elete.)</small></h1>

                        
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

<?php

//=========================================================
if (isset($_POST['manageCabinetAction'])) {
//START manage cabinet actions
    switch ($_POST['manageCabinetAction']) {
        //remove a panel
        case 'removePanel':
            if (isset($_POST['removePanelUID'])) {
                $removePanelUID=$_POST['removePanelUID'];
                //echo "I should be removing a panel now.";
                //Get the parent cabinet information
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                $stmt = $db->prepare('DELETE from panel where panel_UID='.$removePanelUID);
                $stmt->execute();
                    echo "
                    <div class='alert alert-success'>
                    The panel in question has been removed.
                    </div>
                    ";
                }catch(PDOException $e){
                echo "
                    <div class='alert alert-danger'>
                    The panel in question was not removed.
                    </div>
                    ";
                }
            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;
        
        //add a panel
        case 'addPanel':
            if (isset($_POST['addPanelSlot'])) {
                //echo "I should be adding a panel now.";
                $addPanelSlot=$_POST['addPanelSlot'];
                $fk_cabinet_x=$_POST['fk_cabinet_x'];
                $panelType=$_POST['panelType'];
                $portCapacity=$_POST['portCapacity'];

                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        $stmt = $db->prepare('INSERT INTO panel SET fk_cabinet_UID=:fk_cabinet_UID, position=:position, portCapacity=:portCapacity, type=:type');
                        $stmt->bindParam(':fk_cabinet_UID', $fk_cabinet_x);
                        $stmt->bindParam(':position', $addPanelSlot);
                        $stmt->bindParam(':portCapacity', $portCapacity);
                        $stmt->bindParam(':type', $panelType);

                    //execute query
                    $stmt->execute();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Panel Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }





                //echo "I should be removing a panel now.";
                //Get the parent cabinet information

                /*
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                $stmt = $db->prepare('INSERT INTO panel ("fk_cabinet_UID","position","portCapacity","type") VALUES ('.$fk_cabinet_x.','.$addPanelSlot.','.$portCapacity.','.$panelType.')');
                $stmt->execute();
                    echo "
                    <div class='alert alert-success'>
                    The panel in question has been created.
                    </div>
                    ";
                }catch(PDOException $e){
                echo "
                    <div class='alert alert-danger'>
                    The panel in question was not created.
                    </div>
                    ";
                }

                */
            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;
    }
//END manage cabinet actions

echo "<a href='guidedRecordCreation.php' class='btn btn-default' >Back to Guided Record Management</a>";

}
//=========================================================
else{

?>


                <div class="row">
                    <div class="col-xs-12">
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">

    <li role="presentation" class="active">
        <a href="#manageCabinet" aria-controls="manageCabinet" role="tab" data-toggle="tab"><i class="fa fa-fw fa-eye"></i> Manage Cabinet Contents</a>
    </li>

    <li role="presentation">
        <a href="#building" aria-controls="building" role="tab" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> Building</a>
    </li>

    <li role="presentation">
        <a href="#location" aria-controls="location" role="tab" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> Location</a>
    </li>

    <li role="presentation">
        <a href="#storageUnit" aria-controls="storageUnit" role="tab" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> Storage Unit</a>
    </li>

    <li role="presentation">
        <a href="#cabinet" aria-controls="cabinet" role="tab" data-toggle="tab"><i class="fa fa-fw fa-plus"></i> Cabinet</a>
    </li>


    

<!--
    <li role="presentation">
        <a href="#panel" aria-controls="panel" role="tab" data-toggle="tab">Panel</a>
    </li>

    <li role="presentation">
        <a href="#port" aria-controls="port" role="tab" data-toggle="tab">Port</a>
    </li>

    <li role="presentation">
        <a href="#strand" aria-controls="strand" role="tab" data-toggle="tab">Strand</a>
    </li>

    <li role="presentation">
        <a href="#path" aria-controls="path" role="tab" data-toggle="tab">Path</a>
    </li>

-->

</ul>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

<div class="tab-content"><!-- ==================================================================== -->
<!-- START CREATE BUILDING STANDALONE FORM -->
    <div role="tabpanel" class="tab-pane fadeIn animated" id="building">
       
            <?php
            //get a detailed list of each building in the database
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            //Store in multidimensional array
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
            //Get Total
            $totalBuildings=$stmt->rowCount();
            }catch(PDOException $e){}
            echo '
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><i class="fa fa-fw fa-plus-circle"></i> Add Building</h1>
                    <div class="alert alert-info">
                    <p>Note:<br />This form won\'t be used very often. If you are using it try to provide as many details as possible (that includes the optional fields).
                    </p>
                    ';
                    /*
                    <p>
                    An easy way to get the latitude and longitude is to open up google maps or another gps-enabled application on a smartphone while in the building.
                    </p>
                    */
                    echo '
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-12">
                    <form role="form" action="guidedRecordCreation.php" method="POST"> 
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
                        </div>   
                        -->
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
                </div>
            </div>
            ';
            //========
            ?>
    </div>
<!-- END CREATE BUILDING STANDALONE FORM -->
<!-- ==================================================================== -->

<!-- ==================================================================== -->
<!-- START CREATE LOCATION STANDALONE FORM -->
    <div role="tabpanel" class="tab-pane fadeIn animated" id="location">
        <?php
        //========
            echo '
            <div class="row">
                <div class="col-xs-12">
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
                <div class="col-xs-12">
                <!-- START FORM HERE -->
                    <form role="form" action="guidedRecordCreation.php" method="POST"> 
                        <input type="hidden" id="locationAction" name="locationAction" value="create" />
                        <!-- Building Details -->
                        <div class="col-xs-12">
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

                        <div class="col-xs-12" id="createLocationDependentLevelsContainer" name="createLocationDependentLevelsContainer">
    <div class="alert alert-info">Select a building to be presented a list of possible levels.</div>
                            
                        </div>

                        <!--

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="text-muted">Address</label>
                                <input class="form-control" id="createBuildingAddress" name="createBuildingAddress">
                                <p class="help-block">Optional Field. <small>("1111 East Martin Luther King Jr Way")</small></p>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="text-muted">Long</label>
                                <input class="form-control" id="createBuildingLong" name="createBuildingLong">
                                <p class="help-block">Optional Field. <small>("42.863161")</small></p>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="text-muted">Lat</label>
                                <input class="form-control" id="createBuildingLat" name="createBuildingLat">
                                <p class="help-block">Optional Field. <small>("-112.431484")</small></p>
                            </div>
                        </div>        -->
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><small>* </small>Description</label>
                                <input class="form-control" id="createLocationDesc" name="createLocationDesc">
                                <p class="help-block">Try to be as specific as possible.</p>

                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-default">Submit</button>
                                <button type="reset" class="btn btn-lg btn-default">Reset</button>
                            </div>
                        </div>
                    </form>
                <!-- END FORM HERE -->
                </div>
            </div>
            ';
            //========
            ?>
    </div>
<!-- END CREATE LOCATION STANDALONE FORM -->
<!-- ==================================================================== -->

<!-- ==================================================================== -->
<!-- START CREATE STORAGEUNIT STANDALONE FORM -->
    <div role="tabpanel" class="tab-pane fadeIn animated" id="storageUnit">

   
<?php

  //get a detailed list of each building in the database
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            //Store in multidimensional array
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
            //Get Total
            $totalBuildings=$stmt->rowCount();
            }catch(PDOException $e){}





            //========
            echo '
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="page-header"><i class="fa fa-fw fa-plus-circle"></i> Storage-Unit<br />
                    <small>To An Existing Location</small></h1>
                    <div class="alert alert-info">
<p>
Note:<br />
"Storage Unit" refers to any housing unit that contains fiber cabinets / modules.<br />
This includes items such as wall-mounted units, rack units, free-standing cabinets, etc.
If the location you need isn\'t listed in the dropdown click "Location" above to add it.<br />
if you need a new storage-unit "Type" contact the system administrator.
</p>


                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->




            <div class="row">
                <div class="col-xs-12">
                <!-- START FORM HERE -->
                    <form role="form" action="guidedRecordCreation.php" method="POST"> 
                        <input type="hidden" id="storageUnitAction" name="storageUnitAction" value="create" />
                        <!-- Building Details -->
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><small>* </small>Building</label>
                                <select class="form-control" id="createStorageUnitParentBuilding" name="createStorageUnitParentBuilding" required >
                                    <option selected disabled>Select building...</option>';

                                    for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 

                                      echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';

                                    }

                                echo '
                                </select>
                                <p class="help-block">The parent building to this location. <small>(Where is this place?)</small></p>
                            </div>
                        </div>

<div class="col-xs-12" id="createStorageUnitDependentLevelsContainer" name="createStorageUnitDependentLevelsContainer"">
    <div class="alert alert-info">Select a building to be presented a list of possible levels.</div>
</div>


<div class="col-xs-12" id="createStorageUnitDependentLocationsContainer" name="createStorageUnitDependentLocationsContainer">
    <div class="alert alert-info">Select a building and level to be presented a list of possible locations.</div>
</div>


<div class="col-xs-12">
    <div class="form-group">
        <label><small>* </small>StorageUnit Type</label>
         <select class="form-control" id="createStorageUnitType" name="createStorageUnitType" required >
                <option selected disabled>Select a StorageUnit Type...</option>';

$totalStorageUnitTypes=sizeof($config['storageUnitTypes']);

        for ($iStorageUnitTypes=0; $iStorageUnitTypes < $totalStorageUnitTypes; $iStorageUnitTypes++) { 

          echo '<option value="'.$config['storageUnitTypes'][$iStorageUnitTypes].'">'.$config['storageUnitTypes'][$iStorageUnitTypes].'</option>';

        }

        echo '
        </select>
        <p class="help-block">Try to be as specific as possible.</p>
    </div>
</div>


<div class="col-xs-12">
<div class="alert alert-info">
        <p><b>StorageUnit Label</b> should follow the pattern below:<br />
        <b>xxx - yy - zz</b> where:
        <ul>
            <li><b>xxx</b> = The three digit parent building number (001-999)</li>
            <li><b>yy</b> = The two digit parent level / floor number (00-99)</li>
            <li><b>zz</b> = An arbitrary enumeration value for each individual storage unit on a floor (01-99)</li>
        </ul>
        </p>
</div>
<div class="alert alert-success">
        <p>From the selections you have made so far it looks like the label should start with:<br />
        
        <span id="selectedBuildingNumber" name="selectedBuildingNumber"></span> - <span id="selectedBuildingLevel" name="selectedBuildingLevel"></span> - <span class="text-danger" id="arbitIncValue" name="arbitIncValue">zz</span>
        </p>
</div>


        <div class="alert alert-danger">
            <p id="afterGeneratedLabelHint" name="afterGeneratedLabelHint">
            </p>
        </div>

</div>


<div class="col-xs-12">
    <div class="form-group">
        <label><small>* </small>Storage-Unit Label</label>
        <input class="form-control" id="createStorageUnitLabel" name="createStorageUnitLabel">
        <p class="help-block">Consult the chart above.</p>
    </div>
</div>



<div class="col-xs-12">
    <div class="form-group">
        <button type="submit" class="btn btn-lg btn-default">Submit</button>
        <button type="reset" class="btn btn-lg btn-default">Reset</button>
    </div>
</div>
                    </form>
                <!-- END FORM HERE -->

            ';
            //========
            ?>


            </div>
        </div>
    </div>
<!-- END CREATE STORAGEUNIT STANDALONE FORM -->
<!-- ==================================================================== -->

<!-- ==================================================================== -->
<!-- START CREATE CABINET STANDALONE FORM -->
    <div role="tabpanel" class="tab-pane fadeIn animated" id="cabinet">
        <div class="row">
            <div class="col-xs-12">
            <?php
            //========
            echo '
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="page-header"><i class="fa fa-fw fa-plus-circle"></i> Cabinet<br />
                    <small>To An Existing Storage-Unit</small></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
  <!-- START FORM HERE -->
<form role="form" action="guidedRecordCreation.php" method="POST"> 
    <input type="hidden" id="cabinetAction" name="cabinetAction" value="create" />
    <!-- Building Details -->
    <div class="col-xs-12">
        <div class="form-group">
            <label><small>* </small>Building</label>
            <select class="form-control" id="createCabinetParentBuilding" name="createCabinetParentBuilding" required >
                <option selected disabled>Select building...</option>';

                for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 

                  echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';

                }

            echo '
            </select>
            <p class="help-block">The parent building to this location. <small>(Where is this place?)</small></p>
        </div>
    </div>


    <div class="col-xs-12" id="createCabinetDependentLevelsContainer" name="createCabinetDependentLevelsContainer"">
        <div class="alert alert-info">Select a building to be presented a list of possible levels.</div>
    </div>


    <div class="col-xs-12" id="createCabinetDependentLocationsContainer" name="createCabinetDependentLocationsContainer">
        <div class="alert alert-info">Select a building and level to be presented a list of possible locations.</div>
    </div>

    <div class="col-xs-12" id="createCabinetDependentStorageUnitsContainer" name="createCabinetDependentStorageUnitsContainer">
        <div class="alert alert-info">Select a building,level, and location to be presented a list of possible Storage Units.</div>
    </div>



    <div class="col-xs-4">
        <div class="form-group">
            <label><small>* </small>Cabinet Label</label>
            <input required class="form-control" id="createCabinetLabel" name="createCabinetLabel">
            <p class="help-block">This is just 001,002,003...etc.</p>
        </div>
    </div>



    <div class="col-xs-4">
        <div class="form-group">
            <label><small>* </small>Panel Capacity</label>
            <input required class="form-control" id="createCabinetPanelCapacity" name="createCabinetPanelCapacity">
            <p class="help-block">How many panels (modules) can this cabinet hold?</p>
        </div>
    </div>



    <div class="col-xs-4">
        <div class="form-group">
            <label><small>* </small>Notes</label>
            <input required class="form-control" id="createCabinetNotes" name="createCabinetNotes">
            <p class="help-block">Need to record any notes for future reference?</p>
        </div>
    </div>



    <div class="col-xs-12">
        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-default">Submit</button>
            <button type="reset" class="btn btn-lg btn-default">Reset</button>
        </div>
    </div>

</form>
</div>
';



            ?>


            </div>
        </div>
    </div>
<!-- END CREATE CABINET STANDALONE FORM -->
<!-- ==================================================================== -->





<!-- ==================================================================== -->
<!-- START MANAGE CABINET PAGE -->
<div role="tabpanel" class="tab-pane fadeIn animated active" id="manageCabinet">
    <?php
    //========
    echo '
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header"><i class="fa fa-fw fa-edit"></i> Manage Cabinet Contents<br />
            <small>Manage panels, ports, and strands within a cabinet.</small></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->';
    ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
            include_once('manageCabinetContents.php');
            ?>
        </div>
    </div>
</div>
<!-- END MANAGE CABINET PAGE -->
<!-- ==================================================================== -->

<?php
}
?>

            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../bower_components/raphael/raphael-min.js"></script>
    <script src="../bower_components/morrisjs/morris.min.js"></script>

<script type="text/javascript">




//========================================================================
//Locations!
//========================================================================
$("#manageCabinetParentBuilding").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#manageCabinetParentBuilding").val();
    var targetDiv = document.getElementById( "levelBreadcrumbField" );
    //load the child levels based on user selections
    $(targetDiv).html('');
        $('#locationBreadcrumbField').html('');
        $('#storageUnitBreadcrumbField').html('');
        $('#cabinetBreadcrumbField').html('');
    $(targetDiv).addClass('animated fadeInDown');
    $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=levels&parentBuilding="+selectedBuilding);
    $(targetDiv).show("slow");
});
//========================================================================

//========================================================================
//Locations!
//========================================================================
//Change PARENT BUILDING field in the LOCATION FORM
//LOAD LEVELS
$("#createLocationParentBuilding").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createLocationParentBuilding").val();

    //load the child levels based on user selections
    $("#createLocationDependentLevelsContainer").html('');
    $("#createLocationDependentLevelsContainer").load("snippets/ajax/loadDependentSelect.php?form=addLocation&load=levels&parentBuilding="+selectedBuilding);
});
//========================================================================

//========================================================================
//Storage Units!
//========================================================================
//Change PARENT BUILDING field in the STORAGE UNIT FORM
//LOAD LEVELS
$("#createStorageUnitParentBuilding").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createStorageUnitParentBuilding").val();
    //alert(selectedBuilding);

    //load the child levels based on user selections
    $("#createStorageUnitDependentLevelsContainer").html('');
    $("#createStorageUnitDependentLevelsContainer").load("snippets/ajax/loadDependentSelect.php?form=addStorageUnit&load=levels&parentBuilding="+selectedBuilding);

    //tell user to select a level
    //load alert
    $("#createStorageUnitDependentLocationsContainer").html('<div class="alert alert-info">Please select a level.</div>');

});


//========================================================================
//Cabinets!
//========================================================================
//Change PARENT BUILDING field in the STORAGE UNIT FORM
//LOAD LEVELS
$("#createCabinetParentBuilding").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createCabinetParentBuilding").val();
    //alert(selectedBuilding);

    //load the child levels based on user selections
    $("#createCabinetDependentLevelsContainer").html('');
    $("#createCabinetDependentLevelsContainer").load("snippets/ajax/loadDependentSelect.php?form=addCabinet&load=levels&parentBuilding="+selectedBuilding);

    //tell user to select a level
    //load alert
    $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please select a level.</div>');

});





</script>

</body>

</html>
