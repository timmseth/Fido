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
<form>
<h2>Find the cabinet you wish to manipulate.</h2>

<?php
//if there are no get variables available...
if (!isset($_GET['b'])){
?>
    <ol class="breadcrumb">
        <li id="buildingBreadcrumbField" name="buildingBreadcrumbField">
    		<?php
    		echo '<div class="form-group">
    		<label>Building:</label>
    		<select class="form-control" id="manageCabinetParentBuilding" name="manageCabinetParentBuilding" required >
    		<option selected disabled>Select Building...</option>';

    		for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 

    		echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';
    		}
    		echo '
    		</select>
    		</div>';
    		?>
    	</li>

    	<li id="levelBreadcrumbField" name="levelBreadcrumbField">
    		<span class="text-warning">Level</span>
    	</li>

    	<li id="locationBreadcrumbField" name="locationBreadcrumbField">
    		<span class="text-warning">Location</span>
    	</li>

    	<li id="storageUnitBreadcrumbField" name="storageUnitBreadcrumbField">
    		<span class="text-warning">Storage Unit</span>
    	</li>

    	<li id="cabinetBreadcrumbField" name="cabinetBreadcrumbField">
    		<span class="text-warning">Cabinet Label</span>
    	</li>

    </ol>
<?php
}

//if there are get variables available...
else{
?>
    <ol class="breadcrumb">
    <?php
//+======================================+
//|  BUILDING                            |
//+======================================+ 
    if (isset($_GET['b'])) {
    ?>
         <li id="buildingBreadcrumbField" name="buildingBreadcrumbField">
            <?php
            echo '<div class="form-group">
            <label>Building:</label>
            <select class="form-control" id="manageCabinetParentBuilding" name="manageCabinetParentBuilding" required >
            <option disabled>Select Building...</option>';

            for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 
                if ($buildingDetails[$iBuildingSelectGen]['UID']==$_GET['b']) {
                    echo '<option selected value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';
                }
                else{
                    echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';

                }
            }
            echo '
            </select>
            </div>';
            ?>
        </li>
    <?php
    }

    //if a building has NOT been selected
    else{
    ?>
         <li id="buildingBreadcrumbField" name="buildingBreadcrumbField">
            <?php
            echo '<div class="form-group">
            <label>Building:</label>
            <select class="form-control" id="manageCabinetParentBuilding" name="manageCabinetParentBuilding" required >
            <option selected disabled>Select Building...</option>';

            for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 

            echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';
            }
            echo '
            </select>
            </div>';
            ?>
        </li>
    <?php
 }



//+======================================+
//|  LEVEL                               |
//+======================================+ 
if (isset($_GET['le'])){

    //get a detailed list of each building in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid=:thisBuilding');

    $stmt->bindParam(':thisBuilding', $_GET['b']);

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
    <li id="levelBreadcrumbField" name="levelBreadcrumbField">
        <div class="form-group">
        <label><small>* </small>Level</label>
        <select class="form-control" id="manageCabinetParentLevel" name="manageCabinetParentLevel" required >
        <option disabled>Select level...</option>';

        $levelsOneLess=$buildingDetails['parentBuilding_level'];
        $levelsOneLess--;

        for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
            if ($iLevelSelectGen==$_GET['le']) {
                echo '<option selected value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
                if ($iLevelSelectGen==0) {
                echo ' (lowest possible)';
                }
                echo '</option>';
            }
            else{
                echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
                if ($iLevelSelectGen==0) {
                echo ' (lowest possible)';
                }
                echo '</option>';
            }

        }

        echo '</select>
        </div>
    </li>';

}
//no level pre-selected
else{
    //get a detailed list of each building in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid=:thisBuilding');

    $stmt->bindParam(':thisBuilding', $_GET['b']);

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
    <li id="levelBreadcrumbField" name="levelBreadcrumbField">
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
        </div>
    </li>';
}



//+======================================+
//|  LOCATION                            |
//+======================================+ 
//location pre-selected
if (isset($_GET['b']) && isset($_GET['le']) && isset($_GET['lo'])) {
    //get a detailed list of each location in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid=:thisBuilding AND location.level=:thisLevel');
    $stmt->bindParam(':thisBuilding', $_GET['b']);
    $stmt->bindParam(':thisLevel', $_GET['le']);
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
    <li id="locationBreadcrumbField" name="locationBreadcrumbField">
    <div class="form-group">
    <label><small>* </small>Location</label>
    <select class="form-control" id="manageCabinetParentLocation" name="manageCabinetParentLocation" required >
    <option disabled>Select a Location...</option>';

    for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
        if ($locationDetails[$iLocationSelectGen]['parentLocation_uid']==$_GET['lo']) {
    echo '<option selected value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
        }
        else{
    echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';

        }
    }

    echo '
    </select>
    </div>
    </li>';

}
//no location pre-selected
else{
    //get a detailed list of each location in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name, location.level, location.description FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID WHERE building.building_uid="'.$_GET['b'].'" AND location.level="'.$_GET['le'].'"');
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
    <li id="locationBreadcrumbField" name="locationBreadcrumbField">
    <div class="form-group">
    <label><small>* </small>Location</label>
    <select class="form-control" id="manageCabinetParentLocation" name="manageCabinetParentLocation" required >
    <option selected disabled>Select a Location...</option>';

    for ($iLocationSelectGen=1; $iLocationSelectGen <= $totalLocations; $iLocationSelectGen++) { 
    echo '<option value="'.$locationDetails[$iLocationSelectGen]['parentLocation_uid'].'">'.$locationDetails[$iLocationSelectGen]['parentLocation_description'].'</option>';
    }

    echo '
    </select>
    </div>
    </li>';
}










//+======================================+
//|  STORAGE UNIT                        |
//+======================================+ 
//storageunit pre-selected
if (isset($_GET['b']) && isset($_GET['le']) && isset($_GET['lo']) && isset($_GET['sto'])) {
    //get a detailed list of each location in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID WHERE building.building_uid="'.$_GET['b'].'" AND location.level="'.$_GET['le'].'" AND location.location_uid="'.$_GET['lo'].'"');

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
    <li id="storageUnitBreadcrumbField" name="storageUnitBreadcrumbField">
    <div class="form-group">
    <label><small>* </small>Storage Unit</label>
    <select class="form-control" id="manageCabinetParentStorageUnit" name="manageCabinetParentStorageUnit" required >
    <option disabled>Select a Storage Unit...</option>';

    for ($iStorageUnitsSelectGen=1; $iStorageUnitsSelectGen <= $totalStorageUnits; $iStorageUnitsSelectGen++) { 

        if ($StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID']==$_GET['sto']) {
    echo '<option selected value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';
        }
        else{
    echo '<option value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';

        }
    }

    echo '
    </select>
    </div>
    </li>';
}
//no storageunit pre-selected
else{
    //get a detailed list of each location in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID WHERE building.building_uid="'.$_GET['b'].'" AND location.level="'.$_GET['le'].'" AND location.location_uid="'.$_GET['lo'].'"');

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
    <li id="storageUnitBreadcrumbField" name="storageUnitBreadcrumbField">
    <div class="form-group">
    <label><small>* </small>Storage Unit</label>
    <select class="form-control" id="manageCabinetParentStorageUnit" name="manageCabinetParentStorageUnit" required >
    <option selected disabled>Select a Storage Unit...</option>';

    for ($iStorageUnitsSelectGen=1; $iStorageUnitsSelectGen <= $totalStorageUnits; $iStorageUnitsSelectGen++) { 
    echo '<option value="'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_UID'].'">'.$StorageUnitDetails[$iStorageUnitsSelectGen]['parentStorageUnit_label'].'</option>';
    }

    echo '
    </select>
    </div>
    </li>';
}



//+======================================+
//|  STORAGE UNIT                        |
//+======================================+ 
//storageunit pre-selected
if (isset($_GET['b']) && isset($_GET['le']) && isset($_GET['lo']) && isset($_GET['sto']) && isset($_GET['cab'])) {
//get a detailed list of each location in the database
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label,cabinet.label AS "cabinetLabel",cabinet.cabinet_UID FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet ON cabinet.fk_storageUnit_UID=storageunit.storageUnit_UID WHERE building.building_uid="'.$_GET['b'].'" AND location.level="'.$_GET['le'].'" AND location.location_uid="'.$_GET['lo'].'" AND storageunit.storageUnit_UID="'.$_GET['sto'].'"');

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
<li id="cabinetBreadcrumbField" name="cabinetBreadcrumbField">
<div class="form-group">
<label><small>* </small>Cabinet</label>
<select class="form-control" id="manageCabinetUID" name="manageCabinetUID" required >
<option selected disabled>Select a Cabinet...</option>';

for ($iCabinetsSelectGen=1; $iCabinetsSelectGen <= $totalCabinets; $iCabinetsSelectGen++) { 
    if ($CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID']==$_GET['cab']) {
echo '<option selected value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';
    }
    else{
echo '<option value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';

    }
}

echo '
</select>
</div>
</li>';
}
//no storageunit pre-selected
else{
//get a detailed list of each location in the database
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('SELECT building.building_uid,location.location_uid,building.name,location.level,location.description,storageunit.storageUnit_UID,storageunit.label,cabinet.label AS "cabinetLabel",cabinet.cabinet_UID FROM building INNER JOIN location ON location.fk_building_UID=building.building_UID INNER JOIN storageunit ON storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet ON cabinet.fk_storageUnit_UID=storageunit.storageUnit_UID WHERE building.building_uid="'.$_GET['b'].'" AND location.level="'.$_GET['le'].'" AND location.location_uid="'.$_GET['lo'].'" AND storageunit.storageUnit_UID="'.$_GET['sto'].'"');

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
<li id="cabinetBreadcrumbField" name="cabinetBreadcrumbField">
<div class="form-group">
<label><small>* </small>Cabinet</label>
<select class="form-control" id="manageCabinetUID" name="manageCabinetUID" required >
<option selected disabled>Select a Cabinet...</option>';

for ($iCabinetsSelectGen=1; $iCabinetsSelectGen <= $totalCabinets; $iCabinetsSelectGen++) { 
echo '<option value="'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_UID'].'">'.$CabinetDetails[$iCabinetsSelectGen]['parentCabinet_label'].'</option>';
}

echo '
</select>
</div>
</li>';
}






?>




    </ol>
<?php  
}
?>










<?php

/**

    <ol class="breadcrumb">
//+======================================+
//|  BUILDING                            |
//+======================================+ 
if (isset($_GET['b'])){
?>
    <li id="buildingBreadcrumbField" name="buildingBreadcrumbField">
        <?php
        echo '
        <div class="form-group">
        <label>Building:</label>
        <select class="form-control" id="manageCabinetParentBuilding" name="manageCabinetParentBuilding" required >
        <option disabled>Select Building...</option>';
        for ($iBuildingSelectGen=1; $iBuildingSelectGen <= $totalBuildings; $iBuildingSelectGen++) { 
            if ($buildingDetails[$iBuildingSelectGen]['UID']==$_GET['b']) {
            echo '<option selected value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';
            }
            else{
            echo '<option value="'.$buildingDetails[$iBuildingSelectGen]['UID'].'">'.$buildingDetails[$iBuildingSelectGen]['number'].' - '.$buildingDetails[$iBuildingSelectGen]['name'].'</option>';
            }
        }
        echo '
        </select>
        </div>';
        ?>
    </li>
<?php
}
else{
?>

<?php
}
//+======================================+
//|  LEVEL                               |
//+======================================+ 
if (isset($_GET['le'])){

    //get a detailed list of each building in the database
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT building.building_uid,building.levels FROM building WHERE building.building_uid=:thisBuilding');

    $stmt->bindParam(':thisBuilding', $_GET['b']);

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
    <li id="levelBreadcrumbField" name="levelBreadcrumbField">
        <div class="form-group">
        <label><small>* </small>Level</label>
        <select class="form-control" id="manageCabinetParentLevel" name="manageCabinetParentLevel" required >
        <option disabled>Select level...</option>';

        $levelsOneLess=$buildingDetails['parentBuilding_level'];
        $levelsOneLess--;

        for ($iLevelSelectGen=$levelsOneLess; $iLevelSelectGen >= 0; $iLevelSelectGen--) {
            if ($iLevelSelectGen==$_GET['le']) {
                echo '<option selected value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
                if ($iLevelSelectGen==0) {
                echo ' (lowest possible)';
                }
                echo '</option>';
            }
            else{
                echo '<option value="'.$iLevelSelectGen.'">Level '.$iLevelSelectGen;
                if ($iLevelSelectGen==0) {
                echo ' (lowest possible)';
                }
                echo '</option>';
            }

        }

        echo '</select>
        </div>
    </li>';

}

else{
?>
        <!-- NO LEVEL -->
        <li id="levelBreadcrumbField" name="levelBreadcrumbField">
            <span class="text-warning">Level</span>
        </li>
<?php
}

//+======================================+
//|  LOCATION                            |
//+======================================+ 
if (isset($_GET['lo'])){
?>
        <!-- YES LOCATION -->
        <li id="locationBreadcrumbField" name="locationBreadcrumbField">
            <span class="text-warning">Location</span>
        </li>
<?php
}
else{
?>
        <!-- NO LOCATION -->
        <li id="locationBreadcrumbField" name="locationBreadcrumbField">
            <span class="text-warning">Location</span>
        </li>
<?php
}

//+======================================+
//|  STORAGE UNIT                        |
//+======================================+ 
if (isset($_GET['su'])){
?>
        <!-- YES STORAGEUNIT -->
        <li id="storageUnitBreadcrumbField" name="storageUnitBreadcrumbField">
            <span class="text-warning">Storage Unit</span>
        </li>
<?php
}
else{
?>
        <!-- NO STORAGEUNIT -->
        <li id="storageUnitBreadcrumbField" name="storageUnitBreadcrumbField">
            <span class="text-warning">Storage Unit</span>
        </li>
<?php
}

//+======================================+
//|  CABINET                             |
//+======================================+ 
if (isset($_GET['cab'])){
?>
        <!-- YES CABINET -->
        <li id="cabinetBreadcrumbField" name="cabinetBreadcrumbField">
            <span class="text-warning">Cabinet Label</span>
        </li>
<?php
}
else{
?>
        <!-- NO CABINET -->
        <li id="cabinetBreadcrumbField" name="cabinetBreadcrumbField">
            <span class="text-warning">Cabinet Label</span>
        </li>
<?php
}
?>





<?php
}
//END if there ARE get variables
?>
    </ol>
</form>

<div id="cabinetRepresentationDiv" name="cabinetRepresentationDiv">
	<div class="alert alert-info">
    <p>
		Upon making a selection this area will be reconfigured to show only eligible selections.
	</p>	
    </div>
</div>
*/
?>
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>


<script type="text/javascript">
            //change buildings
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

            //change levels
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

            //change locations
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

            //change storage units
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

            //change cabinet UIDs
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