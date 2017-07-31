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
//Everything here will happen BEFORE the top-level (building) list will be shown...
//Do NOT show the building list until after we check for pending changes...
$showTopLevelList=false;


/**
BUILDING PRELOAD
*/
//Building Actions
if($_POST['buildingAction']){
    //No specific building given, yet still a building action.
    if (!$_POST['buildingUID']){
        if($_POST['buildingAction']=='create'){
            //Create a new building mysql...  




        }
    }
    else{
        //Decide what to do with a selected building.
        switch($_POST['buildingAction']) {
            //Edit An Existing Building. (Get Edited Values From User.)
            case 'edit':
            //give user direction
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

            //echo "Edit building information here for update...";
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

            //Update An Existing Building. (Send Edited Values From User to Database.)
            case 'update':
                //check for building to update
                if ($_POST['buildingUID']) {
                    echo "got building UID";
                    //check for required fields
                    if ($_POST['buildingName'] && $_POST['buildingNumber'] && $_POST['buildingLevels']) {
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
                        //display dialog to redirect to main page.
                    }
                }
                break;

            //Delete An Existing Building.
            case 'delete':
                if ($_POST['buildingUID']) {
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
                }       
                break;

            //View An Existing Building. (default)
            default:
            //give user direction
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

            //Using "$_POST['buildingUID']"

            //get level count

            //get all locations on each level

            //get all storage units in each location

            //get all cabinets in each storage unit

            //generate full navigatable list of building contents.
            include('snippets/debug_mockSelectedBuilding.php');

                break;
        }//end switch
    }//end else
}//end (if / elseif / else)

/**
LOCATION PRELOAD
*/
//Do something to a location...
elseif($_POST['locationUID'] && $_POST['locationAction']) {

}

/**
STORAGE UNIT PRELOAD
*/
//Do something to a storage unit...
elseif($_POST['storageUnitUID'] && $_POST['storageUnitAction']) {

}

/**
CABINET PRELOAD
*/
//Do something to a cabinet...
elseif($_POST['cabinetUID'] && $_POST['cabinetAction']) {

}

/**
PORT PRELOAD
*/
//Do something to a port...
elseif($_POST['portUID'] && $_POST['portAction']) {

}

/**
No changes.
*/
//Now that we've checked for any changes to the list we can Show the building list...
else{
    $showBuildingList=true;
}
?>