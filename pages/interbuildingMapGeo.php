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
//NOTE THIS PAGE WILL FAIL TO DISPLAY MAP LINKS PROPERLY IF MYSQL ONLY_FULL_GROUP_BY IS TRUE
//You should be ale to fix this with this command:
//mysql > SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
//you can also set this value in phpmyadmin or persistent change in my.cnf (something like sql_mode = "STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION")
?>
<!DOCTYPE html>
<html lang="en">
<?php 
//require common php functions
include('snippets/commonFunctions.php');
//include snippet - shared head html
include('snippets/sharedHead.php');


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
$thisPage='mapGeo';
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
  <div class="panel-body" style="background:#333;min-height:1500px;">

<?php
generateAlert('info','This page will take into account all direct interbuilding connections and generate a d3.js overlay on top of a Google map using d3.js and the Google maps api.');

$inactiveNodesArray=array();
$activeNodesArray=array();
$nodesArray=array();
$translateNodeNameToNdx=array();
$revisedNodeArray=array();
$newNodeArray=array();
$nodesToDropArray=array();

foreach ($buildingDetails as $key => $value) {
  array_push($nodesArray, $value['number'].' - '.$value['name']);
  $translateNodeNameToNdx[$value['number'].' - '.$value['name']]=sizeof($nodesArray);
}

$linksArray=array();

//get all strands
//======================================
//Start Get Inter-Building Links w/Strength
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $stmt = $db->prepare("SELECT COUNT(strand.strand_UID) AS linkStrength, CONCAT(buildinga.number,' - ',buildinga.name) AS BUILDING_A_COMBINED, CONCAT(buildingb.number,' - ',buildingb.name) AS BUILDING_B_COMBINED, CONCAT(buildinga.building_UID,buildingb.building_UID) AS INTERBUILDING_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID GROUP BY INTERBUILDING_ID"); 
  $stmt->execute();
  $linkStrength=0;
  $incrementNdx=0;

  //Build Manual Link String For D3
  $manualLinkString='var links = [';
    foreach($stmt as $row) {
      $manualLinkString.='
        {"source": "'.$row['BUILDING_A_COMBINED'].'", "target": "'.$row['BUILDING_B_COMBINED'].'", "type": 1 },';
    }
  $manualLinkString=rtrim($manualLinkString,',');
  $manualLinkString.='];';
}
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Error:<br />'.$e.'.');
}

?>

<!-- Generated Graph -->
  <div id="map" style="height: 100%; width: 100%;"></div>
  <script src="https://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="../bower_components/d3/d3v3.js"></script>
  <!-- <script src="https://d3js.org/d3.v3.min.js"></script> -->
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
<script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>
<script src="../bower_components/raphael/raphael-min.js"></script>


<?php

echo '
<script type="text/javascript">

// Create the Google Map…
var map = new google.maps.Map(d3.select("#map").node(), {
  zoom: 17,
  // ISU preferred center 42.861506, -112.42779 @ zoom 17
  center: new google.maps.LatLng(42.861506, -112.42779),
  mapTypeId: google.maps.MapTypeId.HYBRID
});


// Load the station data. When the data comes back, create an overlay.
d3.json("data/stations.json", function(error, data) {
  if (error) throw error;

  var overlay = new google.maps.OverlayView();

  // Add the container when the overlay is added to the map.
  overlay.onAdd = function() {
    var layer = d3.select(this.getPanes().overlayLayer).append("div")
        .attr("class", "stations");

    // Draw each marker as a separate SVG element.
    // We could use a single SVG, but what size would it have?
    overlay.draw = function() {
      var projection = this.getProjection(),
          padding = 0;

      var marker = layer.selectAll("svg")
          .data(d3.entries(data))
          .each(transform) // update existing markers
        .enter().append("svg")
          .each(transform)
          .attr("class", "marker");

      // Add a circle.
      marker.append("circle")
          .attr("r", 4.5)
          .attr("cx", padding)
          .attr("cy", padding);

      // Add a label.
      marker.append("text")
          .attr("x", padding + 7)
          .attr("y", padding)
          .attr("dy", ".31em")
          .text(function(d) { return d.key; });

      function transform(d) {
        d = new google.maps.LatLng(d.value[1], d.value[0]);
        d = projection.fromLatLngToDivPixel(d);
        return d3.select(this)
            .style("left", "0px")
            .style("top", (d.y - padding) + "px");
      }
    };
  };

  // Bind our overlay to the map…
  overlay.setMap(map);

});


</script>
';

?>



<script type="text/javascript">
  /*
  //resize map after load
  
  


// Create the Google Map…
var map;

var mapOptions = "{  zoom: 16,  center: new google.maps.LatLng(42.861806, -112.425379),  mapTypeId: google.maps.MapTypeId.SATELLITE  }";

map = new google.maps.Map(d3.select("#map").node(),
            mapOptions);


// Load the station data. When the data comes back, create an overlay.
d3.json("data/stations.json", function(error, data) {
  if (error) throw error;

  var overlay = new google.maps.OverlayView();

  // Add the container when the overlay is added to the map.
  overlay.onAdd = function() {
    var layer = d3.select(this.getPanes().overlayLayer).append("div")
        .attr("class", "stations");

    // Draw each marker as a separate SVG element.
    // We could use a single SVG, but what size would it have?
    overlay.draw = function() {
      var projection = this.getProjection(),
          padding = 0;

      var marker = layer.selectAll("svg")
          .data(d3.entries(data))
          .each(transform) // update existing markers
        .enter().append("svg")
          .each(transform)
          .attr("class", "marker");

      // Add a circle.
      marker.append("circle")
          .attr("r", 4.5)
          .attr("cx", padding)
          .attr("cy", padding);

      // Add a label.
      marker.append("text")
          .attr("x", padding + 7)
          .attr("y", padding)
          .attr("dy", ".31em")
          .text(function(d) { return d.key; });

      function transform(d) {
        d = new google.maps.LatLng(d.value[1], d.value[0]);
        d = projection.fromLatLngToDivPixel(d);
        return d3.select(this)
            .style("left", (d.x - padding) + "px")
            .style("top", (d.y - padding) + "px");
      }
    };
  };
       
   
}

google.maps.event.addDomListener(window, "load", initialize);
google.maps.event.addDomListener(window, "resize", function() {
 var center = map.getCenter();
 google.maps.event.trigger(map, "resize");
 map.setCenter(center); 
});



});
*/
</script>
</body>
</html>
