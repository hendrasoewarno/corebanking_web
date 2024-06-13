<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Edit Account</title>
</head>
<body>

<?php
include "library.php";

session_start();

if (!isset($_REQUEST["noac"])) {
?>
<h1>Edit Account</h1>
<form action= "editAccount.php" method="POST" enctype="application/x-www-form-urlencoded">
<label for="noac">NO.AC:</label>
<input type="text" name="noac" maxlength="20"/><br>
<input type="submit" value="Ajukan"/>
<input type= "reset" value="Reset"/>
</form>
<?php
}
else {
	$con = openConnection();
	
	if(!isset($_REQUEST["nik"])) {
	
		$con = openConnection();
	
		$sqlS = <<<EOD
			SELECT noac, nama, nik FROM account Where noac=:noac;
EOD;
	
		$dataAwal = queryArrayValue($con, $sqlS, array("noac"=>$_REQUEST["noac"]));
		
		if ($dataAwal) {
			$_SESSION["noac"] = $_REQUEST["noac"];
?>
<h1>Edit Account</h1>
<form action= "editAccount.php" method="POST" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="noac" value="<?php echo $dataAwal['noac']; ?>">
<label for="upah">Nik:</label>
<input type="text" name="nik" value="<?php echo $dataAwal['nik']; ?>" maxlength="30"/ readonly><br>
<label for="upah">Nama:</label>
<input type="text" name="nama" value="<?php echo $dataAwal['nama']; ?>" maxlength="100"/><br>
<input type="submit" value="Ajukan"/>
<input type= "reset" value="Reset"/>
</form>
<?php
		}
		else {
			echo "Data tidak ditemukan";
		}
	}
	else {
		if ($_SESSION["noac"]!=$_REQUEST["noac"])
			echo "<h1>NO.AC tidak konsisten!<h1>";
		else {
			echo "<h1>Hasil Edit Account</h1>";
			$data=array();
			$data["noac"]=$_REQUEST["noac"];
			$data["nik"]=$_REQUEST["nik"];
			$data["nama"]=$_REQUEST["nama"];
	
			$sqlU = <<<EOD
			UPDATE account SET nik=:nik, nama=:nama WHERE noac=:noac;
EOD;

			updateRow($con, $sqlU, $data);
	
			$sqlS = <<<EOD
				SELECT noac, nik, nama FROM account Where noac=:noac;
EOD;

			$hasil = queryArrayValue($con, $sqlS, array("noac"=>$_REQUEST["noac"]));
	
			if ($hasil) {
				echo "NO.AC :" . $hasil["noac"] . "</br>";
				echo "Nama :" . $hasil["nama"] . "</br>";
				echo "NIK :" . $hasil["nik"] . "</br>";
			}
			else {
				echo "Tidak ditemukan";
			}
		}
	}
}
?>
</body>
</html>


