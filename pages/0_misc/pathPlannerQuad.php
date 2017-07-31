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

<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1>Path Planner</h1>
        </div>
        <div class="panel-body">
<?php

//Initialize array to hold direct connections
$directConnections=array();

//======================================================+
//          MAKE BUILDING INDEX (UID => OTHER INFO)     |
//======================================================+
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
$stmt = $db->prepare('SELECT * FROM building');
//execute query
$stmt->execute();
//store variables
$buildingCounter=0;
$thisUID='';
foreach($stmt as $row) {
    //fill the building details array
    $thisUID=$row['building_UID'];
    $buildings[$thisUID]['UID']=$row['building_UID'];
    $buildings[$thisUID]['number']=$row['number'];
    $buildings[$thisUID]['name']=$row['name'];
    $buildings[$thisUID]['levels']=$row['levels'];
    $buildings[$thisUID]['notes']=$row['notes'];
    $buildings[$thisUID]['address']=$row['address'];
    $buildings[$thisUID]['long']=$row['long'];
    $buildings[$thisUID]['lat']=$row['lat'];
    //setup each key for the direct connections
    $directConnections[$row['building_UID']]=array();
    $buildingCounter++;
}
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve building index.<br />'.$e->getMessage());
}

//01    Frazier Hall    0206
//04    Liberal Arts    0209
//08    Pharmacy        0212
//10    Admin           0202
//14    Pond SUB        0216
    //05    Business Admin  0003
    //18    M & O           0220
    //20    Heat Plant      0222
    //19    Grounds         0221
        //33    Fueling Sta     0234
        //16    Haz Mat Sto     0218

//Get Selected Buildings
$sourceBuilding_UID='0206';         //debug test source
$destinationBuilding_UID='0218';    //debug test destination

//=========================================+
//          USER INFO DISPLAY              |
//=========================================+
generateAlert('info','Checking for connections between <span class="label label-primary">'.$buildings[$sourceBuilding_UID]['name'].'</span> & <span class="label label-primary">'.$buildings[$destinationBuilding_UID]['name'].'</span>');

//======================================+
//   CREATE DIRECT CONNECTIONS ARRAY    |
//======================================+
foreach ($buildings as $key => $value) {
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        //prepare statement to prevent SQL injection
        $stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID=:sourceBuilding || buildingb.building_UID=:sourceBuilding) GROUP BY INTERPANEL_ID");
        //bind variables to query avoiding interpolation of variables
        $stmt->bindParam(':sourceBuilding', $key);
        //execute query
        $stmt->execute();
        //Add each direct connection to a multidimensional array.
        foreach($stmt as $row) {
            //if UID A matches source, store UID B
            if ($key==$row['A_BUID']) {
                $directConnections[$key][$row['B_BUID']]=$row['B_BUID'];
            }
            //if UID B matches source, store UID A
            if ($key==$row['B_BUID']) {
                $directConnections[$key][$row['A_BUID']]=$row['A_BUID'];
            }
        }
    }
    catch(PDOException $e){
        //Report Error Message(s)
        generateAlert('danger','Could not retrieve building connections.<br />'.$e->getMessage());
    }
}



//=================================================================================+
//   SCAN DIRECT CONNECTIONS TO FIND COMMON LINKS BETWEEN SOURCE AND DESTINATION   |
//=================================================================================+



//SOURCE
$sourceConnectionTiers=array();
foreach ($directConnections[$sourceBuilding_UID] as $key => $value) {
    array_push($sourceConnectionTiers, $value);
}



//DESTINATION
$destinationConnectionTiers=array();
foreach ($directConnections[$destinationBuilding_UID] as $key => $value) {
    array_push($destinationConnectionTiers, $value);
}



//echo '<pre> Source:';
//print_r($sourceConnectionTiers);
//echo '</pre>';



//echo '<pre> Destination:';
//print_r($destinationConnectionTiers);
//echo '</pre>';



/*
//source
$sourceFound=array();
$sourceChecked=array();

//destination
$destinationFound=array();
$destinationChecked=array();
*/
$checkedBuildings=array();

$pathHops=array();
$connectionCrawlFromSource=array();
$connectionCrawlFromSource['source']=$sourceBuilding_UID;
$connectionCrawlFromSource['destination']=$destinationBuilding_UID;

//CREATE crawl from source to destination
foreach ($directConnections[$sourceBuilding_UID] as $key => $value) {
if (!in_array($value, $checkedBuildings)){
    $connectionCrawlFromSource[$sourceBuilding_UID]['hop1'][]=$value;
    array_push($checkedBuildings, $value);
    if ($value==$destinationBuilding_UID) {
            array_push($pathHops, $sourceBuilding_UID);
            array_push($pathHops, $value);
            array_push($pathHops, $valuea);
        }
    foreach ($directConnections[$value] as $keya => $valuea) {
    if (!in_array($valuea, $checkedBuildings)){
    $connectionCrawlFromSource[$sourceBuilding_UID]['hop2'][]=$valuea;
        //$connectionCrawlFromSource[$sourceBuilding_UID][$value][]=$valuea;
        array_push($checkedBuildings, $valuea);
        //store path to destination
        if ($valuea==$destinationBuilding_UID) {
            array_push($pathHops, $sourceBuilding_UID);
            array_push($pathHops, $value);
            array_push($pathHops, $valuea);
        }
        foreach ($directConnections[$valuea] as $keyb => $valueb) {
        if (!in_array($valueb, $checkedBuildings)){
            $connectionCrawlFromSource[$sourceBuilding_UID]['hop3'][]=$valueb;
            //$connectionCrawlFromSource[$sourceBuilding_UID][$value][$valuea][]=$valueb;
            array_push($checkedBuildings, $valueb);
            //store path to destination
            if ($valueb==$destinationBuilding_UID) {
                array_push($pathHops, $sourceBuilding_UID);
                array_push($pathHops, $value);
                array_push($pathHops, $valuea);
                array_push($pathHops, $valueb);
            }
            foreach ($directConnections[$valueb] as $keyc => $valuec) {
            if (!in_array($valuec, $checkedBuildings)){
                $connectionCrawlFromSource[$sourceBuilding_UID]['hop4'][]=$valuec;
                //$connectionCrawlFromSource[$sourceBuilding_UID][$value][$valuea][$valueb][]=$valuec;
                //store path to destination
                if ($valuec==$destinationBuilding_UID) {
                    array_push($pathHops, $sourceBuilding_UID);
                    array_push($pathHops, $value);
                    array_push($pathHops, $valuea);
                    array_push($pathHops, $valueb);
                    array_push($pathHops, $valuec);
                }
                array_push($checkedBuildings, $valuec);
            }
            }
        }
        }
    }
    }
}
}


//connection crawl display
//echo 'Size Of Connection Crawl:'.sizeof($connectionCrawlFromSource[$sourceBuilding_UID]);

//setup path info
$path=array();
foreach ($pathHops as $key => $value) {
    $path[$key]['UID']=$value;
    $path[$key]['name']=$buildings[$value]['name'];
    $path[$key]['number']=$buildings[$value]['number'];
}


//start row
echo '<div class="row">';

//if hop 1
switch (sizeof($connectionCrawlFromSource[$sourceBuilding_UID])) {
    case '1':
        echo '<div class="col-xs-12">';
        echo '<p>The source and destination building are directly connected.</p>';
        echo '<p>The following interbuilding connection must be made:</p>';
        echo '</div>';

        break;
    
    case '2':
        echo '<div class="col-xs-12">';
        echo '<p>The source and destination building are connected indirectly.</p>';
        echo '<p>The following interbuilding connections must be made:</p>';
        echo '</div>';

        break;
    
    case '3':
        echo '<div class="col-xs-12">';
        echo '<p>The source and destination building are connected indirectly.</p>';
        echo '<p>The following interbuilding connections must be made:</p>';
        echo '</div>';

        break;
    
    case '4':
        echo '<div class="col-xs-12">';
        echo '<p>The source and destination building are connected indirectly.</p>';
        echo '<p>The following interbuilding connections must be made:</p>';
        echo '</div>';
       
  
        //first hop options
        echo '
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <p>First Hop</p>
                    <span class="label label-success">'.$path[0]['number'].' - '.$path[0]['name'].'</span> 
                    <i class="fa fa-fw fa-exchange"></i> 
                    <span class="label label-default">'.$path[1]['number'].' - '.$path[1]['name'].'</span>
                </div>
                <div class="panel-body">
                ';
                //get connection options
                $fourthHopOptions=getConnectionsBetween($path[0]['UID'],$path[1]['UID']);

                //
                echo '<table class="table table-display table-responsive table-bordered">';

                foreach ($fourthHopOptions['links'] as $key => $value) {
                $linkCount=$key+1;
                
                if ($key=='0') {
                    # code...
                    if ($path[0]['UID']==$value['A']['building_UID']) {
                        # code...
                        echo '
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
                    }
                    if ($path[0]['UID']==$value['B']['building_UID']) {
                        # code...
                        echo '
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
                    }
                }



                //source to destination
                if ($path[0]['UID']==$value['A']['building_UID']) {
                echo '
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                        </tr>
                ';
                }

                //destination to source
                if ($path[0]['UID']==$value['B']['building_UID']) {
                echo '

                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                        </tr>
                ';
                }


                }

                echo '
                    </tbody>
                    </table>

                </div>
            </div>
        </div>
        ';
        
        //second hop options
        echo '
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <p>Second Hop</p>
                    <span class="label label-default">'.$path[1]['number'].' - '.$path[1]['name'].'</span> 
                    <i class="fa fa-fw fa-exchange"></i> 
                    <span class="label label-default">'.$path[2]['number'].' - '.$path[2]['name'].'</span>
                </div>
                <div class="panel-body">
                ';
                //get connection options
                $fourthHopOptions=getConnectionsBetween($path[1]['UID'],$path[2]['UID']);

                //
                foreach ($fourthHopOptions['links'] as $key => $value) {
                    $linkCount=$key+1;
                
                //source to destination
                if ($path[1]['UID']==$value['A']['building_UID']) {
                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                        </tr>
                    </tbody>
                    </table>
                ';
                }

                //destination to source
                if ($path[1]['UID']==$value['B']['building_UID']) {
                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                        </tr>
                    </tbody>
                    </table>
                ';
                }

                }
                echo '
                </div>
            </div>
        </div>
        ';
        
        //third hop options
        echo '
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <p>Third Hop</p>
                    <span class="label label-default">'.$path[2]['number'].' - '.$path[2]['name'].'</span> 
                    <i class="fa fa-fw fa-exchange"></i> 
                    <span class="label label-default">'.$path[3]['number'].' - '.$path[3]['name'].'</span>
                </div>
                <div class="panel-body">
                ';
                //get connection options
                $fourthHopOptions=getConnectionsBetween($path[2]['UID'],$path[3]['UID']);

                //
                foreach ($fourthHopOptions['links'] as $key => $value) {
                    $linkCount=$key+1;
                
                //source to destination
                if ($path[2]['UID']==$value['A']['building_UID']) {
                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                        </tr>
                    </tbody>
                    </table>
                ';
                }

                //destination to source
                if ($path[2]['UID']==$value['B']['building_UID']) {
                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                        </tr>
                    </tbody>
                    </table>
                ';
                }

                }
                echo '
                </div>
            </div>
        </div>
        ';
        
        //fourth hop options
        echo '
        <div class="col-xs-12">
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <p>Fourth Hop</p>
                    <span class="label label-default">'.$path[3]['number'].' - '.$path[3]['name'].'</span> 
                    <i class="fa fa-fw fa-exchange"></i> 
                    <span class="label label-success">'.$path[4]['number'].' - '.$path[4]['name'].'</span>
                </div>
                <div class="panel-body">
                ';
                //get connection options
                $fourthHopOptions=getConnectionsBetween($path[3]['UID'],$path[4]['UID']);

                //
                foreach ($fourthHopOptions['links'] as $key => $value) {
                    $linkCount=$key+1;
                
                //source to destination
                if ($path[3]['UID']==$value['A']['building_UID']) {
                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                        </tr>

                    </tbody>
                    </table>
                ';
                }

                //destination to source
                if ($path[3]['UID']==$value['B']['building_UID']) {


                echo '
                    <table class="table table-display table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-center" colspan="3">'.$value['B']['building_number'].' - '.$value['B']['building_name'].'</th>
                            <th class="text-center" colspan="3">'.$value['A']['building_number'].' - '.$value['A']['building_name'].'</th>
                        </tr>
                        <tr>
                            <th class="text-center">Connection</th>
                            <th>Storage Unit</th>
                            <th>Cabinet</th>
                            <th>Panel</th>
                            <!-- connections -->
                            <th>Panel</th>
                            <th>Cabinet</th>
                            <th>Storage Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">'.$linkCount.'</td>
                            <td>'.$value['B']['storageunit_label'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['B']['cabinet_UID'].'">
                            '.$value['B']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['B']['panel_position'].'</td>
                            <td>'.$value['A']['panel_position'].'</td>
                            <td>
                            <a target="_blank" href="manageCabinet.php?uid='.$value['A']['cabinet_UID'].'">
                            '.$value['A']['cabinet_label'].'
                            </a>
                            </td>
                            <td>'.$value['A']['storageunit_label'].'</td>
                        </tr>
                    </tbody>
                    </table>
                ';
                }

                }
                echo '
                </div>
            </div>
        </div>
        ';

        break;
    
    default:
        # code...
        break;
}
//start row
echo '</div>';

//debug print
//debugPrintData($path);


//debug print
//debugPrintData($connectionCrawlFromSource);




function getConnectionsBetween($source,$destination){
//global auth
global $dbHost;
global $dbName;
global $dbUser;
global $dbPassword;

//init the array
$tempConnections=array();

//set elements
$tempConnections['source']=$source;
$tempConnections['destination']=$destination;

//SQL STUFF
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
  $stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, cabineta.cabinet_UID AS A_CAB_UID, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, cabinetb.cabinet_UID AS B_CAB_UID, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID=:source AND buildingb.building_UID=:destination OR buildingb.building_UID=:source AND buildinga.building_UID=:destination) GROUP BY INTERPANEL_ID"); 
//bidn query
$stmt->bindParam(':source', $source);
$stmt->bindParam(':destination', $destination);
//execute query
$stmt->execute();
//store variables
$connectionCounter=0;
    foreach($stmt as $row) {
        //link stregnth - strand count per panel
        $tempConnections['links'][$connectionCounter]['linkStrength']=$row['linkStrength'];

    //A details
    $tempConnections['links'][$connectionCounter]['A']['building_UID']=$row['A_BUID'];
    $tempConnections['links'][$connectionCounter]['A']['building_name']=$row['A_NAME'];
    $tempConnections['links'][$connectionCounter]['A']['building_number']=$row['A_NUMBER'];
    $tempConnections['links'][$connectionCounter]['A']['storageunit_label']=$row['A_SU'];
    $tempConnections['links'][$connectionCounter]['A']['cabinet_label']=$row['A_CAB'];
    $tempConnections['links'][$connectionCounter]['A']['cabinet_UID']=$row['A_CAB_UID'];
    $tempConnections['links'][$connectionCounter]['A']['panel_position']=$row['A_PAN'];

    //B details
    $tempConnections['links'][$connectionCounter]['B']['building_UID']=$row['B_BUID'];
    $tempConnections['links'][$connectionCounter]['B']['building_name']=$row['B_NAME'];
    $tempConnections['links'][$connectionCounter]['B']['building_number']=$row['B_NUMBER'];
    $tempConnections['links'][$connectionCounter]['B']['storageunit_label']=$row['B_SU'];
    $tempConnections['links'][$connectionCounter]['B']['cabinet_label']=$row['B_CAB'];
    $tempConnections['links'][$connectionCounter]['B']['cabinet_UID']=$row['B_CAB_UID'];
    $tempConnections['links'][$connectionCounter]['B']['panel_position']=$row['B_PAN'];

        //inc counter
        $connectionCounter++;
    }
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
}




//return that array
return $tempConnections;
}




/*
//source building scan for tier
foreach ($directConnections[$sourceBuilding_UID] as $key => $value) {
    echo 'Key '.$key.' Destinations: <br />';
    foreach ($directConnections[$value] as $keya => $valuea) {
        echo $valuea.'<br />';
    }
}



//destination building scan for tier
foreach ($directConnections[$destinationBuilding_UID] as $key => $value) {
    echo 'Key '.$key.' Destinations: <br />';
    foreach ($directConnections[$value] as $keya => $valuea) {
        echo $valuea.'<br />';
    }
}

*/

//===================================+
//   PRINT ALL DIRECT CONNECTIONS    |
//===================================+
/*
echo '<div class="row">';
$countDataSets=0;
foreach ($directConnections as $key => $value){
    if (sizeof($value)>0){
        if (($countDataSets % 4)==0) {
            echo '</div>';
            echo '<div class="row">';
        }
        echo '<div class="col-xs-12 col-lg-3">';
        echo '<div class="well">';
        echo '<div class="alert alert-info">';
        echo '<b>Source Building: </b><br />';
        echo $buildings[$key]['name'];
        echo '<hr>';
        $destinationCount=0;
        foreach ($value as $key_2 => $value_2) {
            if ($destinationCount==0) {
                echo '<b>Direct Connections: </b><br />';
            }
            echo $buildings[$value_2]['name'];
            echo '<br />';
            $destinationCount++;
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        $countDataSets++;
    }
}
echo '</div>';
*/




//===================================+
//   PRINT ALL DIRECT CONNECTIONS    |
//===================================+
//debugPrintData($directConnections);














/*
echo '<div class="alert alert-info">TIER 1</div>';

//$connectionTiers[0]=$sourceBuilding_UID;
//$connectionTiers=array();
//What buildings have we found
//$foundBuildings=array();
//What buildings have been checked.
//$checkedBuildings=array();
//array_push($foundBuildings, $sourceBuilding_UID);

//checkedBuildings
//Display
echo '<p>Generating Tree For Source: '.$buildings[$sourceBuilding_UID]['name'].'</p>';
//get tier 1 data and store it.
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
$stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID=:sourceBuilding || buildingb.building_UID=:sourceBuilding) GROUP BY INTERPANEL_ID");

//bind variables to query
$stmt->bindParam(':sourceBuilding', $sourceBuilding_UID);
//execute query
$stmt->execute();
//store variables
$connectionCounter=1;
foreach($stmt as $row) {
//if source is a store b as tier 1
if ($sourceBuilding_UID==$row['A_BUID']) {
//Add this uid to the list of found buildings
array_push($foundBuildings, $row['B_BUID']);
//Add the rest of the details here
$connectionTiers[$connectionCounter][$row['B_BUID']]=$row['B_BUID'];

}
//if source is b store a as tier 1
if ($sourceBuilding_UID==$row['B_BUID']) {
//Add this uid to the list of found buildings
array_push($foundBuildings, $row['A_BUID']);
//Add the rest of the details here
$connectionTiers[$connectionCounter][$row['A_BUID']]=$row['A_BUID'];

}
$connectionCounter++;
}
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
}

//set the source as checked now that we have.
array_push($checkedBuildings, $sourceBuilding_UID);



//====================================
//          FOUND BUILDINGS
//====================================
//init csv of found buildings
$foundBuildingsString='';
//Go for the next tiers
foreach ($foundBuildings as $key => $value) {
    //add to csv string
    $foundBuildingsString.=$value.',';
}
//remove last comma
$foundBuildingsString=rtrim($foundBuildingsString,',');



//====================================
//          CHECKED BUILDINGS
//====================================
//init csv of found buildings
$checkedBuildingsString='';
//Go for the next tiers
foreach ($checkedBuildings as $key => $value) {
    //add to csv string
    $checkedBuildingsString.=$value.',';
}
//remove last comma
$checkedBuildingsString=rtrim($checkedBuildingsString,',');

echo '<pre>Connection Tiers:<br />';
print_r($connectionTiers);
echo '</pre>';

echo '<pre>Found Buildings:<br />';
print_r($foundBuildings);
echo '</pre>';

echo '<pre>Checked Buildings:<br />';
print_r($checkedBuildings);
echo '</pre>';



//==============================================
//          LAST CONNECTION TIER BUILDINGS
//==============================================
//init csv of found buildings
$lastConnectionTierString='';
//Go for the next tiers
foreach ($connectionTiers as $key => $value) {
    if (is_array($value)) {
        foreach ($value as $keyY => $valueY) {
            $lastConnectionTierString.=$valueY.',';
        }
    }
    else{
    //add to csv string
    $lastConnectionTierString.=$value.',';
    }
}
//remove last comma
$lastConnectionTierString=rtrim($lastConnectionTierString,',');


//check to see if we have checked all found buildings
$diffCheck=array_diff_key($foundBuildings, $checkedBuildings);

if (isset($diffCheck)) {
    echo 'Not Matched.';
    print_r($diffCheck);
    //take steps to bring this array into compliance
    //check the foudn buildings
    foreach ($foundBuildings as $key => $value) {
        //is the current "found building" is a checked building
        if (in_array($value, $checkedBuildings)) {
            
        }
    }
}
else{
    echo 'Matched.';
}



echo '<div class="alert alert-info">END</div>';

echo '<pre>Connection Tiers:<br />';
print_r($connectionTiers);
echo '</pre>';

echo '<pre>Found Buildings:<br />';
print_r($foundBuildings);
echo '</pre>';

echo '<pre>Checked Buildings:<br />';
print_r($checkedBuildings);
echo '</pre>';


*/
?>
    


</div>
</div><!-- End Panel Body -->
</div><!-- End Panel -->

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
</body>
</html>
<?php
/**


/*
//get tier 1 data and store it.
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
//prepare statement
$stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID=:sourceBuilding || buildingb.building_UID=:sourceBuilding) GROUP BY INTERPANEL_ID");
//bind variables to query
$stmt->bindParam(':sourceBuilding', $sourceBuilding_UID);
//execute query
$stmt->execute();
//store variables
$connectionCounter=1;
    foreach($stmt as $row) {
    //if source is a store b as tier 1
    if ($sourceBuilding_UID==$row['A_BUID']) {
    //Add this uid to the list of found buildings
    array_push($foundBuildings, $row['B_BUID']);
    //Add the rest of the details here
    $connectionTiers[$connectionCounter][$row['B_BUID']]=$row['B_BUID'];
    }
    //if source is b store a as tier 1
    if ($sourceBuilding_UID==$row['B_BUID']) {
    //Add this uid to the list of found buildings
    array_push($foundBuildings, $row['A_BUID']);
    //Add the rest of the details here
    $connectionTiers[$connectionCounter][$row['A_BUID']]=$row['A_BUID'];
    }
        $connectionCounter++;
    }
}
//Catch Errors (if errors)
catch(PDOException $e){
//Report Error Message(s)
generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
}
*/



/*

//====================================
//          GET TIER 2
//====================================
echo '<div class="alert alert-info">TIER 2</div>';
echo 'Previous ConnectionTier:';
foreach ($foundBuildings as $key => $value) {
    //Display
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    //prepare statement
    $stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID IN(:foundBuildingsString) AND buildinga.building_UID NOT IN(:checkedBuildingsString) OR (buildingb.building_UID IN(:foundBuildingsString) AND buildingb.building_UID NOT IN(:checkedBuildingsString))) GROUP BY INTERPANEL_ID");
    //bind variables to query
    $stmt->bindParam(':foundBuildingsString', $value);
    $stmt->bindParam(':checkedBuildingsString', $checkedBuildingsString);
    //execute query
    $stmt->execute();
    //store variables
        $connectionCounter++;
    foreach($stmt as $row){
        //if source is a store b as tier 1
        if ($value==$row['A_BUID']) {
        //Add this uid to the list of found buildings
        array_push($foundBuildings, $row['B_BUID']);
        if (!in_array($value, $foundBuildings)) {
            array_push($foundBuildings, $value);
        }
        if (!in_array($value, $checkedBuildings)) {
            array_push($checkedBuildings, $value);
        }
        //Add the rest of the details here
        $connectionTiers[$connectionCounter][$row['B_BUID']]=$row['B_BUID'];
        }
        //if source is b store a as tier 1
        if ($value==$row['B_BUID']) {
        //Add this uid to the list of found buildings
        array_push($foundBuildings, $row['A_BUID']);
        if (!in_array($value, $foundBuildings)) {
            array_push($foundBuildings, $value);
        }
        if (!in_array($value, $checkedBuildings)) {
            array_push($checkedBuildings, $value);
        }
        //Add the rest of the details here
        $connectionTiers[$connectionCounter][$row['A_BUID']]=$row['A_BUID'];
        }
    }
    //print found and checked
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
    //Report Error Message(s)
    generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
    }
}

//reindex array keys
$connectionTiers = array_values($connectionTiers);
//remove duplicates of array
$foundBuildings=array_unique($foundBuildings);
//reindex array keys
$foundBuildings = array_values($foundBuildings);

echo '<pre>Connection Tiers:<br />';
print_r($connectionTiers);
echo '</pre>';

echo '<pre>Found Buildings:<br />';
print_r($foundBuildings);
echo '</pre>';

echo '<pre>Checked Buildings:<br />';
print_r($checkedBuildings);
echo '</pre>';

?>



<?php
//====================================
//          GET TIER 3
//====================================
echo '<div class="alert alert-info">TIER 3</div>';
$connectionCounter++;

foreach ($foundBuildings as $key => $value) {
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
        //prepare statement
        $stmt = $db->prepare("SELECT buildinga.building_UID AS A_BUID, buildinga.number AS A_NUMBER, buildinga.name AS A_NAME, storageunita.label AS A_SU, cabineta.label AS A_CAB, panela.position AS A_PAN, COUNT(strand.strand_UID) AS linkStrength, panelb.position AS B_PAN, cabinetb.label AS B_CAB, storageunitb.label AS B_SU, buildingb.name AS B_NAME, buildingb.number AS B_NUMBER, buildingb.building_UID AS B_BUID, CONCAT(panela.panel_UID,panelb.panel_UID) AS INTERPANEL_ID FROM strand INNER JOIN port AS porta ON porta.port_UID=strand.fk_port_UID_a INNER JOIN port AS portb ON portb.port_UID=strand.fk_port_UID_b INNER JOIN panel AS panela ON panela.panel_UID=porta.fk_panel_UID INNER JOIN panel AS panelb ON panelb.panel_UID=portb.fk_panel_UID INNER JOIN cabinet AS cabineta ON cabineta.cabinet_UID=panela.fk_cabinet_UID INNER JOIN cabinet AS cabinetb ON cabinetb.cabinet_UID=panelb.fk_cabinet_UID INNER JOIN storageunit AS storageunita ON storageunita.storageUnit_UID=cabineta.fk_storageUnit_UID INNER JOIN storageunit AS storageunitb ON storageunitb.storageUnit_UID=cabinetb.fk_storageUnit_UID INNER JOIN location AS locationa ON locationa.location_UID=storageunita.fk_location_UID INNER JOIN location AS locationb ON locationb.location_UID=storageunitb.fk_location_UID INNER JOIN building AS buildinga ON buildinga.building_UID=locationa.fk_building_UID INNER JOIN building AS buildingb ON buildingb.building_UID=locationb.fk_building_UID WHERE buildinga.building_UID!=buildingb.building_UID AND (buildinga.building_UID IN(:foundBuildingsString) AND buildinga.building_UID NOT IN(:checkedBuildingsString) OR (buildingb.building_UID IN(:foundBuildingsString) AND buildingb.building_UID NOT IN(:checkedBuildingsString))) GROUP BY INTERPANEL_ID");
        //bind variables to query
        $stmt->bindParam(':foundBuildingsString', $value);
        $stmt->bindParam(':checkedBuildingsString', $checkedBuildingsString);
        //execute query
        $stmt->execute();
        //store variables
        foreach($stmt as $row){
            //if source is a store b as tier 1
            if ($value==$row['A_BUID']) {
                //Add this uid to the list of found buildings
                array_push($foundBuildings, $row['B_BUID']);
                if(!in_array($value, $foundBuildings)) {
                    array_push($foundBuildings, $value);
                }
                if(!in_array($value, $checkedBuildings)) {
                    array_push($checkedBuildings, $value);
                }
                //Add the rest of the details here
                if (!in_array($row['B_BUID'], $connectionTiers)){
                    if (!in_array($row['B_BUID'], $connectionTiers[$connectionCounter-1])) {
                    $connectionTiers[$connectionCounter][$row['B_BUID']]=$row['B_BUID'];
                    }
                }
            }
            //if source is b store a as tier 1
            if ($value==$row['B_BUID']){
                //Add this uid to the list of found buildings
                array_push($foundBuildings, $row['A_BUID']);
                if (!in_array($value, $foundBuildings)){
                    array_push($foundBuildings, $value);
                }
                if (!in_array($value, $checkedBuildings)){
                    array_push($checkedBuildings, $value);
                }
                //Add the rest of the details here
                if (!in_array($row['A_BUID'], $connectionTiers)){
                    if (!in_array($row['B_BUID'], $connectionTiers[$connectionCounter-1])) {
                    $connectionTiers[$connectionCounter][$row['A_BUID']]=$row['A_BUID'];
                    }
                }
            }
        }
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
        //Report Error Message(s)
        generateAlert('danger','Could not retrieve list of buildings.<br />'.$e->getMessage());
    }
}

//reindex array keys
$connectionTiers = array_values($connectionTiers);
//remove duplicates of array
$foundBuildings = array_unique($foundBuildings);
//reindex array keys
$foundBuildings = array_values($foundBuildings);




echo '<pre>Connection Tiers:<br />';
print_r($connectionTiers);
echo '</pre>';


echo '<pre>Found Buildings:<br />';
print_r($foundBuildings);
echo '</pre>';

echo '<pre>Checked Buildings:<br />';
print_r($checkedBuildings);
echo '</pre>';

*/






//=======================================================================+
//      LOOP CHECK ALL TIERS UNTIL WE HAVE ONLY MATCHING DATA SETS.      |
//=======================================================================/


?>