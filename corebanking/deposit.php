<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<title>Deposit</title>
</head>
<body>

<?php
include "library.php";

session_start();

if (!isset($_REQUEST["noac"])) {
?>
<h1>Deposit</h1>
<form action= "deposit.php" method="POST" enctype="application/x-www-form-urlencoded">
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
	
		$sqlS = <<<EOD
			SELECT noac, nama, nik FROM account Where noac=:noac;
EOD;
	
		$dataAwal = queryArrayValue($con, $sqlS, array("noac"=>$_REQUEST["noac"]));
		
		if ($dataAwal) {
			$_SESSION["noac"] = $_REQUEST["noac"];
?>
<h1>Deposit</h1>
<form action= "deposit.php" method="POST" enctype="application/x-www-form-urlencoded">
<input type="hidden" name="noac" value="<?php echo $dataAwal['noac']; ?>">
<label for="upah">Nik:</label><span><?php echo $dataAwal['nik']; ?></span><br>
<label for="upah">Nama:</label><span><?php echo $dataAwal['nama']; ?></span><br>
<input type="number" name="amount" value="0" maxlength="15"/><br>
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
			#belum ada pemeriksaan jika amount negatif (-)
			echo "<h1>Hasil Transaction</h1>";
		
		    $cabang = "1271110101";
			
			$data=array();
			$data["noac"]=$_REQUEST["noac"];
			$data["amount"]=$_REQUEST["amount"];
			
			try {
				$con->BeginTransaction();
				#cegah SELECT FOR UPDATE lainnya sampai transaksi ini selesai
				$sqlS = "SELECT noac, balance+:amount as balance, lasttxid+1 as txid FROM account WHERE noac=:noac FOR UPDATE";
				
				$values = queryArrayValue($con, $sqlS, $data);
				
				if ($values) {
					$data["txid"]=$values["txid"];
					$data["cabang"]=$cabang;
					$data["code"]="11";
							
					$sqlI = "INSERT INTO transaction(noac, txid, cabang, code, amount, timestamp) VALUES (:noac, :txid, :cabang, :code, :amount, now());";
			
					$affected = createRow($con, $sqlI, $data);
								
					if($affected>0) {
					
						$sqlU = <<<EOD
							UPDATE account SET lasttxid=:txid, balance=:balance  WHERE noac=:noac;
EOD;
						$affected=updateRow($con, $sqlU, $values);
								
						if ($affected>0) {						
							$con->commit();
							
							$sqlS = <<<EOD
								SELECT noac, txid, cabang, code, amount, timestamp FROM transaction WHERE noac=:noac and txid=:txid and amount=:amount;
EOD;
							$hasil = queryArrayValue($con, $sqlS, array("noac"=>$values["noac"], "txid"=>$values["txid"], "amount"=>$data["amount"]));
							
							if ($hasil) {
								echo "NO.AC :" . $hasil["noac"] . "</br>";
								echo "TXID :" . $hasil["txid"] . "</br>";
								echo "CABANG :" . $hasil["cabang"] . "</br>";
								echo "CODE :" . $hasil["code"] . "</br>";
								echo "AMOUNT :" . formatUang($hasil["amount"]) . "</br>";
								echo "TIMESTAMP :" . $hasil["timestamp"] . "</br>";
							}
							else {
								echo "Transaksi tidak ditemukan";
							}								
						}
						else
							throw new Exception("Gagal Update Account!");
					}
					else
						throw new Exception("Gagal Simpan Transaksi!");
				}
				else
					throw new Exception("NO.AC tidak ditemukan!");
			} catch (Exception $e) {
				echo "<h1>" . $e->getMessage() . "</h1>";
				$con->rollback();
			}
		}
	}
}
?>
</body>
</html>


