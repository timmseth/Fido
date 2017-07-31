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
	//get panel
	if (isset($_GET['value_panelUID'])) {
		$value_panelUID=$_GET['value_panelUID'];
	}
	//get port
	if (isset($_GET['value_panelUID'])) {
		$value_portUID=$_GET['value_portUID'];
	}
?>

<form id="addStrandForm" name="addStrandForm" action="manageCabinet.php?uid=0131" method="POST"> 
	<div class="noBorder col-xs-12">
		<?php echo '<p>Add form to add a strand on panel '.$value_panelUID.' port '.$value_portUID.'.</p> ';?>
		<?php 
		echo '
		<input type="hidden" name="panel_a" id="panel_a" value="'.$value_panelUID.'" /> 
		<input type="hidden" name="port_a" id="port_a" value="'.$value_portUID.'" />
		';
		?>
		
		<div class="noBorder col-xs-12 col-md-6"> 
			<div id="workSpaceDestinationBuilding" name="workSpaceDestinationBuilding"></div> 
			<div id="workSpaceDestinationLevel" name="workSpaceDestinationLevel"></div> 
			<div id="workSpaceDestinationLocation" name="workSpaceDestinationLocation"></div> 
			<div id="workSpaceDestinationStorageUnit" name="workSpaceDestinationStorageUnit"></div> 
			<div id="workSpaceDestinationCabinet" name="workSpaceDestinationCabinet"></div> 
			<div id="workSpaceDestinationPanel" name="workSpaceDestinationPanel"></div> 
			<div id="workSpaceDestinationPort" name="workSpaceDestinationPort"></div> 
		</div>
		
		<div class="noBorder col-xs-12 col-md-6"> 
			<div class="form-group">		
				<label for="strandLength">Length (ft):</label> 
				<input required class="form-control recalculateExpectedLoss" type="number" name="strandLength" id="strandLength"> 

				<label for="strandMode">Mode:</label>
				<select required class="form-control recalculateExpectedLoss" name="strandMode" id="strandMode">
					<option selected disabled value="">Select a mode...</option>
					<option value="singlemode">Singlemode</option>
					<option value="multimode">Multimode</option>
				</select> 
			</div> 

			<div class="form-group">
				<div id="strandCoreSelectContainer">
					<label for="strandCore">Core Size:</label> 
					<select required class="form-control recalculateExpectedLoss" name="strandCore" id="strandCore">
						<option selected disabled value="">Select a core size...</option>
						<option value="8.3">8.3</option>
						<option value="50">50</option>
						<option value="62.5">62.5</option>
					</select>  
				</div> 
			</div> 

			<div class="form-group">
				<div id="strandWavelengthSelectContainer">
					<label for="strandWave">Wavelength:</label> 
					<select required class="form-control recalculateExpectedLoss" name="strandWave" id="strandWave">
						<option selected disabled value="">Select a wavelength...</option>
						<option value="850">850</option>
						<option value="1300">1300</option>
						<option value="1310">1310</option>
						<option value="1383">1383</option>
						<option value="1550">1550</option>
						<option value="1625">1625</option>
					</select>
				</div>
			</div> 

			<div class="form-group">
				<label for="strandSpliceCount">Splice Count:</label>
				<input required class="form-control recalculateExpectedLoss" type="number" name="strandSpliceCount" id="strandSpliceCount" > 
			</div> 

			<div class="form-group">
				<label for="strandConPairs">Connector Pairs Count:</label> 
				<input required class="form-control recalculateExpectedLoss" type="number" name="strandConPairs" id="strandConPairs" > 
			</div> 

			<div class="form-group">
				<label for="strandExpLoss">Expected Loss:</label> 
				<input required class="form-control" type="text" name="strandExpLoss" id="strandExpLoss">
			</div> 

		</div>
		<input id="submitAddStrand" name="submitAddStrand" type="submit" class="btn btn-default submit" value="Create Strand">
	</div>
</form>

<?php
?>

