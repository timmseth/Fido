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
//use config.php (required for database login etc.)
require('../../configPointer.php');
//load core size and wavelength options FROM selected mode
if($_GET['mode']){
    //Get Selected Mode
    $selectedMode=$_GET['mode'];
    //Get Destination Container
    $destinationContainer=$_GET['container'];
    //Get selectedValue
    $selectedValue=$_GET['selectedValue'];
    switch($selectedMode){
//SINGLEMODE
        case 'singlemode':
        //if core size
        if($destinationContainer=='core'){
          echo "<label class=\"label\">Core Size</label>";
          echo "<select name=\"strandCore\" id=\"strandCore\" class=\"recalculateExpectedLoss form-control\">";
            echo '<option selected value="">Select an option...</option>';
            foreach ($config['strandOptions']['coresize']['singlemode'] as &$value) {
              if($value==$selectedValue){
                echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
              }
              else {
                echo "<option value=\"".$value."\">".ucfirst($value)."</option>";
              }
            }
          echo "</select>";
        }
        //if wavelength
        elseif($destinationContainer=='wave'){
          echo "<label class=\"label\">Wavelength</label>";
          echo "<select name=\"strandWave\" id=\"strandWave\" class=\"recalculateExpectedLoss form-control\">";
            echo '<option selected value="">Select an option...</option>';
            foreach ($config['strandOptions']['wavelength']['singlemode'] as &$value) {
              if($value=='1310'){
                echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
              }
              else {
                echo "<option value=\"".$value."\">".ucfirst($value)."</option>";
              }
            }
          echo "</select>";
        }
        //default action
        else{}
        break;
//MULTIMODE
        case 'multimode':
        //if core size
        if($destinationContainer=='core'){
          echo "<label class=\"label\">Core Size</label>";
          echo "<select name=\"strandCore\" id=\"strandCore\" class=\"recalculateExpectedLoss form-control\">";
            echo '<option selected value="">Select an option...</option>';
            foreach ($config['strandOptions']['coresize']['multimode'] as &$value) {
              if($value=='850'){
                echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
              }
              else {
                echo "<option value=\"".$value."\">".ucfirst($value)."</option>";
              }
            }
          echo "</select>";
        }
        //if wavelength
        elseif($destinationContainer=='wave'){
          echo "<label class=\"label\">Wavelength</label>";
          echo "<select name=\"strandWave\" id=\"strandWave\" class=\"recalculateExpectedLoss form-control\">";
            echo '<option selected value="">Select an option...</option>';
            foreach ($config['strandOptions']['wavelength']['multimode'] as &$value) {
              if($value=='850'){
                echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
              }
              else {
                echo "<option value=\"".$value."\">".ucfirst($value)."</option>";
              }
            }
          echo "</select>";
        }
        //default action
        else{}
        break;
    }
}
//nothing to load
else{
}
