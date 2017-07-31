<!DOCTYPE html>
<html lang="en">
<?php 
//include snippet - shared head html
include('snippets/sharedHead.php');

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
?>
<body>
<div id="wrapper">

<!-- START Navigation -->
<?php
$thisPage='manageCabinetContents';
echo '<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">';
include('snippets/sharedTopNav.php');
include('snippets/sharedSideNav.php');
echo '</nav>';
?>
<!-- END Navigation -->

<!-- START Page Content -->
<div id="page-wrapper">
<div class="container-fluid animated fadeIn">
<!-- Print Breadcrumbs -->
<?php 
$breadcrumbDetails=array();
$breadcrumbDetails=getBreadcrumbsForCabinet($_GET['uid']);
include('snippets/sharedBreadcrumbs.php');
?>

<div role="tabpanel" class="tab-pane fadeIn animated" id="manageCabinet">

    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header"><i class="fa fa-fw fa-edit"></i> Manage Cabinet Contents<br />
            <small>Manage panels, ports, and strands within a cabinet.</small></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-primary animated bounce">
                <div class="panel-heading">
                    <h3 class="panel-title">Cabinet Workspace</h3>
                </div>
                <div class="panel-body" id="currentWorkspaceParent" name="currentWorkspaceParent">
                    <div class="noBorder" id="currentWorkspaceContents" name="currentWorkspaceContents">
                        <p>Here's how this page works:</p>
                        <ol>
                            <li>Click the buttons below to do things within this cabinet.</li>
                            <li>The contents of this workspace (right here) will change accordingly.</li>
                            <li>Follow the provided instructions to accomplish the desired task.</li>
                        </ol>
                        <p>Click on an option below to try it out.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
//=====================================
//DEBUG
//=====================================
echo '<pre>GET:';
print_r($_GET);
echo '</pre>';
//$_GET['uid'] => 0162
echo '<pre>POST:';
print_r($_POST);
echo '</pre>';
//none
//get cabinet UID
//$thisCabinetUID=$_GET['uid'];
//getCabinetDetails($thisCabinetUID);
//showCabinetView($thisCabinetUID);
//function showCabinetView($thisCabinetUID){
//echo "Test for cabinet:".$thisCabinetUID;
//}







//===================================================
//Do many things BEFORE showing the cabinet view.
//===================================================

//Do any pending updates to the cabinet before showing the cabinet.
if (isset($_POST['manageCabinetAction'])) {
    switch ($_POST['manageCabinetAction']) {







        
        //add a strand
        case 'mapPanel':
            //find out what size the panels we are dealing with are...
            $mappingTypeOption=$_POST['mappingTypeOption'];

            switch ($_POST['mappingTypeOption']) {
                case 'sixToSix':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'TwelveToTwelve':
                    //set variables
                    $mappingPortCount=12;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'SixToTwelveTop':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'SixToTwelveBottom':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b AND port.number > 6 ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=7;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                        $i_b=$i+6;
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i_b]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                        try {
                        //prepare query
                        $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                        //bind parameters
                        $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                        $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                        $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                        $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                        $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                        $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                        $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                        $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                        $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                        //execute query
                        $stmt->execute();
                        echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                        }
                        //Catch Errors (if errors)
                        catch(PDOException $e){
                        //Report Error Message(s)
                        echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                        }

                    }

                    break;
                

                default:
                    # code...
                    break;
            }
            break;
        






        case 'updateCabinet':
            //load POST variables
            $thisCabinetUID=$_POST['thisCabinetUID'];
            $thisCabinetLabel=$_POST['cabinetLabel'];
            $thisPanelCapacity=$_POST['cabinetPanelCapacity'];
            $thisCabinetNotes=$_POST['cabinetNotes'];
            //do the actual update & report
            updateCabinetDetails($thisCabinetUID,$thisCabinetLabel,$thisPanelCapacity,$thisCabinetNotes);
            break;

        //add a panel
        case 'addPanel':

            echo "<pre>";
            print_r($_POST);
            echo "</pre>";

            //Check for post data
            if (isset($_POST['fk_cabinet_x']) && isset($_POST['addPanelSlot']) && isset($_POST['panelType']) && isset($_POST['portCapacity'])) {
                    //get POST Details
                    $addPanelSlot=$_POST['addPanelSlot'];
                    $fk_cabinet_x=$_POST['fk_cabinet_x'];
                    $panelType=$_POST['panelType'];
                    $portCapacity=$_POST['portCapacity'];

                    //create a panel here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //Add the panel
                    try {
                        //prepare query
                        $stmt = $db->prepare('INSERT INTO panel(fk_cabinet_UID,position,portCapacity,type) VALUES(:fk_cabinet_UID,:position,:portCapacity,:type)');
                        //bind variables
                        $stmt->bindParam(':fk_cabinet_UID', $fk_cabinet_x);
                        $stmt->bindParam(':position', $addPanelSlot);
                        $stmt->bindParam(':portCapacity', $portCapacity);
                        $stmt->bindParam(':type', $panelType);
                        //run query
                        $stmt->execute();

            //===============
            
                        //get last insert id
                        $newPanelId = $db->lastInsertId();

                        //Report Success
                        generateAlert('success','Panel '.$newPanelId.' Created.');
 
                        //Add ports
                        $addPortError=0;
                        for ($iPortsAddedToPanel=1; $iPortsAddedToPanel <= $portCapacity; $iPortsAddedToPanel++) { 
                            $dbx = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                            $dbx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            try {
                                $stmtx = $dbx->prepare('INSERT INTO port(fk_panel_UID, number) VALUES(:fk_panel_UID,:number)');
                                $stmtx->bindParam(':fk_panel_UID', $newPanelId);
                                $stmtx->bindParam(':number', $iPortsAddedToPanel);
                                $stmtx->execute();
                            }
                            catch(PDOException $ex){
                                generateAlert('danger','Error adding port to panel.<br />'.$ex->getMessage());
                                $addPortError=1;
                            }
                        }


                    }
                    //catch errors adding panel
                    catch(PDOException $e){
                        generateAlert('danger','There was an error adding this panel.<br />'.$e->getMessage());
                    }


                }
            else{
                generateAlert('danger','I do not have enough information to proceed.');
            }

            break;
        
//=============
//=============
//=============




        //add a jumper
        case 'addJumper':
        
        if (isset($_POST['addStrandDestinationDepartment']) && isset($_POST['addStrandEquipmentName']) && isset($_POST['addStrandEquipmentDesc'])) {
            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";

          

        //add the equipment
            $eqLocation=$_POST['addStrandDestinationLocation'];
            $eqMake=$_POST['addStrandDestinationEqMake'];
            $eqModel=$_POST['addStrandDestinationEqModel'];
            $eqDepartment=$_POST['addStrandDestinationDepartment'];
            $eqName=$_POST['addStrandEquipmentName'];
            $eqDesc=$_POST['addStrandEquipmentDesc'];
            //check for all details to insert equipment
            if (isset($eqLocation) && isset($eqMake) && isset($eqModel) && isset($eqDepartment) && isset($eqName) && isset($eqDesc)) {

                $equipmentSuccess=0;


                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        //$stmt = $db->prepare('INSERT INTO equipment SET fk_department_UID=:department, fk_location_UID=:location, fk_make_UID=:make, fk_model_UID=:model,name=:name,description=:description');
                        $stmt = $db->prepare('INSERT INTO equipment SET fk_department_UID=:department, fk_location_UID=:location, fk_make_UID=:make, fk_model_UID=:model,name=:name,description=:description ON DUPLICATE KEY UPDATE equipment_UID=LAST_INSERT_ID(equipment_UID), description=:description');

                        $stmt->bindParam(':department', $eqDepartment);
                        $stmt->bindParam(':location', $eqLocation);
                        $stmt->bindParam(':make', $eqMake);
                        $stmt->bindParam(':model', $eqModel);
                        $stmt->bindParam(':name', $eqName);
                        $stmt->bindParam(':description', $eqDesc);

                    //execute query
                    $stmt->execute();
                    
                    //get the new equipment id
                    $newEquipmentUID = $db->lastInsertId();

                    //Save Total Rows
                    //Report Success
                    $equipmentSuccess=1;
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Piece(s) of equipment has/have been created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';


                }



                //if the equipment was added make a jumper and add the new equipment uid as the destination.
            if ($equipmentSuccess && isset($_POST['port_a'])) {
                $port_a=$_POST['port_a'];

                //====================
                //create a jumper here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert jumper with equipment.
                        $stmt = $db->prepare('INSERT INTO jumper SET fk_port_UID_a=:port_a, fk_equipment_UID=:equipment');

                        $stmt->bindParam(':port_a', $port_a);
                        $stmt->bindParam(':equipment', $newEquipmentUID);

                    //execute query
                    $stmt->execute();
                    
                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Jumper added connecting to the following equipment:<br />
                        Name:'.$eqName.'<br />
                        Description:'.$eqDesc.'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

           
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //====================
            }


            }
            else{

                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to create equipment. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }







//newEquipmentUID

        //$lastId = $db->lastInsertId();

        //add the jumper
            //$port_a=$_POST['port_a'];


        }

        
        else{
            $port_a=$_POST['port_a'];
            $port_b=$_POST['addStrandDestinationPort'];
            if (isset($port_a) && isset($port_b)) {
                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        $stmt = $db->prepare('INSERT INTO jumper SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b');

                        $stmt->bindParam(':port_a', $port_a);
                        $stmt->bindParam(':port_b', $port_b);

                    //execute query
                    $stmt->execute();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Jumper Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{

                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to proceed. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
        }




            break;















//=============
//=============
//=============

        default:
            break;
    }
}

//Do these things IF there are no updates
else{

}

//=============================================
//DO THESE THINGS IF THERE IS A SPECIFIC CABINET UID.
//Do these things EVERY time regardless of any updates.
//=============================================
if (isset($_GET['uid']) && $_GET['uid']!=''){
    //Get Cabinet Details
    $thisCabinetUID=$_GET['uid'];
    $thisCabinetDetails=array();
    $thisCabinetDetails=getCabinetDetails($thisCabinetUID);
    $thisCabinetLabel=$thisCabinetDetails['label'];
    $thisPanelCapacity=$thisCabinetDetails['panelCapacity'];
    $thisCabinetNotes=$thisCabinetDetails['notes'];
    ?>

    <!-- Show Edit Cabinet Details Form -->
    <div class="row">
    <div class="col-xs-12">
        <a class="btn btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#editCabinetDetailsForm" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-fw fa-edit"></i> Toggle Edit Cabinet Details Form</a>
        <hr>
        <div id="editCabinetDetailsForm" class="text-center collapse " role="tabpanel" aria-labelledby="">
            <div class="well well-lg">
            <h4>Edit cabinet details</h4>
            <?php
            $formMethod='POST';
            $formAction='manageCabinet.php?uid='.$thisCabinetUID;
            generateEditCabinetForm($formAction,$formMethod,$thisCabinetUID,$thisCabinetLabel,$thisPanelCapacity,$thisCabinetNotes);
            ?>
            </div>
        </div>
    </div>
    </div>

    <?php
    //Get Cabinet, Panel, Port, Strand, and Jumper Details.
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    try {
        //prepare query
        $stmt = $db->prepare('SELECT cabinet.cabinet_UID,cabinet.label,cabinet.panelCapacity,panel.panel_UID,panel.position,panel.portCapacity,panel.type,port.port_UID,port.number,port.strandStatus,port.jumperStatus,strand.strand_UID,strand.length,strand.mode,strand.coreSize,strand.wavelength,strand.inTolerance,strand.spliceCount,strand.connectorPairsCount,strand.expectedLoss,strand.lastMeasuredLoss,strand.fk_port_UID_a AS strand_port_a,strand.fk_port_UID_b AS strand_port_b, jumper.jumper_UID,jumper.fk_port_UID_a AS jumper_port_a,jumper.fk_port_UID_b AS jumper_port_b,jumper.fk_equipment_UID FROM port LEFT OUTER JOIN panel ON panel.panel_UID=port.fk_panel_UID LEFT OUTER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID LEFT OUTER JOIN strand ON port.fk_strand_UID=strand.strand_UID LEFT OUTER JOIN jumper ON port.fk_jumper_UID=jumper.jumper_UID WHERE cabinet.cabinet_UID=:cabinet_UID');
        //bind parameters
        $stmt->bindParam(':cabinet_UID', $thisCabinetUID);
        //execute query
        $stmt->execute();
        //Store in multidimensional array
        //$iPorts=1;
        foreach($stmt as $row) {
        //get cabinet details
        $cabinetPortDetails[$row['cabinet_UID']]['cabinet_UID']=$row['cabinet_UID'];
        $cabinetPortDetails[$row['cabinet_UID']]['label']=$row['label'];
        $cabinetPortDetails[$row['cabinet_UID']]['panelCapacity']=$row['panelCapacity'];
        //get each panel details
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['panel_UID']=$row['panel_UID'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['position']=$row['position'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['portCapacity']=$row['portCapacity'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['type']=$row['type'];
        //get port details
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['port_UID']=$row['port_UID'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['number']=$row['number'];
        //strand details
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['status']=$row['strandStatus'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['strand_UID']=$row['strand_UID'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['length']=$row['length'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['mode']=$row['mode'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['coreSize']=$row['coreSize'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['wavelength']=$row['wavelength'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['inTolerance']=$row['inTolerance'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['spliceCount']=$row['spliceCount'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['connectorPairsCount']=$row['connectorPairsCount'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['expectedLoss']=$row['expectedLoss'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['lastMeasuredLoss']=$row['lastMeasuredLoss'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['strand_port_a']=$row['strand_port_a'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['strandDetails']['strand_port_b']=$row['strand_port_b'];
        //jumper details
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['jumperDetails']['status']=$row['jumperStatus'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['jumperDetails']['jumper_port_a']=$row['jumper_port_a'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['jumperDetails']['jumper_port_b']=$row['jumper_port_b'];
        $cabinetPortDetails[$row['cabinet_UID']]['panels'][$row['position']]['ports'][$row['number']]['jumperDetails']['fk_equipment_UID']=$row['fk_equipment_UID'];
        }
    }
    //Catch Errors (if errors)
    catch(PDOException $e){
        //Report Error Message(s)
        generateAlert('danger','Could not get port details for this cabinet.<br />'.$e->getMessage());
    }

    //display panels
    $panelCapacity=$cabinetPortDetails[$thisCabinetUID]['panelCapacity'];
    //echo '<div class="row">';
    for ($panelNdx=1; $panelNdx <= $panelCapacity; $panelNdx++) { 
        //Start New Row
        if ($panelNdx % 3==0) {
        echo '<div class="row">';
        }

        //Start Panel X Details
        echo '<div class="col-xs-12 col-lg-4">';

//Print Panel Details Section
if (isset($cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx])) {
//display existing panel
//delete existing panel
//generateAlert('info','Panel: '.$panelNdx.'.');

//===============================================

echo "<div class='panel'>";
echo "<div class='panel-heading'>";

    //Print Delete Panel Button
    echo "
    <form action='manageCabinet.php?uid=".$thisCabinetUID."' method='POST' class='form-inline'>
    <input type='hidden' id='manageCabinetAction' name='manageCabinetAction' value='removePanel'>
    <input type='hidden' id='removePanelUID' name='removePanelUID' value='".$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx][$panel_UID]."'>
    <button title='Delete this panel.' type='submit' class='inline-block text-right btn btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-fw fa-times'></i>
    </button>
    </form>
    ";

    //Print Panel Position and Type
    echo "
    <h4 class='text-center'>Panel ".$thisPanelPosition."</h4>
    <h4 class='text-center'>Panel Type: ".strtoupper($cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['type'])."</h4>
    <h4 class='text-center'><a data-cabinetUID='".$thisCabinetUID."' data-cabinetLabel='".$cabinetPortDetails[$thisCabinetUID]['label']."' data-panelUID='".$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['panel_UID']."' data-panelPosition='".$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['position']."' data-panelCap='".$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['portCapacity']."' class='mapPanel btn btn-default'><i class='fa fa-fw fa-exchange'></i> Map Panel</a></h4>
    ";

echo "
    <!-- LEGEND/KEY -->
    <div class='text-center noBorder'>                                                       
    <span class='label'><i class='fa fa-square text-success'> Port Active</i></span>
    <span class='label'><i class='fa fa-square text-muted'> Port Not Active</i></span>
    <br />
    <span class='label'><i class='fa fa-question text-muted'> Attenuation Unknown</i></span>
    <span class='label'><i class='fa fa-check-square-o text-success'> Attenuation Pass</i></span>
    <span class='label'><i class='fa fa-warning text-danger'> Attenuation Fail</i></span>
    <br />
    <span class='label'><i class='fa fa-info-circle text-info'> Info / Destination</i></span>
    <span class='label'><i class='fa fa-plus-square text-primary'> Add Strand/Jumper</i></span>
    <span class='label'><i class='fa fa-minus-square text-danger'> Remove Strand/Jumper</i></span>
    </div>
";
echo "</div>";

echo "<div class='panel-body'>";

//========

echo '
<table class="display table table-striped table-bordered table-hover panelRep">
<thead>
    <tr>
    <th colspan="" class="text-center">Boot<br />(Strand)</th>
    <th colspan="" class="text-center">Front<br />(Jumper)</th>
    <th colspan="" class="text-center">Boot<br />(Strand)</th>
    <th colspan="" class="text-center">Front<br />(Jumper)</th>
    </tr>
</thead>
<tbody>';

    for ($iPorts=1; $iPorts <= $cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['portCapacity']; $iPorts++){



        //Print for each port... 
        if ($iPorts==1) {
        echo '<tr>';
        }
        //===================================
        //START STRAND / BOOT PORTION OF PORT
        //===================================
        echo '<td class="text-center border-right">';
        





        //If this port has an active strand connected to it...
        if ($cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['strandDetails']['status']=='active'){
            //Print the strand info accordion controls
            echo '<a data-toggle="collapse" data-parent="#accordion" href="#strandInfo'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['panel_UID'].$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-info fa-fw fa-info-circle"></i></a>';
            //Print Port Number
            echo $iPorts.'<br />';
            //Print the appropriate icons
            echo '<span class="fa-stack fa-lg" id="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'" name="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'"> <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-success"></i> <i class="fa fa-fw fa fa-circle fa-stack-1x"></i></span>';
            //Delete Strand Button
            echo '<form class="form-inline" action="manageCabinet.php?uid='.$thisCabinetUID.'" method="POST"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="removeStrand" /> <input type="hidden" name="targetStrand" id="targetStrand" value="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['strandDetails']['strand_UID'].'" /> <button title="Remove this strand." class="noPaddingNoBorderButton" type="submit" onclick="return confirm(\'Are you sure?\')"><i class="fa text-danger fa-fw fa-minus-square"></i></button> </form>'; 
        }

        //If this port does NOT have an active strand connected to it...
        else{
            //Print Port Number
            echo $iPorts.'<br />';

            //Print the appropriate icons
            echo '<span class="fa-stack fa-lg" id="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'" name="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'"> <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-muted"></i> <i class="fa fa-fw fa fa-dot-circle-o fa-stack-1x"></i> </span>';

            //Print Add/Create/Connect Strand Button
            echo '<form><a title="Add a strand here." class="addStrandClass" data-cabinetLabel="'.$cabinetPortDetails[$thisCabinetUID]['label'].'" data-cabinetUID="'.$thisCabinetUID.'" data-panelPosition="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['position'].'" data-panelUID="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['panel_UID'].'" data-portNumber="'.$iPorts.'" data-portUID="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'"><i class="fa fa-fw fa-plus-square"></i></a> </form>'; 
        }
        echo '</td>';
        //=================================
        //END STRAND / BOOT PORTION OF PORT
        //=================================


        //====================================
        //START JUMPER / FRONT PORTION OF PORT
        //====================================
        echo '<td class="text-center border-left">';
        
        if ($cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['jumperDetails']['status']=='active') {
        echo '<a data-toggle="collapse" data-parent="#accordion" href="#jumperInfo'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx].$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-info fa-fw fa-info-circle"></i></a>';
        }
        echo ''.$iPorts.'<br />'; 
        if ($cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['jumperDetails']['status']=='active') {

        array_push($jumpersInThisCabinet, $cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['jumperDetails']['jumper_UID']);

        echo '
        <span class="fa-stack fa-lg" id="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'" name="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'">
        <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-success"></i>
        <i class="fa fa-fw fa fa-circle fa-stack-1x"></i>
        </span>';

        echo '
        <form class="form-inline" action="manageCabinet.php?uid='.$thisCabinetUID.'" method="POST">
        <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="removeJumper" />
        <input type="hidden" name="targetJumper" id="targetJumper" value="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['jumperDetails']['jumper_UID'].'" />
        <button title="Remove this jumper." class="noPaddingNoBorderButton" type="submit" onclick="return confirm(\'Are you sure?\')"><i class="fa text-danger fa-fw fa-minus-square"></i></button>
        </form>';

        }
        else{
        echo '
        <span class="fa-stack fa-lg" id="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'" name="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'">
        <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-muted"></i>
        <i class="fa fa-fw fa fa-dot-circle-o fa-stack-1x"></i>
        </span>';
        echo '
        <form>
        <a title="Add a jumper here." class="addJumperClass" data-cabinetLabel="'.$cabinetPortDetails[$thisCabinetUID]['label'].'" data-cabinetUID="'.$thisCabinetUID.'" data-panelPosition="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['position'].'" data-panelUID="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['panel_UID'].'" data-portNumber="'.$iPorts.'" data-portUID="'.$cabinetPortDetails[$thisCabinetUID]['panels'][$panelNdx]['ports'][$iPorts]['port_UID'].'"><i class="fa fa-fw fa-plus-square"></i></a>
        </form>';

        }
        echo '</td>';
        //==================================
        //END JUMPER / FRONT PORTION OF PORT
        //==================================



        if ($iPorts==$thisPanelPortCap) {
        echo '<tr>';
        }
        else if ($iPorts % 2 == 0) {
        echo '</tr>';
        echo '<tr>';
        }
        else{
        }


    }

    ?>
</tbody>
</table>
<?php
echo "</div>";
echo "</div>";
}

//Print No Panel Section
else{
//add new panel
//generateAlert('warning','No Panel In Slot '.$panelNdx.'.');
//Print add panel form.
?>

<div class="panel panel-warning">
<div class="panel-heading">
<h4 class="text-center">Empty Slot (<?php echo $panelNdx;?>)</h4>
<p class="text-center">Add a panel by clicking on the button below.</p> 
</div> 
<div class="panel-body"> 
<?php 
echo '<a title="Add a panel here." class="btn btn-primary btn-block" data-toggle="collapse" data-target="#addPanel'.$panelNdx.'">';
?>

<i class="fa fa-fw fa-align-left fa-plus-circle"></i> Add Panel Here </a> <br /> 
<div <?php echo ' id="addPanel'.$panelNdx.'" ';?> class="panel panel-default collapse">


<div class="panel-body"> 
<?php 
$formAction='manageCabinet.php?uid='.$thisCabinetUID;
$formMethod='POST';
generateAddPanelForm($formAction,$formMethod,$panelNdx,$thisCabinetUID);
?>
</div> 
</div> 
</div> 
</div>
<?php
}
    //End Panel X Details
    echo '</div>';

    //End New Row
    if ($panelNdx % 3==0) {
    echo '</div>';
    }
}

echo '
<div class="row">
<div class="col-xs-12">
';

    //DEBUG
    echo '<pre>';
    print_r($cabinetPortDetails);
    echo '</pre>';

echo '
</div>
</div>
';  

}
//=============================================
//NO CABINET UID HAS BEEN SPECIFIED
//=============================================
else{
    generateAlert('danger','No cabinet has been specified. You may need to <a href="guidedRecordCreation.php">go back</a> and try this again.');
}







/*
//=========================================================
if (isset($_POST['manageCabinetAction'])) {
//START manage cabinet actions
    switch ($_POST['manageCabinetAction']) {


        //add a strand
        case 'mapPanel':
            //find out what size the panels we are dealing with are...
            $mappingTypeOption=$_POST['mappingTypeOption'];

            switch ($_POST['mappingTypeOption']) {
                case 'sixToSix':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'TwelveToTwelve':
                    //set variables
                    $mappingPortCount=12;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'SixToTwelveTop':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                    try {
                    //prepare query
                    $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                    //bind parameters
                    $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                    $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                    $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                    $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                    $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                    $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                    $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                    $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                    $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                    //execute query
                    $stmt->execute();
                    echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    }

                    break;
                
                case 'SixToTwelveBottom':
                    //set variables
                    $mappingPortCount=6;
                    $panel_a=$_POST['panel_a'];
                    $panel_b=$_POST['addStrandDestinationPanel'];
                    $strandLength=$_POST['strandLength'];
                    $strandMode=$_POST['strandMode'];
                    $strandCore=$_POST['strandCore'];
                    $strandWave=$_POST['strandWave'];
                    $strandSpliceCount=$_POST['strandSpliceCount'];
                    $strandConPairs=$_POST['strandConPairs'];
                    $strandExpLoss=$_POST['strandExpLoss'];

                    $panel_ports_a=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_a ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_a', $panel_a);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=1;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_A[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_A[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }

                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel A.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    $panel_ports_b=array();
                    //create a building here.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        //prepare query
                        $stmt = $db->prepare('SELECT panel.position, panel.panel_UID, port.port_UID,port.number FROM port INNER JOIN panel ON panel.panel_UID=port.fk_panel_UID WHERE fk_panel_UID=:panel_b AND port.number > 6 ORDER BY number ASC');
                        //bind parameters
                        $stmt->bindParam(':panel_b', $panel_b);
                        //execute query
                        $stmt->execute();
                        //Store in multidimensional array
                        $iPorts=7;
                        foreach($stmt as $row) {
                            //$panel_Ports_A['panel_UID']=$row['panel_UID'];
                            //$panel_Ports_A['panel_position']=$row['position'];
                            $panel_Ports_B[$iPorts]['UID']=$row['port_UID'];
                            $panel_Ports_B[$iPorts]['number']=$row['number'];
                            $iPorts++;
                        }
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: Could not find the ports in panel B.<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

                    //SET PORT INFORMATION FOR EACH STRAND INSERT
                    $strandCreationArray=array();
                    for ($i=1; $i <= $mappingPortCount; $i++) { 
                        $i_b=$i+6;
                    //set strand creation details
                    //echo '<hr>'.$panel_Ports_A[$i]['UID'].'<hr>';
                    $strandCreationArray[$i]['port_a']=$panel_Ports_A[$i]['UID'];
                    $strandCreationArray[$i]['port_b']=$panel_Ports_B[$i_b]['UID'];
                    //add strand details
                    $strandCreationArray[$i]['strandLength']=$_POST['strandLength'];
                    $strandCreationArray[$i]['strandMode']=$_POST['strandMode'];
                    $strandCreationArray[$i]['strandCore']=$_POST['strandCore'];
                    $strandCreationArray[$i]['strandWave']=$_POST['strandWave'];
                    $strandCreationArray[$i]['strandSpliceCount']=$_POST['strandSpliceCount'];
                    $strandCreationArray[$i]['strandConPairs']=$_POST['strandConPairs'];
                    $strandCreationArray[$i]['strandExpLoss']=$_POST['strandExpLoss'];
                    //print strand creation details
                    //echo '<br />Port A ('.$panel_Ports_A[$i]['UID'].') to Port B ('.$panel_Ports_B[$i]['UID'].')';
                    }

                    //create some strands yo.
                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    foreach ($strandCreationArray as $key => $value) {


                        try {
                        //prepare query
                        $stmt = $db->prepare('INSERT INTO strand SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b, length=:length, mode=:mode, coreSize=:coreSize, wavelength=:wavelength, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:expectedLoss');
                        //bind parameters
                        $stmt->bindParam(':port_a', $strandCreationArray[$key]['port_a']);
                        $stmt->bindParam(':port_b', $strandCreationArray[$key]['port_b']);
                        $stmt->bindParam(':length', $strandCreationArray[$key]['strandLength']);
                        $stmt->bindParam(':mode', $strandCreationArray[$key]['strandMode']);
                        $stmt->bindParam(':coreSize', $strandCreationArray[$key]['strandCore']);
                        $stmt->bindParam(':wavelength', $strandCreationArray[$key]['strandWave']);
                        $stmt->bindParam(':spliceCount', $strandCreationArray[$key]['strandSpliceCount']);
                        $stmt->bindParam(':connectorPairsCount', $strandCreationArray[$key]['strandConPairs']);
                        $stmt->bindParam(':expectedLoss', $strandCreationArray[$key]['strandExpLoss']);
                        //execute query
                        $stmt->execute();
                        echo '<div class="alert alert-success"><strong>Success!</strong>:<br /> Strand Created Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].') <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                        }
                        //Catch Errors (if errors)
                        catch(PDOException $e){
                        //Report Error Message(s)
                        echo '<div class="alert alert-danger"><strong>Error!</strong>:<br /> Strand Creation Failed Between Port A ('.$strandCreationArray[$key]['port_a'].') and Port B ('.$strandCreationArray[$key]['port_b'].')<br />'.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                        }

                    }

                    break;
                

                default:
                    # code...
                    break;
            }




            break;











        //remove a panel
        case 'removePanel':
            if (isset($_POST['removePanelUID'])) {
                $removePanelUID=$_POST['removePanelUID'];
                





            //DELETE THE PANEL
                //Get the parent cabinet information
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                $stmt = $db->prepare('DELETE from panel where panel_UID=:panel');
                $stmt->bindParam(':panel', $removePanelUID);
                $stmt->execute();
                    echo "
                    <div class='alert alert-success'>
                    The panel in question (".$removePanelUID.") has been removed.
                    </div>
                    ";
                }catch(PDOException $e){
                echo "
                    <div class='alert alert-danger'>
                    The panel in question (".$removePanelUID.") was not removed.<br />
                    Error:".$e."
                    </div>
                    ";
                }


//


            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;
        
        //add a panel
        case 'addPanel':

            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";

            if (isset($_POST['fk_cabinet_x']) && isset($_POST['addPanelSlot']) && isset($_POST['panelType']) && isset($_POST['portCapacity'])) {
                //echo "I should be adding a panel now.";
                $addPanelSlot=$_POST['addPanelSlot'];
                $fk_cabinet_x=$_POST['fk_cabinet_x'];
                $panelType=$_POST['panelType'];
                $portCapacity=$_POST['portCapacity'];

                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        $stmt = $db->prepare('INSERT INTO panel(fk_cabinet_UID,position,portCapacity,type) VALUES(:fk_cabinet_UID,:position,:portCapacity,:type)');
                        //asdf
                        $stmt->bindParam(':fk_cabinet_UID', $fk_cabinet_x);
                        $stmt->bindParam(':position', $addPanelSlot);
                        $stmt->bindParam(':portCapacity', $portCapacity);
                        $stmt->bindParam(':type', $panelType);

                    //execute query
                    $stmt->execute();

                    //get last insert id
                    $newPanelId = $db->lastInsertId();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Panel Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

                        $addPortError=0;

                    for ($iPortsAddedToPanel=1; $iPortsAddedToPanel <= $portCapacity; $iPortsAddedToPanel++) { 

                        //create a building here.
                        $dbx = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                        $dbx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        try {
                            //insert building with note.
                                $stmtx = $dbx->prepare('INSERT INTO port(fk_panel_UID, number) VALUES(:fk_panel_UID,:number)');
                                //asdf
                                $stmtx->bindParam(':fk_panel_UID', $newPanelId);
                                $stmtx->bindParam(':number', $iPortsAddedToPanel);

                            //execute query
                            $stmtx->execute();
                        }
                        //Catch Errors (if errors)
                        catch(PDOException $ex){
                        //Report Error Message(s)
                        echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$ex->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                        $addPortError=1;
                        }



                    }
                    //Report Success
                    if ($addPortError==1) {
                        echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$iPortsAddedToPanel.' Ports Created Within the New Panel.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                        $addPortError=0;





                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }








            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;

        //add a strand
        case 'addStrand':
            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";
            //echo "I should be adding a strand now.";

            //set variables
            $port_a=$_POST['port_a'];
            $port_b=$_POST['addStrandDestinationPort'];
            $strandLength=$_POST['strandLength'];
            $strandMode=$_POST['strandMode'];
            $strandCore=$_POST['strandCore'];
            $strandWave=$_POST['strandWave'];
            $strandSpliceCount=$_POST['strandSpliceCount'];
            $strandConPairs=$_POST['strandConPairs'];
            $strandExpLoss=$_POST['strandExpLoss'];
            $lastCabinet="0131";
            $lastCabinet=$_POST['lastCabinet'];


            if (isset($port_a) && isset($port_b) && isset($strandLength) && isset($strandMode) && isset($strandCore) && isset($strandWave) && isset($strandSpliceCount) && isset($strandConPairs) && isset($strandExpLoss)) {

                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        $stmt = $db->prepare('INSERT INTO strand SET length=:strandLength, mode=:strandMode, coreSize=:strandCore, wavelength=:strandWave, expectedLoss=:strandExpLoss, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, notes="", fk_port_UID_a=:port_a, fk_port_UID_b=:port_b');

                        $stmt->bindParam(':port_a', $port_a);
                        $stmt->bindParam(':port_b', $port_b);
                        $stmt->bindParam(':strandLength', $strandLength);
                        $stmt->bindParam(':strandMode', $strandMode);
                        $stmt->bindParam(':strandCore', $strandCore);
                        $stmt->bindParam(':strandWave', $strandWave);
                        $stmt->bindParam(':spliceCount', $strandSpliceCount);
                        $stmt->bindParam(':connectorPairsCount', $strandConPairs);
                        $stmt->bindParam(':strandExpLoss', $strandExpLoss);

                    //execute query
                    $stmt->execute();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success">
                    
                    <strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Strand Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }

            }
            else{
                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to proceed. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }


            break;

        //update a cabinet
        case 'updateCabinet':
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "I should be updating a cabinet now.";


            break;



        //update a strand
        case 'updateStrand':
            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";
            //echo "I should be adding a strand now.";

            //set variables
            $manageCabinetAction=$_POST['manageCabinetAction'];
            $targetStrand=$_POST['targetStrand'];
            $strandLength=$_POST['strandLength'];
            $strandMode=$_POST['strandMode'];
            $strandCore=$_POST['strandCore'];
            $strandWave=$_POST['strandWave'];
            $strandSpliceCount=$_POST['strandSpliceCount'];
            $strandConPairs=$_POST['strandConPairs'];
            $strandExpLoss=$_POST['strandExpLoss'];
            $lastMeasuredLoss=$_POST['lastMeasuredLoss'];
            $strandNotes=$_POST['strandNotes'];

            if (isset($manageCabinetAction) && isset($targetStrand) && isset($strandLength) && isset($strandMode) && isset($strandCore) && isset($strandWave) && isset($strandSpliceCount) && isset($strandConPairs) && isset($strandExpLoss) && isset($lastMeasuredLoss) && isset($strandNotes)){
                //echo "Ready to update strand!";
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                //update strand.
                    $stmt = $db->prepare('UPDATE strand SET length=:strandLength, mode=:strandMode, coreSize=:strandCore, wavelength=:strandWave, spliceCount=:spliceCount, connectorPairsCount=:connectorPairsCount, expectedLoss=:strandExpLoss, lastMeasuredLoss=:lastMeasuredLoss, notes=:strandNotes WHERE strand_UID=:strand_UID LIMIT 1');
                    $stmt->bindParam(':strand_UID', $targetStrand);
                    $stmt->bindParam(':strandLength', $strandLength);
                    $stmt->bindParam(':strandMode', $strandMode);
                    $stmt->bindParam(':strandCore', $strandCore);
                    $stmt->bindParam(':strandWave', $strandWave);
                    $stmt->bindParam(':spliceCount', $strandSpliceCount);
                    $stmt->bindParam(':connectorPairsCount', $strandConPairs);
                    $stmt->bindParam(':strandExpLoss', $strandExpLoss);
                    $stmt->bindParam(':lastMeasuredLoss', $lastMeasuredLoss);
                    $stmt->bindParam(':strandNotes', $strandNotes);

                //execute query
                $stmt->execute();

                //Save Total Rows
                //Report Success
                echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Strand Updated. </div>';
                //<a href="manageCabinet.php?uid='.$lastCabinet.'">Back to Cabinet</a>.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }

            }
            else{
                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to proceed. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }


            break;







        //add a jumper
        case 'addJumper':
        
        if (isset($_POST['addStrandDestinationDepartment']) && isset($_POST['addStrandEquipmentName']) && isset($_POST['addStrandEquipmentDesc'])) {
            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";

          

        //add the equipment
            $eqLocation=$_POST['addStrandDestinationLocation'];
            $eqMake=$_POST['addStrandDestinationEqMake'];
            $eqModel=$_POST['addStrandDestinationEqModel'];
            $eqDepartment=$_POST['addStrandDestinationDepartment'];
            $eqName=$_POST['addStrandEquipmentName'];
            $eqDesc=$_POST['addStrandEquipmentDesc'];
            //check for all details to insert equipment
            if (isset($eqLocation) && isset($eqMake) && isset($eqModel) && isset($eqDepartment) && isset($eqName) && isset($eqDesc)) {

                $equipmentSuccess=0;


                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        //$stmt = $db->prepare('INSERT INTO equipment SET fk_department_UID=:department, fk_location_UID=:location, fk_make_UID=:make, fk_model_UID=:model,name=:name,description=:description');
                    $stmt = $db->prepare('INSERT INTO equipment SET fk_department_UID=:department, fk_location_UID=:location, fk_make_UID=:make, fk_model_UID=:model,name=:name,description=:description ON DUPLICATE KEY UPDATE equipment_UID=LAST_INSERT_ID(equipment_UID), description=:description');

                        $stmt->bindParam(':department', $eqDepartment);
                        $stmt->bindParam(':location', $eqLocation);
                        $stmt->bindParam(':make', $eqMake);
                        $stmt->bindParam(':model', $eqModel);
                        $stmt->bindParam(':name', $eqName);
                        $stmt->bindParam(':description', $eqDesc);

                    //execute query
                    $stmt->execute();
                    
                    //get the new equipment id
                    $newEquipmentUID = $db->lastInsertId();

                    //Save Total Rows
                    //Report Success
                    $equipmentSuccess=1;
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Piece(s) of equipment has/have been created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';


                }



                //if the equipment was added make a jumper and add the new equipment uid as the destination.
            if ($equipmentSuccess && isset($_POST['port_a'])) {
                $port_a=$_POST['port_a'];

                //====================
                //create a jumper here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert jumper with equipment.
                        $stmt = $db->prepare('INSERT INTO jumper SET fk_port_UID_a=:port_a, fk_equipment_UID=:equipment');

                        $stmt->bindParam(':port_a', $port_a);
                        $stmt->bindParam(':equipment', $newEquipmentUID);

                    //execute query
                    $stmt->execute();
                    
                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Jumper added connecting to the following equipment:<br />
                        Name:'.$eqName.'<br />
                        Description:'.$eqDesc.'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

           
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //====================
            }


            }
            else{

                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to create equipment. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }







//newEquipmentUID

        //$lastId = $db->lastInsertId();

        //add the jumper
            //$port_a=$_POST['port_a'];


        }

        
        else{
            $port_a=$_POST['port_a'];
            $port_b=$_POST['addStrandDestinationPort'];
            if (isset($port_a) && isset($port_b)) {
                //create a building here.
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
                    //insert building with note.
                        $stmt = $db->prepare('INSERT INTO jumper SET fk_port_UID_a=:port_a, fk_port_UID_b=:port_b');

                        $stmt->bindParam(':port_a', $port_a);
                        $stmt->bindParam(':port_b', $port_b);

                    //execute query
                    $stmt->execute();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Jumper Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
                //Catch Errors (if errors)
                catch(PDOException $e){
                //Report Error Message(s)
                echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                }
            }
            else{

                echo '<div class="alert alert-danger"><strong>Error!</strong>: I do not have enough information to proceed. <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
        }




            break;

















        //remove a jumper
        case 'removeJumper':
            if (isset($_POST['manageCabinetAction']) && $_POST['manageCabinetAction']=='removeJumper' && isset($_POST['targetJumper'])) {
                //echo "<pre>";
                //print_r($_POST);
                //echo "</pre>";
                $targetJumper=$_POST['targetJumper'];

                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        $stmt = $db->prepare('delete from jumper where jumper_UID=:jumper_UID');
                        $stmt->bindParam(':jumper_UID', $targetJumper);
                      
                    //execute query
                    $stmt->execute();
echo "<div class='alert alert-success'><p><b>Success!</b> - The jumper with UID ".$targetJumper." has been removed.</p></div>";
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;



        //remove a strand
        case 'removeStrand':
            if (isset($_POST['manageCabinetAction']) && $_POST['manageCabinetAction']=='removeStrand' && isset($_POST['targetStrand'])) {
                //echo "<pre>";
                //print_r($_POST);
                //echo "</pre>";
                $targetStrand=$_POST['targetStrand'];

                    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                        $stmt = $db->prepare('delete from strand where strand_UID=:strand_UID');
                        $stmt->bindParam(':strand_UID', $targetStrand);
                      
                    //execute query
                    $stmt->execute();
echo "<div class='alert alert-success'><p><b>Success!</b> - The strand with UID ".$targetStrand." has been removed.</p></div>";
                    }
                    //Catch Errors (if errors)
                    catch(PDOException $e){
                    //Report Error Message(s)
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }

            }
            else{
                echo "I do not have enough information to proceed.";
            }
            break;
   
    }
//END manage cabinet actions

}
//=========================================================
*/




    ?>

</div><!-- END MANAGE CABINET PAGE -->
</div><!-- /.container-fluid -->
</div><!-- /#page-wrapper -->
</div><!-- /#wrapper -->



    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Custom Scripts -->
    <script type="text/javascript">
        //change buildings
        $("#manageCabinetParentBuilding").change(function(event) {
        //get the user selections
        var selectedBuilding = $("#manageCabinetParentBuilding").val();
        var targetDiv = document.getElementById( "levelBreadcrumbField" );
        //load the child levels based on user selections
        $(targetDiv).html('');
            $('#locationBreadcrumbField').html('');
            $('#storageUnitBreadcrumbField').html('');
            $('#cabinetBreadcrumbField').html('');
        $(targetDiv).addClass('animated fadeInDown');
        $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=levels&parentBuilding="+selectedBuilding);
        $(targetDiv).show("slow");
        });

        //change levels
        $("#manageCabinetParentLevel").change(function(event) {
            //get the user selections
            var selectedBuilding = $("#manageCabinetParentBuilding").val();
            var selectedLevel = $("#manageCabinetParentLevel").val();
            var targetDiv = document.getElementById( "locationBreadcrumbField" );
            //load the child levels based on user selections
            $(targetDiv).html('');
                $('#storageUnitBreadcrumbField').html('');
                $('#cabinetBreadcrumbField').html('');
            $(targetDiv).addClass('animated fadeInDown');
            $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=locations&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel);
            $(targetDiv).show("slow");
        });

        //change locations
        $("#manageCabinetParentLocation").change(function(event) {
            //get the user selections
            var selectedBuilding = $("#manageCabinetParentBuilding").val();
            var selectedLevel = $("#manageCabinetParentLevel").val();
            var selectedLocation = $("#manageCabinetParentLocation").val();
            var targetDiv = document.getElementById( "storageUnitBreadcrumbField" );
            //load the child levels based on user selections
            $(targetDiv).html('');
                $('#cabinetBreadcrumbField').html('');
            $(targetDiv).addClass('animated fadeInDown');
            $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=storageUnits&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation);
            $(targetDiv).show("slow");
        });

        //change storage units
        $("#manageCabinetParentStorageUnit").change(function(event) {
            //get the user selections
            var selectedBuilding = $("#manageCabinetParentBuilding").val();
            var selectedLevel = $("#manageCabinetParentLevel").val();
            var selectedLocation = $("#manageCabinetParentLocation").val();
            var selectedStorageUnit = $("#manageCabinetParentStorageUnit").val();
            var targetDiv = document.getElementById("cabinetBreadcrumbField");
            //load the child levels based on user selections
            $(targetDiv).html('');
            $(targetDiv).addClass('animated fadeInDown');
            $(targetDiv).load("snippets/ajax/loadDependentSelect.php?form=manageCabinet&load=cabinets&parentBuilding="+selectedBuilding+"&parentLevel="+selectedLevel+"&parentLocation="+selectedLocation+"&parentStorageUnit="+selectedStorageUnit);
            $(targetDiv).show("slow");
        });

        //change cabinet UIDs
        $("#manageCabinetUID").change(function(event) {
            //get the user selections
            var selectedBuilding = $("#manageCabinetParentBuilding").val();
            var selectedLevel = $("#manageCabinetParentLevel").val();
            var selectedLocation = $("#manageCabinetParentLocation").val();
            var selectedStorageUnit = $("#manageCabinetParentStorageUnit").val();
            var selectedCabinet = $("#manageCabinetUID").val();
            var targetDiv = document.getElementById( "cabinetRepresentationDiv" );
            //load the child levels based on user selections
            $(targetDiv).html('');
            $(targetDiv).addClass('animated fadeInDown');
            $(targetDiv).load("snippets/ajax/manageCabinetContents.php?uid="+selectedCabinet);
            window.location = './manageCabinet.php?uid='+selectedCabinet;
        });

        $("#createCabinetParentBuilding").change(function(event) {
        //get the user selections
        var selectedBuilding = $("#createCabinetParentBuilding").val();
        //alert(selectedBuilding);
        //load the child levels based on user selections
        $("#createCabinetDependentLevelsContainer").html('');
        $("#createCabinetDependentLevelsContainer").load("snippets/ajax/loadDependentSelect.php?form=addCabinet&load=levels&parentBuilding="+selectedBuilding);
        //tell user to select a level
        //load alert
        $("#createCabinetDependentLocationsContainer").html('<div class="alert alert-info">Please select a level.</div>');
        });

        $("#cabinetWorkspaceDemoReset").click(function(event) {
        //var targetDiv = document.getElementById("currentWorkspaceContents");
        var targetDiv = document.getElementById("currentWorkspaceParent");
        var targetDivContents = document.getElementById("currentWorkspaceContents");
        var imageUrl = '../dist/img/blueprintPaper.png';
        //Reset Background
        $(targetDiv).css('background', 'url(' + imageUrl + ')');
        $(targetDiv).css('color', 'white');
        //Reset Contents
        $(targetDivContents).html('');
        $(targetDivContents).html("<p>Here's how this page works:</p><ol><li>Click the buttons below to do things within this cabinet.</li><li>The contents of this workspace (right here) will change accordingly.</li><li>Follow the provided instructions to accomplish the desired task.</li> </ol> <p>Click on an option below to try it out.</p>"); 
        //Animate to draw attention
        $(targetDiv).removeClass('animated bounce');
        $(targetDiv).addClass('animated bounce');
        });

        $("#cabinetWorkspaceDemo1").click(function(event) {
        //var targetDiv = document.getElementById("currentWorkspaceContents");
        var targetDiv = document.getElementById("currentWorkspaceParent");
        //load css changes to give an example
        $(targetDiv).css('background-image', 'none');
        $(targetDiv).css('background-color', 'white');
        $(targetDiv).css('color', '#333');
        //Animate to draw attention
        $(targetDiv).removeClass('animated bounce');
        $(targetDiv).addClass('animated bounce');
        });

        $("#cabinetWorkspaceDemo2").click(function(event) {
        var targetDiv = document.getElementById("currentWorkspaceContents");
        var imageUrl = '../dist/img/elephantHeadPolygon.png';
        //var targetDiv = document.getElementById("currentWorkspaceParent");
        $(targetDiv).html('');
        $(targetDiv).html('<img class="img-responsive" src="'+imageUrl+'"></img>');
        //Animate to draw attention
        $(targetDiv).removeClass('animated bounce');
        $(targetDiv).addClass('animated bounce');
        });

        $(".addJumperClass").click(function(event) {
        var targetDiv = document.getElementById("currentWorkspaceContents");
        var value_cabinetUID = $(this).attr('data-cabinetUID');
        var value_cabinetLabel = $(this).attr('data-cabinetLabel');
        var value_panelUID = $(this).attr('data-panelUID');
        var value_panelPosition = $(this).attr('data-panelPosition');
        var value_portUID = $(this).attr('data-portUID');
        var value_portNumber = $(this).attr('data-portNumber');
        //init a variable to hold the html we will insert
        var htmltoInsert='';
        //craft the add jumper form - start with user directions etc
        htmltoInsert+='<p><label class="label label-primary">ADD JUMPER</label> <label class="label label-primary">FROM</label> <label class="label label-primary">cabinet ' + value_cabinetLabel + '</label> <label class="label label-primary">panel ' + value_panelPosition + '</label> <label class="label label-primary">port ' + value_portNumber + '</label></p>';
        //start form
        htmltoInsert+='<form id="addJumperForm" name="addJumperForm" action="manageCabinet.php?uid=' + value_cabinetUID + '" method="POST">';
        //add hidden fields
        htmltoInsert+='<input type="hidden" name="panel_a" id="panel_a" value="' + value_panelUID + '">';
        htmltoInsert+='<input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="addJumper">';
        htmltoInsert+='<input type="hidden" name="port_a" id="port_a" value="' + value_portUID + '">';
        htmltoInsert+='<input type="hidden" name="lastCabinet" id="lastCabinet" value="' + value_cabinetUID + '">';
        //add containers to add things to via ajax
        htmltoInsert+='<div id="workSpaceDestinationBuilding" name="workSpaceDestinationBuilding"></div> <div id="workSpaceDestinationLevel" name="workSpaceDestinationLevel"></div> <div id="workSpaceDestinationLocation" name="workSpaceDestinationLocation"></div> <div id="workSpaceDestinationMake" name="workSpaceDestinationMake"></div><div id="workSpaceDestinationModel" name="workSpaceDestinationModel"></div><div id="workSpaceDestinationDepartment" name="workSpaceDestinationDepartment"></div> <div id="workSpaceDestinationDesc" name="workSpaceDestinationDesc"></div><div id="workSpaceDestinationStorageUnit" name="workSpaceDestinationStorageUnit"></div> <div id="workSpaceDestinationCabinet" name="workSpaceDestinationCabinet"></div> <div id="workSpaceDestinationPanel" name="workSpaceDestinationPanel"></div> <div id="workSpaceDestinationPort" name="workSpaceDestinationPort"></div>';
        //add final submit button
        htmltoInsert+='<div class="form-group"> <button type="submit" class="btn btn-default">Create Jumper</button></div>';
        //end form
        htmltoInsert+='</form>';
        //clear the work area where the add jumper form will be placed
        $(targetDiv).html('');
        //insert the add jumper form
        $(targetDiv).html(htmltoInsert); 
        $(targetDiv).removeClass('animated bounce');
        //find out where to load the destination building options
        var loadBuildingHereDiv = document.getElementById('workSpaceDestinationBuilding');
        //add building options via ajax
        $(loadBuildingHereDiv).load('snippets/ajax/loadDependentSelect.php?form=addStrand&load=buildings&checkJumpers=yes');
        //draw user attention to the new destination building options
        $(targetDiv).addClass('animated bounce');
        });

        $(".mapPanel").click(function(event) {
        //get events
        var value_cabinetUID = $(this).attr('data-cabinetUID');
        var value_cabinetLabel = $(this).attr('data-cabinetLabel');
        var value_panelUID = $(this).attr('data-panelUID');
        var value_panelCap = $(this).attr('data-panelCap');
        var value_panelPosition = $(this).attr('data-panelPosition');

        //var value_mapPanelsType=$('mapPanelForm input[type=radio]:checked').val();
        //alert(value_mapPanelsType);

 
 //alert($('input[name=radioName]:checked', '#myForm').val());

        //set the workspace as the target html destination
        var targetDiv = document.getElementById("currentWorkspaceContents");
        //init a variable to hold the html we will insert
        var htmltoInsert='';
        //clear the work area where the add strand form will be placed
        $(targetDiv).html('');
        //add some user direction etc...
        htmltoInsert+='<p><label class="label label-primary">MAP</label> <label class="label label-primary">Cabinet ' + value_cabinetLabel + '</label> <label class="label label-primary">Panel ' + value_panelPosition + '</label> <label class="label label-primary">to</label></p>';
        //START FORM:
        htmltoInsert+='<form id="mapPanelForm" name="mapPanelForm" action="manageCabinet.php?uid=' + value_cabinetUID + '" method="POST">';
        //add hidden fields
        htmltoInsert+='<input type="hidden" name="panel_a" id="panel_a" value="' + value_panelUID + '"> <input type="hidden" name="lastCabinet" id="lastCabinet" value="' + value_cabinetUID + '"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="mapPanel">';
        
        //Mapping type: start formgroup
        htmltoInsert+='<div class="form-group">';

        //Mapping type: user-direction
        htmltoInsert+='<div class="help-block"><p>Select one of the following options first:</p></div>';
        
        //Mapping type: 6-6
        htmltoInsert+='<div class="radio"> <label> <input type="radio" name="mappingTypeOption" id="mappingTypeSixToSix" value="sixToSix" checked> Map a 6-port panel to another 6-port panel. </label> </div>';
        
        //Mapping type: 12-12
        htmltoInsert+='<div class="radio"> <label> <input type="radio" name="mappingTypeOption" id="mappingTypeTwelveToTwelve" value="TwelveToTwelve"> Map a 12-port panel to another 12-port panel. </label> </div>';
        
        //Mapping type: 6-12 (top)
        htmltoInsert+='<div class="radio"> <label> <input type="radio" name="mappingTypeOption" id="mappingTypeSixToTwelveTop" value="SixToTwelveTop"> Map a 6-port panel to the top half of a 12-port panel (ports 1-6). </label> </div>';
        
        //Mapping type: 6-12 (bottom)
        htmltoInsert+='<div class="radio"> <label> <input type="radio" name="mappingTypeOption" id="mappingTypeSixToTwelveBottom" value="SixToTwelveBottom"> Map a 6-port panel to the bottom half of a 12-port panel (ports 7-12). </label> </div>';
        
        //Mapping type: 6-12 (left)
        htmltoInsert+='<div class="radio"> <label> <input disabled type="radio" name="mappingTypeOption" id="mappingTypeSixToTwelveLeft" value="sixToTwelveLeft"> Map a 6-port panel to the left-hand column of a 12-port panel (ports 1,3,5,7,9,11). </label> <p class="help-block">This option will not work yet.</p> </div>';
        
        //Mapping type: 6-12 (right)
        htmltoInsert+='<div class="radio"> <label> <input disabled type="radio" name="mappingTypeOption" id="mappingTypeSixToTwelveRight" value="SixToTwelveRight"> Map a 6-port panel to the right-hand column of a 12-port panel (ports 2,4,6,8,10,12). </label> <p class="help-block">This option will not work yet.</p></div>';

        //Mapping type: end formgroup
        htmltoInsert+='</div>';

        //page break horizontal rule
        htmltoInsert+='<hr>';

        //column 1
        htmltoInsert+='<div class="col-xs-6"><h4>Destination:</h4>';

        //destination building
        htmltoInsert+='<div id="workSpaceDestinationBuilding" name="workSpaceDestinationBuilding"></div>';

        //destination level
        htmltoInsert+='<div id="workSpaceDestinationLevel" name="workSpaceDestinationLevel"></div>';

        //destination location
        htmltoInsert+='<div id="workSpaceDestinationLocation" name="workSpaceDestinationLocation"></div>';

        //destination storage unit
        htmltoInsert+='<div id="workSpaceDestinationStorageUnit" name="workSpaceDestinationStorageUnit"></div>';

        //destination cabinet
        htmltoInsert+='<div id="workSpaceDestinationCabinet" name="workSpaceDestinationCabinet"></div>';

        //destination panel
        htmltoInsert+='<div id="workSpaceDestinationPanel" name="workSpaceDestinationPanel"></div>';

        //end column 1
        htmltoInsert+='</div>';

        //column 2
        htmltoInsert+='<div class="col-xs-6"><h4>Strand Details:</h4>';

        //add static form element - strand length
        htmltoInsert+='<div class="form-group"> <label for="strandLength">Length (ft):</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandLength" id="strandLength"> </div>';
        //add static form element - strand mode
        htmltoInsert+='<div class="form-group"> <label for="strandMode">Mode:</label> <select required class="form-control recalculateExpectedLoss" name="strandMode" id="strandMode"> <option selected disabled value="">Select a mode...</option> <option value="singlemode">Singlemode</option> <option value="multimode">Multimode</option> </select> </div>';
        //add static form element - strand coresize
        htmltoInsert+='<div class="form-group"> <label for="strandCore">Core Size:</label> <select required class="form-control recalculateExpectedLoss" name="strandCore" id="strandCore"> <option selected disabled value="">Select a core size...</option> <option value="8.3">8.3</option> <option value="50">50</option> <option value="62.5">62.5</option> </select> </div>';
        //add static form element - strand wavelength
        htmltoInsert+='<div class="form-group"> <label for="strandWave">Wavelength:</label> <select required class="form-control recalculateExpectedLoss" name="strandWave" id="strandWave"> <option selected disabled value="">Select a wavelength...</option> <option value="850">850</option> <option value="1300">1300</option> <option value="1310">1310</option> <option value="1383">1383</option> <option value="1550">1550</option> <option value="1625">1625</option> </select> </div>';
        //add static form element - strand splicecount
        htmltoInsert+='<div class="form-group"> <label for="strandSpliceCount">Splice Count:</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandSpliceCount" id="strandSpliceCount" > </div>';
        //add static form element - strand connector pairs
        htmltoInsert+='<div class="form-group"> <label for="strandConPairs">Connector Pairs Count:</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandConPairs" id="strandConPairs" > </div>';
        //add static form element - strand expected loss
        htmltoInsert+='<div class="form-group"> <label for="strandExpLoss">Expected Loss:</label> <input required class="form-control" type="text" name="strandExpLoss" id="strandExpLoss" > </div>';

        //end column 2
        htmltoInsert+='</div>';

        //add final submit button
        htmltoInsert+='<div class="col-xs-12 text-center"><div class="form-group"><button type="submit" class="btn btn-default">Connect these panels on every port.</button><p class="help-block">This will create a strand with the above details on every port of the source panel and connect them to the destination panel.</p></div></div>';

        //END FORM:
        htmltoInsert+='</form>';

        //insert the map panel form
        $(targetDiv).html(htmltoInsert);
        $(targetDiv).removeClass('animated bounce'); 

        //draw user attention to the new map panel options
        $(targetDiv).addClass('animated bounce');

        //find out where to load the destination building options
        var loadBuildingHereDiv = document.getElementById("workSpaceDestinationBuilding");

        //add building options via ajax
        $(loadBuildingHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=buildings&mapPanels=yes");

        });

        $(".addStrandClass").click(function(event) {
        var targetDiv = document.getElementById("currentWorkspaceContents");
        var value_cabinetUID = $(this).attr('data-cabinetUID');
        var value_cabinetLabel = $(this).attr('data-cabinetLabel');
        var value_panelUID = $(this).attr('data-panelUID');
        var value_panelPosition = $(this).attr('data-panelPosition');
        var value_portUID = $(this).attr('data-portUID');
        var value_portNumber = $(this).attr('data-portNumber');
        //init a variable to hold the html we will insert
        var htmltoInsert='';
        //craft the add strand form - start with user directions etc
        htmltoInsert+='<p><label class="label label-primary">ADD STRAND</label> <label class="label label-primary">FROM</label> <label class="label label-primary">cabinet ' + value_cabinetLabel + '</label> <label class="label label-primary">panel ' + value_panelPosition + '</label> <label class="label label-primary">port ' + value_portNumber + '</label></p>';
        //start the form
        htmltoInsert+='<form id="addStrandForm" name="addStrandForm" action="manageCabinet.php?uid=' + value_cabinetUID + '" method="POST">';
        //add hidden fields
        htmltoInsert+='<input type="hidden" name="panel_a" id="panel_a" value="' + value_panelUID + '"> <input type="hidden" name="lastCabinet" id="lastCabinet" value="' + value_cabinetUID + '"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="addStrand"> <input type="hidden" name="port_a" id="port_a" value="' + value_portUID + '">';
        //add containers to add things to via ajax
        htmltoInsert+='<div id="workSpaceDestinationBuilding" name="workSpaceDestinationBuilding"></div> <div id="workSpaceDestinationLevel" name="workSpaceDestinationLevel"></div> <div id="workSpaceDestinationLocation" name="workSpaceDestinationLocation"></div> <div id="workSpaceDestinationStorageUnit" name="workSpaceDestinationStorageUnit"></div> <div id="workSpaceDestinationCabinet" name="workSpaceDestinationCabinet"></div> <div id="workSpaceDestinationPanel" name="workSpaceDestinationPanel"></div> <div id="workSpaceDestinationPort" name="workSpaceDestinationPort"></div>';
        //add static form element - strand length
        htmltoInsert+='<div class="form-group"> <label for="strandLength">Length (ft):</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandLength" id="strandLength"> </div>';
        //add static form element - strand mode
        htmltoInsert+='<div class="form-group"> <label for="strandMode">Mode:</label> <select required class="form-control recalculateExpectedLoss" name="strandMode" id="strandMode"> <option selected disabled value="">Select a mode...</option> <option value="singlemode">Singlemode</option> <option value="multimode">Multimode</option> </select> </div>';
        //add static form element - strand coresize
        htmltoInsert+='<div class="form-group"> <label for="strandCore">Core Size:</label> <select required class="form-control recalculateExpectedLoss" name="strandCore" id="strandCore"> <option selected disabled value="">Select a core size...</option> <option value="8.3">8.3</option> <option value="50">50</option> <option value="62.5">62.5</option> </select> </div>';
        //add static form element - strand wavelength
        htmltoInsert+='<div class="form-group"> <label for="strandWave">Wavelength:</label> <select required class="form-control recalculateExpectedLoss" name="strandWave" id="strandWave"> <option selected disabled value="">Select a wavelength...</option> <option value="850">850</option> <option value="1300">1300</option> <option value="1310">1310</option> <option value="1383">1383</option> <option value="1550">1550</option> <option value="1625">1625</option> </select> </div>';
        //add static form element - strand splicecount
        htmltoInsert+='<div class="form-group"> <label for="strandSpliceCount">Splice Count:</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandSpliceCount" id="strandSpliceCount" > </div>';
        //add static form element - strand connector pairs
        htmltoInsert+='<div class="form-group"> <label for="strandConPairs">Connector Pairs Count:</label> <input required class="form-control recalculateExpectedLoss" type="number" name="strandConPairs" id="strandConPairs" > </div>';
        //add static form element - strand expected loss
        htmltoInsert+='<div class="form-group"> <label for="strandExpLoss">Expected Loss:</label> <input required class="form-control" type="text" name="strandExpLoss" id="strandExpLoss" > </div>';
        //add final submit button
        htmltoInsert+='<div class="form-group"> <button type="submit" class="btn btn-default">Create Strand</button> </div>';
        //end form
        htmltoInsert+='</form>';
        //clear the work area where the add strand form will be placed
        $(targetDiv).html('');
        //insert the add strand form
        $(targetDiv).html(htmltoInsert); 
         $(targetDiv).removeClass('animated bounce'); 
        //find out where to load the destination building options
         var loadBuildingHereDiv = document.getElementById("workSpaceDestinationBuilding");
        //add building options via ajax
        $(loadBuildingHereDiv).load("snippets/ajax/loadDependentSelect.php?form=addStrand&load=buildings");
        //draw user attention to the new destination building options
        $(targetDiv).addClass('animated bounce');
        });
    </script>

</body>

</html>

<?php

                        //report total ports added

                        
                            /*
                            if ($addPortError==1) {
                                generateAlert('success',$iPortsAddedToPanel.' Ports Created Within the New Panel.');
                            }
                            $addPortError=0;
                            }
                            catch(PDOException $e){
                                generateAlert('danger','Error adding port to panel.<br />'.$ex->getMessage());
                            }
                        }
                            */



                    /*
                    try {
                    //insert panel
                    $stmt = $db->prepare('INSERT INTO panel(fk_cabinet_UID,position,portCapacity,type) VALUES(:fk_cabinet_UID,:position,:portCapacity,:type)');
                    //bind variables
                    $stmt->bindParam(':fk_cabinet_UID', $fk_cabinet_x);
                    $stmt->bindParam(':position', $addPanelSlot);
                    $stmt->bindParam(':portCapacity', $portCapacity);
                    $stmt->bindParam(':type', $panelType);

                    //execute query
                    $stmt->execute();

                    //get last insert id
                    $newPanelId = $db->lastInsertId();

                    //Save Total Rows
                    //Report Success
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Panel Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

                    $addPortError=0;

                    for ($iPortsAddedToPanel=1; $iPortsAddedToPanel <= $portCapacity; $iPortsAddedToPanel++) { 
                    $dbx = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                    $dbx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    try {
                    $stmtx = $dbx->prepare('INSERT INTO port(fk_panel_UID, number) VALUES(:fk_panel_UID,:number)');
                    //asdf
                    $stmtx->bindParam(':fk_panel_UID', $newPanelId);
                    $stmtx->bindParam(':number', $iPortsAddedToPanel);
                    $stmtx->execute();
                    }
                    catch(PDOException $ex){
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$ex->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    $addPortError=1;
                    }
                    }
                    if ($addPortError==1) {
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$iPortsAddedToPanel.' Ports Created Within the New Panel.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    $addPortError=0;
                    }
                    catch(PDOException $e){
                    echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
                    }
                    */

/*

            if (isset($_POST['fk_cabinet_x']) && isset($_POST['addPanelSlot']) && isset($_POST['panelType']) && isset($_POST['portCapacity'])) {
            //echo "I should be adding a panel now.";
            $addPanelSlot=$_POST['addPanelSlot'];
            $fk_cabinet_x=$_POST['fk_cabinet_x'];
            $panelType=$_POST['panelType'];
            $portCapacity=$_POST['portCapacity'];

            //create a building here.
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);




            try {
            //insert panel
            $stmt = $db->prepare('INSERT INTO panel(fk_cabinet_UID,position,portCapacity,type) VALUES(:fk_cabinet_UID,:position,:portCapacity,:type)');
            //bind variables
            $stmt->bindParam(':fk_cabinet_UID', $fk_cabinet_x);
            $stmt->bindParam(':position', $addPanelSlot);
            $stmt->bindParam(':portCapacity', $portCapacity);
            $stmt->bindParam(':type', $panelType);

            //execute query
            $stmt->execute();

            //get last insert id
            $newPanelId = $db->lastInsertId();

            //Save Total Rows
            //Report Success
            echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Panel Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';

            $addPortError=0;

            for ($iPortsAddedToPanel=1; $iPortsAddedToPanel <= $portCapacity; $iPortsAddedToPanel++) { 

            //create a building here.
            $dbx = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $dbx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            //insert building with note.
            $stmtx = $dbx->prepare('INSERT INTO port(fk_panel_UID, number) VALUES(:fk_panel_UID,:number)');
            //asdf
            $stmtx->bindParam(':fk_panel_UID', $newPanelId);
            $stmtx->bindParam(':number', $iPortsAddedToPanel);

            //execute query
            $stmtx->execute();
            }
            //Catch Errors (if errors)
            catch(PDOException $ex){
            //Report Error Message(s)
            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$ex->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            $addPortError=1;
            }
            }
            //Report Success
            if ($addPortError==1) {
            echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$iPortsAddedToPanel.' Ports Created Within the New Panel.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            $addPortError=0;
            }
            //Catch Errors (if errors)
            catch(PDOException $e){
            //Report Error Message(s)
            echo '<div class="alert alert-danger"><strong>Error!</strong>: '.$e->getMessage().'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            }
            else{
            echo "I do not have enough information to proceed.";
            }
            */
?>