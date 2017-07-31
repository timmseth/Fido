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

					//==============================================
					//START ATTENUATION CALCULATOR
			        $(".recalculateExpectedLoss").on("keyup change", function(event) {
						//where does the result go?
						var attenuationPerKM = 0;
						var box_strandExpLoss = document.getElementById("strandExpLoss");

						//init constants
						var totalLoss=0;
						var feetToKmMultiplier=0.0003048;
						var attenuationPerConnectorPair=0.75;
						var attenuationPerSplice=0.3;

						//get user selections
						var totalFeetLength=$("#strandLength").val();
						var totalConnectorPairs=$("#strandConPairs").val();
						var totalSpliceCount=$("#strandSpliceCount").val();
						var fiberMode=$("#strandMode").val();
						var fiberCoreSize=$("#strandCore").val();
						var fiberWavelength=$("#strandWave").val();

						if (fiberMode=="singlemode") {
						//clear/set to default if using a restricted value
							if (fiberCoreSize=='50' || fiberCoreSize=='62.5' || fiberWavelength=='850' || fiberWavelength=='1300') {
								$("#strandCore").prop('selectedIndex', 0);
								$("#strandWave").prop('selectedIndex', 0);
							}
							//enable stuff
							$("#strandCore option[value='8.3']").prop('disabled', false);
							$("#strandWave option[value='1310']").prop('disabled', false);
							$("#strandWave option[value='1550']").prop('disabled', false);
							$("#strandWave option[value='1383']").prop('disabled', false);
							$("#strandWave option[value='1625']").prop('disabled', false);
							//disable stuff
							$("#strandCore option[value='50']").prop('disabled', true);
							$("#strandCore option[value='62.5']").prop('disabled', true);
							$("#strandWave option[value='850']").prop('disabled', true);
							$("#strandWave option[value='1300']").prop('disabled', true);
							$("#strandWave").val('1310');
						}

						if (fiberMode=="multimode") {
							//clear/set to default if using a restricted value
							if (fiberCoreSize=='8.3' || fiberWavelength=='1310' || fiberWavelength=='1550' || fiberWavelength=='1383' || fiberWavelength=='1625') {
								$("#strandCore").prop('selectedIndex', 0);
								$("#strandWave").prop('selectedIndex', 0);
							}
							//enable stuff
							$("#strandCore option[value='50']").prop('disabled', false);
							$("#strandCore option[value='62.5']").prop('disabled', false);
							$("#strandWave option[value='850']").prop('disabled', false);
							$("#strandWave option[value='1300']").prop('disabled', false);
							//disable stuff
							$("#strandCore option[value='8.3']").prop('disabled', true);
							$("#strandWave option[value='1310']").prop('disabled', true);
							$("#strandWave option[value='1550']").prop('disabled', true);
							$("#strandWave option[value='1383']").prop('disabled', true);
							$("#strandWave option[value='1625']").prop('disabled', true);
							$("#strandWave").val('850');
						}

							//load fiber "core size" and "wavelength" options based on selected "fiber mode"
							//$("#strandCoreSelectContainer").load("../pages/snippets/ajax/ajaxLoadStrandOptions.php?mode="+fiberMode+"&container=core&selectedValue="+fiberCoreSize);
							//$("#strandWavelengthSelectContainer").load("../pages/snippets/ajax/ajaxLoadStrandOptions.php?mode="+fiberMode+"&container=wave&selectedValue="+fiberWavelength);
							//activate core size and wavelength selections
							//$("#strandCore").removeAttr("disabled");
							//$("#strandWavelength").removeAttr("disabled");
						//if no mode selected
						else{
							//alert("no mode.");
						}

						console.log('\n\nNew Strand Data:');
						console.log('totalFeetLength: '+totalFeetLength);
						console.log('totalConnectorPairs: '+totalConnectorPairs);
						console.log('totalSpliceCount: '+totalSpliceCount);
						console.log('fiberMode: '+fiberMode);
						console.log('fiberCoreSize: '+fiberCoreSize);
						console.log('fiberWavelength: '+fiberWavelength);


						//attenuation per km switch
						attenuationPerKM = 0.3;

						if (fiberMode=="singlemode" && (fiberWavelength=="1310" || fiberWavelength=="1383")){
							attenuationPerKM = 0.4;
						}

						if (fiberMode=="multimode" && fiberCoreSize=="62.5"){
							attenuationPerKM = 3.4;
						}

						if (fiberMode=="multimode" && fiberCoreSize=="50"){
							attenuationPerKM = 3.0;
						}

					

						var totalKmLength=totalFeetLength*feetToKmMultiplier;

						//calculate length,connector,and splice loss separately
						var lengthLoss=(totalKmLength * attenuationPerKM);
						var connectorLoss=(totalConnectorPairs * attenuationPerConnectorPair);
						var spliceLoss=(totalSpliceCount * attenuationPerSplice);

						console.log('Per KM Attenuation at : '+attenuationPerKM);
						console.log(attenuationPerKM+' * '+totalKmLength);
						console.log('lengthLoss : '+lengthLoss);
						console.log('connectorLoss : '+connectorLoss);
						console.log('spliceLoss : '+spliceLoss);
						
						//find total loss
						totalLoss=lengthLoss+connectorLoss+spliceLoss;
						console.log('totalLoss : '+totalLoss);
						
						//clear
						$(box_strandExpLoss).val("");
						//set
						$(box_strandExpLoss).val(totalLoss);
						//Animate to draw attention
						$(box_strandExpLoss).removeClass("animated bounce");
						$(box_strandExpLoss).addClass("animated bounce");

				
					//END ATTENUATION CALCULATOR                   
					//==============================================
			        });
