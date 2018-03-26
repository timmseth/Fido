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
$thisPage='equipment';
//generatePageStartHtml($thisPage);
//writeHeader('FiDo Guided Record Creation');

//include snippet - shared head html
//include('snippets/sharedHead.php');

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

?>


<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    //$thisPage='equipment';
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
                    <h1 class="page-header">Equipment Manager</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->


<?php
//DEBUG - POST DATA
//echo "POST DATA:<br />";
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";


//MAKE ACTIONS
if (isset($_POST['makeAction'])) {
    $makeAction=$_POST['makeAction'];
    switch ($makeAction) {
        case 'add':
            if (isset($_POST['newMake']) && isset($_POST['newDescription'])){
                $newName=$_POST['newMake'];
                $newDescription=$_POST['newDescription'];
                //update make
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('INSERT INTO eq_make set name=:newName, description=:newDescription');
                    $stmt->bindParam(':newName', $newName);
                    $stmt->bindParam(':newDescription', $newDescription);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The new make ( '.$newName.') has been added to the database.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;

        case 'update':
            if (isset($_POST['oldName']) && isset($_POST['newName']) && isset($_POST['oldDesc']) && isset($_POST['newDesc']) && isset($_POST['makeUID'])) {
                //get old and new name for update
                $makeUID=$_POST['makeUID'];
                $oldName=$_POST['oldName'];
                $newName=$_POST['newName'];
                $oldDesc=$_POST['oldDesc'];
                $newDesc=$_POST['newDesc'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('UPDATE eq_make set name=:newName, description=:newDescription WHERE make_UID=:UID');
                    $stmt->bindParam(':newName', $newName);
                    $stmt->bindParam(':newDescription', $newDesc);
                    $stmt->bindParam(':UID', $makeUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The make name has been changed from '.$oldName.' to '.$newName.'. The make description has been changed from '.$oldDesc.' to '.$newDesc.'.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;

        case 'delete':
            if (isset($_POST['makeUID']) && isset($_POST['makeName'])) {
                //get new name for update
                $makeUID=$_POST['makeUID'];
                $makeName=$_POST['makeName'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('DELETE FROM eq_make WHERE make_UID=:makeUID');
                    $stmt->bindParam(':makeUID', $makeUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The make in question ('.$makeName.') has been removed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;

        default:
            break;
    }
}


//DEPARTMENT ACTIONS
if (isset($_POST['departmentAction'])) {
    $departmentAction=$_POST['departmentAction'];
    switch ($departmentAction) {
        case 'add':
            if (isset($_POST['newName'])) {
                //get new name for update
                $newName=$_POST['newName'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('INSERT INTO department set name=:newName');
                    $stmt->bindParam(':newName', $newName);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The new department ( '.$newName.') has been created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;
        case 'update':
            if (isset($_POST['oldName']) && isset($_POST['newName']) && isset($_POST['departmentUID'])) {
                //get old and new name for update
                $deptUID=$_POST['departmentUID'];
                $oldName=$_POST['oldName'];
                $newName=$_POST['newName'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('UPDATE department set name=:newName WHERE department_UID=:UID');
                    $stmt->bindParam(':newName', $newName);
                    $stmt->bindParam(':UID', $deptUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The department name has been changed from '.$oldName.' to '.$newName.'.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;
        case 'delete':
            if (isset($_POST['departmentUID']) && isset($_POST['departmentName'])) {
                //get new name for update
                $departmentUID=$_POST['departmentUID'];
                $departmentName=$_POST['departmentName'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('DELETE FROM department WHERE department_UID=:departmentUID');
                    $stmt->bindParam(':departmentUID', $departmentUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The department in question ('.$departmentName.') has been removed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;
        default:
            break;
    }
}



if (isset($_POST['modelAction'])) {
    $modelAction=$_POST['modelAction'];
    switch ($modelAction) {
        case 'add':
            if (isset($_POST['newMake']) && isset($_POST['newModel']) && isset($_POST['newDescription'])) {
                //get new name for update
                $newMake=$_POST['newMake'];
                $newName=$_POST['newModel'];
                $newDescription=$_POST['newDescription'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('INSERT INTO eq_model set fk_make_UID=:newMake,name=:newName,description=:newDescription');
                    $stmt->bindParam(':newMake', $newMake);
                    $stmt->bindParam(':newName', $newName);
                    $stmt->bindParam(':newDescription', $newDescription);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The new department ( '.$newName.') has been created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;

        case 'update':
            if (isset($_POST['oldName']) && isset($_POST['newName']) && isset($_POST['oldMake']) && isset($_POST['newMake']) && isset($_POST['oldDesc']) && isset($_POST['newDesc']) && isset($_POST['modelUID'])) {
                //get new name for update
                $newMake=$_POST['newMake'];
                $newMake=$_POST['newMake'];
                $newName=$_POST['oldName'];
                $newName=$_POST['newName'];
                $oldDesc=$_POST['oldDesc'];
                $newDesc=$_POST['newDesc'];
                $modelUID=$_POST['modelUID'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('UPDATE eq_model set fk_make_UID=:newMake,name=:newName,description=:newDescription WHERE model_UID=:modelUID');
                    $stmt->bindParam(':newMake', $newMake);
                    $stmt->bindParam(':newName', $newName);
                    $stmt->bindParam(':newDescription', $newDesc);
                    $stmt->bindParam(':modelUID', $modelUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The new model ( '.$newName.') has been created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;

        case 'delete':
            if (isset($_POST['modelUID']) && isset($_POST['modelName'])) {
                //get new name for update
                $modelUID=$_POST['modelUID'];
                $modelName=$_POST['modelName'];

                //update department
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    $stmt = $db->prepare('DELETE FROM eq_model WHERE model_UID=:modelUID');
                    $stmt->bindParam(':modelUID', $modelUID);
                    $stmt->execute();
                   
                    //show success message
                    echo '<div class="alert alert-success"><strong>Success!</strong>: The model in question ('.$modelName.') has been removed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                catch(PDOException $e){
                    //show danger message
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{
                //show danger message
                echo '<div class="alert alert-danger"><strong>Error!</strong>: Not enough information to proceed.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            break;
        
        default:
            # code...
            break;
    }
}



?>


            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#departments">Departments</a></li>
                        <li><a data-toggle="tab" href="#makes">Makes</a></li>
                        <li><a data-toggle="tab" href="#models">Models</a></li>
                        <li><a data-toggle="tab" href="#equipment">Equipment</a></li>
                    </ul>

                    <div class="tab-content">

                        <!-- Department Records -->
                        <div id="departments" class="tab-pane fade in active">
                            <?php
                            $makeDetails=array();
                            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            try {
                            $stmt = $db->prepare('SELECT * FROM department order by name asc');
                            $stmt->execute();
                            foreach($stmt as $row) {
                            $deptDetails[$row['department_UID']]['uid']=$row['department_UID'];
                            $deptDetails[$row['department_UID']]['name']=$row['name'];
                            }
                            $totalDepts=$stmt->rowCount();
                            //echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fa fa-fw fa-smile-o"></i>  '.$stmt->rowCount().' "Makes" Found. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            catch(PDOException $e){
                            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                      <span class="badge"><?php echo $totalDepts;?></span> Department Records</div>
                                      <div class="panel-body">         
                                        <table id="makeRecords" class="display table table-bordered table-striped" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Departments</th>
                                                    <th class="text-center" colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Departments</th>
                                                    <th class="text-center" colspan="2">Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php
                                            foreach ($deptDetails as $key => $value) {
                                                echo '
                                                <tr>
                                                    <form class="form-inline" method="POST">
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="oldName" id="oldName" value="'.$value["name"].'">
                                                            <input required type="text" class="form-control" name="newName" id="newName" value="'.$value["name"].'">
                                                        </div>
                                                    </td>
                                                    <td>
                                                            <input type="hidden" name="departmentAction" id="departmentAction" value="update">
                                                            <input type="hidden" name="departmentUID" id="departmentUID" value="'.$value["uid"].'">
                                                            <button type="submit" class="btn btn-block btn-info"><i class=\'fa fa-edit\'></i> Edit</button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <form method="POST">
                                                            <input type="hidden" name="departmentAction" id="departmentAction" value="delete">
                                                            <input type="hidden" name="departmentUID" id="departmentUID" value="'.$value["uid"].'">
                                                            <input type="hidden" name="departmentName" id="departmentName" value="'.$value["name"].'">
                                                            <button type="submit" class="btn btn-block btn-danger" onclick="return confirm(\'Are you sure? Deleting this department will also destroy any equipment belonging to this department. This is irreversible.\')"><i class=\'fa fa-times\'></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                ';
                                            }
                                            ?>

                                               <tr>
                                                    <form method="POST">
                                                        <td>
                                                            <div class="form-group">
                                                              <input required type="text" class="form-control" name="newName" id="newName" placeholder="<Department Name>">
                                                            </div>
                                                        </td>
                                                        <td colspan="2">
                                                            <input type="hidden" name="departmentAction" id="departmentAction" value="add">
                                                            <button type="submit" class="btn btn-block btn-primary"><i class='fa fa-plus'></i> Add Department</button>
                                                        </td>
                                                    </form>
                                                </tr>

                                            </tbody>
                                        </table>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Make Records -->
                        <div id="makes" class="tab-pane fade">
                            <?php
                            $makeDetails=array();
                            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            try {
                            $stmt = $db->prepare('SELECT * FROM eq_make order by name asc');
                            $stmt->execute();
                            foreach($stmt as $row) {
                            $makeDetails[$row['make_UID']]['uid']=$row['make_UID'];
                            $makeDetails[$row['make_UID']]['name']=$row['name'];
                            $makeDetails[$row['make_UID']]['description']=$row['description'];
                            $makeDetails[$row['make_UID']]['lastModified']=$row['lastModified'];
                            }
                            $totalMakes=$stmt->rowCount();
                            //echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fa fa-fw fa-smile-o"></i>  '.$stmt->rowCount().' "Makes" Found. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            catch(PDOException $e){
                            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                      <span class="badge"><?php echo $totalMakes;?></span> Make Records</div>
                                      <div class="panel-body">         
                                        <table id="makeRecords" class="display table table-bordered table-striped" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Make</th>
                                                    <th>Description</th>
                                                    <th>Last Modified</th>
                                                    <th colspan="2">Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Make</th>
                                                    <th>Description</th>
                                                    <th>Last Modified</th>
                                                    <th colspan="2">Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php
                                            foreach ($makeDetails as $key => $value) {
                                                echo '
                                                <tr>
                                                <form class="form-inline" method="POST">
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="oldName" id="oldName" value="'.$value["name"].'">
                                                            <input required type="text" class="form-control" name="newName" id="newName" value="'.$value["name"].'">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="oldDesc" id="oldDesc" value="'.$value["description"].'">
                                                            <textarea required class="form-control" name="newDesc" id="newDesc">'.$value["description"].'</textarea>
                                                        </div>
                                                    </td>
                                                    <td>'.$value["lastModified"].'</td>
                                                    <td>
                                                        <input type="hidden" name="makeAction" id="makeAction" value="update">
                                                        <input type="hidden" name="makeUID" id="makeUID" value="'.$value["uid"].'">
                                                        <button type="submit" class="btn btn-block btn-info"><i class=\'fa fa-edit\'></i> Edit</button>
                                                    </td>
                                                </form>



                                                    <td>
                                                        <form method="POST">
                                                            <input type="hidden" name="makeAction" id="makeAction" value="delete">
                                                            <input type="hidden" name="makeUID" id="makeUID" value="'.$value["uid"].'">
                                                            <input type="hidden" name="makeName" id="makeName" value="'.$value["name"].'">
                                                            <button type="submit" class="btn btn-block btn-danger" onclick="return confirm(\'Are you sure? Deleting this make will also destroy any equipment belonging to this department. This is irreversible.\')"><i class=\'fa fa-times\'></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                ';
                                            }
                                            ?>


                                                <tr>
                                                    <form method="POST">
                                                        <td>
                                                            <div class="form-group">
                                                              <input required type="text" class="form-control" name="newMake" id="newMake" placeholder="<Make>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                              <input required type="text" class="form-control" name="newDescription" id="newDescription" placeholder="<Description>">
                                                            </div>
                                                        </td>
                                                        <td colspan="3">
                                                            <input type="hidden" name="makeAction" id="makeAction" value="add">
                                                            <button type="submit" class="btn btn-block btn-primary"><i class='fa fa-plus'></i> Add Make</button>
                                                        </td>
                                                    </form>
                                                </tr>


                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Model Records -->
                        <div id="models" class="tab-pane fade">
                            <?php
                            $modelDetails=array();
                            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            try {
                            $stmt = $db->prepare('SELECT eq_model.fk_make_UID, eq_model.model_UID AS "model_UID",eq_make.name AS "make",eq_model.name AS "model",eq_model.description AS "desc",eq_model.lastModified AS "lastMod" FROM eq_model INNER JOIN eq_make ON eq_model.fk_make_UID=eq_make.make_UID order by eq_model.name asc');
                            $stmt->execute();
                            foreach($stmt as $row) {
                            $modelDetails[$row['model_UID']]['uid']=$row['model_UID'];
                            $modelDetails[$row['model_UID']]['makeUID']=$row['fk_make_UID'];
                            $modelDetails[$row['model_UID']]['make']=$row['make'];
                            $modelDetails[$row['model_UID']]['name']=$row['model'];
                            $modelDetails[$row['model_UID']]['description']=$row['desc'];
                            $modelDetails[$row['model_UID']]['lastModified']=$row['lastMod'];
                            }
                            $totalModels=$stmt->rowCount();
                            //echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fa fa-fw fa-smile-o"></i> '.$stmt->rowCount().' "Models" Found. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            catch(PDOException $e){
                            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                        <span class="badge"><?php echo $totalModels;?></span> Model Records</div>
                                        <div class="panel-body">         
                                            <table id="modelRecords" class="display table table-bordered table-striped" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Make</th>
                                                        <th>Model</th>
                                                        <th>Description</th>
                                                        <th>Last Modified</th>
                                                        <th colspan="2">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Make</th>
                                                        <th>Model</th>
                                                        <th>Description</th>
                                                        <th>Last Modified</th>
                                                        <th colspan="2">Actions</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                <?php
                                                foreach ($modelDetails as $key => $value) {
                                                    
                                                echo '
                                                <tr>
                                                <form class="form-inline" method="POST">
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" name="oldMake" id="oldMake" value="'.$value["makeUID"].'">
                                                            <select class="form-control" name="newMake" id="newMake" >
                                                            ';

                                                foreach ($makeDetails as $keyx => $valuex) {
                                                    if ($value["makeUID"]==$valuex["uid"]) {
                                                        echo '<option selected value="'.$valuex['uid'].'">'.$valuex['name'].'</option>';
                                                    }
                                                    else{
                                                        echo '<option value="'.$valuex['uid'].'">'.$valuex['name'].'</option>';
                                                    }
                                                }
                                                
                                                            echo '
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="oldName" id="oldName" value="'.$value["name"].'">

                                                            <input type="text" class="form-control" name="newName" id="newName" value="'.$value["name"].'">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="hidden" class="form-control" name="oldDesc" id="oldDesc" value="'.$value["description"].'">
                                                            <textarea required class="form-control" name="newDesc" id="newDesc">'.$value["description"].'</textarea>
                                                        </div>
                                                    </td>
                                                    <td>'.$value["lastModified"].'</td>
                                                    <td>
                                                        <input type="hidden" name="modelAction" id="modelAction" value="update">
                                                        <input type="hidden" name="modelUID" id="modelUID" value="'.$value["uid"].'">
                                                        <button type="submit" class="btn btn-block btn-info"><i class=\'fa fa-edit\'></i> Edit</button>
                                                    </td>
                                                </form>



                                                    <td>
                                                        <form method="POST">
                                                            <input type="hidden" name="modelAction" id="modelAction" value="delete">
                                                            <input type="hidden" name="modelUID" id="modelUID" value="'.$value["uid"].'">
                                                            <input type="hidden" name="modelName" id="modelName" value="'.$value["name"].'">
                                                            <button type="submit" class="btn btn-block btn-danger" onclick="return confirm(\'Are you sure? Deleting this model will also destroy any equipment that is categorized as this model. This is irreversible.\')"><i class=\'fa fa-times\'></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                ';



                                                    /*
                                                    echo '
                                                    <tr>
                                                        <td>'.$value["make"].'</td>
                                                        <td>'.$value["name"].'</td>
                                                        <td>'.$value["description"].'</td>
                                                        <td>'.$value["lastModified"].'</td>
                                                    <td>
                                                     <input type="submit" class="btn btn-danger btn-block" value="&times;">
                                                     </td>
                                                    </tr>
                                                    ';
                                                    */
                                                }
                                                ?>
<tr>
                                                    <form method="POST">
                                                        <td>
                                                            <div class="form-group">
                                                            <select class="form-control" name="newMake" id="newMake" >
                                                <?php
                                                foreach ($makeDetails as $keyx => $valuex) {
                                                    echo '<option value="'.$valuex['uid'].'">'.$valuex['name'].'</option>';
                                                }
                                                ?>            
                                                            </select>
                                                            </div>
                                                        </td>
                                                      <td>
                                                            <div class="form-group">
                                                              <input required type="text" class="form-control" name="newModel" id="newModel" placeholder="<Model>">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                              <input required type="text" class="form-control" name="newDescription" id="newDescription" placeholder="<Description>">
                                                            </div>
                                                        </td>

                                                        <td colspan="3">
                                                            <input type="hidden" name="modelAction" id="modelAction" value="add">
                                                            <button type="submit" class="btn btn-block btn-primary"><i class='fa fa-plus'></i> Add Model</button>
                                                        </td>
                                                    </form>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment Records -->
                        <div id="equipment" class="tab-pane fade">
                            <?php
                            $equipmentDetails=array();
                            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            try {
                            $stmt = $db->prepare('SELECT eq_make.name AS "makeName", eq_model.name AS "modelName", department.name AS "departmentName", location.description AS "locationDescription", equipment.equipment_UID,equipment.fk_department_UID,equipment.fk_location_UID,equipment.fk_make_UID,equipment.fk_model_UID,equipment.name,equipment.description,equipment.lastModified FROM equipment INNER JOIN eq_make on equipment.fk_make_UID=eq_make.make_UID INNER JOIN eq_model on equipment.fk_model_UID=eq_model.model_UID INNER JOIN department on equipment.fk_department_UID=department.department_UID INNER JOIN location on equipment.fk_location_UID=location.location_UID order by eq_make.name asc, eq_model.name asc, equipment.name asc');
                            $stmt->execute();
                            foreach($stmt as $row) {
                            $equipmentDetails[$row['equipment_UID']]['uid']=$row['equipment_UID'];
                            $equipmentDetails[$row['equipment_UID']]['department']=$row['departmentName'];
                            $equipmentDetails[$row['equipment_UID']]['location']=$row['locationDescription'];
                            $equipmentDetails[$row['equipment_UID']]['make']=$row['makeName'];
                            $equipmentDetails[$row['equipment_UID']]['model']=$row['modelName'];
                            $equipmentDetails[$row['equipment_UID']]['name']=$row['name'];
                            $equipmentDetails[$row['equipment_UID']]['description']=$row['description'];
                            $equipmentDetails[$row['equipment_UID']]['lastModified']=$row['lastModified'];
                            }
                            $totalEquipments=$stmt->rowCount();
                            //echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fa fa-fw fa-smile-o"></i> '.$stmt->rowCount().' "Pieces of Equipment" Found. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            catch(PDOException $e){
                            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                            }
                            ?>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="panel panel-primary">
                                      <div class="panel-heading">
                                      <span class="badge"><?php echo $totalEquipments;?></span> Equipment Records</div>
                                      <div class="panel-body">         
                                        <table id="equipmentRecords" class="display table table-bordered table-striped" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Location</th>
                                                    <th>Make</th>
                                                    <th>Model</th>
                                                    <th>Equipment</th>
                                                    <th>Description</th>
                                                    <th>Last Modified</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Location</th>
                                                    <th>Make</th>
                                                    <th>Model</th>
                                                    <th>Equipment</th>
                                                    <th>Description</th>
                                                    <th>Last Modified</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php
                                            foreach ($equipmentDetails as $key => $value) {
                                                echo '
                                                <tr>
                                                    <td>'.$value["department"].'</td>
                                                    <td>'.$value["location"].'</td>
                                                    <td>'.$value["make"].'</td>
                                                    <td>'.$value["model"].'</td>
                                                    <td>'.$value["name"].'</td>
                                                    <td>'.$value["description"].'</td>
                                                    <td>'.$value["lastModified"].'</td>
                                                </tr>
                                                ';
                                            }
                                            ?>
                                            </tbody>
                                        </table>

                                        <div class="alert alert-danger">
                                            <p>You cannot add equipment from this page.</p>
                                        </div>
                                        <div class="alert alert-info">
                                            <p>Equipment can be added at the manage cabinet contents page.</p>
                                        </div>

                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->





























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
    <!-- <script src="../bower_components/morrisjs/morris.min.js"></script> -->
    <!-- <script src="../js/morris-data.js"></script> -->

    <!-- DataTables JavaScript -->
    <script src="../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            //init make records datatable
            $('#makeRecords').DataTable( {
                "paging":   false,
                "ordering": false,
                "info":     false
            } );

            //init model records datatable
            $('#modelRecords').DataTable( {
                "paging":   false,
                "ordering": false,
                "info":     false
            } );

            //init equipment records datatable
            $('#equipmentRecords').DataTable( {
                "paging":   false,
                "ordering": false,
                "info":     false
            } );

        } );
    </script>


</body>

</html>
