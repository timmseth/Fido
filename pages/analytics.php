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
$thisPage='analytics';
generatePageStartHtml($thisPage);
writeHeader('FiDo Analytics');
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
//GRAPHS
?>



<div class="row">
<div class="col-xs-12">
<div class="col-xs-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
        <h4 class="text-center">Monthly Data Entry</h4>
        </div>
        <div class="panel-body noPadding">
            <div class="panelChart" id="dataEntryRecords"></div>
        </div>
    </div>
</div>
</div>
</div>


<div class="row">
<div class="col-xs-12">
<div class="col-xs-12 col-lg-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
        <h4 class="text-center">Jumper Port Availability</h4>
        </div>
        <div class="panel-body noPadding">
            <div class="panelChart" id="jumperDonut"></div>
        </div>
    </div>
</div>

<div class="col-xs-12 col-lg-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
        <h4 class="text-center">Strand Port Availability</h4>
        </div>
        <div class="panel-body noPadding">
            <div class="panelChart" id="strandDonut"></div>
        </div>
    </div>
</div>

<div class="col-xs-12 col-lg-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
        <h4 class="text-center">Panel Type Distribution</h4>
        </div>
        <div class="panel-body noPadding">
            <div class="panelChart" id="panelTypeDonut"></div>
        </div>
    </div>
</div>
</div>
</div>



            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

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
    <script src="../bower_components/morrisjs/morris.min.js"></script>

    <?php

//PORT STATUS (jumper and strand)
    //get active ports from database.
    $activeJumperPorts=getSomeFromDatabase('ports','jumperStatus','active');

    //get available ports from database.
    $availableJumperPorts=getSomeFromDatabase('ports','jumperStatus','available');

    //Set Chart Data (1)
    $thisLabelOne='Available Ports';
    $thisDataOne=$availableJumperPorts;

    //Set Chart Data (2)
    $thisLabelTwo='Active Ports';
    $thisDataTwo=$activeJumperPorts;

    echo '
    <script>
    Morris.Donut({
    element: "jumperDonut",
    data: [{
    label: "'.$thisLabelOne.'",
    value: '.$thisDataOne.'
    }, {
    label: "'.$thisLabelTwo.'",
    value: '.$thisDataTwo.'
    }],
    resize: true
    });
    </script>
    ';

    //get active ports from database.
    $activeStrandPorts=getSomeFromDatabase('ports','strandStatus','active');

    //get available ports from database.
    $availableStrandPorts=getSomeFromDatabase('ports','strandStatus','available');

    //Set Chart Data (1)
    $thisLabelOne='Available Ports';
    $thisDataOne=$availableStrandPorts;

    //Set Chart Data (2)
    $thisLabelTwo='Active Ports';
    $thisDataTwo=$activeStrandPorts;

    echo '
    <script>
    Morris.Donut({
    element: "strandDonut",
    data: [{
    label: "'.$thisLabelOne.'",
    value: '.$thisDataOne.'
    }, {
    label: "'.$thisLabelTwo.'",
    value: '.$thisDataTwo.'
    }],
    resize: true
    });
    </script>
    ';



//Panel Types
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT portCapacity,type,count(panel_UID) AS count FROM panel GROUP BY type,portCapacity');
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $panelDistributions[$iCounter]['count']=$row['count'];
            $panelDistributions[$iCounter]['types']=$row['type'];
            $panelDistributions[$iCounter]['portCapacity']=$row['portCapacity'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}

    $tempString='';
    foreach ($panelDistributions as $key => $value) {
        $tempString.='{
        label: "'.strtoupper($value['types']).' - '.$value['portCapacity'].' Port",
        value: "'.$value['count'].'"
        },';
    }
    $tempString=substr(trim($tempString), 0, -1);

    echo '
    <script>
    Morris.Donut({
    element: "panelTypeDonut",
    data: ['.$tempString.'],
    resize: true
    });
    </script>
    ';



//STRAND data entry
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT COUNT(strand_UID) AS strandCount, MONTH(lastmodified) AS month, YEAR(lastmodified) AS year FROM strand WHERE YEAR(lastmodified)=:thisyear GROUP BY MONTH(lastmodified) ORDER BY lastmodified ASC');
    $stmt->bindParam(':thisyear', date(Y));
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $dataEntryStrands[$iCounter]['strandCount']=$row['strandCount'];
            $dataEntryStrands[$iCounter]['month']=$row['month'];
            $dataEntryStrands[$iCounter]['year']=$row['year'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}


//JUMPER data entry
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT COUNT(jumper_UID) AS jumperCount, MONTH(lastmodified) AS month, YEAR(lastmodified) AS year FROM jumper WHERE YEAR(lastmodified)=:thisyear GROUP BY MONTH(lastmodified) ORDER BY lastmodified ASC');
    $stmt->bindParam(':thisyear', date(Y));
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $dataEntryJumpers[$iCounter]['jumperCount']=$row['jumperCount'];
            $dataEntryJumpers[$iCounter]['month']=$row['month'];
            $dataEntryJumpers[$iCounter]['year']=$row['year'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}


//PORT data entry
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT COUNT(port_UID) AS portCount, MONTH(lastmodified) AS month, YEAR(lastmodified) AS year FROM port WHERE YEAR(lastmodified)=:thisyear GROUP BY MONTH(lastmodified) ORDER BY lastmodified ASC');
    $stmt->bindParam(':thisyear', date(Y));
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $dataEntryPorts[$iCounter]['portCount']=$row['portCount'];
            $dataEntryPorts[$iCounter]['month']=$row['month'];
            $dataEntryPorts[$iCounter]['year']=$row['year'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}

//PANEL data entry
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT COUNT(panel.panel_UID) AS panelCount, MONTH(panel.lastMod) AS month, YEAR(panel.lastMod) AS year FROM panel WHERE YEAR(panel.lastMod)=:thisyear GROUP BY MONTH(panel.lastMod) ORDER BY panel.lastMod ASC');
    $stmt->bindParam(':thisyear', date(Y));
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $dataEntryPanels[$iCounter]['panelCount']=$row['panelCount'];
            $dataEntryPanels[$iCounter]['month']=$row['month'];
            $dataEntryPanels[$iCounter]['year']=$row['year'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}

//CABINET data entry
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
    $stmt = $db->prepare('SELECT COUNT(cabinet.cabinet_UID) AS cabinetCount, MONTH(cabinet.lastmodified) AS month, YEAR(cabinet.lastmodified) AS year FROM cabinet WHERE YEAR(cabinet.lastmodified)=:thisyear GROUP BY MONTH(cabinet.lastmodified) ORDER BY cabinet.lastmodified ASC');
    $stmt->bindParam(':thisyear', date(Y));
    $stmt->execute();
    $iCounter=0;
        foreach($stmt as $row) {
            $dataEntryCabinets[$iCounter]['cabinetCount']=$row['cabinetCount'];
            $dataEntryCabinets[$iCounter]['month']=$row['month'];
            $dataEntryCabinets[$iCounter]['year']=$row['year'];
            $iCounter++;
        }
    }
    catch(PDOException $e){}

//echo (date(Y)-5);

//$tempLabels='';
$tempString='';
foreach ($dataEntryPorts as $key => $value) {
    $tempString.='{
    y: "2017-'.str_pad($value['month'],2,'0',STR_PAD_LEFT).'",
    a: "'.$value['portCount'].'",
    b: "'.$dataEntryJumpers[$key]['jumperCount'].'",
    c: "'.$dataEntryStrands[$key]['strandCount'].'",
    d: "'.$dataEntryPanels[$key]['panelCount'].'",
    e: "'.$dataEntryCabinets[$key]['cabinetCount'].'"
    },';
}
$tempString=substr(trim($tempString), 0, -1);

echo '
<script>
Morris.Area({
element: "dataEntryRecords",
data: ['.$tempString.'],
xkey: "y",
ykeys: ["a","c","d","e"],
labels: ["Port Entry","Strand Entry","Panel Entry","Cabinet Entry"],
xLabels: "month"
});
</script>
';

?>
</body>
</html>
