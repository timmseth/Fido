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
//============================================
//WHEN THE DOCUMENT HAS LOADED RUN THIS++++++|
$(document).ready(function(){
   //For preselections via breadcrumb trail navigation on the Guided Record Management page.
  });
           







  //============================
  //START ATTENUATION CALCULATOR
  //reference snippets/ajax/ajaxAddStrandModal_resources.php
  $(".influencesAttenuation").on('keyup change', function(event) {
  var thisUniqueStrandValue= $(this).attr('thisUniqueStrandValue');
  //check mode
  var attenuationPerKM=modeChecker();
  //attenuation per km switch
  //var attenuationPerKM = 0.3;
  //init constants
  var totalLoss=0;
  var feetToKmMultiplier=0.0003048;
  var attenuationPerConnectorPair=0.75;
  var attenuationPerSplice=0.3;
  //init/get user inputs
  var totalFeetLength=$("#strandLength").val();
  var totalConnectorPairs=$("#connectorPairs").val();
  var totalSpliceCount=$("#spliceCount").val();
  //convert feet to km
  var totalKmLength=totalFeetLength*feetToKmMultiplier;
  //calculate length,connector,and splice loss separately
  var lengthLoss=(totalKmLength * attenuationPerKM);
  var connectorLoss=(totalConnectorPairs * attenuationPerConnectorPair);
  var spliceLoss=(totalSpliceCount * attenuationPerSplice);
  //find total loss
  totalLoss=lengthLoss+connectorLoss+spliceLoss;
  //display expected loss
  $("#expectedLoss").val(totalLoss);
  });
  //mode checking function
  function modeChecker(){
    //default attenuation
    var attenuationPerKM = 0.3;
    //if mode selected
    if ($("#fiberModeSelect").val()) {
    //alert("found mode.");
    //get mode, coresize, and wavelength
    var fiberMode=$("#fiberModeSelect").val();
    var fiberCoreSize=$("#fiberCoreSizeSelect").val();
    var fiberWavelength=$("#fiberWavelengthSelect").val();
    //load fiber "core size" and "wavelength" options based on selected "fiber mode"
    $("#fiberCoreSizeSelectContainer").load("snippets/ajax/ajaxAddStrandModalLoadStrandOptions.php?mode="+fiberMode+"&container=core&selectedValue="+fiberCoreSize);
    $("#fiberWavelengthSelectContainer").load("snippets/ajax/ajaxAddStrandModalLoadStrandOptions.php?mode="+fiberMode+"&container=wave&selectedValue="+fiberWavelength);
    //activate core size and wavelength selections
    $("#fiberCoreSizeSelect").removeAttr("disabled");
    $("#fiberWavelengthSelect").removeAttr("disabled");
    }
    //if no mode selected
    else{
      //alert("no mode.");
    }
    return attenuationPerKM;
  }
  //END ATTENUATION CALCULATOR+++++++++++++++++++|
  //==============================================
});


//==============================================
//START ATTENUATION CALCULATOR+++++++++++++++++|
/*
Attenuation formula from Cisco:
TA = n x C + c x J + L x a + M
where:
n—number of connectors
C—attenuation for one optical connector (dB)
c—number of splices in elementary cable section
J—attenuation for one splice (dB)
M—system margin (patch cords, cable bend, unpredictable optical attenuation events, and so on, should be considered around 3dB)
a—attenuation for optical cable (dB/Km)
L—total length of the optical cable
==============================================================
For wavelength 1310nm: Normal
TA =  n x C     + c x J     + L       x a          + M   = 
      2 x 0.6dB + 4 x 0.1dB + 20.5Km  x 0.38dB/Km  + 3dB = 12.39dB
Normal:12.39dB 
Worst :16.05dB
--------------------------------------------------------------
Attenuation Chart (dB) - 1310nm Wavelength
------------+---------+---------------------+-----------------
Conditions  |per Km   |per Connector Pair   |Per joint(splice)  
------------+---------+---------------------+-----------------
Min(Best)   |0.3      |0.4                  |0.02
Avg(Normal) |0.38     |0.6                  |0.1
Max(Worst)  |0.5      |1                    |0.2
==============================================================
For wavelength 1550nm: Normal
TA =  n x C     + c x J     + L       x a           + M = 
      2 x 0.35dB+ 4 x 0.05dB+ 20.5Km  x 0.22dB/Km   + 3dB = 8.41dB
Normal:8.41dB 
Worst :13dB
--------------------------------------------------------------
Attenuation Chart (dB) - 1550nm Wavelength
------------+---------+---------------------+-----------------
Conditions  |per Km   |per Connector Pair   |Per joint(splice)  
------------+---------+---------------------+-----------------
Min(Best)   |0.17     |0.2                  |0.01
Avg(Normal) |0.22     |0.35                 |0.05
Max(Worst)  |0.4      |0.7                  |0.1
*/
/*
//On EVENT - change destination port
$("#destinationPort").change(function(event) {
  //activate ATTENUATION CALCULATOR
  $(".influencesAttenuation").removeAttr("disabled");
  //disable care and wv at first.
  $(".startDisabled").prop('disabled', true);
});

//On EVENT - change anything affecting strand attenuation
//reference snippets/ajax/ajaxAddStrandModal_resources.php
$(".influencesAttenuation").on('keyup change', function(event) {
//check mode
modeChecker();

//attenuation per km switch
//var attenuationPerKM = 0.3;

//init constants
var totalLoss=0;
var feetToKmMultiplier=0.0003048;

//max attenuation
var attenuationPerConnectorPair=0.75;
var attenuationPerSplice=0.3;
var attenuationPerKM = 0.3;

//init/get user inputs
var totalFeetLength=$("#strandLength").val();
var totalConnectorPairs=$("#connectorPairs").val();
var totalSpliceCount=$("#spliceCount").val();

//convert feet to km
var totalKmLength=totalFeetLength*feetToKmMultiplier;

//calculate length,connector,and splice loss separately
var lengthLoss=(totalKmLength * attenuationPerKM);
var connectorLoss=(totalConnectorPairs * attenuationPerConnectorPair);
var spliceLoss=(totalSpliceCount * attenuationPerSplice);
totalLoss=lengthLoss+connectorLoss+spliceLoss;
//display total loss as calculated
$("#expectedLoss").val(totalLoss);

});

//mode checking function
function modeChecker(){
//default attenuation
  //if mode selected
  if ($("#fiberModeSelect").val()) {
    //alert("found mode.");

    //get mode, coresize, and wavelength
    var fiberMode=$("#fiberModeSelect").val();
    var fiberCoreSize=$("#fiberCoreSizeSelect").val();
    var fiberWavelength=$("#fiberWavelengthSelect").val();
    
    //load fiber "core size" options
    $("#fiberCoreSizeSelectContainer").load("snippets/ajax/modals/ajaxAddStrandModalLoadStrandOptions.php?mode="+fiberMode+"&container=core&selectedValue="+fiberCoreSize);

    //load wavelength options
    $("#fiberWavelengthSelectContainer").load("snippets/ajax/modals/ajaxAddStrandModalLoadStrandOptions.php?mode="+fiberMode+"&container=wave&selectedValue="+fiberWavelength);
    
    //activate core size and wavelength selections
    $("#fiberCoreSizeSelect").removeAttr("disabled");
    $("#fiberWavelengthSelect").removeAttr("disabled");

  }

  //if no mode selected
  else{
    //alert("no mode.");

  }

}
*/


/*


<?php
//use config.php (required for database login etc.)
require_once("../../../../fidoConfig.php");
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
echo "<select name=\"fiberCoreSizeSelect\" id=\"fiberCoreSizeSelect\" class=\"influencesAttenuation\">";
  foreach ($config['fiberCoreSizes_singlemode'] as &$value) {
    if($value==$selectedValue){
      echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
    }
    elseif($value==$config['defaultOptions']['singlemodeCoreSize']) {
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
echo "<select name=\"fiberWavelengthSelect\" id=\"fiberWavelengthSelect\" class=\"influencesAttenuation\">";
  foreach ($config['fiberWavelengths_singlemode'] as &$value) {
    if($value==$selectedValue){
      echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
    }
    elseif($value==$config['defaultOptions']['singlemodeWavelength']) {
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
echo "<select name=\"fiberCoreSizeSelect\" id=\"fiberCoreSizeSelect\" class=\"influencesAttenuation\">";
  foreach ($config['fiberCoreSizes_multimode'] as &$value) {
    if($value==$selectedValue){
      echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
    }
    elseif($value==$config['defaultOptions']['multimodeCoreSize']) {
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
echo "<select name=\"fiberWavelengthSelect\" id=\"fiberWavelengthSelect\" class=\"influencesAttenuation\">";
  foreach ($config['fiberWavelengths_multimode'] as &$value) {
    if($value==$selectedValue){
      echo "<option selected value=\"".$value."\">".ucfirst($value)."</option>";
    }
    elseif($value==$config['defaultOptions']['multimodeWavelength']) {
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

*/


