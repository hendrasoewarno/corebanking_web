<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Tambah Cabang</title>
</head>
<body>

<?php
include "library.php";

if (!isset($_REQUEST["keterangan"])) {
?>
<h1>Tambah Cabang</h1>
<form action= "addCabang.php" method="POST" enctype="application/x-www-form-urlencoded">
<label for="cabang">Cabang:</label>
<input type="text" name="cabang" maxlength="10"/><br>
<label for="keterangan">Keterangan:</label>
<input type="text" name="keterangan" maxlength="255"/><br>
<input type="submit" value="Ajukan"/>
<input type= "reset" value="Reset"/>
</form>
<?php
}
else {
	$con = openConnection();
	
	echo "<h1>Hasil Tambah Cabang</h1>";
	
	$data=array();
	$data["cabang"]=$_REQUEST["cabang"];
	$data["keterangan"]=strtoupper($_REQUEST["keterangan"]);
	
	$sqlI = <<<EOD
	INSERT INTO Cabang(cabang, keterangan)
		values (:cabang, :keterangan);
EOD;

	createRow($con, $sqlI, $data);
	
	$sqlS = <<<EOD
	SELECT cabang, keterangan FROM cabang WHERE cabang=:cabang;
EOD;

	$hasil = queryArrayValue($con, $sqlS, array("cabang"=>$_REQUEST["cabang"]));
	
	if ($hasil) {
		echo "Cabang :" . $hasil["cabang"] . "</br>";
		echo "Keterangan :" . $hasil["keterangan"] . "</br>";	
	}
	else {
		echo "Tidak ditemukan";
	}
}
?>
</body>
</html>
