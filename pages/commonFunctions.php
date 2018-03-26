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
<?php
function writeAlert($alertType,$alertText){
	//switch between alert types
	switch ($alertType) {
		case 'success':
			echo '<div class="alert alert-success">';
			echo '<strong>Success!</strong> ';
			break;
		case 'info':
			echo '<div class="alert alert-info">';
			echo '<strong>Info!</strong> ';
			break;
		case 'warning':
			echo '<div class="alert alert-warning">';
			echo '<strong>Warning!</strong> ';
			break;
		case 'danger':
			echo '<div class="alert alert-danger">';
			echo '<strong>Error!</strong> ';
			break;
		default:
			break;
	}
	//write alert text & dismissal
	echo $alertText;
	echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	echo '</div>';
}

function writeHeader($textString){
    echo '<div class="row">';
    echo '<div class="col-lg-12">';
    echo '<h1 class="page-header">';
    echo $textString;
    echo '</h1>';
    echo '</div>';
    echo '<!-- /.col-lg-12 -->';
    echo '</div>';
    echo '<!-- /.row -->';
}

function generatePageStartHtml($pageName){
	echo '
	<body>
	<div id="wrapper">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	'; 
	include('snippets/sharedTopNav.php');
	include('snippets/sharedSideNav.php');
	echo '</nav>
	<div id="page-wrapper">
	<div class="container-fluid animated fadeIn">';
	include('snippets/sharedBreadcrumbs.php');
}

function writeJumbo($header,$text,$btext,$bhref){
	echo '<div class="row">';
	echo '<div class="col-xs-12">';
	echo '<div class="jumbotron">';
	echo '<h1>'.$header.'</h1>';
	echo '<p>'.$text.'</p>';
	echo '<div class="col-xs-12">';
	echo '<p><a class="btn btn-primary btn-lg" href="'.$bhref.'" role="button">'.$btext.'</a></p>';
	echo '<br />';
	echo '</div>';
	echo '<br />';
	echo '</div>';
	echo '</div>';
	echo '</div>';                      
}
                                           
function getTotalElementCounts(){
	$totalBuildings=getTotalFromDatabase('buildings');
	$totalLocations=getTotalFromDatabase('locations');
	$totalStorageUnits=getTotalFromDatabase('storageUnits');
	$totalCabinets=getTotalFromDatabase('cabinets');
	$totalPanels=getTotalFromDatabase('panels');
	$totalPorts=getTotalFromDatabase('ports');
	$totalStrands=getTotalFromDatabase('strands');
	$totalJumpers=getTotalFromDatabase('jumpers');
}               

//Debug Print POST/GET data function
function getTotalFromDatabase($elementToCount){
    global $dbHost;
    global $dbName;
    global $dbUser;
    global $dbPassword;
    $temp_foundRecords=0;
    switch($elementToCount){
        //COUNT BUILDINGS
        case 'buildings':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM building order by number asc');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT locations
        case 'locations':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM location');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT storageUnits
        case 'storageUnits':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM storageunit');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT cabinets
        case 'cabinets':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM cabinet');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT panels
        case 'panels':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM panel');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT ports
        case 'ports':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM port');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT STRANDS
        case 'strands':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM strand');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT JUMPERS
        case 'jumpers':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM jumper');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        //COUNT PATHS
        case 'paths':
            $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
            $stmt = $db->prepare('SELECT * FROM path');
            $stmt->execute();
            $temp_foundRecords=$stmt->rowCount();
            }catch(PDOException $e){}
            break;
        default:
            echo '<div class="alert alert-danger">Error!<br />An unhandeled exception has occurred.</div>';
            break;
    }
    return $temp_foundRecords;
}


?>