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
$thisPage='pathPlanner';
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
    <h3>Interbuilding Network Map</h3>
  </div>
  <div class="panel-body">

    <?php
$fileNodeContents='';
$fileNodeContents.='
{
"nodes": [
';
//$fileNodeContents.='  {"id": "ISU", "group": 1},';


foreach ($buildingDetails as $key => $value) {
if ($key==sizeof($buildingDetails)) {
$fileNodeContents.='  {"id": "'.$value['number'].' - '.$value['name'].'", "group": 1}
';
}
else{
$fileNodeContents.='  {"id": "'.$value['number'].' - '.$value['name'].'", "group": 1},
';
}
}



$fileNodeContents.='],
"links": [
';
$fileNodeContents.='  {"source": "050 - Library", "target": "005 - Business Administration", "value": 15},
';


//get all strands
/*

*/

//======================================
//Start Get Inter-Building Links w/Strength
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
  $stmt = $db->prepare("SELECT COUNT(strand.strand_UID) AS linkStrength, CONCAT(buildinga.number,' - ',buildinga.name) AS BUILDING_A_COMBINED, CONCAT(buildingb.number,' - ',buildingb.name) AS BUILDING_B_COMBINED, CONCAT(buildinga.building_UID,buildingb.building_UID) AS INTERBUILDING_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID GROUP BY INTERBUILDING_ID"); 
  $stmt->execute();
  $linkStrength=0;
  foreach($stmt as $row) {
  //find strength of link
  $linkStrength=2;
  
  if ($row['linkStrength']>=120) {
  $linkStrength=15;
  }
  else{
  $linkStrength=2;
  }
  
  //print to file
  $fileNodeContents.='  {"source": "'.$row['BUILDING_A_COMBINED'].'", "target": "'.$row['BUILDING_B_COMBINED'].'", "value": '.$linkStrength.'},';
  }
}
catch(PDOException $e){
}

//End Get Inter-Building Links w/Strength
//======================================



//get building number and name from each strand

//format as an array of connectionArray 
// [source] = "NUMBER - NAME"
// [destination] = "NUMBER - NAME"
// [linkStrength] = xx (1-20)







/*
//dummy links
foreach ($buildingDetails as $key => $value) {
  if ($key==sizeof($buildingDetails)) {
$fileNodeContents.='  {"source": "'.$value['number'].' - '.$value['name'].'", "target": "ISU", "value": 2}
';
  }
  else{
$fileNodeContents.='  {"source": "'.$value['number'].' - '.$value['name'].'", "target": "ISU", "value": 2},
';
  }
}
*/
$fileNodeContents=rtrim($fileNodeContents,',');








$fileNodeContents.=']
}
';

    ?>

<?php 
//echo '<pre>';
//echo $fileNodeContents;
//echo '</pre>';
$fileName='buildingMap.json';
//echo '<p>The file: <b>'.$fileName.'</b> will be written now...</p>';
$myfile = fopen('buildingMap.json', "w") or die("Unable to open file!");
fwrite($myfile, $fileNodeContents);
fclose($myfile);

?>

    <!-- Generated Graph -->
    <svg id="viz2" width="800" height="800"></svg>

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
<script src="https://d3js.org/d3.v4.min.js"></script>
<script type="text/javascript">

//Constants for the SVG
var svg = d3.select("svg#viz2"),
    width  = +svg.attr("width"),
    height = +svg.attr("height");

//Set up a dark background
svg.append("rect")
    .attr("width", "100%")
    .attr("height", "100%")
    .attr("fill", "#333");

//Set up a colour scale
var color = d3.scaleOrdinal(d3.schemeCategory20);

//Set up a force layout
var simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }))
    .force("charge", d3.forceManyBody())
    .force("center", d3.forceCenter(width / 2, height / 2));

//Load JSON data into nodes and do stuff with the data.
d3.json("buildingMap.json", function(error, graph) {
  
  //Check for errors.
  if (error) throw error;

  //Create a graph data structure out of the json data
  var node = svg.append("g")
    .attr("class", "nodes")
    .selectAll("circle")
    .data(graph.nodes)
    .enter().append("image")
      .attr("xlink:href", "../dist/img/building-front_white.svg")
      .attr("x", 200)
      .attr("y", 200)
      .attr("width", 16)
      .attr("height", 16)
      .style("cursor", "pointer")
      .call(d3.drag()
        .on("start",dragstarted)
        .on("drag",dragged)
        .on("end",dragended));

  //Do the same with the labels for the nodes 
  var labels = svg.append("g")
    .attr("class", "labels")
    .selectAll("text")
    .data(graph.nodes)
    .enter()
      .append("text")
      .attr("dx", function(d, i){return (i * 70) + 42})
      .attr("dy", height / 2 + 5)
      .style("cursor", "pointer")
      .text(function(d){return d.id;})
      .call(d3.drag()
        .on("start",dragstarted)
        .on("drag",dragged)
        .on("end",dragended));

  //Create all the line svgs between nodes.
  var link = svg.append("g")
    .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
    .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

  //Add name tooltips on hover.
  node.append("title")
    .text(function(d) { return d.id; });

  //Force direct nodes.
  simulation
    .nodes(graph.nodes)
    .on("tick", ticked);

  //Force direct links.
  simulation.force("link")
    .links(graph.links);

  //Nodes will repel eachother do they do not overlap.
  //We should make this apply to labels too asap.
  var padding = 5, radius=5;
  function collide(alpha) {
    var quadtree = d3.quadtree()
    .addAll(graph.nodes);
    return function(d) {
      var rb = 2*radius + padding,
          nx1 = d.x - rb,
          nx2 = d.x + rb,
          ny1 = d.y - rb,
          ny2 = d.y + rb;
      quadtree.visit(function(quad, x1, y1, x2, y2) {
        if (quad.point && (quad.point !== d)) {
          var x = d.x - quad.point.x,
              y = d.y - quad.point.y,
              l = Math.sqrt(x * x + y * y);
            if (l < rb) {
            l = (l - rb) / l * alpha;
            d.x -= x *= l;
            d.y -= y *= l;
            quad.point.x += x;
            quad.point.y += y;
          }
        }
        return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
      });
    };
  }

  //Initialize nodes, labels, and links.
  function ticked() {
    node
      .attr("x", function(d) { return (d.x - 10); })
      .attr("y", function(d) { return (d.y - 7); })
      .each(collide(0.5)); //added
    labels
      .attr("dx", function(d) { return (d.x + 10); })
      .attr("dy", function(d) { return (d.y + 5); })
      .each(collide(0.5)); //added
    link
      .attr("x1", function(d) { return d.source.x; })
      .attr("y1", function(d) { return d.source.y; })
      .attr("x2", function(d) { return d.target.x; })
      .attr("y2", function(d) { return d.target.y; });
  }
});

//start drag action
function dragstarted(d) {
  if (!d3.event.active) simulation.alphaTarget(0.3).restart();
  d.fx = d.x;
  d.fy = d.y;
}

//drag movement action
function dragged(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

//end drag action
function dragended(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}

</script>
?>
</body>
</html>
