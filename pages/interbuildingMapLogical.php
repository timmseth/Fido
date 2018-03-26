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
include('./commonFunctions.php');
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
  foreach($stmt as $row) {
  //find strength of link
  $linkStrength=2;
  
  if ($row['linkStrength']>=120) {
  $linkStrength=15;
  }
  else{
  $linkStrength=2;
  }
  

  $aNdxSearch=$row['BUILDING_A_COMBINED'];
  $bNdxSearch=$row['BUILDING_B_COMBINED'];

  //print to file
  $linksArray[$incrementNdx]['source']=$translateNodeNameToNdx[$aNdxSearch]-1;
  $linksArray[$incrementNdx]['destination']=$translateNodeNameToNdx[$bNdxSearch]-1;
  $linksArray[$incrementNdx]['value']='6';

  array_push($activeNodesArray, $translateNodeNameToNdx[$aNdxSearch]-1);
  array_push($activeNodesArray, $translateNodeNameToNdx[$bNdxSearch]-1);
  
  $incrementNdx++;
  }
}
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Error:<br />'.$e.'.');
}

//remove duplicates
$activeNodesArray=array_unique($activeNodesArray);


asort($activeNodesArray);

//print active list
//echo '<pre>';
/*
echo '<h3>Active Nodes</h3>';
  print_r($activeNodesArray);
  echo '<hr>';
echo '<h3>Active Nodes With Building</h3>';
*/
  foreach ($activeNodesArray as $keyA => $valueA) {
   /*
    echo $valueA;
    echo ' | ';
    echo $nodesArray[$valueA]."
";
*/
  }

//=======================================================
//FILTER THE NODE LIST BY ACTIVE NODES
//=======================================================
foreach ($activeNodesArray as $keyA => $valueA) {
//echo 'Check for :'.$valueA.'<br />';
  foreach ($nodesArray as $keyN => $valueN) {
    if ($valueA!=$valueN) {
      if(!in_array($valueA, $revisedNodeArray)){
        //echo 'Adding '.$valueA.' to revised node array.';
        array_push($revisedNodeArray, $valueA);
      }
    }
  }
}
/*
foreach ($inactiveNodesArray as $key => $value) {
  //unset($nodesArray[$value]);
  $revisedNodeArray[]=$value;
}
*/
//=======================================================
//=======================================================
//=======================================================

/*

echo '
<!-- Column 1 -->
<div class="col-xs-3">
  <div class="alert alert-info">';
echo '<pre>';
echo '<h3>All Nodes:</h3>';
print_r($nodesArray);
echo '</pre>';
  echo '
  </div>
</div>



<!-- Column 2 -->
<div class="col-xs-3">
  <div class="alert alert-info">';
echo '<pre>';
echo '<h3>Rev-Active Nodes:</h3>';
print_r($revisedNodeArray);
echo '</pre>';
  echo '
  </div>
</div>




<!-- Column 3 -->
<div class="col-xs-36">
  <div class="alert alert-info">';
*/

//echo '<pre>';
//echo '<h3>Node Action:</h3>';
foreach ($nodesArray as $keyN => $valueN) {
//newline
//echo '<br />';
//print values
//echo 'Analyze Key("'.$keyN.'") - Value("'.$valueN.'")';
//echo '<br />';
//make decision
$tempMatchFound=0;
foreach ($revisedNodeArray as $keyR => $valueR) {
  if ($keyN==$valueR) {
    $tempMatchFound=1;
  }
}
//display match result
if ($tempMatchFound) {
  //echo '<span class="label label-success">Keep.</span> - <span class="text-success">'.$keyN.' | '.$valueN.'</span>';
    //echo '<br />';
}
else{
//  echo '<span class="label label-danger">Drop.</span> - <span class="text-danger">'.$keyN.' | '.$valueN.'</span>';
    //echo '<br />';
  array_push($nodesToDropArray, $keyN);
}
}

/*
echo '<pre>';
print_r($nodesToDropArray);
echo '</pre>';

foreach ($nodesToDropArray as $key => $value) {
  unset($nodesArray[$value]);
}

echo '<pre>';
print_r($nodesArray);
echo '</pre>';

echo '<pre>';
print_r($linksArray);
echo '</pre>';
*/
//=======================================================
//=======================================================
//=======================================================

?>

<!-- Generated Graph -->
<svg id="viz2" width="100%" height="800"></svg>

<?php

//print_r($linksArray);


$tempConsoleString='var links = [';
foreach ($linksArray as $key => $value) {
  if ($value['source']<100 && $value['destination']<100) {
  $tempConsoleString.='
  {"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 },';
  }
}
$tempConsoleString=rtrim($tempConsoleString,',');
$tempConsoleString.='];';

echo '<pre>';
echo $tempConsoleString;
echo '</pre>';

?>
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
<script src="../bower_components/d3/d3.js"></script>
<script src="../bower_components/d3/d3.layout.js"></script>
<script src="../bower_components/d3/d3.geom.js"></script>

<?php
echo '
<script type="text/javascript">
//Constants for the SVG
var vis = d3.select("svg#viz2"),
    w  = 1200,
    h = 800;

//Set up a dark background
vis.append("svg:rect")
    .attr("width", w)
    .attr("height", h)
    .attr("fill", "#333");

//init variables
var labelDistance = 0;
';

echo '
var nodes = [];
var labelAnchors = [];
var links=[];
var labelAnchorLinks = [];
';

foreach ($nodesArray as $key => $value) {
  echo '
  labelAnchorLinks.push({
    source : '.$key.' * 2,
    target : '.$key.' * 2 + 1,
    value : 1
  });
  ';
}

for ($i=0; $i < sizeof($nodesArray); $i++) { 
  echo '
    var node = {
      label : "'.$nodesArray[$i].'"
    };
    nodes.push(node);
    labelAnchors.push({
      node : node
    });
    labelAnchors.push({
      node : node
    });
  ';
}

echo $tempConsoleString;

echo '
//$("#nodesDebug").append(JSON.stringify(nodes,null,4));
//$("#linksDebug").append(JSON.stringify(links,null,4));
//$("#labelAnchorsDebug").append(JSON.stringify(labelAnchors,null,4));
//$("#labelAnchorLinksDebug").append(JSON.stringify(labelAnchorLinks,null,4));

//create force simulation for nodes
var force = d3.layout.force()
.size([w, h])
.nodes(nodes)
.links(links)
.gravity(1)
.linkDistance(50)
.charge(-3000)
.linkStrength(function(x){return x.value * 10});

//start simulation for nodes
force.start();

//create force simulation for labels
var force2 = d3.layout.force()
.nodes(labelAnchors)
.links(labelAnchorLinks)
.gravity(0)
.linkDistance(0)
.linkStrength(8)
.charge(-100)
.size([w, h]);

//start simulation for labels
force2.start();

//create links between nodes
var link = vis.selectAll("line.link")
.data(links)
.enter()
.append("svg:line")
.attr("class", "link")
.style("stroke", "#CCC");

//create
var node = vis.selectAll("g.node")
.data(force.nodes())
.enter()
.append("svg:g")
.attr("class", "node");

//create circles 
node.append("svg:circle")
.attr("r", 5)
.style("fill", "#555")
.style("stroke", "#ff6633")
.style("stroke-width", 3);

//apply physics to nodes
node.call(force.drag);

var anchorLink = vis.selectAll("line.anchorLink")
.data(labelAnchorLinks)
.enter()
.append("svg:line")
.attr("class", "anchorLink")
.style("stroke", "#999");

var anchorNode = vis.selectAll("g.anchorNode")
.data(force2.nodes())
.enter()
.append("svg:g")
.attr("class", "anchorNode");

anchorNode.append("svg:circle")
.attr("r", 0)
.style("fill", "#ff6633");

anchorNode.append("svg:text")
.text(function(d, i) {return i % 2 == 0 ? "" : d.node.label})
.style("fill", "#fff").style("font-family", "Arial")
.style("font-size", 12);

var updateLink = function() {
  this.attr("x1", function(d) {
    return d.source.x;
  }).attr("y1", function(d) {
    return d.source.y;
  }).attr("x2", function(d) {
    return d.target.x;
  }).attr("y2", function(d) {
    return d.target.y;
  });
}

var updateNode = function() {
  this.attr("transform", function(d) {
    return "translate(" + d.x + "," + d.y + ")";
    alert(d.x);
    alert(d.y);
  });

}

force.on("tick", function() {

  force2.start();

  node.call(updateNode);

  anchorNode.each(function(d, i) {
    if(i % 2 == 0) {
      d.x = d.node.x;
      d.y = d.node.y;
    } else {
      var b = this.childNodes[1].getBBox();
      var diffX = d.x - d.node.x;
      var diffY = d.y - d.node.y;
      var dist = Math.sqrt(diffX * diffX + diffY * diffY)+4;

      var shiftX = b.width * (diffX - dist) / (dist * 2);
      shiftX = Math.max(-b.width, Math.min(0, shiftX));

      var shiftY = 5;
      this.childNodes[1].setAttribute("transform", "translate(" + shiftX + "," + shiftY + ")");
    }
  });

  anchorNode.call(updateNode);

  link.call(updateLink);

  anchorLink.call(updateLink);
});

</script>';



//echo '<hr>';
/*
foreach ($nodesArray as $keyN => $valueN) {
  if(in_array($valueN, $revisedNodeArray)){
  echo '<span class="label label-success">Keep.</span> - <span class="text-success">'.$keyN.' | '.$valueN.'</span>';
    echo '<br />';
  }
  else{
  echo '<span class="label label-danger">Drop.</span> - <span class="text-danger">'.$keyN.' | '.$valueN.'</span>';
    echo '<br />';
    unset($nodesArray[$valueN]);
  }
}
*/
/*


<!-- Column 2 -->
<div class="col-xs-3">
  <div class="alert alert-info">';
echo '<pre>';
echo '<h3>Active Nodes:</h3>';
print_r($activeNodesArray);
echo '</pre>';
  echo '
  </div>
</div>

*/
//echo 'console.log(\''.$tempConsoleString.'\');';
/*
echo '<hr>';
echo '<h3>Nodes To NOT Graph</h3>';
print_r($inactiveNodesArray);
*/
/*
foreach ($nodesArray as $keyN => $valueN) {
  $tempSource='';
  $tempDestination='';
  $tempSource=$valueN['source'];
  $tempDestination=$valueN['destination'];
  foreach ($activeNodesArray as $keyA => $valueA) {
    $tempActiveNodeValue=$valueA;

    if ($tempActiveNodeValue==$tempSource || $tempActiveNodeValue==$tempDestination) {
    }
    else{
      unset($nodesArray['$keyN']);
    }
  }
}
*/


/*
//echo '<pre>';
//print_r($linksArray);
//echo '</pre>';



<!--
      <div class="alert alert-info">
      <h2>Nodes</h2>
        <div id="nodesDebug" name="nodesDebug">
        </div>
      </div>

      <div class="alert alert-info">
      <h2>Links</h2>
        <div id="linksDebug" name="linksDebug">
        </div>
      </div>

      <div class="alert alert-info">
      <h2>Label-Anchors</h2>
        <div id="labelAnchorsDebug" name="labelAnchorsDebug">
        </div>
      </div>

      <div class="alert alert-info">
      <h2>Label-Anchor Links</h2>
        <div id="labelAnchorLinksDebug" name="labelAnchorLinksDebug">
        </div>
      </div>
-->




  //unset($nodesArray[$value]);

//End Get Inter-Building Links w/Strength
//======================================



//

/*
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





$fileNodeContents=rtrim($fileNodeContents,',');








$fileNodeContents.=']
}
';
*/

//echo '<pre>';
//print_r($nodesArray);
//echo '</pre>';

//echo '<pre>';
//print_r($translateNodeNameToNdx);
//echo '</pre>';

//echo '<pre>';
//print_r($linksArray);
//echo '</pre>';

/*
$fileName='buildingMap.json';
//echo '<p>The file: <b>'.$fileName.'</b> will be written now...</p>';
$myfile = fopen('buildingMap.json', "w") or die("Unable to open file!");
fwrite($myfile, $fileNodeContents);
fclose($myfile);


$linkString='';
$linkString='var links = [
';
foreach ($linksArray as $key => $value) {
  $linkString.='{"source": 0, "target": 1, "value": 0.11750186320223321 },
  ';
}
$linkString.='];';
*/

/*
$combinedLinkText='';
$combinedLinkText.='
var links = [
';
$linkCounter=1;
foreach ($linksArray as $key => $value) {
echo '
console.log(\'Counter: '.$linkCounter.'\');
';
echo '
console.log(\'Size of link array: '.sizeof($linksArray).'\');
';


  if ($linkCounter!=sizeof($linksArray)) {
    $combinedLinkText.='{"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 },
    ';
  }
  else{
    $combinedLinkText.='{"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 }';
  }
$linkCounter++;
}
$combinedLinkText.='];';

echo $combinedLinkText;
*/



/*
echo 'var links = [';
foreach ($linksArray as $key => $value) {

  echo 'console.log(\'{"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 }, \');';

  echo '{"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 },';

}
echo '{"source": 8, "target": 99, "value": 0.16839691457195938 }';
echo '];';
*/




/*
$tempConsoleString='var links = [';
foreach ($linksArray as $key => $value) {
  $tempConsoleString.='{"source": '.$value['source'].', "target": '.$value['destination'].', "value": 0.11750186320223321 },';
}
$tempConsoleString=rtrim($tempConsoleString,',');
$tempConsoleString.='];';


echo 'console.log(\''.$tempConsoleString.'\');';

echo 'var links = [';
echo '];';
*/

/*
echo 'var links = [';
echo '{"source": 0, "target": 1, "value": 0.11750186320223321 },';
echo '{"source": 1, "target": 2, "value": 0.9347818223025258 }, 
{"source": 2, "target": 3, "value": 0.3126300896145545 }, 
{"source": 6, "target": 97, "value": 0.9347818223025258 }, 
{"source": 7, "target": 98, "value": 0.3126300896145545 },';
echo '{"source": 8, "target": 99, "value": 0.16839691457195938 }';
echo '];';

*/


/*
echo '
//create links
for(var i = 0; i < 30; i++) {
  for(var j = 0; j < i; j++) {
    if(Math.random() > .95){
      links.push({
        source : i,
        target : j,
        value : 1
      });
    }
  }
};
';

*/



//echo 'alert('.$linkString.');';

/*
$linkString='var links = [
';
foreach ($linksArray as $key => $value) {
  $linkString.='{"source": 0, "target": 1, "value": 0.11750186320223321 },
  ';
}
$linkString.='
];';

echo $linkString;

echo '
$("#linkStringDebug").append(JSON.stringify(linkString,null,4));
';

echo '
var links = [{"source": 0, "target": 1, "value": 0.11750186320223321 }, {"source": 1, "target": 2, "value": 0.9347818223025258 }, {"source": 2, "target": 3, "value": 0.3126300896145545 }, {"source": 3, "target": 4, "value": 0.16839691457195938 }, {"source": 4, "target": 5, "value": 0.010873578397785022 }];
';
*/




/*
foreach ($nodesArray as $keyN => $valueN) {
  foreach ($linksArray as $keyL => $valueL) {
    if ($valueL['source']==$keyN || $valueL['destination']==$keyN) {
      echo '
      links.push({
        source : '.$keyN.',
        target : '.$keyN.',
        value : 1
      });
      ';
    }
  }
}
*/



/*


foreach ($nodesArray as $keyN => $valueN) {
  foreach ($linksArray as $keyL => $valueL) {
    if ($valueL['source']==$keyN || $valueL['destination']==$keyN) {
      echo '
      labelAnchorLinks.push({
      source : '.$keyN.' * 2,
      target : '.$keyN.' * 2 + 1,
      value : 1
      });
      ';
    }
  }
}


foreach ($nodesArray as $keyN => $valueN) {
  foreach ($linksArray as $keyL => $valueL) {
    if ($valueL['source']==$keyN){
      echo '
      //alert("'.$valueL['source'].'");
      //alert("");
      ';
      
      echo '
      alert("'.$nodesArray[$valueL['source']].'");
      var node = {
        label : "'.$nodesArray[$valueL['source']].'"
      };
      nodes.push(node);
      labelAnchors.push({
        node : node
      });
      labelAnchors.push({
        node : node
      });
      ';
      
    }

    if ($valueL['destination']==$keyN){
      
      echo '
      alert("'.$nodesArray[$valueL['destination']].'");
      var node = {
        label : "'.$nodesArray[$valueL['destination']].'"
      };
      nodes.push(node);
      labelAnchors.push({
        node : node
      });
      labelAnchors.push({
        node : node
      });
      ';
      
    }
  }
}

*/


/*
foreach ($linksArray as $key => $value) {
echo '
links.push({
  source : '.$value['source'].',
  target : '.$value['destination'].',
  weight : '.$value['value'].'
});
labelAnchorLinks.push({
  source : '.$value['source'].',
  target : '.$value['destination'].',
  weight : 1
});
';
}
*/

/*
echo '
var nodes = [{"label": "node 0"}, {"label": "node 1"}, {"label": "node 2"}, {"label": "node 3"}, {"label": "node 4"}, {"label": "node 5"}, {"label": "node 6"}, {"label": "node 7"}, {"label": "node 8"}, {"label": "node 9"} ];

var labelAnchors = [{"node": {"label": "node 0"} }, {"node": {"label": "node 0"} }, {"node": {"label": "node 1"} }, {"node": {"label": "node 1"} }, {"node": {"label": "node 2"} }, {"node": {"label": "node 2"} }, {"node": {"label": "node 3"} }, {"node": {"label": "node 3"} }, {"node": {"label": "node 4"} }, {"node": {"label": "node 4"} }, {"node": {"label": "node 5"} }, {"node": {"label": "node 5"} }, {"node": {"label": "node 6"} }, {"node": {"label": "node 6"} }, {"node": {"label": "node 7"} }, {"node": {"label": "node 7"} }, {"node": {"label": "node 8"} }, {"node": {"label": "node 8"} }, {"node": {"label": "node 9"} }, {"node": {"label": "node 9"} }];

var labelAnchorLinks = [{"source": 0, "target": 1, "value": 1 }, {"source": 2, "target": 3, "value": 1 }, {"source": 4, "target": 5, "value": 1 }, {"source": 6, "target": 7, "value": 1 }, {"source": 8, "target": 9, "value": 1 }, {"source": 10, "target": 11, "value": 1 }, {"source": 12, "target": 13, "value": 1 }, {"source": 14, "target": 15, "value": 1 }, {"source": 16, "target": 17, "value": 1 }, {"source": 18, "target": 19, "value": 1 } ]; 

var links = [{"source": 6, "target": 4, "value": 0.11750186320223321 }, {"source": 7, "target": 2, "value": 0.9347818223025258 }, {"source": 8, "target": 1, "value": 0.3126300896145545 }, {"source": 9, "target": 2, "value": 0.16839691457195938 }, {"source": 9, "target": 8, "value": 0.010873578397785022 }];
';

*/

/*
echo '
//create nodes
for(var i = 0; i < 10; i++) {
  var node = {
    label : "node " + i
  };
  nodes.push(node);
  labelAnchors.push({
    node : node
  });
  labelAnchors.push({
    node : node
  });
};
';
*/



$linkBuilder='var links = ';
$linkBuilder.='[';
foreach ($linksArray as $key => $value){
$linkBuilder .= '{ "source": '.$value["source"].', "target": '.$value["destination"].', "value": 1 },';
}
$linkBuilder=rtrim($linkBuilder,',');
$linkBuilder.='];';

//echo 'alert(\''.$linkBuilder.'\');';

//echo $linkBuilder;



/*
echo '
var links = [ 
  { "source": 11, "target": 6, "value": 1 }, 
  { "source": 14, "target": 2, "value": 1 }, 
  { "source": 14, "target": 12, "value": 1 }, 
  { "source": 16, "target": 6, "value": 1 }, 
  { "source": 16, "target": 14, "value": 1 }, 
  { "source": 17, "target": 5, "value": 1 }, 
  { "source": 17, "target": 10, "value": 1 }, 
  { "source": 18, "target": 7, "value": 1 }, 
  { "source": 19, "target": 17, "value": 1 }, 
  { "source": 20, "target": 19, "value": 1 }, 
  { "source": 21, "target": 18, "value": 1 }, 
  { "source": 22, "target": 10, "value": 1 }, 
  { "source": 25, "target": 23, "value": 1 }, 
  { "source": 26, "target": 11, "value": 1 }, 
  { "source": 26, "target": 22, "value": 1 }
];'; 
*/


/*
echo '
var links = [{ 
"source": 1, "target": 93, "value": 1 },{ "source": 1, "target": 92, "value": 1 },{ "source": 1, "target": 100, "value": 1 },{ "source": 5, "target": 4, "value": 1 },{ "source": 100, "target": 99, "value": 1 },{ "source": 99, "target": 98, "value": 1 },{ "source": 99, "target": 97, "value": 1 },{ "source": 99, "target": 94, "value": 1 },{ "source": 97, "target": 98, "value": 1 },{ "source": 97, "target": 96, "value": 1 },{ "source": 96, "target": 98, "value": 1 },{ "source": 96, "target": 95, "value": 1 },{ "source": 95, "target": 98, "value": 1 },{ "source": 94, "target": 98, "value": 1 },{ "source": 94, "target": 95, "value": 1 }];
';
*/

/*
foreach ($linksArray as $key => $value){
echo '
  links.push({
    source : '.$value['source'].',
    target : '.$value['destination'].',
    value : 1
  });
';
}
*/


?>
</body>
</html>
