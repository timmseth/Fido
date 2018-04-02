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
if (isset($thisPage)) {
    echo '<ol class="breadcrumb">';
        switch ($thisPage) {
            case 'home':
                echo '
                <li class="active">Home</li>
                ';
                break;

            case 'browseAll':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Direct Browse</li>
                ';
                break;



            case 'crud':
                if(isset($_POST['buildingUID']) && isset($_POST['buildingName'])){
                echo '
                    <li><a href="index.php">Home</a></li>
                    <li><a href="module_crud.php">Browse Database</a></li>
                    <li class="active"><a href="guidedRecordCreation.php?b='.$_POST['buildingUID'].'">'.$_POST['buildingName'].'</a></li>
                    ';
                    /*
                    <li><a href="module_crud.php">Manage Cabinet Contents</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248">Test Building</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0">Level 0</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0&lo=0228">Test Room</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0&lo=0228&sto=0295">000-00-01</a></li>
                    <li class="readonly">005</li>
                    */
                }
                else{
                echo '
                    <li><a href="index.php">Home</a></li>
                    <li class="active">Browse Database</li>
                    ';
                    /*
                    <li><a href="module_crud.php">Manage Cabinet Contents</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248">Test Building</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0">Level 0</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0&lo=0228">Test Room</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b=0248&le=0&lo=0228&sto=0295">000-00-01</a></li>
                    <li class="readonly">005</li>
                    */
                }
                break;

            case 'analytics':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Analytics</li>
                ';
                break;

            case 'dashboard':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Dashboard</li>
                ';
                break;

            case 'pathPlanner':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Path Planner</li>
                ';
                break;

            case 'guidedCrud':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Guided C.R.U.D.</li>
                ';
                break;

            case 'equipment':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Equipment C.R.U.D.</li>
                ';
                break;

            case 'tutorials':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Tutorials</li>
                ';
                break;

            case 'mapGeo':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">IBF Map (Geo)</li>
                ';
                break;

            case 'mapLogical':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">IBF Map (Logical)</li>
                ';
                break;



            case 'manageCabinetContents':
                if (isset($breadcrumbDetails["buildingUID"]) && isset($breadcrumbDetails["locationUID"]) && isset($breadcrumbDetails["storageUnitUID"]) && isset($breadcrumbDetails["cabinetLabel"])) {
                    echo '
                    <li><a href="index.php">Home</a></li>
                    <li><a href="guidedRecordCreation.php">Guided Record Creation</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b='.$breadcrumbDetails["buildingUID"].'">'.$breadcrumbDetails["buildingName"].'</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b='.$breadcrumbDetails["buildingUID"].'&le='.$breadcrumbDetails["buildingLevel"].'">Level '.$breadcrumbDetails["buildingLevel"].'</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b='.$breadcrumbDetails["buildingUID"].'&le='.$breadcrumbDetails["buildingLevel"].'&lo='.$breadcrumbDetails["locationUID"].'">'.$breadcrumbDetails["locationDesc"].'</a></li>
                    <li class="readonly"><a href="guidedRecordCreation.php?b='.$breadcrumbDetails["buildingUID"].'&le='.$breadcrumbDetails["buildingLevel"].'&lo='.$breadcrumbDetails["locationUID"].'&sto='.$breadcrumbDetails['storageUnitUID'].'">'.$breadcrumbDetails["storageUnitLabel"].'</a></li>
                    <li class="readonly">'.$breadcrumbDetails["cabinetLabel"].'</li>
                    <li class="active">Manage Cabinet Contents</li>
                    ';
                }
                else{

                    echo '
                    <li><a href="index.php">Home</a></li>
                    <li class="readonly">Guided Record Creation</li>';
                }

                break;

            default:
                break;
        }
    echo '</ol>';
}
?>

