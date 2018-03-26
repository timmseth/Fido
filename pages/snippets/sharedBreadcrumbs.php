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

            case 'analytics':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Analytics</li>
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
                <li class="active">Guided Record Creation</li>
                ';
                break;

            case 'equipment':
                echo '
                <li><a href="index.php">Home</a></li>
                <li class="active">Equipment Management</li>
                ';
                break;

            case 'tutorials':
                echo '
                <li><a href="module_tutorials.php">Home</a></li>
                <li class="active">Tutorials</li>
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

