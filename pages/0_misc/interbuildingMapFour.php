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
    <svg id="viz2" width="1200" height="800"></svg>

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




<script type="text/javascript">
//Constants for the SVG
var vis = d3.select("svg#viz2"),
    w  = +vis.attr("width"),
    h = +vis.attr("height");

//Set up a dark background
vis.append("svg:rect")
    .attr("width", w)
    .attr("height", h)
    .attr("fill", "#333");

//init variables
var labelDistance = 0;
/*
var nodes = [{"label": "node 0"}, {"label": "node 1"}, {"label": "node 2"}, {"label": "node 3"}, {"label": "node 4"}, {"label": "node 5"}, {"label": "node 6"}, {"label": "node 7"}, {"label": "node 8"}, {"label": "node 9"} ];

var labelAnchors = [{"node": {"label": "node 0"} }, {"node": {"label": "node 0"} }, {"node": {"label": "node 1"} }, {"node": {"label": "node 1"} }, {"node": {"label": "node 2"} }, {"node": {"label": "node 2"} }, {"node": {"label": "node 3"} }, {"node": {"label": "node 3"} }, {"node": {"label": "node 4"} }, {"node": {"label": "node 4"} }, {"node": {"label": "node 5"} }, {"node": {"label": "node 5"} }, {"node": {"label": "node 6"} }, {"node": {"label": "node 6"} }, {"node": {"label": "node 7"} }, {"node": {"label": "node 7"} }, {"node": {"label": "node 8"} }, {"node": {"label": "node 8"} }, {"node": {"label": "node 9"} }, {"node": {"label": "node 9"} }];

var labelAnchorLinks = [{"source": 0, "target": 1, "value": 1 }, {"source": 2, "target": 3, "value": 1 }, {"source": 4, "target": 5, "value": 1 }, {"source": 6, "target": 7, "value": 1 }, {"source": 8, "target": 9, "value": 1 }, {"source": 10, "target": 11, "value": 1 }, {"source": 12, "target": 13, "value": 1 }, {"source": 14, "target": 15, "value": 1 }, {"source": 16, "target": 17, "value": 1 }, {"source": 18, "target": 19, "value": 1 } ]; 

var links = [{"source": 6, "target": 4, "value": 0.11750186320223321 }, {"source": 7, "target": 2, "value": 0.9347818223025258 }, {"source": 8, "target": 1, "value": 0.3126300896145545 }, {"source": 9, "target": 2, "value": 0.16839691457195938 }, {"source": 9, "target": 8, "value": 0.010873578397785022 }];
*/

var nodes = [{label:"node 1"},{label:"node 2"},{label:"node 3"},{label:"node 4"}];

var labelAnchors = [{label:"node 1"},{label:"node 2"},{label:"node 3"},{label:"node 4"}];



//create links
for(var i = 0; i < nodes.length; i++) {
  for(var j = 0; j < i; j++) {
    if(Math.random() > .95)
      links.push({
        source : i,
        target : j,
        value : Math.random()
      });
  }
  labelAnchorLinks.push({
    source : i * 2,
    target : i * 2 + 1,
    value : 1
  });
};



//create force simulation for nodes
var force = d3.layout
.force()
.size([w, h])
.nodes(nodes)
.links(links)
.gravity(1)
.linkDistance(50)
.charge(-3000)
.linkStrength(function(x){return x.value * 10});


/*
$("#nodesDebug").append(JSON.stringify(nodes,null,4));
$("#linksDebug").append(JSON.stringify(links,null,4));
$("#labelAnchorsDebug").append(JSON.stringify(labelAnchors,null,4));
$("#labelAnchorLinksDebug").append(JSON.stringify(labelAnchorLinks,null,4));
nodes[
    {
        "label": "node 0"
    },
    {
        "label": "node 1"
    },
    {
        "label": "node 2"
    },
    {
        "label": "node 3"
    },
    {
        "label": "node 4"
    },
    {
        "label": "node 5"
    },
    {
        "label": "node 6"
    },
    {
        "label": "node 7"
    },
    {
        "label": "node 8"
    },
    {
        "label": "node 9"
    }
]  interbuildingMapFour.php:290:1
links[
    {
        "source": 6,
        "target": 4,
        "value": 0.11750186320223321
    },
    {
        "source": 7,
        "target": 2,
        "value": 0.9347818223025258
    },
    {
        "source": 8,
        "target": 1,
        "value": 0.3126300896145545
    },
    {
        "source": 9,
        "target": 2,
        "value": 0.16839691457195938
    },
    {
        "source": 9,
        "target": 8,
        "value": 0.010873578397785022
    }
]  interbuildingMapFour.php:291:1
labelAnchors[
    {
        "node": {
            "label": "node 0"
        }
    },
    {
        "node": {
            "label": "node 0"
        }
    },
    {
        "node": {
            "label": "node 1"
        }
    },
    {
        "node": {
            "label": "node 1"
        }
    },
    {
        "node": {
            "label": "node 2"
        }
    },
    {
        "node": {
            "label": "node 2"
        }
    },
    {
        "node": {
            "label": "node 3"
        }
    },
    {
        "node": {
            "label": "node 3"
        }
    },
    {
        "node": {
            "label": "node 4"
        }
    },
    {
        "node": {
            "label": "node 4"
        }
    },
    {
        "node": {
            "label": "node 5"
        }
    },
    {
        "node": {
            "label": "node 5"
        }
    },
    {
        "node": {
            "label": "node 6"
        }
    },
    {
        "node": {
            "label": "node 6"
        }
    },
    {
        "node": {
            "label": "node 7"
        }
    },
    {
        "node": {
            "label": "node 7"
        }
    },
    {
        "node": {
            "label": "node 8"
        }
    },
    {
        "node": {
            "label": "node 8"
        }
    },
    {
        "node": {
            "label": "node 9"
        }
    },
    {
        "node": {
            "label": "node 9"
        }
    }
]  interbuildingMapFour.php:292:1
labelAnchorLinks[
    {
        "source": 0,
        "target": 1,
        "value": 1
    },
    {
        "source": 2,
        "target": 3,
        "value": 1
    },
    {
        "source": 4,
        "target": 5,
        "value": 1
    },
    {
        "source": 6,
        "target": 7,
        "value": 1
    },
    {
        "source": 8,
        "target": 9,
        "value": 1
    },
    {
        "source": 10,
        "target": 11,
        "value": 1
    },
    {
        "source": 12,
        "target": 13,
        "value": 1
    },
    {
        "source": 14,
        "target": 15,
        "value": 1
    },
    {
        "source": 16,
        "target": 17,
        "value": 1
    },
    {
        "source": 18,
        "target": 19,
        "value": 1
    }
]  interbuildingMapFour.php:293:1



console.log("nodes"+JSON.stringify(nodes,null,4));
console.log("links"+JSON.stringify(links,null,4));
console.log("labelAnchors"+JSON.stringify(labelAnchors,null,4));
console.log("labelAnchorLinks"+JSON.stringify(labelAnchorLinks,null,4));
*/



//start simulation for nodes
force.start();

//create force simulation for labels
var force2 = d3.layout
.force()
.nodes(labelAnchors)
.links(labelAnchorLinks)
.gravity(0)
.linkDistance(0)
.linkStrength(8)
.charge(-100)
.size([w, h]);

//start simulation for labels
force2.start();

//create links bewteen nodes
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
//node.call(force.drag);

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

</script>
?>
</body>
</html>
