<?php
class Account {
	$conn=null;
	$values = Null;
	
	function __construct($conn) {
		$this->conn = $conn;
	}
	
	function add($noAc, $nama, $nik) {
		$values = array("noac"=>$noAc, "nama"=>$nama, "nik"=>$nik, "pin"=>"123456", "balance"=>0);
		$sql = "INSERT INTO account(noac, nama, nik, pin, balance) VALUES (:noac, :nama, :nik, :pin, :balance)";
		return createRow($this->conn, $sql, $values);
	}
	
	#belum ada check amount negatif
	function deposit($noAc, $cabang, $amount) {
		$this->conn->BeginTransaction();
		#cegah SELECT FOR UPDATE lainnya sampai transaksi ini selesai
		$sqlS = "SELECT noac, balance+:amount as balance, lasttxid+1 as txid FROM account WHERE noac=:noac FOR UPDATE";
		$values = queryArrayValue($con, $sqlS, $data);
				
		if ($values) {
			$data["txid"]=$values["txid"];
			$data["cabang"]=$cabang;
			$data["code"]="11";				
		
	}
	
	
	function search($noAc) {
		$sql = "SELECT noac, nama, nik, pin, balance FROM account WHERE noac=:noac";
		$this->values = queryArrayValue($this->conn, $sql, array("noac"=>$noAc));
		return $this->values!=null;	
	}
	
}