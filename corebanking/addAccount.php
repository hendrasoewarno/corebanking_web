<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Tambah Account</title>
</head>
<body>

<?php
include "library.php";

if (!isset($_REQUEST["nik"])) {
?>
<h1>Tambah Account</h1>
<form action= "addAccount.php" method="POST" enctype="application/x-www-form-urlencoded">
<label for="nama">Nama:</label>
<input type="text" name="nama" maxlength="1000"/><br>
<label for="nik">NIK:</label>
<input type="text" name="nik" maxlength="16"/><br>
<input type="submit" value="Ajukan"/>
<input type= "reset" value="Reset"/>
</form>
<?php
}
else {
	$con = openConnection();
	
	echo "<h1>Hasil Tambah Account</h1>";
	
    $cabang = "1271110101";
	
	$sqlS="SELECT count(*) FROM account WHERE noac like :cabang;";
	$count=querySingleValue($con, $sqlS, array("cabang"=>$cabang."%"));
	
	$noac=$cabang . str_pad($count+1, 4, "0",STR_PAD_LEFT);
	$data=array();
	$data["noac"]=$noac;
	$data["nama"]=strtoupper($_REQUEST["nama"]);
	$data["nik"]=$_REQUEST["nik"];
	$data["pin"]="123456";
	
	$sqlI = <<<EOD
	INSERT INTO account(noac, nama, nik, pin, balance, lasttxid)
		values (:noac, :nama, :nik, :pin, 0, 0);
EOD;

	createRow($con, $sqlI, $data);
	
	$sqlS = <<<EOD
	SELECT noac, nama, nik, balance FROM account WHERE noac=:noac;
EOD;

	$hasil = queryArrayValue($con, $sqlS, array("noac"=>$noac));
	
	if ($hasil) {
		echo "NO.AC :" . $hasil["noac"] . "</br>";
		echo "Nama :" . $hasil["nama"] . "</br>";
		echo "NIK :" . $hasil["nik"] . "</br>";
		echo "Balance :" . $hasil["balance"] . "</br>";
		
	}
	else {
		echo "Tidak ditemukan";
	}
}
?>
</body>
</html>
