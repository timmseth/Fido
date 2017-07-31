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
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="separator">Main</li>

                <li>
                <a href="index.php">
                <i class="text-primary fa fa-dashboard fa-fw"></i> Dashboard
                </a>
                </li>

                <li>
                <a href="analytics.php">
                <i class="text-primary fa fa-bar-chart fa-fw"></i> Analytics
                </a>
                </li>

            <li class="separator">Data Entry</li>

                <li>
                <a href="module_crud.php">
                <i class="text-primary fa fa-table fa-fw"></i> Browse Database
                </a>
                </li>

                <li>
                <a href="guidedRecordCreation.php">
                <i class="text-primary fa fa-plus fa-fw"></i> Guided C.R.U.D.
                </a>
                </li>

                <li>
                <a href="equipment_crud.php">
                <i class="text-primary fa fa-server fa-fw"></i> Manage Equipment
                </a>
                </li>

            <li class="separator">Maps</li>
                
                <li>
                <a href="interbuildingMapLogical.php">
                <i class="text-info fa fa-connectdevelop fa-fw"></i> 
                Logical IBF Map
                </a>
                </li>

            <li class="separator">Under Development</li>

                <li>
                <a href="pathPlanner.php">
                <i class="text-info fa fa-exchange fa-fw"></i> Path Planner
                </a>
                </li>


                <li>
                <a href="interbuildingMapGeo.php">
                <i class="text-info fa fa-map fa-fw"></i> IBF Map (Geo)
                </a>
                </li>

            <li class="separator">Help</li>

                <li>
                <a href="module_tutorials.php">
                <i class="text-success fa fa-info fa-fw"></i> Tutorials
                </a>
                </li>

                <!-- <li>
                <a target="_blank" href="module_help.php">
                <i class="text-success fa fa-question fa-fw"></i> User Guide
                </a>
                </li> -->
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->