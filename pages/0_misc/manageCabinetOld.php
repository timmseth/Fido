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
        <div class="col-xs-12">
            <a class="btn btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#editCabinetDetailsForm" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-fw fa-edit"></i> Toggle Edit Cabinet Details Form</a>
            <hr>
            <div id="editCabinetDetailsForm" class="text-center collapse " role="tabpanel" aria-labelledby="">
                <div class="well well-lg">
                    <h4>Edit cabinet details</h4>
                    <div class="alert alert-danger">
                        <p>This is not functional yet.</p>
                    </div>
                    <?php
                    echo '
                    <form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'">
                        <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="updateCabinet" />

                        <div class="form-group">
                            <label for="cabinetLabel">Cabinet Label:</label>
                            <input class="form-control text-center" type="text" name="cabinetLabel" id="cabinetLabel" value="">
                        </div>

                        <div class="form-group">
                            <label for="cabinetPanelCapacity">Panel Capacity:</label>
                            <input class="form-control text-center" type="text" name="cabinetPanelCapacity" id="cabinetPanelCapacity" value="">
                        </div>

                        <div class="form-group">
                            <label for="cabinetNotes">Cabinet Notes:</label>
                            <input class="form-control text-center" type="text" name="cabinetNotes" id="cabinetNotes" value="">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary disabled" type="submit">Update Cabinet Details</button>
                        </div>
                    </form>
                    ';
                    ?>
                </div>
            </div>
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







/*
            //create a building here.
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                //insert building with note.
                    $stmt = $db->prepare('SELECT port_UID FROM port WHERE fk_panel_UID=:panel_a ORDER BY number ASC');

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
*/

//get list of all ports in panel A ordered by port number
    //SELECT port_UID from port where fk_panel_UID=:panel_A ORDER by number ASC
//get list of all ports in panel B ordered by port number
    //SELECT port_UID from port where fk_panel_UID=:panel_B ORDER by number ASC




/*
sixToSix Test Data
    [panel_a] => 0469
    [lastCabinet] => 0131
    [manageCabinetAction] => mapPanel
    [mappingTypeOption] => sixToSix
    [addStrandDestinationBuilding] => 0248
    [addStrandDestinationLevel] => 0
    [addStrandDestinationLocation] => 0189
    [addStrandDestinationStorageUnit] => 0257
    [addStrandDestinationCabinet] => 0131
    [addStrandDestinationPanel] => 0470
    [strandLength] => 100
    [strandMode] => singlemode
    [strandCore] => 8.3
    [strandWave] => 1310
    [strandSpliceCount] => 0
    [strandConPairs] => 1
    [strandExpLoss] => 0.762192
*/




/*
TwelveToTwelve Test Data
    [panel_a] => 0473
    [lastCabinet] => 0131
    [manageCabinetAction] => mapPanel
    [mappingTypeOption] => TwelveToTwelve
    [addStrandDestinationBuilding] => 0248
    [addStrandDestinationLevel] => 0
    [addStrandDestinationLocation] => 0189
    [addStrandDestinationStorageUnit] => 0257
    [addStrandDestinationCabinet] => 0131
    [addStrandDestinationPanel] => 0474
    [strandLength] => 100
    [strandMode] => singlemode
    [strandCore] => 8.3
    [strandWave] => 1310
    [strandSpliceCount] => 0
    [strandConPairs] => 1
    [strandExpLoss] => 0.762192
*/






/*

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
*/
            break;











        //remove a panel
        case 'removePanel':
            if (isset($_POST['removePanelUID'])) {
                $removePanelUID=$_POST['removePanelUID'];
                


/*
            //RESET STATUS OF ALL PORTS THAT ARE CONNECTED TO ANY OF THESE PORTS VIA JUMPER
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
               $stmt = $db->prepare('UPDATE port SET port.jumperStatus="available" WHERE port.fk_jumper_UID IN(SELECT jumper_UID FROM jumper INNER JOIN port ON jumper.jumper_UID=port.fk_jumper_UID INNER JOIN panel on port.fk_panel_UID=panel.panel_UID WHERE panel.panel_UID=:panel)');

                $stmt->bindParam(':panel', $removePanelUID);

                $stmt->execute();
                    echo "
                    <div class='alert alert-success'>
                    Set the jumper status of all ports in connected panels to 'available' and removed all connected jumpers.
                    </div>
                    ";
                }catch(PDOException $e){
                echo "
                    <div class='alert alert-danger'>
                    The jumper status of ports in connected panels could not be updated.<br />
                    Error:".$e."
                    </div>
                    ";
                }


            //RESET STATUS OF ALL PORTS THAT ARE CONNECTED TO ANY OF THESE PORTS VIA STRAND
                $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                try {
               $stmt = $db->prepare('UPDATE port SET port.strandStatus="available" WHERE port.fk_strand_UID IN(SELECT strand_UID FROM strand INNER JOIN port ON strand.strand_UID=port.fk_strand_UID INNER JOIN panel on port.fk_panel_UID=panel.panel_UID WHERE panel.panel_UID=:panel)');

                $stmt->bindParam(':panel', $removePanelUID);

                $stmt->execute();
                    echo "
                    <div class='alert alert-success'>
                    Set the strand status of all ports in connected panels to 'available' and removed all connected strands.
                    </div>
                    ";
                }catch(PDOException $e){
                echo "
                    <div class='alert alert-danger'>
                    The strand status of ports in connected panels could not be updated.<br />
                    Error:".$e."
                    </div>
                    ";
                }
*/
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


/**



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
*/




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


        /*
            $port_b=$_POST['addStrandDestinationPort'];
            $strandLength=$_POST['strandLength'];
            $strandMode=$_POST['strandMode'];
            $strandCore=$_POST['strandCore'];
            $strandWave=$_POST['strandWave'];
            $strandSpliceCount=$_POST['strandSpliceCount'];
            $strandConPairs=$_POST['strandConPairs'];
            $strandExpLoss=$_POST['strandExpLoss'];


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
                    echo '<div class="alert alert-success"><strong>Success!</strong> <i class="fi-results"></i> '.$stmt->rowCount().' Strand Created.<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
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
*/

            break;







        //add a jumper
        case 'addJumper':
        /*
        ===============================================
        CREATE JUMPER BETWEEN PORT AND EQUIPMENT
        ===============================================
        */
        if (isset($_POST['addStrandDestinationDepartment']) && isset($_POST['addStrandEquipmentName']) && isset($_POST['addStrandEquipmentDesc'])) {
            //echo "<pre>";
            //print_r($_POST);
            //echo "</pre>";

           /*
    [panel_a] => 0458
    [manageCabinetAction] => addJumper
    [port_a] => 03429
    [lastCabinet] => 0131
    [portConnectsToOption] => equipment
    [addStrandDestinationBuilding] => 0248
    [addStrandDestinationLevel] => 0
    [addStrandDestinationLocation] => 0189
    [addStrandDestinationEqMake] => 1
    [addStrandDestinationEqModel] => 4
    [addStrandDestinationDepartment] => 1
    [addStrandEquipmentName] => Testname
    [addStrandEquipmentDesc] => Testdesc
        */

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

        /*
        ===============================================
        CREATE JUMPER BETWEEN TWO PORTS (CROSS CONNECT)
        ===============================================
        */
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

if (isset($_GET['uid']) && $_GET['uid']!=''){

    $jumpersInThisCabinet=array();

    //DELETE A PANEL FROM THE CABINET
    if (isset($_POST['panelAction']) && $_POST['panelAction']=="removePanel") {
        if (isset($_POST['removePanelUID']) && $_POST['removePanelUID']!="") {
            echo "I should be deleting panel ".$_POST['removePanelUID']." now.";
        }
    }

    //ADD A PANEL FROM THE CABINET
    if (isset($_POST['panelAction']) && $_POST['panelAction']=="addPanel") {
        if (isset($_POST['addPanelUID']) && $_POST['addPanelUID']!="") {
            echo "I should be adding panel ".$_POST['addPanelUID']." now.";
        }
    }

    //Get the cabinet UID
    if (isset($_GET['uid']) && $_GET['uid']!="") {
        $cabinet_uid=$_GET['uid'];
    }

    //Get the cabinet UID
    if (isset($_POST['uid']) && $_POST['uid']!="") {
        $cabinet_uid=$_POST['uid'];
    }

    //Get the cabinet UID
    if (isset($cabinet_uid) && $cabinet_uid!="") {

//get current location
$ourCurrentLocation='na';
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('select location_UID from location INNER JOIN storageunit on storageunit.fk_location_UID=location.location_UID INNER JOIN cabinet on cabinet.fk_storageunit_UID=storageunit.storageUnit_UID where cabinet.cabinet_UID=:cabinet_UID');
$stmt->bindParam(':cabinet_UID', $cabinet_uid);
$stmt->execute();
//Store in multidimensional array
foreach($stmt as $row) {
$ourCurrentLocation=$row['location_UID'];
}
}catch(PDOException $e){}

//get a list all storage units in this location
$storageUnitsInLocation=array();
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
$stmt = $db->prepare('select storageUnit_UID,label from storageunit where storageunit.fk_location_UID=:location_UID'); 
//Store in multidimensional array
$stmt->bindParam(':location_UID', $ourCurrentLocation);
$stmt->execute();
$i=0;
foreach($stmt as $row) {
    $i++;
    $storageUnitsInLocation[$i]['UID']=$row['storageUnit_UID'];
    $storageUnitsInLocation[$i]['label']=$row['label'];
}
}catch(PDOException $e){}

        //Init arrays
        $cabinetDetails = array();
        $panelDetails = array();
        $portDetails = array();
        //set panel details
        $panelDetails['panel_UID'] = array();
        $panelDetails['position'] = array();
        $panelDetails['portCapacity'] = array();
        $panelDetails['type'] = array();
        //set port details
        $portDetails['uid'] = array();
        $portDetails['status'] = array();

        //Get the parent cabinet information
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT * from cabinet where cabinet_UID='.$cabinet_uid);
        $stmt->execute();
        foreach($stmt as $row) {
            $cabinetDetails['fk_storageUnit_UID']=$row['fk_storageUnit_UID'];
            $cabinetDetails['cabinet_UID']=$row['cabinet_UID'];
            $cabinetDetails['label']=$row['label'];
            $cabinetDetails['panelCapacity']=$row['panelCapacity'];
            $cabinetDetails['notes']=$row['notes'];
        }
        $totalCabinets=$stmt->rowCount();
        }catch(PDOException $e){}

        //Get the child panels information
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT * from panel where fk_cabinet_UID='.$cabinet_uid);
        $stmt->execute();
        $i=0;
        foreach($stmt as $row) {
            $panelDetails['panel_UID'][]=$row['panel_UID'];
            $panelDetails['position'][]=$row['position'];
            $panelDetails['portCapacity'][]=$row['portCapacity'];
            $panelDetails['type'][]=$row['type'];

            $panelDetailsByUID[$row['panel_UID']]['panel_UID']=$row['panel_UID'];
            $panelDetailsByUID[$row['panel_UID']]['position']=$row['position'];
            $panelDetailsByUID[$row['panel_UID']]['portCapacity']=$row['portCapacity'];
            $panelDetailsByUID[$row['panel_UID']]['type']=$row['type'];
            //set positiondetails
            $panelPositionDetails[$row['position']]['panel_UID']=$row['panel_UID'];
            $panelPositionDetails[$row['position']]['portCapacity']=$row['portCapacity'];
            $panelPositionDetails[$row['position']]['type']=$row['type'];


    //=======================
        //Get the child ports information
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT * from port where fk_panel_UID='.$row["panel_UID"].'');
        $stmt->execute();
        foreach($stmt as $rowx) {
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['port_UID']=$rowx['port_UID'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['number']=$rowx['number'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['strandStatus']=$rowx['strandStatus'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['fk_strand_UID']=$rowx['fk_strand_UID'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['jumperStatus']=$rowx['jumperStatus'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['fk_jumper_UID']=$rowx['fk_jumper_UID'];
            $panelPortDetails[$row['panel_UID']][$rowx['number']]['lastModified']=$rowx['lastModified'];
    //=======================


//==============
//==============
//==============
//==============
//Init jumper info arrays
$jumperGetInfoArray=array();

//GET jumper info
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $stmtw = $db->prepare('SELECT PanelA.panel_UID AS "Panel_UID_A", PanelA.position AS "Panel_A", PortA.number AS "PortA", jumper.fk_port_UID_a AS "Port_UID_a", PanelB.panel_UID AS "Panel_UID_B", PanelB.position AS "Panel_B", PortB.number AS "PortB", jumper.fk_port_UID_b AS "Port_UID_b", department.name AS "Department", equipment.equipment_UID, equipment.name AS "EqName", equipment.description AS "EqDesc", eq_make.name AS "EqMake", eq_model.name AS "EqModel", jumper.jumper_UID AS "Jumper_UID"FROM jumper LEFT JOIN port AS PortA ON PortA.port_UID=jumper.fk_port_UID_a LEFT JOIN port AS PortB ON PortB.port_UID=jumper.fk_port_UID_b LEFT JOIN panel AS PanelA ON PanelA.panel_UID=PortA.fk_panel_UID LEFT JOIN panel AS PanelB ON PanelB.panel_UID=PortB.fk_panel_UID LEFT JOIN equipment ON equipment.equipment_UID=jumper.fk_equipment_UID LEFT JOIN department ON department.department_UID=equipment.fk_department_UID LEFT JOIN eq_model ON eq_model.model_UID=equipment.fk_model_UID LEFT JOIN eq_make ON eq_make.make_UID=eq_model.fk_make_UID ORDER BY Panel_UID_A ASC, Panel_A ASC, PortA ASC, Panel_UID_B ASC, Panel_B ASC, PortB ASC');
    $stmtw->execute();

    //record in array
    foreach($stmtw as $roww) {

// ========================
// AND PANEL B DETAILS
// ========================

        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['jumper_UID']=$roww['Jumper_UID'];
        if (isset($roww['Port_UID_b'])) {
            $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['jumperType']='crossConnect';
        }
        else{
            if (isset($roww['EqDesc']) && isset($roww['EqDesc']) && isset($roww['EqDesc'])) {
                $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['jumperType']='equipment';
            }
        }
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['Port_UID_a']=$roww['Port_UID_a'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['Port_UID_b']=$roww['Port_UID_b'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['Panel_UID_A']=$roww['Panel_UID_A'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['Panel']=$roww['Panel'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['PortA']=$roww['PortA'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['Department']=$roww['Department'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['EqMake']=$roww['EqMake'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['EqModel']=$roww['EqModel'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['EqName']=$roww['EqName'];
        $jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['EqDesc']=$roww['EqDesc'];



    //start the jumper entry
        $jumperInfoArray[$roww['Panel_UID_A']][$roww['PortA']]='
      <div id="jumperInfo'.$roww['Panel_UID_A'].''.$roww['PortA'].'" class="text-center collapse" role="tabpanel" aria-labelledby=""> 
      <div class="alert alert-info">
      <p class="text-right">
       <a data-toggle="collapse" data-parent="#accordion" href="#jumperInfo'.$roww['Panel_UID_A'].''.$roww['PortA'].'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-danger fa-fw fa-times"></i></a>
      </p>

      <h4>Jumper Info - Panel '.$roww['Panel'].' - Port '.$roww['PortA'].'.</h4>
        <hr>
      
    ';

    //add conditional equipment info
    if ($jumperGetInfoArray[$roww['Panel_UID_A']][$roww['PortA']]['jumperType']=='equipment') {

        $port_a_parentDetails=getParentsFromDatabase($roww['Port_UID_a'],'port');

        $jumperInfoArray[$roww['Panel_UID_A']][$roww['PortA']].='
        <div class="row">
            <div class="col-xs-12 col-md-6">
            <div class="panel">
                <table class="table table-bordered table-striped table-hover display">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">A</th>
                        </tr>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Building</td>
                            <td>'.$port_a_parentDetails['building_name'].'</td>
                        </tr>
                        <tr>
                            <td>Level</td>
                            <td>'.$port_a_parentDetails['location_level'].'</td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>'.$port_a_parentDetails['location_description'].'</td>
                        </tr>
                        <tr>
                            <td>Storage Unit</td>
                            <td>'.$port_a_parentDetails['storageunit_label'].'</td>
                        </tr>
                        <tr>
                            <td>Cabinet</td>
                            <td>'.$port_a_parentDetails['cabinet_label'].'</td>
                        </tr>
                        <tr>
                            <td>Panel</td>
                            <td>'.$port_a_parentDetails['panel_position'].'</td>
                        </tr>
                        <tr>
                            <td>Port</td>
                            <td>'.$roww['PortA'].'</td>
                        </tr>       
                    </tbody>
                </table>
            </div>
            </div>

            <div class="col-xs-12 col-md-6">
            <div class="panel">
                <table class="table table-bordered table-striped table-hover display">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Equipment</th>
                        </tr>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Department</td>
                            <td>'.$roww['Department'].'</td>
                        </tr>
                        <tr>
                            <td>Make</td>
                            <td>'.$roww['EqMake'].'</td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>'.$roww['EqModel'].'</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>'.$roww['EqName'].'</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>'.$roww['EqDesc'].'</td>
                        </tr>
                    </tbody>
                </table>
            </div>
<a class="btn btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#editJumperDetailsForm'.$thisPanelUID.''.$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-fw fa-edit"></i> Toggle Edit Details Form</a>
            </div>



        </div>

<div id="editJumperDetailsForm'.$thisPanelUID.''.$iPorts.'" class="text-center collapse " role="tabpanel" aria-labelledby="">
<div class="well well-lg">

<h3>Equipment Details:</h3>

<form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'">
    <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="updateJumper" />
    <input type="hidden" name="targetJumper" id="targetJumper" value="'.$rowy['strand_UID'].'" />

    <div class="form-group">
    <label for="strandLength">Length (ft):</label>
    <input class="form-control text-center" type="number" name="strandLength" id="strandLength" value="'.$rowy['length'].'">
    </div>

    <div class="form-group">
    <label for="strandMode">Mode:</label>
    <input class="form-control text-center" type="text" name="strandMode" id="strandMode" value="'.$rowy['mode'].'">
    </div>

    <div class="form-group">
    <label for="strandCore">Core Size:</label>
    <input class="form-control text-center" type="text" name="strandCore" id="strandCore" value="'.$rowy['coreSize'].'" >
    </div>

    <div class="form-group">
    <label for="strandWave">Wavelength:</label>
    <input class="form-control text-center" type="text" name="strandWave" id="strandWave" value="'.$rowy['wavelength'].'" >
    </div>

    <div class="form-group">
    <label for="strandSpliceCount">Splice Count:</label>
    <input class="form-control text-center" type="number" name="strandSpliceCount" id="strandSpliceCount" value="'.$rowy['spliceCount'].'" >
    </div>

    <div class="form-group">
    <label for="strandConPairs">Connector Pairs Count:</label>
    <input class="form-control text-center" type="number" name="strandConPairs" id="strandConPairs" value="'.$rowy['connectorPairsCount'].'" >
    </div>

    <div class="form-group">
    <label for="strandExpLoss">Expected Loss:</label>
    <input class="form-control text-center" step="0.1" type="number" name="strandExpLoss" id="strandExpLoss" value="'.$rowy['expectedLoss'].'" >
    </div>
    
    <div class="form-group">
    <label for="strandExpLoss">Last Measured Loss:</label>
    <input class="form-control text-center" step="0.1" type="number" name="lastMeasuredLoss" id="lastMeasuredLoss" value="'.$rowy['lastMeasuredLoss'].'" >    
    </div>

    <div class="form-group">
    <label for="strandNotes">Notes:</label>
    <input class="form-control text-center" type="text" name="strandNotes" id="strandNotes" value="'.$rowy['notes'].'" >
    </div>
<!--
    <div class="form-group">
    <button class="btn btn-primary" type="submit">Update Strand Details</button>
    </div>
-->
</form>
</div>
</div>

        ';


}
//add conditional cross connect info
else{


$port_a_parentDetails=getParentsFromDatabase($roww['Port_UID_a'],'port');
$port_b_parentDetails=getParentsFromDatabase($roww['Port_UID_b'],'port');


    $jumperInfoArray[$roww['Panel_UID_A']][$roww['PortA']].='
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <div class="panel">
            <table class="table table-bordered table-striped table-hover display">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">A</th>
                    </tr>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Building</td>
                        <td>'.$port_a_parentDetails['building_name'].'</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>'.$port_a_parentDetails['location_level'].'</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>'.$port_a_parentDetails['location_description'].'</td>
                    </tr>
                    <tr>
                        <td>Storage Unit</td>
                        <td>'.$port_a_parentDetails['storageunit_label'].'</td>
                    </tr>
                    <tr>
                        <td>Cabinet</td>
                        <td>'.$port_a_parentDetails['cabinet_label'].'</td>
                    </tr>
                    <tr>
                        <td>Panel</td>
                        <td>'.$port_a_parentDetails['panel_position'].'</td>
                    </tr>
                    <tr>
                        <td>Port</td>
                        <td>'.$roww['PortA'].'</td>
                    </tr>       
                </tbody>
            </table>
        </div>
        </div>
        <div class="col-xs-12 col-md-6">
        <div class="panel">
               <table class="table table-bordered table-striped table-hover display">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">B</th>
                    </tr>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Building</td>
                        <td>'.$port_b_parentDetails['building_name'].'</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>'.$port_b_parentDetails['location_level'].'</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>'.$port_b_parentDetails['location_description'].'</td>
                    </tr>
                    <tr>
                        <td>Storage Unit</td>
                        <td>'.$port_b_parentDetails['storageunit_label'].'</td>
                    </tr>
                    <tr>
                        <td>Cabinet</td>
                        <td>'.$port_b_parentDetails['cabinet_label'].'</td>
                    </tr>
                    <tr>
                        <td>Panel</td>
                        <td>'.$port_b_parentDetails['panel_position'].'</td>
                    </tr>
                    <tr>
                        <td>Port</td>
                        <td>'.$roww['PortB'].'</td>
                    </tr>       
                </tbody>
            </table>
        </div>
        </div>
    </div>
    ';
}

//add conditional info

//close the entry
$jumperInfoArray[$roww['Panel_UID_A']][$roww['PortA']].='
  </div>
  </div>
    ';

// ========================
// AND PANEL B DETAILS
// ========================


        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['jumper_UID']=$roww['Jumper_UID'];
        if (isset($roww['Port_UID_b'])) {
            $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['jumperType']='crossConnect';
        }
        else{
            if (isset($roww['EqDesc']) && isset($roww['EqDesc']) && isset($roww['EqDesc'])) {
                $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['jumperType']='equipment';
            }
        }
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['Port_UID_a']=$roww['Port_UID_a'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['Port_UID_b']=$roww['Port_UID_b'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['Panel_UID_B']=$roww['Panel_UID_B'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['Panel']=$roww['Panel'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['PortB']=$roww['PortB'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['Department']=$roww['Department'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['EqMake']=$roww['EqMake'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['EqModel']=$roww['EqModel'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['EqName']=$roww['EqName'];
        $jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['EqDesc']=$roww['EqDesc'];



    //start the jumper entry
        $jumperInfoArray[$roww['Panel_UID_B']][$roww['PortB']]='
      <div id="jumperInfo'.$roww['Panel_UID_B'].''.$roww['PortB'].'" class="text-center collapse" role="tabpanel" aria-labelledby=""> 
      <div class="alert alert-info">
      <p class="text-right">
       <a data-toggle="collapse" data-parent="#accordion" href="#jumperInfo'.$roww['Panel_UID_B'].''.$roww['PortB'].'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-danger fa-fw fa-times"></i></a>
      </p>

      <h4>Jumper Info - Panel '.$roww['Panel'].' - Port '.$roww['PortB'].'.</h4>
        <hr>
      
    ';

    //add conditional equipment info
    if ($jumperGetInfoArray[$roww['Panel_UID_B']][$roww['PortB']]['jumperType']=='equipment') {

        $port_a_parentDetails=getParentsFromDatabase($roww['Port_UID_a'],'port');

        $jumperInfoArray[$roww['Panel_UID_B']][$roww['PortB']].='
        <div class="row">
            <div class="col-xs-12 col-md-6">
            <div class="panel">
                <table class="table table-bordered table-striped table-hover display">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">A</th>
                        </tr>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Building</td>
                            <td>'.$port_a_parentDetails['building_name'].'</td>
                        </tr>
                        <tr>
                            <td>Level</td>
                            <td>'.$port_a_parentDetails['location_level'].'</td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>'.$port_a_parentDetails['location_description'].'</td>
                        </tr>
                        <tr>
                            <td>Storage Unit</td>
                            <td>'.$port_a_parentDetails['storageunit_label'].'</td>
                        </tr>
                        <tr>
                            <td>Cabinet</td>
                            <td>'.$port_a_parentDetails['cabinet_label'].'</td>
                        </tr>
                        <tr>
                            <td>Panel</td>
                            <td>'.$port_a_parentDetails['panel_position'].'</td>
                        </tr>
                        <tr>
                            <td>Port</td>
                            <td>'.$roww['PortB'].'</td>
                        </tr>       
                    </tbody>
                </table>
            </div>
            </div>

            <div class="col-xs-12 col-md-6">
            <div class="panel">
                <table class="table table-bordered table-striped table-hover display">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Equipment</th>
                        </tr>
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Department</td>
                            <td>'.$roww['Department'].'</td>
                        </tr>
                        <tr>
                            <td>Make</td>
                            <td>'.$roww['EqMake'].'</td>
                        </tr>
                        <tr>
                            <td>Model</td>
                            <td>'.$roww['EqModel'].'</td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td>'.$roww['EqName'].'</td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td>'.$roww['EqDesc'].'</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        ';


}
//add conditional cross connect info
else{


$port_a_parentDetails=getParentsFromDatabase($roww['Port_UID_a'],'port');
$port_b_parentDetails=getParentsFromDatabase($roww['Port_UID_b'],'port');


    $jumperInfoArray[$roww['Panel_UID_B']][$roww['PortB']].='
    <div class="row">
        <div class="col-xs-12 col-md-6">
        <div class="panel">
            <table class="table table-bordered table-striped table-hover display">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">A</th>
                    </tr>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Building</td>
                        <td>'.$port_a_parentDetails['building_name'].'</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>'.$port_a_parentDetails['location_level'].'</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>'.$port_a_parentDetails['location_description'].'</td>
                    </tr>
                    <tr>
                        <td>Storage Unit</td>
                        <td>'.$port_a_parentDetails['storageunit_label'].'</td>
                    </tr>
                    <tr>
                        <td>Cabinet</td>
                        <td>'.$port_a_parentDetails['cabinet_label'].'</td>
                    </tr>
                    <tr>
                        <td>Panel</td>
                        <td>'.$port_a_parentDetails['panel_position'].'</td>
                    </tr>
                    <tr>
                        <td>Port</td>
                        <td>'.$roww['PortB'].'</td>
                    </tr>       
                </tbody>
            </table>
        </div>
        </div>
        <div class="col-xs-12 col-md-6">
        <div class="panel">
               <table class="table table-bordered table-striped table-hover display">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">B</th>
                    </tr>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Building</td>
                        <td>'.$port_b_parentDetails['building_name'].'</td>
                    </tr>
                    <tr>
                        <td>Level</td>
                        <td>'.$port_b_parentDetails['location_level'].'</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>'.$port_b_parentDetails['location_description'].'</td>
                    </tr>
                    <tr>
                        <td>Storage Unit</td>
                        <td>'.$port_b_parentDetails['storageunit_label'].'</td>
                    </tr>
                    <tr>
                        <td>Cabinet</td>
                        <td>'.$port_b_parentDetails['cabinet_label'].'</td>
                    </tr>
                    <tr>
                        <td>Panel</td>
                        <td>'.$port_b_parentDetails['panel_position'].'</td>
                    </tr>
                    <tr>
                        <td>Port</td>
                        <td>'.$roww['PortA'].'</td>
                    </tr>       
                </tbody>
            </table>
        </div>
        </div>
    </div>
    ';
}

//add conditional info

//close the entry
$jumperInfoArray[$roww['Panel_UID_B']][$roww['PortB']].='
  </div>
  </div>
    ';


    }

}catch(PDOException $ev){}





//==============
//==============
//==============
//==============




        //Get the child strands information
        $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
        $stmt = $db->prepare('SELECT * from strand where fk_port_UID_a='.$rowx['port_UID'].' OR fk_port_UID_b='.$rowx['port_UID']);
        $stmt->execute();


            foreach($stmt as $rowy) {    
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['strand_UID']=$rowy['strand_UID'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['fk_path_UID']=$rowy['fk_path_UID'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['length']=$rowy['length'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['mode']=$rowy['mode'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['coreSize']=$rowy['coreSize'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['wavelength']=$rowy['wavelength'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['inTolerance']=$rowy['inTolerance'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['spliceCount']=$rowy['spliceCount'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['connectorPairsCount']=$rowy['connectorPairsCount'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['expectedLoss']=$rowy['expectedLoss'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['lastMeasuredLoss']=$rowy['lastMeasuredLoss'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['notes']=$rowy['notes'];
            $panelPortStrandDetails[$row['panel_UID']][$rowx['number']]['strand'][$rowy['strand_UID']]['lastmodified']=$rowy['lastmodified'];



//Get the child strands information
$dbv = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$dbv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $stmtv = $dbv->prepare('SELECT building.building_UID, building.name AS buildingName, location.location_UID, location.description AS locationDesc, storageunit.storageUnit_UID, storageunit.label AS storageUnitLabel, cabinet.cabinet_UID, cabinet.label AS cabinetLabel, panel.panel_UID, panel.position as panelPosition, port.port_UID, port.number AS portNumber, port.fk_strand_UID FROM port INNER JOIN panel ON port.fk_panel_UID=panel.panel_UID INNER JOIN cabinet ON cabinet.cabinet_UID=panel.fk_cabinet_UID INNER JOIN storageunit ON storageunit.storageUnit_UID=cabinet.fk_storageUnit_UID INNER JOIN location ON location.location_UID=storageunit.fk_location_UID INNER JOIN building ON building.building_UID=location.fk_building_UID WHERE fk_strand_UID=:fk_strand_UID AND port_UID != :port_UID');
   // $stmtv = $dbv->prepare('SELECT building.building_UID, location.location_UID, storageunit.storageUnit_UID, cabinet.cabinet_UID, panel_UID, port.port_UID, port.fk_strand_UID FROM port ';

$stmtv->bindParam(':fk_strand_UID', $rowy['strand_UID']);
$stmtv->bindParam(':port_UID', $rowx['port_UID']);
$stmtv->execute();        
$destinationDetails=array();
foreach($stmtv as $rowv) {
    $destinationDetails['building']=$rowv['building_UID'];
    $destinationDetails['buildingName']=$rowv['buildingName'];
    $destinationDetails['location']=$rowv['location_UID'];
    $destinationDetails['locationDesc']=$rowv['locationDesc'];
    $destinationDetails['storageUnit']=$rowv['storageUnit_UID'];
    $destinationDetails['storageUnitLabel']=$rowv['storageUnitLabel'];
    $destinationDetails['cabinet']=$rowv['cabinet_UID'];
    $destinationDetails['cabinetLabel']=$rowv['cabinetLabel'];
    $destinationDetails['panel']=$rowv['panel_UID'];
    $destinationDetails['panelPosition']=$rowv['panelPosition'];
    $destinationDetails['port']=$rowv['port_UID'];
    $destinationDetails['portNumber']=$rowv['portNumber'];
    $destinationDetails['strand']=$rowv['fk_strand_UID'];
}

}catch(PDOException $ev){}

/**
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['Port_UID_a']=$roww['Port_UID_a'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['Port_UID_b']=$roww['Port_UID_b'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['Panel']=$roww['Panel'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['Port']=$roww['Port'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['Department']=$roww['Department'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['EqName']=$roww['EqName'];
        $jumperGetInfoArray[$roww['Panel_UID']][$roww['Port']]['EqDesc']=$roww['EqDesc'];
*/




    //add strand info
    $strandInfoArray[$row['panel_UID']][$rowx['number']]='

    <div id="strandInfo'.$row['panel_UID'].''.$rowx['number'].'" class="alert alert-info collapse" role="tabpanel" aria-labelledby=""> 
    

  <p class="text-right">
  <a data-toggle="collapse" data-parent="#accordion" href="#strandInfo'.$row['panel_UID'].''.$rowx['number'].'" aria-expanded="true" aria-controls="collapseOne"><span class="text-danger text-right"><i class="fa fa-fw fa-eye-slash"></i> Hide Details</span></a>
  </p>

<!-- DESTINATION -->  
 <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Destination:</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">
                    <b>Building:</b> '.$destinationDetails['buildingName'].'<br />
                    <b>Location:</b> '.$destinationDetails['locationDesc'].'<br />
                    <b>Storage Unit:</b> '.$destinationDetails['storageUnitLabel'].'<br />
                    <b>Cabinet:</b> '.$destinationDetails['cabinetLabel'].'<br />
                    <b>Panel:</b> '.$destinationDetails['panelPosition'].'<br />
                    <b>Port:</b> '.$destinationDetails['portNumber'].'
                    </td>
                </tr>

            </tbody>
        </table>

<!-- EDIT DETAILS -->  
<a class="btn btn-block btn-primary" data-toggle="collapse" data-parent="#accordion" href="#editDetailsForm'.$thisPanelUID.''.$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-fw fa-edit"></i> Toggle Edit Details Form</a>
<div id="editDetailsForm'.$thisPanelUID.''.$iPorts.'" class="text-center collapse " role="tabpanel" aria-labelledby="">
<div class="well well-lg">
  <p class="text-right">
  <a data-toggle="collapse" data-parent="#accordion" href="#editDetailsForm'.$thisPanelUID.''.$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><span class="text-danger text-right"><i class="fa fa-fw fa-eye-slash"></i> Hide Form</span></a>
  </p>
  <br />
    <h3>Strand Details:</h3>
    <p>Panel '.$destinationDetails['panelPosition'].' - Port '.$rowx['number'].'</p>

<form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'">
    <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="updateStrand" />
    <input type="hidden" name="targetStrand" id="targetStrand" value="'.$rowy['strand_UID'].'" />

    <div class="form-group">
    <label for="strandLength">Length (ft):</label>
    <input class="form-control text-center" type="number" name="strandLength" id="strandLength" value="'.$rowy['length'].'">
    </div>

    <div class="form-group">
    <label for="strandMode">Mode:</label>
    <input class="form-control text-center" type="text" name="strandMode" id="strandMode" value="'.$rowy['mode'].'">
    </div>

    <div class="form-group">
    <label for="strandCore">Core Size:</label>
    <input class="form-control text-center" type="text" name="strandCore" id="strandCore" value="'.$rowy['coreSize'].'" >
    </div>

    <div class="form-group">
    <label for="strandWave">Wavelength:</label>
    <input class="form-control text-center" type="text" name="strandWave" id="strandWave" value="'.$rowy['wavelength'].'" >
    </div>

    <div class="form-group">
    <label for="strandSpliceCount">Splice Count:</label>
    <input class="form-control text-center" type="number" name="strandSpliceCount" id="strandSpliceCount" value="'.$rowy['spliceCount'].'" >
    </div>

    <div class="form-group">
    <label for="strandConPairs">Connector Pairs Count:</label>
    <input class="form-control text-center" type="number" name="strandConPairs" id="strandConPairs" value="'.$rowy['connectorPairsCount'].'" >
    </div>

    <div class="form-group">
    <label for="strandExpLoss">Expected Loss:</label>
    <input class="form-control text-center" step="0.1" type="number" name="strandExpLoss" id="strandExpLoss" value="'.$rowy['expectedLoss'].'" >
    </div>
    
    <div class="form-group">
    <label for="strandExpLoss">Last Measured Loss:</label>
    <input class="form-control text-center" step="0.1" type="number" name="lastMeasuredLoss" id="lastMeasuredLoss" value="'.$rowy['lastMeasuredLoss'].'" >    
    </div>

    <div class="form-group">
    <label for="strandNotes">Notes:</label>
    <input class="form-control text-center" type="text" name="strandNotes" id="strandNotes" value="'.$rowy['notes'].'" >
    </div>

    <div class="form-group">
    <button class="btn btn-primary" type="submit">Update Strand Details</button>
    </div>

</form>
</div>
</div>


       
    </div> ';
            }
                }catch(PDOException $e){}

            //=======================

        }
        }catch(PDOException $e){}


//=======================

//echo '<div class="alert alert-info">';
    //echo "<pre>";
        //print_r($jumperGetInfoArray);
    //echo "</pre>";
//echo '</div>';

//=======


        }
        $totalPanels=$stmt->rowCount();
        }catch(PDOException $e){}

        $panelCount = sizeof($panelDetails['panel_UID']);
        

//CREATE cabinet representation
//print each panel (module) slot (even empty ones)
for ($iPanelCapCounter=1; $iPanelCapCounter <= $cabinetDetails['panelCapacity']; $iPanelCapCounter++) {
if ($iPanelCapCounter % 3==0) {
echo '<div class="row">';
}
if (in_array($iPanelCapCounter, $panelDetails['position'])) {
$thisPanelUID='';
$thisPanelPosition='';
$thisPanelPortCap='';
$thisPanelType='';
foreach ($panelPositionDetails as $key => $value) {
if ($key==$iPanelCapCounter) {
$thisPanelUID=$panelPositionDetails[$iPanelCapCounter]['panel_UID'];
$thisPanelPosition=$iPanelCapCounter;
$thisPanelPortCap=$panelPositionDetails[$iPanelCapCounter]['portCapacity'];
$thisPanelType=$panelPositionDetails[$iPanelCapCounter]['type']; 

//
echo "
<div class='col-xs-12 col-lg-4'>
<div class='panel panel-default panel-panel'>
<div class='panel-heading'>
";

    //Print Delete Panel Button
    echo "
    <form action='manageCabinet.php?uid=".$cabinet_uid."' method='POST' class='form-inline'>
    <input type='hidden' id='manageCabinetAction' name='manageCabinetAction' value='removePanel'>
    <input type='hidden' id='removePanelUID' name='removePanelUID' value='".$thisPanelUID."'>
    <button title='Delete this panel.' type='submit' class='inline-block text-right btn btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fa fa-fw fa-times'></i>
    </button>
    </form>
    ";

    //Print Panel Position and Type
    echo "
    <h4 class='text-center'>Panel ".$thisPanelPosition."</h4>
    <h4 class='text-center'>Panel Type: ".strtoupper($thisPanelType)."</h4>
    <h4 class='text-center'><a data-cabinetUID='".$cabinet_uid."' data-cabinetLabel='".$cabinetDetails['label']."' data-panelUID='".$thisPanelUID."' data-panelPosition='".$panelDetailsByUID[$thisPanelUID]['position']."' data-panelCap='".$thisPanelPortCap."' class='mapPanel btn btn-default'><i class='fa fa-fw fa-exchange'></i> Map Panel</a></h4>
    ";

    //print details for each port in the panel
    for ($iPorts=1; $iPorts <= $thisPanelPortCap; $iPorts++) {

        //print collapsible accordion strand info form
        echo "<div class='bg-info'>";
            echo $strandInfoArray[$thisPanelUID][$iPorts];
        echo "</div>";

        //print collapsible accordion jumper info form
        echo "<div class='bg-info'>";
            echo $jumperInfoArray[$thisPanelUID][$iPorts];
        echo "</div>";

        //print collapsible accordion add strand form
        echo '<div id="addStrandForm'.$thisPanelUID.''.$iPorts.'" class="text-center collapse panel panel-default" role="tabpanel" aria-labelledby=""> <div class="panel-body"> <p>Are you sure you want to add a strand (back/boot) on port '.$iPorts.' of this panel?</p> <form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="addStrand" /> <input type="hidden" name="targetCabinet" id="targetCabinet" value="'.$cabinet_uid.'" /> <input type="hidden" name="targetPanel" id="targetPanel" value="'.$thisPanelUID.'" /> <input type="hidden" name="targetPanelPosition" id="targetPanelPosition" value="'.$thisPanelPosition.'" /> <input type="hidden" name="targetPortNumber" id="targetPortNumber" value="'.$iPorts.'" /> <input type="hidden" name="lastCabinet" id="lastCabinet" value="'.$cabinet_uid.'" /> <label for="strandLength">Length (ft):</label> <input required class="input_'.$thisPanelUID.'_'.$iPorts.'_strand form-control" type="number" name="strandLength" id="strandLength"> <label for="strandMode">Mode:</label> <input required class="form-control" type="text" name="strandMode" id="strandMode" > <label for="strandCore">Core Size:</label> <input required class="form-control" type="text" name="strandCore" id="strandCore" > <label for="strandWave">Wavelength:</label> <input required class="form-control" type="text" name="strandWave" id="strandWave" > <label for="strandSpliceCount">Splice Count:</label> <input required class="form-control" type="number" name="strandSpliceCount" id="strandSpliceCount" > <label for="strandConPairs">Connector Pairs Count:</label> <input required class="form-control" type="number" name="strandConPairs" id="strandConPairs" > <label for="strandExpLoss">Expected Loss:</label><input required class="form-control" type="number" name="strandExpLoss" id="strandExpLoss"></form></div></div>';

        //print collapsible accordion add jumper form
        echo '<div id="addJumperForm'.$thisPanelUID.''.$iPorts.'" class="text-center collapse panel panel-default" role="tabpanel" aria-labelledby=""> <div class="panel-body"> <p>Add a jumper (front) from port '.$iPorts.' of this panel to:</p> <form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="addJumper" /> <input type="hidden" name="targetCabinet" id="targetCabinet" value="'.$cabinet_uid.'" /> <input type="hidden" name="targetPanel" id="targetPanel" value="'.$thisPanelUID.'" /> <input type="hidden" name="targetPanelPosition" id="targetPanelPosition" value="'.$thisPanelPosition.'" /> <input type="hidden" name="targetPortNumber" id="targetPortNumber" value="'.$iPorts.'" /> <label for="destinationPanelNumber">Panel:</label> <select required id="destinationPanelNumber" name="destinationPanelNumber" class="form-control"> '; for ($iPanelCapCounterx=1; $iPanelCapCounterx <= $cabinetDetails['panelCapacity']; $iPanelCapCounterx++) {echo "<option value='".$iPanelCapCounterx."@".$panelPositionDetails[$iPanelCapCounterx]['panel_UID']."'>"; echo $iPanelCapCounterx; echo "</option>"; } echo '</select> <label for="destinationPortNumber">Port:</label><input required placeholder="Which Port?" class="form-control" type="number" name="destinationPortNumber" id="destinationPortNumber" ><br /><button class="btn btn-primary" type="submit">Add Jumper</button></form></div></div>';
    }

    echo "
    </div>
    <div class='panel-body'>
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
    ?>

    <table class='display table table-striped table-bordered table-hover panelRep'>
        <thead>
            <tr>
            <th colspan="" class="text-center">Boot<br />(Strand)</th>
            <th colspan="" class="text-center">Front<br />(Jumper)</th>
            <th colspan="" class="text-center">Boot<br />(Strand)</th>
            <th colspan="" class="text-center">Front<br />(Jumper)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //Print for each port... 
            for ($iPorts=1; $iPorts <= $thisPanelPortCap; $iPorts++) {
            if ($iPorts==1) {
            echo '<tr>';
            }
            //===================================
            //START STRAND / BOOT PORTION OF PORT
            //===================================
            echo '<td class="text-center border-right">';
            
            //If this port has an active strand connected to it...
            if ($panelPortDetails[$thisPanelUID][$iPorts]['strandStatus']=='active'){
                //Print the strand info accordion controls
                echo '<a data-toggle="collapse" data-parent="#accordion" href="#strandInfo'.$thisPanelUID.''.$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-info fa-fw fa-info-circle"></i></a>';

                //Print Port Number
                echo $iPorts.'<br />';

                //Print the appropriate icons
                echo '<span class="fa-stack fa-lg" id="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'" name="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'"> <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-success"></i> <i class="fa fa-fw fa fa-circle fa-stack-1x"></i></span>';

                //Print Delete Strand Button
                echo '<form class="form-inline" action="manageCabinet.php?uid='.$cabinet_uid.'" method="POST"> <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="removeStrand" /> <input type="hidden" name="targetStrand" id="targetStrand" value="'.$panelPortDetails[$thisPanelUID][$iPorts]['fk_strand_UID'].'" /> <button title="Remove this strand." class="noPaddingNoBorderButton" type="submit" onclick="return confirm(\'Are you sure?\')"><i class="fa text-danger fa-fw fa-minus-square"></i></button> </form>'; 
            }

            //If this port does NOT have an active strand connected to it...
            else{
                //Print Port Number
                echo $iPorts.'<br />';

                //Print the appropriate icons
                echo '<span class="fa-stack fa-lg" id="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'" name="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'"> <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-muted"></i> <i class="fa fa-fw fa fa-dot-circle-o fa-stack-1x"></i> </span>';

                //Print Add/Create/Connect Strand Button
                echo '<form><a title="Add a strand here." class="addStrandClass" data-cabinetLabel="'.$cabinetDetails['label'].'" data-cabinetUID="'.$cabinet_uid.'" data-panelPosition="'.$panelDetailsByUID[$thisPanelUID]['position'].'" data-panelUID="'.$thisPanelUID.'" data-portNumber="'.$iPorts.'" data-portUID="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'"><i class="fa fa-fw fa-plus-square"></i></a> </form>'; 
            }
            echo '</td>';
            //=================================
            //END STRAND / BOOT PORTION OF PORT
            //=================================


            //====================================
            //START JUMPER / FRONT PORTION OF PORT
            //====================================
            echo '<td class="text-center border-left">';
            
            if ($panelPortDetails[$thisPanelUID][$iPorts]['jumperStatus']=='active') {
            echo '<a data-toggle="collapse" data-parent="#accordion" href="#jumperInfo'.$thisPanelUID.''.$iPorts.'" aria-expanded="true" aria-controls="collapseOne"><i class="fa text-info fa-fw fa-info-circle"></i></a>';
            }
            echo ''.$iPorts.'<br />'; 
            if ($panelPortDetails[$thisPanelUID][$iPorts]['jumperStatus']=='active') {

            array_push($jumpersInThisCabinet, $panelPortDetails[$thisPanelUID][$iPorts]['fk_jumper_UID']);

            echo '
            <span class="fa-stack fa-lg" id="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'" name="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'">
            <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-success"></i>
            <i class="fa fa-fw fa fa-circle fa-stack-1x"></i>
            </span>';

            echo '
            <form class="form-inline" action="manageCabinet.php?uid='.$cabinet_uid.'" method="POST">
            <input type="hidden" name="manageCabinetAction" id="manageCabinetAction" value="removeJumper" />
            <input type="hidden" name="targetJumper" id="targetJumper" value="'.$panelPortDetails[$thisPanelUID][$iPorts]['fk_jumper_UID'].'" />
            <button title="Remove this jumper." class="noPaddingNoBorderButton" type="submit" onclick="return confirm(\'Are you sure?\')"><i class="fa text-danger fa-fw fa-minus-square"></i></button>
            </form>';

            }
            else{
            echo '
            <span class="fa-stack fa-lg" id="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'" name="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'">
            <i class="fa fa-fw fa-2x fa-square fa-stack-2x text-muted"></i>
            <i class="fa fa-fw fa fa-dot-circle-o fa-stack-1x"></i>
            </span>';
            echo '
            <form>
            <a title="Add a jumper here." class="addJumperClass" data-cabinetLabel="'.$cabinetDetails['label'].'" data-cabinetUID="'.$cabinet_uid.'" data-panelPosition="'.$panelDetailsByUID[$thisPanelUID]['position'].'" data-panelUID="'.$thisPanelUID.'" data-portNumber="'.$iPorts.'" data-portUID="'.$panelPortDetails[$thisPanelUID][$iPorts]['port_UID'].'"><i class="fa fa-fw fa-plus-square"></i></a>
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
}
}

echo '
</div>
</div>
</div>
';  
            }
            else{
                //Print add panel form.
                echo '<div class="col-xs-12 col-lg-4"> <div class="panel panel-warning"> <div class="panel-heading"> <h4 class="text-center">Empty Slot ('.$iPanelCapCounter.')</h4> <p class="text-center">Add a panel by clicking on the button below.</p> </div> <div class="panel-body"> <a title="Add a panel here." class="btn btn-primary btn-block" data-toggle="collapse" data-target="#addPanel'.$iPanelCapCounter.'"> <i class="fa fa-fw fa-align-left fa-plus-circle"></i> Add Panel Here </a> <br /> <div id="addPanel'.$iPanelCapCounter.'" class="panel panel-default collapse"> <div class="panel-body"> <form method="POST" action="manageCabinet.php?uid='.$cabinet_uid.'" id="addPanelForm'.$iPanelCapCounter.'" name="addPanelForm'.$iPanelCapCounter.'"> <input type="hidden" id="manageCabinetAction" name="manageCabinetAction" value="addPanel"> <input type="hidden" id="addPanelSlot" name="addPanelSlot" value="'.$iPanelCapCounter.'"> <input type="hidden" id="fk_cabinet_x" name="fk_cabinet_x" value="'.$cabinet_uid.'"> <div class="form-group"> <label for="panelType">Panel Type</label> <select id="panelType" name="panelType" class="form-control"> <option selected value="st">ST</option> <option value="sc">SC</option> <option value="lc">LC</option> <option value="mtrj">MTRJ</option> </select> </div> <div class="form-group"> <label for="portCapacity">Port Capacity</label> <input max="12" type="number" class="form-control" id="portCapacity" name="portCapacity"> </div> <button type="submit" class="text-right btn btn-primary">Submit</button> <br /><br /> </form> </div> </div> </div> </div></div>';
            }
            if ($iPanelCapCounter % 3==0) {
                echo '</div>';
            }
        }
    }
}
else{
?>

    <div class="row">
        <div class="col-xs-12">
            <?php include_once('manageCabinetContents.php'); ?>
        </div>
    </div>
<?php 
} 
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
