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

switch ($alertType) {
	case 'success':
		echo '<div class="alert alert-success">';
		echo '<strong>Success!</strong> ';
		echo $alertText;
		break;
	
	case 'info':
		echo '<div class="alert alert-info">';
		echo '<strong>Info!</strong> ';
		echo $alertText;
		break;
	
	case 'warning':
		echo '<div class="alert alert-warning">';
		echo '<strong>Warning!</strong> ';
		echo $alertText;
		break;

	case 'danger':
		echo '<div class="alert alert-danger">';
		echo '<strong>Error!</strong> ';
		echo $alertText;
		break;
	
	default:
		# code...
		break;
}
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



?>