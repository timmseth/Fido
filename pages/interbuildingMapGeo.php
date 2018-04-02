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
$thisPage='mapGeo';


//include('snippets/sharedHead.php');

//get totals from database.
$totalBuildings=getTotalFromDatabase('buildings');
$totalLocations=getTotalFromDatabase('locations');
$totalStorageUnits=getTotalFromDatabase('storageUnits');
$totalCabinets=getTotalFromDatabase('cabinets');
$totalPanels=getTotalFromDatabase('panels');
$totalPorts=getTotalFromDatabase('ports');
$totalStrands=getTotalFromDatabase('strands');
$totalPaths=getTotalFromDatabase('paths');

//======================================
//Start Load Building Array
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
		$buildingDetails[$i]['lastMod']=$row['lastmodified'];
	}
	$totalBuildings=$stmt->rowCount();
}
catch(PDOException $e){
}

//End Load Building Array
//======================================
?>

<body>
<div id="wrapper">
<?php
//$thisPage='mapGeo';
?>
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
<?php include('snippets/sharedTopNav.php');?>
<?php include('snippets/sharedSideNav.php');?>
</nav>
<div id="page-wrapper">
<div class="container-fluid animated fadeIn">
<?php include('snippets/sharedBreadcrumbs.php');?>
<!-- ======================================== -->
<!-- START Page Content -->
<!-- ======================================== -->

<div class="panel panel-default">
	<div class="panel-heading">
	    <h3>Interbuilding Network Map - Geo</h3>
	</div>
	<div class="panel-body">



                        <?php
                        generateAlert('danger','This page is currently in a non-functional state. This part of FiDo is under development.');
                        ?>


    <?php
    //write building name, latitude, and longitude to file
    $fileWriteContents='';

    ?>
    <iframe width="100%" sandbox="allow-popups allow-scripts allow-forms allow-same-origin" src="interbuildingMapGeoEmbed.php" marginwidth="0" marginheight="0" style="height:700px;" scrolling="no"></iframe>
	</div>
</div>

<!-- ======================================== -->
<!-- END Page Content -->
<!-- ======================================== -->
</div><!-- /.container-fluid -->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->

<!-- jQuery + Misc Scripts -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="../bower_components/raphael/raphael-min.js"></script>
</body>
?>
</html>
