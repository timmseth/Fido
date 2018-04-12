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
$thisPage='dashboard';
generatePageStartHtml($thisPage);
writeHeader('FiDo Dashboard');
//write jumbotron
writeJumbo('Guided Record Creation','Click the button below to create a new Building, Location, Storage Unit, Cabinet, Panel, Port, Strand, or Jumper.','Get Started!','guidedRecordCreation.php');
?>

<div class="row">

    <!-- Buildings -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-building fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total buildings from database.
                        $totalBuildings=getTotalFromDatabase('buildings');
                        echo $totalBuildings; 
                        ?>
                        </div>
                        <div>Buildings!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Locations -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-map-marker fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total locations from database.
                        $totalLocations=getTotalFromDatabase('locations');
                        echo $totalLocations; ?>
                        </div>
                        <div>Locations!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Storage Units -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-cube fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total storage units from database.
                        $totalStorageUnits=getTotalFromDatabase('storageUnits');
                        echo $totalStorageUnits; ?>
                        </div>
                        <div>Storage Units!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cabinets -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-server fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total cabinets from database.
                        $totalCabinets=getTotalFromDatabase('cabinets');
                        echo $totalCabinets; ?>
                        </div>
                        <div>Cabinets!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panels -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bars fa-rotate-90 fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total panels from database.
                        $totalPanels=getTotalFromDatabase('panels');
                        echo $totalPanels; ?>
                        </div>
                        <div>Panels!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ports -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-dot-circle-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total ports from database.
                        $totalPorts=getTotalFromDatabase('ports');
                        echo $totalPorts; ?>
                        </div>
                        <div>Ports!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strands -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-connectdevelop fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        //get total strands from database.
                        $totalStrands=getTotalFromDatabase('strands');
                        echo $totalStrands; ?>
                        </div>
                        <div>Strands!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumpers -->
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-exchange fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                        <?php 
                        $totalJumpers=getTotalFromDatabase('jumpers');
                        echo $totalJumpers; ?>
                        </div>
                        <div>Jumpers!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//generatePageStartHtml($thisPage);
echo '</div> <!-- /.container-fluid -->';
echo '</div> <!-- /#page-wrapper -->';
echo '</div> <!-- /#wrapper -->';
?>

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

<!-- Load Dependent Locations -->
<script type="text/javascript">
//anytime the building or level changes
$(".loadDependentLocations").change(function(event) {
    //get the user selections
    var selectedBuilding = $("#createStorageUnitParentBuilding").val();
    var selectedLevel = $("#createStorageUnitParentLevel").val();
    //load the child locations based on user selections
    $("#dependentLocationsContainer").html('');
    $("#dependentLocationsContainer").load("snippets/ajax/loadDependentSelect.php?load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel);
});
</script>
</body>
</html>
