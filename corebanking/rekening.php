<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Rekening Koran</title>
</head>
<body>

<?php
include "library.php";

if (!isset($_REQUEST["noac"])) {
?>
<h1>Rekening Koran</h1>
<form action= "rekening.php" method="POST" enctype="application/x-www-form-urlencoded">
<label for="noac">NO.AC:</label>
<input type="text" name="noac" maxlength="20"/><br>
<input type="submit" value="Ajukan"/>
<input type= "reset" value="Reset"/>
</form>
<?php
}
else {
	$con = openConnection();
	
	if(!isset($_REQUEST["amount"])) {
	
		$sqlH = <<<EOD
			SELECT noac, nama, nik, balance FROM account Where noac=:noac;
EOD;
	
		$dataHeader = queryArrayValue($con, $sqlH, array("noac"=>$_REQUEST["noac"]));
		echo "<h1>Rekening Koran</h1>";
		echo "No.AC:" . $dataHeader["noac"] . "</br>";
		echo "Nama:" . $dataHeader["nama"] . "</br>";
		echo "NIK:" . $dataHeader["nik"] . "</br>";
		echo "Balance:" . $dataHeader["balance"] . "</br>";

		$sqlD = <<<EOD
			SELECT noac, txid, cabang, code, amount, timestamp FROM transaction WHERE noac=:noac;
EOD;

		$dataDetails = queryArrayRowsValues($con, $sqlD, array("noac"=>$_REQUEST["noac"]));
		
		echo "<table>";
		echo "<thead><tr><th>TXID</th><th>Cabang</th><th>Code</th><th>Amount</th><th>TimeStamp</th></tr></thead>";
		echo "<tbody>";
		if (sizeof($dataDetails)) {
			for ($i=0; $i<sizeof($dataDetails); $i++) {
				$row=$dataDetails[$i];
				echo "<tr><th>" . $row["txid"] . "</th><td>" . $row["cabang"] . "</td><td>" . $row["code"] . "</td><td style='text-align: right;'>" . formatUang($row["amount"], False) . "</td><td>" . $row["timestamp"] . "</td></tr>";				
			}			
		}
		echo "</tbody>";
	}
}
?>
</body>
</html>


