<!DOCTYPE html>
<html lang="en">

<?php 
include('snippets/sharedHead.php');

echo '<div class="alert alert-info">This page exists for testing purposes only.</div>';


//get connected
$db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
$stmt = $db->prepare('SELECT name AS thisName, equipment_UID, (SELECT COUNT(*) FROM equipment WHERE name = thisName ) AS count FROM equipment ORDER BY thisName ASC'); 
$stmt->execute();

$eq=array();
//$eq['name']=array();
//$eq['count']=array();
//$eq['uids']=array();
//$eq_names=array();
//$eq_counts=array();
//$eq_lists=array();

$tempName='';
$tempCount='';
$eqNdx=0;

foreach($stmt as $row) {
	if ($row['thisName']!=$tempName) {
		$tempName=$row['thisName'];
		$tempCount=$row['count'];
		$eqNdx++;
		//store name on index
		$eq[$eqNdx]['name'][]=$tempName;
		//store count on index

		$eq[$eqNdx]['count'][]=$tempCount;
	}
	else{
		$tempName=$row['name'];
		$tempCount=$row['count'];
		//store name on index
		$eq[$eqNdx]['name'][]=$tempName;
		//store count on index
		$eq[$eqNdx]['count'][]=$tempCount;
	}
}

}   

//Catch Errors (if errors)
catch(PDOException $e){}

echo '<div class="alert alert-info">';
foreach ($eq as $key => $value) {
	echo 'EQ Name:'.$value['name'][0];
	echo '<br />';
	echo 'EQ Count:'.$value['count'][0];
	echo '<br />';
	echo '<br />';
}
echo '</div>';

echo '<pre>';
print_r($eq);
echo '</pre>';

/*
//get connected
    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.'', $dbUser, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

foreach ($jumper_uid_array as $key => $value) {

try {
	$stmt = $db->prepare('UPDATE jumper SET notes=:thisDesc WHERE jumper_UID=:thisUID');
	$stmt->bindParam(':thisUID', $jumper_uid_array[$key]);
	$stmt->bindParam(':thisDesc', strtolower($jumper_desc_array[$key]));
	$stmt->execute();
	}   

	//Catch Errors (if errors)
	catch(PDOException $e){}

}


*/

?>

</html>