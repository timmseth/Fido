<?php
/**
==START===================
*/
//DEBUG
//debugPrintData($_POST);
/*
Array Example:
//$_POST['locationUID'] => 			0172
//$_POST['locationDescription'] => 	Room A503
//$_POST['locationLevel'] => 		4
//$_POST['parentBuildingUID'] => 	0001
//$_POST['locationAction'] => 		view
*/

//PDO Connect
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  try {
    //Prepare Query

    $stmt = $db->prepare("SELECT DISTINCT t4.cabinet_UID AS cabinet_UID, t4.label AS cabinet_label, t4.panelCapacity AS cabinet_panelCapacity, t4.notes AS cabinet_notes, t3.storageUnit_UID AS storageUnit_UID, t3.label AS storageUnit_label, t2.location_UID as location_UID, t1.building_UID as building_UID FROM cabinet AS t4 INNER JOIN storageunit AS t3 INNER JOIN location AS t2 INNER JOIN building AS t1 WHERE t2.location_UID=:location_UID AND t4.fk_storageUnit_UID=t3.storageUnit_UID AND t3.fk_location_UID=t2.location_UID AND t2.fk_building_UID=t1.building_UID ORDER BY t4.label asc");


    //bind values (values are not interpolated into the query)
    $stmt->bindParam(':location_UID', $_POST['locationUID']);

    // Run Query
    $stmt->execute();
    //Store in multidimensional array
    $iCabinet=0;
    foreach($stmt as $row) {
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['storageUnit_label']=$row['storageUnit_label'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['storageUnit_UID']=$row['storageUnit_UID'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['uid']=$row['cabinet_UID'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['label']=$row['cabinet_label'];
    $cabinetArray[$row['storageUnit_label']][$iCabinet]['notes']=$row['cabinet_notes'];
    $iCabinet++;
  }
  //Report Total Rows
  $totalCabinets=$stmt->rowCount();
  }
  //Report Error (if error)
  catch(PDOException $e){
    echo '<div class="alert-box alert noMarginBottomAlert">Error: ' . $e->getMessage() . '</div>';
  }

//debugPrintData($cabinetArray);

//get building name from building uid
        echo '                
        <!-- User Direction -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            User Direction
                        </div>
                        <div class="panel-body">
                            <p><b>Use overview</b> to <b>create</b>, <b>update</b>, and <b>delete</b> panels</b> from this cabinet.</p>
                            <p><b>Select a panel number from the nav-bar below</b> for details about individual ports, strands, and jumpers.<br />
                            </p>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->';




//PANEL / PORT details (get from database. these values are for testing only.)
$totalPanels=6;
$portSize=12;

echo '     
<div class="row">
<div class="col-lg-12">';
echo '
<div class="panel panel-primary">
                        <div class="panel-heading">
                            Cabinet Contents
                        </div>
                        <!-- /.panel-heading -->

                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">';
                            echo '<li class="disabled"><a class="btn disabled text-black">Select a panel:</a></li>';  
                            echo '<li class="active"><a href="#allPanels" data-toggle="tab" aria-expanded="true">All</a></li>';  

                            for ($iPanelCounter=1; $iPanelCounter <= $totalPanels; $iPanelCounter++) { 
                            echo '<li><a href="#panel'.$iPanelCounter.'" data-toggle="tab" aria-expanded="true">'.$iPanelCounter.'</a></li>';                
                            }
                            echo '</ul>
                        </div>
</div>
</div>
</div>
';



echo '     
<!-- Tab panes -->

<div class="tab-content">';

//ALL panels overview (tab content)
echo '
<div class="tab-pane fade active in" id="allPanels">
';




echo '
<div class="row cabinetRepContainer">
<!-- Start Of Panels -->
';



          /*Replaces 2 with the number of panels in this cabinet*/

for ($iLoopThroughPanels=1; $iLoopThroughPanels <= 6; $iLoopThroughPanels++) { 




echo '
  <!-- Panel 1 -->
  <div class="col-xs-12 col-sm-6">
    <div class="panel panel-primary divRepPanel">
      <div class="panel-heading">
      Panel '.$iLoopThroughPanels.' - ST
      </div>
      <div class="panel-body">
<h4>
<span class="label"><i class="fa fa-square text-success"> Port Active</i></span>
<span class="label"><i class="fa fa-square text-muted"> Port Not Active</i></span>
<span class="label"><i class="fa fa-question text-muted"> Attenuation Unknown</i></span>
<span class="label"><i class="fa fa-check-square-o text-success"> Attenuation Pass</i></span>
<span class="label"><i class="fa fa-warning text-danger"> Attenuation Fail</i></span>
</h4>
';

          /*Replaces 2 with the number of panels in this cabinet*/

       echo '
        <!-- Row of Ports -->
        <div class="col-xs-12">';


    /*Replaces 6 with the number of ports in this panel*/
    for ($iLoopThroughPorts=1; $iLoopThroughPorts <= 6; $iLoopThroughPorts++) { 

          if ($iLoopThroughPorts > 2) {
            
          echo '<div class="col-lg-6 notFirstRow">';
            # code...
          }
          else{
        echo ' <div class="col-lg-6">';

          }

          echo '
            <!-- TABLE SEPARATE JUMPER AND -->
            <div class="table-responsive">
              <table class="table portRepresentation">
              <thead>
              <tr>
              <th colspan="2">Port '.$iLoopThroughPorts.'</th><br />
                <i class="fa fa-2x fa-square text-success"></i> / <i class="fa fa-2x fa-square text-muted"></i><br />
              </tr>
              <tr>
              <th>Back<br />
                <a href="#"><i class="fa fa-info text-success"> Connection Info</i></a>

              </th>
              <th>Front<br />
                <a href="#"><i class="fa fa-info text-success"> Connection Info</i></a>
                

              </th>
              </tr>
              </thead>
              <tbody>
              <tr>
              <td>
                Signal Strength:<br />
                <i class="fa fa-2x fa-question text-muted"></i>  / 
                <i class="fa fa-2x fa-check-square-o text-success"></i>  / 
                <i class="fa fa-2x fa-warning text-danger"></i>
              </td>
              <td>
                Signal Strength:<br />
                <i class="fa fa-2x fa-question text-muted"></i> /  
                <i class="fa fa-2x fa-check-square-o text-success"></i>  / 
                <i class="fa fa-2x fa-warning text-danger"></i>
              </td>
              </tr>
              </tbody>
              </table>
            </div>
          </div>
          ';
    }
        echo '
        </div>
      </div>

    </div>
  </div>

<!-- ======================================== -->';

}

echo '

<!-- End Of Panels -->
</div>
';



//EACH individual panel (tab content)
for($iPanelCounter=1; $iPanelCounter <= $totalPanels; $iPanelCounter++) { 
    echo '
    <div class="tab-pane fade" id="panel'.$iPanelCounter.'">
    <h4>Panel'.$iPanelCounter.'</h4>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>
    ';
}

                                

                            echo '</div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
';



echo '</div>
<!-- /.col-lg-xx -->
</div>
<!-- /.row -->';

/**
==END=====================
*/
?>