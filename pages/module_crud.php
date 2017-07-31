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
include('./commonFunctions.php');
//include snippet - shared head html
include('snippets/sharedHead.php');
//write page start snippet
$thisPage='home';
generatePageStartHtml($thisPage);
writeHeader('Database<small> (<b>C</b>reate. <b>R</b>ead. <b>U</b>pdate. <b>D</b>elete.)</small>');


















/*======================
Pre-Load Cabinet Actions
======================*/
if(isset($_POST['cabinetAction']) && isset($_POST['cabinetUID'])){
switch ($_POST['cabinetAction']) {
//========================================================
//START DELETE CABINET
    case 'delete':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('DELETE FROM cabinet WHERE cabinet_UID=:cabinetUID');
            $stmt->bindParam(':cabinetUID', $_POST['cabinetUID']);
            $stmt->execute();
            writeAlert('success',' <i class="fi-results"></i> Cabinet '.$_POST['cabinetLabel'].' of Storage Unit '.$_POST['cabinetStorageUnitLabel'].' was successfully removed from the system.<br />(this includes all child elements including panels, ports, strands, and jumpers)');
        }
        catch(PDOException $e){
            writeAlert('danger',$e->getMessage());
        }
        break;
//END DELETE CABINET
//========================================================
//START EDIT CABINET
    case 'edit':
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
        <b>Edit the fields below</b> to set new values.<br />
        <b>Hit update record</b> to save your changes.
        </p><form action="module_crud.php" method="POST">
        <input type="hidden" name="cabinetUID" id="cabinetUID" value="'.$_POST['cabinetUID'].'" />
        <input type="hidden" name="cabinetAction" id="cabinetAction" value="update" />
        <div class="dataTable_wrapper">
        <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-updateBuilding">
        <thead>
        <tr>
        <th class="center" colspan="3">Cabinet Record</th>
        </tr>
        <tr>

        <th>Label</th>
        <th>Notes</th>
        <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr class="odd  dt-body-center">
        <!-- Type -->
        <td>
        <div class="form-group-lg">
        <input class="form-control" id="cabinetLabel" name="cabinetLabel" value="'.$_POST['cabinetLabel'].'">
        </div>
        </td>

        <!-- Notes -->
        <td>
        <div class="form-group-lg">
        <input class="form-control" id="cabinetNotes" name="cabinetNotes" value="'.$_POST['cabinetNotes'].'">
        </div>
        </td>

        <!-- Update Button -->
        <td>
        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-edit"></i> Update Record</button>

        </td>
        </tr>
        </tbody>
        </table>
        </form>
        </div>
        </div>
        </div>
        <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->';
        break;
//END EDIT CABINET
//========================================================
//START UPDATE CABINET
    case 'update':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('UPDATE cabinet SET label=:label, notes=:notes WHERE cabinet_UID=:cabinetUID');
            $stmt->bindParam(':cabinetUID', $_POST['cabinetUID']);
            $stmt->bindParam(':label', $_POST['cabinetLabel']);
            $stmt->bindParam(':notes', $_POST['cabinetNotes']);
            $stmt->execute();
            echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> The selected cabinet now has a label of "'.$_POST['cabinetLabel'].'" and the following note atttached:<br />"'.$_POST['cabinetNotes'].'". <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        catch(PDOException $e){
            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        include('snippets/crud_module/crud_module_cabinets.php');
        break;
//END UPDATE CABINET
//========================================================
//START DEFAULT CABINET
    default:
        break;
//END DEFAULT CABINET
}
}

//Storage-Unit Actions...
elseif(isset($_POST['storageUnitAction']) && isset($_POST['storageUnitUID'])){
switch ($_POST['storageUnitAction']) {

    //view the cabinets in this storage unit
    case 'view':
        include('snippets/crud_module/crud_module_cabinets.php');
        break;

    //delete this storage unit
    case 'delete':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $db->prepare('DELETE FROM storageunit WHERE storageUnit_UID=:storageUnitUID');
            $stmt->bindParam(':storageUnitUID', $_POST['storageUnitUID']);
            $stmt->execute();
            echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Deleted.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        catch(PDOException $e){
            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        break;

    //edit the details of this storage unit
    case 'edit':
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
        <b>Edit the fields below</b> to set new values.<br />
        <b>Hit update record</b> to save your changes.
        </p><form action="module_crud.php" method="POST">
        <input type="hidden" name="storageUnitUID" id="storageUnitUID" value="'.$_POST['storageUnitUID'].'" />
        <input type="hidden" name="storageUnitAction" id="storageUnitAction" value="update" />
        <div class="dataTable_wrapper">
        <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-updateBuilding">
        <thead>
        <tr>
        <th class="center" colspan="2">Storage-Unit Record</th>
        </tr>
        <tr>
        <th>Type</th>
        <th>Label</th>
        </tr>
        </thead>
        <tbody>
        <tr class="odd  dt-body-center">
        <!-- Type -->
        <td>
        <div class="form-group-lg">
        <input class="form-control" id="storageUnitType" name="storageUnitType" value="'.$_POST['storageUnitType'].'">
        </div>
        </td>
        ";
        echo "<!-- Label -->
        <td>
        <div class="form-group-lg">
        <input class="form-control" id="storageUnitLabel" name="storageUnitLabel" value="'.$_POST['storageUnitLabel'].'">
        </div>
        </td>
        <!-- Update Button -->
        <td>
        </td>
        </tr>
        </tbody>
        </table>
        <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-edit"></i> Update Record</button>
        </form>
        </div>
        </div>
        </div>
        <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->';
        break;

    //update this storage unit with the details from the edit page
    case 'update':
        echo '                
        <!-- User Direction -->
        <div class="row">
        <div class="col-lg-12">';
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('UPDATE storageunit SET type=:storageUnitType, label=:storageUnitLabel WHERE storageUnit_UID=:storageUnitUID');
        $stmt->bindParam(':storageUnitLabel', $_POST['storageUnitLabel']);
        $stmt->bindParam(':storageUnitType', $_POST['storageUnitType']);
        $stmt->bindParam(':storageUnitUID', $_POST['storageUnitUID']);
        $stmt->execute();
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        catch(PDOException $e){
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        echo '
        </div>
        <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->';

        //show list of storage units
        include('snippets/crud_module/crud_module_locations.php');


        break;



    default:
        # code...
        break;
}
}

//Location Actions...
elseif(isset($_POST['locationAction']) && isset($_POST['locationUID'])){
switch ($_POST['locationAction']) {
    //view the Storage Units in this location
    case 'view':
            include('snippets/crud_module/crud_module_storageUnits.php');
        break;

    //delete the selected location
    case 'delete':
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('DELETE FROM location WHERE fk_building_UID=:fk_building_UID AND location_UID=:location_UID AND description=:description AND level=:level LIMIT 1');
        $stmt->bindParam(':fk_building_UID', $_POST['parentBuildingUID']);
        $stmt->bindParam(':location_UID', $_POST['locationUID']);
        $stmt->bindParam(':description', $_POST['locationDescription']);
        $stmt->bindParam(':level', $_POST['locationLevel']);
        $stmt->execute();
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Deleted.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        catch(PDOException $e){
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        break;


    //edit the details of this location
    case 'edit':
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
                            <b>Edit the fields below</b> to set new values.<br />
                            <b>Hit update record</b> to save your changes.
                            </p>
                        </div>
                    </div>
                </div><!-- /.col-lg-12 -->
            </div><!-- /.row -->

        <!-- Record Edit Fields -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Edit Record
                        </div>
                        <div class="panel-body">

                            <!-- /.row -->
                        <form action="module_crud.php" method="POST">
                        <input type="hidden" name="locationUID" id="locationUID" value="'.$_POST['locationUID'].'"/>
                        <input type="hidden" name="parentBuildingUID" id="parentBuildingUID" value="'.$_POST['parentBuildingUID'].'"/>
                        <input type="hidden" name="locationAction" id="buildingAction" value="update" />

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-updateBuilding">
                                <thead>
                                    <tr>
                                        <th>Decription</th>
                                        <th>Level</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="odd  dt-body-center">
                                        <!-- Description -->
                                        <td>
                                            <div class="form-group-lg">
                                                <input class="form-control" id="locationDescription" name="locationDescription" value="'.$_POST['locationDescription'].'">
                                            </div>
                                        </td>
                                       <!-- Level -->
                                        <td>
                                            <div class="form-group-lg">
                                                <input class="form-control" id="locationLevel" name="locationLevel" value="'.$_POST['locationLevel'].'">
                                            </div>
                                        </td>
                                        <!-- Update Button -->
                                        <td>
                            <button type="submit" class="btn btn-primary btn-lg btn-block"><i class="fa fa-edit"></i> Update Record</button>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                        </div>
                    </div>
                </div><!-- /.col-lg-12 -->
            </div><!-- /.row -->';


        break;

    case 'update':
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        //Prepare Query
        $stmt = $db->prepare('UPDATE location SET description=:description, level=:level WHERE fk_building_UID=:fk_building_UID AND location_UID=:location_UID');

        //bind values (values are not interpolated into the query)
        $stmt->bindParam(':description', $_POST['locationDescription']);
        $stmt->bindParam(':level', $_POST['locationLevel']);
        $stmt->bindParam(':fk_building_UID', $_POST['parentBuildingUID']);
        $stmt->bindParam(':location_UID', $_POST['locationUID']);

        // Run Query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }

        //now show list of locations
        include('snippets/crud_module/crud_module_locations.php');

        break;


    default:
        # code...
        break;
}
}


elseif(isset($_POST['locationAction']) && $_POST['locationAction']=='create'){
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('INSERT into location(fk_building_UID,level,description) VALUES(:fk_building_UID,:level,:description)');
        $stmt->bindParam(':fk_building_UID', $_POST['createLocationParentBuilding']);
        $stmt->bindParam(':level', $_POST['createLocationParentLevel']);
        $stmt->bindParam(':description', $_POST['createLocationDesc']);
        $stmt->execute();
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Location Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        catch(PDOException $e){
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //now show list of locations
        include('snippets/crud_module/crud_module_locations.php');
}



//Building Actions...
elseif(isset($_POST['buildingAction']) && isset($_POST['buildingUID'])){
switch ($_POST['buildingAction']) {

    case 'view':
            include('snippets/crud_module/crud_module_locations.php');
        break;

    case 'delete':
        //echo "Delete building action here...";
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        //Prepare Query
        $stmt = $db->prepare('DELETE FROM building WHERE building_UID=:building_UID AND name=:buildingName AND number=:buildingNumber LIMIT 1');

        //bind values (values are not interpolated into the query)
        $stmt->bindParam(':building_UID', $_POST['buildingUID']);
        $stmt->bindParam(':buildingName', $_POST['buildingName']);
        $stmt->bindParam(':buildingNumber', $_POST['buildingNumber']);

        // Run Query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Deleted.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        break;

    case 'edit':
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
        <b>Edit the fields below</b> to set new values.<br />
        <b>Hit update record</b> to save your changes.
        </p>
        </div>
        </div>
        </div>
        <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->';
        echo "<form action='module_crud.php' method='POST'>
        <input type='hidden' name='preload' id='preload' value='1' />
        <input type='hidden' name='buildingUID' id='buildingUID' value='".$_POST['buildingUID']."' />
        <input type='hidden' name='buildingAction' id='buildingAction' value='update' />";
        echo '
        <div class="dataTable_wrapper">
        <table class="table table-striped table-bordered table-hover" id="dataTables-updateBuilding">
        <thead>
        <tr>
        <th class="center" colspan="6">Building Record</th>
        </tr>
        <tr>
        <th>Name</th>
        <th>Number</th>
        <th>Levels</th>
        <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        <tr class="odd  dt-body-center">';
        echo "<!-- Name -->
        <td>
        <div class='form-group-lg'>
        <input class='form-control' id='buildingName' name='buildingName' value='".$_POST['buildingName']."'>
        </div>
        </td>
        ";
        echo "<!-- Number -->
        <td>
        <div class='form-group-lg'>
        <input class='form-control' id='buildingNumber' name='buildingNumber' value='".$_POST['buildingNumber']."'>
        </div>
        </td>
        ";
        echo "<!-- Levels -->
        <td>
        <div class='form-group-lg'>
        <input class='form-control' id='buildingLevels' name='buildingLevels' value='".$_POST['buildingLevels']."'>
        </div>
        </td>
        ";
        echo "<!-- Notes -->
        <td>
        <div class='form-group-lg'>
        <textarea class='form-control' id='buildingNotes' name='buildingNotes'>".$_POST['buildingNotes']."</textarea>
        </div>
        </td>
        ";
        echo '<!-- Update Button -->
        <td>
        </td>
        </tr>
        </tbody>
        </table>';
        echo " <button type='submit' class='btn btn-primary btn-lg btn-block'><i class='fa fa-edit'></i> Update Record</button>";
        echo "</form>";
        break;

    case 'update':

        //check for optional fields
        //update with notes
        if ($_POST['buildingNotes']) {
        //echo "Run mysql job to update all columns here.";
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        //Prepare Query
        $stmt = $db->prepare('UPDATE building SET name=:name, number=:number, levels=:levels, notes=:notes WHERE building_UID=:building_UID');

        //bind values (values are not interpolated into the query)
        $stmt->bindParam(':name', $_POST['buildingName']);
        $stmt->bindParam(':number', $_POST['buildingNumber']);
        $stmt->bindParam(':levels', $_POST['buildingLevels']);
        $stmt->bindParam(':notes', $_POST['buildingNotes']);
        $stmt->bindParam(':building_UID', $_POST['buildingUID']);

        // Run Query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Rows Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }

        }
        //update without notes
        else{
        //echo "Run mysql job to update all columns except notes here.";
        //PDO Connect
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        //Prepare Query
        $stmt = $db->prepare('UPDATE building SET name=:name, number=:number, levels=:levels WHERE building_UID=:building_UID');

        //bind values (values are not interpolated into the query)
        $stmt->bindParam(':name', $_POST['buildingName']);
        $stmt->bindParam(':number', $_POST['buildingNumber']);
        $stmt->bindParam(':levels', $_POST['buildingLevels']);
        $stmt->bindParam(':building_UID', $_POST['buildingUID']);

        // Run Query
        $stmt->execute();

        //Save Total Rows
        //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Row Updated.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
        //Catch Errors (if errors)
        catch(PDOException $e){
        //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }



        }
        break;

    default:
        # code...
        break;
}
}

/*==================
DEFAULT TO BUILDINGS
==================*/
else{



    //create a new one?
    if($_POST['buildingAction']=='create'){

        debugPrintData($_POST);
        //check for required fields
        if($_POST['createBuildingName'] && $_POST['createBuildingNumber'] && $_POST['createBuildingLevels']){
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
































































/**
Building Actions
//Check for pending building actions...
if(isset($_POST['buildingAction'])){

        //decide on a specific building action...
        switch($_POST['buildingAction']) {
        //Edit An Existing Building. (Get Edited Values From User.)
        case 'edit':

        break;

        //Update An Existing Building. (Send Edited Values From User to Database.)
        case 'update':
        //check for building to update
        if ($_POST['buildingUID']) {
            //echo "got building UID";
            //check for required fields
            if ($_POST['buildingName'] && $_POST['buildingNumber'] && $_POST['buildingLevels']) {
                
            }
        }
        break;

        //Delete An Existing Building.
        case 'delete':
        if ($_POST['buildingUID']) {

        }       
        break;

        //View An Existing Building.
        case 'view':
            //generate full navigatable list of selected building contents.
        break;

        //no default action
        default:
            
        break;

        }//end switch
    }
*/



//}

/**
SHOW ALL BUILDINGS
*/
/*
if((!isset($_POST['locationAction']) && !isset($_POST['cabinetAction'])) && ( (!isset($_POST['buildingAction']) ) || ( ($_POST['buildingAction']!='edit') && ($_POST['buildingAction']!='view') ) ) ){
*/
    //Get a list of all current buildings in the database (store in array).
    //Initialize Multidimensional array to hold building list...
    $buildingDetails=array("UID" => array(),"number" => array(),"name" => array(),"levels" => array(),"notes" => array(),"lastMod" => array());
    //PDO Connect
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      try {
      //Prepare Query
      $stmt = $db->prepare('SELECT * FROM building order by number asc');
      // Run Query
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
      //$buildingDetails[$i]['status']=$row['status'];
      $buildingDetails[$i]['lastMod']=$row['lastmodified'];
      }
      //Save Total Rows
      $totalBuildings=$stmt->rowCount();
      //Report Success
        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Results Returned. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
      }
    //Catch Errors (if errors)
    catch(PDOException $e){
    //Report Error Message(s)
        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
    }
    //End Load Building Array



?>

        <!-- User Direction -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                User Direction
                            </div>
                            <div class="panel-body">
                                <p>
                                <b>Click on a building to drill down</b> and view or edit contents.<br />
                                <b>Search any column by typing in the search box</b> on the right. The table will update to reflect your search criteria automatically.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

    <!-- List of all top level (building) records. -->
    <div class="row">
        <div class="col-lg-12">

        <!-- display building list -->
        <div class="panel panel-primary">
        <div class="panel-heading">
        Building Records
        </div>
        <div class="panel-body">

                <!-- START create new building-->
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#createBuildingAccordion"><i class="fa fa-plus-square"></i> Create New Building</a>
                            </h4>
                        </div>
                        <div id="createBuildingAccordion" class="panel-collapse collapse">
                            <div class="panel-body">
                            <form role="form" action="module_crud.php" method="POST"> 
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
                    </div>
                </div>
                <!-- END create new building -->

            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-buildings">
                    <thead>
                        <tr>
                            <th class="center" colspan="7">Building Records</th>
                        </tr>
                        <tr>
                            
                            <th>UID</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Notes</th>
                            <th class="alert-danger" style="width:1%;">Delete</th>
                            <th class="center alert-info" style="width:1%;">Update</th>
                            <th class="center">Drill Down</th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            
                            <th>UID</th>
                            <th>Name</th>
                            <th>Number</th>
                            <th>Notes</th>
                            <th class="alert-danger" style="width:1%;">Delete</th>
                            <th class="center alert-info" style="width:1%;">Update</th>
                            <th class="center">Drill Down</th>

                        </tr>
                    </tfoot>
                    <tbody>
            <?php
            //Generate Full List Of Buildings.
            $i=1;while($i<=$totalBuildings){
                //print the row even/odd class:
                if ($i % 2 == 0) {
                    echo '<tr class="even ">';
                }
                else{
                    echo '<tr class="odd  dt-body-center">';
                }


                //print hidden UID
                echo "<td>".$buildingDetails[$i]['UID']."</td>";

                //print the rest of the row:
                echo "<td>".$buildingDetails[$i]['name']."</td>";
                echo "<td>".$buildingDetails[$i]['number']."</td>";
                echo "<td>".$buildingDetails[$i]['notes']."</td>";

                //delete button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$buildingDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='delete' />
                        <button type='submit' class='btn btn-outline btn-danger btn-lg btn-block' onclick=\"return confirm('Are you sure? Deleting this building will also destroy everything inside this building. This is irreversible.')\"><i class='fa fa-times'></i></button>
                        </form>
                </td>";


                //edit button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value='".$buildingDetails[$i]['UID']."' />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='edit' />
                        <button type='submit' class='btn btn-outline btn-info btn-lg btn-block'><i class='fa fa-edit'></i></button>
                    </form>
                </td>";


                //print drill down button
                echo "
                <td>
                    <form action='module_crud.php' method='POST'>
                        <input type='hidden' name='buildingUID' id='buildingUID' value=".$buildingDetails[$i]['UID']." />
                        <input type='hidden' name='buildingNumber' id='buildingNumber' value='".$buildingDetails[$i]['number']."' />
                        <input type='hidden' name='buildingName' id='buildingName' value='".$buildingDetails[$i]['name']."' />
                        <input type='hidden' name='buildingLevels' id='buildingLevels' value='".$buildingDetails[$i]['levels']."' />
                        <input type='hidden' name='buildingNotes' id='buildingNotes' value='".$buildingDetails[$i]['notes']."' />
                        <input type='hidden' name='buildingAction' id='buildingAction' value='view' />
                        <button type='submit' class='btn btn btn-primary btn-lg btn-block'><i class='fa fa-chevron-circle-right'></i></button>
                    </form>
                </td>";





                echo "</tr>";
                //increment counter
                $i++;
            }
            ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
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

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {

//BUILDINGS DATATABLE
    //init datatable and set options
    $('#dataTables-buildings').DataTable({
        searching: true,
        "search": {
            "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        "order": [[ 1, "asc" ]],
        "columnDefs": [
        {
            "targets": [0,3],
            "visible": false
        },
        {
            "targets": [4,5,6],
            "orderable": false
        }
    ]
    });
//END BUILDINGS DATATABLE

//LOCATIONS DATATABLE
    //init datatable and set options
    $('#dataTables-locations').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var elementHiddenBuildingPass = $('#elementHiddenBuildingPass').val();
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before('<tr class="group"><td colspan="5">Level '+group+' - <a target="_blank" href="module_mapping_interior.php?b='+elementHiddenBuildingPass+'&l='+group+'">view map</a></td></tr>');
        last = group;
        }});
        },
        "order": [[ 2, "desc" ]],
        "columnDefs": [
        {
        "targets": [0],
        "visible": false
        },
        {
        "targets": [3,4,5],
        "orderable": false
        }]
    });
// Order by the grouping
    $('#dataTables-locations tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
//END LOCATIONS DATATABLE


//STORAGEUNITS DATATABLE
    //init datatable and set options
    $('#dataTables-storageUnits').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var elementHiddenBuildingPass = $('#elementHiddenBuildingPass').val();
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before('<tr class="group"><td colspan="4">'+group.toUpperCase()+'</td></tr>');
        last = group;
        }});
        },
        "order": [[ 2, "desc" ]],
        "columnDefs": [
        {
        "targets": [0,2],
        "visible": false
        },
        {
        "targets": [3,4,5],
        "orderable": false
        }]
    });
// Order by the grouping
    $('#dataTables-storageUnits tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
//END STORAGEUNITS DATATABLE


//CABINETS DATATABLE
    //init datatable and set options
    $('#dataTables-cabinets').DataTable({
        searching: true,
        "search": {
        "smart": false
        },
        paging: false,
        stateSave: true,
        responsive: true,
        autoWidth: false,
        ordering: true,
        //set callback function to group locations by level
        "drawCallback": function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        api.column(2, {page:'current'} ).data().each( function ( group, i ) {
        if ( last !== group ) {
        $(rows).eq( i ).before(
            '<tr class="group"><td colspan="5">Storage Unit '+group+'</td></tr>'
        );
        last = group;
        }});
        },
        "order": [[ 2, "asc" ]],
        "columnDefs": [
        {
        "targets": [0,2],
        "visible": false
        },
        {
        "targets": [4,5,6],
        "orderable": false
        }]
    });
// Order by the grouping
    $('#dataTables-cabinets tbody').on( 'click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
    table.order( [ 2, 'desc' ] ).draw();
    }
    else {
    table.order( [ 2, 'asc' ] ).draw();
    }
});
//END CABINETS DATATABLE

//START panelDisplay DATATABLE
    //init datatable and set options
    $('.dataTables-portsDisplay').DataTable({
        searching: false,
        info: false,
        paging: false,
        ordering: false
    });
//END panelDisplay DATATABLE



});//END ON READY RUN SCRIPTS
</script>

</body>

</html>

?>







<?php
/*

    //check for a specific building...
    if($_POST['buildingUID']){

       echo "<input type='hidden' name='elementHiddenBuildingPass' id='elementHiddenBuildingPass' value='".$_POST['buildingUID']."' />";


//StorageUnit Actions
if(isset($_POST['storageUnitAction'])){
    if($_POST['storageUnitUID']){
        switch($_POST['storageUnitAction']) {
            //Delete An Existing Location.
            case 'delete':

            break;



            case 'edit':
                if ($_POST['storageUnitUID'] && $_POST['storageUnitType'] && $_POST['storageUnitLabel']) {
 
                }

            break;




            case 'update':
                if ($_POST['storageUnitUID']) {

                }
            break;

            if ($_POST['storageUnitUID'] && $_POST['storageUnitLabel'] && $_POST['storageUnitType']) {
              

            }
            else{
                echo "I do not have enough information to proceed.";
            }
        
        //View A StorageUnit's Contents. (default)
        case 'view':

        break;


        }
    }
}

*/




/**
Location Actions
*/
/*
if(isset($_POST['locationAction'])){
//given a location UID
    if($_POST['locationUID']){
    //decide on a specific building action...
    switch($_POST['locationAction']) {
        //Edit An Existing Location. (Get Edited Values From User.)
        case 'edit':
        
        break;
        //Update An Existing Building. (Send Edited Values From User to Database.)
        case 'update':
        //check for building to update
        if ($_POST['locationUID']) {
            //echo "got location UID";
            //check for required fields
            if ($_POST['locationDescription'] && $_POST['locationLevel']) {
  

            }
            //generate full navigatable list of selected building contents.
            include('snippets/crud_module/crud_module_locations.php');

        }
        break;

        //Delete An Existing Location.
        case 'delete':
        if ($_POST['locationUID']) {


        }       
        break;
        
        //View A Location's Contents. (default)
        case 'view':
            //generate full navigatable list of location contents.
            //include('snippets/locationContents.php');
            //include('snippets/crud_module/crud_module_storageUnits.php');
        break;

       
        default:
    }
}
}
*/

/**
start
*/
/*
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
<div class="col-lg-12">';



echo '
    <div class="panel panel-primary">
        <div class="panel-heading">
            Contents of Building '.$_POST['buildingNumber'].' - '.$_POST['buildingName'].'
        </div>
        <!-- .panel-heading -->
        <div class="panel-body">';
//START PANEL BODY


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
    <table class="table table-striped table-bordered table-hover table-responsive" id="dataTables-locations">
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
                //start level cell
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
                        <button type='submit' class='btn btn-danger' onclick=\"return confirm('Are you sure? Deleting this Location will also destroy everything inside this location. This is irreversible.')\"><i class='fa fa-times'></i></button>
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

/**
end
*/
/*
        break;
*/



//not given a location UID


/*
if($_POST['parentBuildingUID']){
//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//Prepare Query
$stmt = $db->prepare('SELECT name from building WHERE building_UID=:fk_building_UID');
//bind values (values are not interpolated into the query)
$stmt->bindParam(':fk_building_UID', $_POST['parentBuildingUID']);
// Run Query
$stmt->execute();
//Save Total Rows
foreach($stmt as $row) {
$thisBuildingName=$row['name'];
}
//Report Success

}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
} 
}

//get building name from UID for reporting.
*/

/*
<div class="row">
<div class="col-lg-12">
<a target="_blank" href="https://fido.netel.isu.edu/projects/FiDo/resources/fido_userguide_1_2017.pdf" class="btn btn-default">Help. I don't know what to do.</a> <br /><br />
</div>
</div>
*/

/*
//Cabinet Actions
if(isset($_POST['cabinetAction'])){
switch($_POST['cabinetAction']) {
case 'view':
include('snippets/crud_module/crud_module_ports.php');
break;

default:
echo "DEFAULT cabinet action output.";
break;
}
}
*/


?>
