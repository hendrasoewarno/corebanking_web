<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Rekening Koran</title>
</head>
<body>

<?php
include "library.php";

$con = openConnection();
	
if(!isset($_REQUEST["amount"])) {
	
	$sql = <<<EOD
		SELECT noac, nama, nik FROM account;
EOD;
	
	
	$data = queryArrayRowsValues($con, $sql, array());
	
	echo "<table>";
	echo "<thead><tr><th>No.AC</th><th>Nama</th><th>NIK</th></tr></thead>";
	echo "<tbody>";
	if (sizeof($data)) {
		for ($i=0; $i<sizeof($data); $i++) {
			$row=$data[$i];
			echo "<tr><th>" . $row["noac"] . "</th><td>" . $row["nama"] . "</td><td>" . $row["nik"] . "</td></tr>";				
		}			
	}
	echo "</tbody>";
}
?>
</body>
</html>


