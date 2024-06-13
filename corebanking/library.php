<?php
function openConnection() {
	try {
		$con = new PDO('mysql:host=localhost;port=3306;dbname=corebanking', 'root', '');
		#$con = new PDO('sqlsrv:Server=YouAddress;Database=YourDatabase', 'Username', 'Password');
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
	return $con;
}

//Select
function querySingleValue($con, $sSql, $values) {
//return single Value
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			
			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);
				
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
				return $row[0];
			} else {
				return null;
			}
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function queryArrayValue($con, $sSql, $values) {
//return array values
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;

			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);				
		
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				return $row;
			} else {
				return null;
			}
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function queryArrayRowsValue($con, $sSql, $values) {
//return array values (1 dimensi)
	$arr = array();	
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;

			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);				
		
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				do {
					$arr[] = $row[0];
			
				} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));
			}
		return $arr;
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function queryArrayRowsValues($con, $sSql, $values) {
//return array values (2 dimensi)
	$arr = array();		
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
		
			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);				
			
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				do {

					$arr[] = $row;
				
				} while ($row = $stmt->fetch(PDO::FETCH_ASSOC));		
			}
						
			return $arr;
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

//CRUD
function createRow($con, $sSql, $values) {
//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;

			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);				

			$stmt->execute();
			return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function updateRow($con, $sSql, $values) {
//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;

			foreach ($paramValues as $key=>$value)
				$stmt->bindValue(':'.$key,$value);				

			$stmt->execute();
			return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function deleteRow($con, $sSql, $values) {
//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
		throw new Exception("0:  (" . $con->errno . ") " . $con->error);
	} else {
		$paramValues = $values;

		foreach ($paramValues as $key=>$value)
			$stmt->bindValue(':'.$key,$value);				

		$stmt->execute();
		return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function formatUang($number, $mataUang=true) {
	return ($mataUang?"Rp. ":"") . number_format($number,0,",",".");
}

function isLogin() {
	return isset($_SESSION['userid']) and isset($_SESSION['password']);
}

function isValidUser() {
	#return (strtolower($_SESSION['userid']=="hendra") && $_SESSION['password']=="soewarno");
$con=openConnection();	
$sqlS = "Select count(*) as ada from zoperator where userid=:userid and password=:password";
$ada = querySingleValue($con, $sqlS, array("userid"=>$_SESSION["userid"], "password"=>sha1($_SESSION["password"])));
return ($ada>0);
}

function loginPage($title, $message) {
?>
<!doctype html>
<html>
<head>
	<title><?php echo $title; ?></title>
</head>
<body>
	<h1><?php echo $message; ?></h1>
	<form action="index.php" method="POST" class="form" id="form">
		<input type="text" name="userid" placeholder="UserId">
		<input type="password" name="password" placeholder="Password">
		<button type="submit" id="login-button">Login</button>
	</form>
</body>
</html>
<?php
}

//--------------------------Entry Point-------------------------------
//setting timezone
date_default_timezone_set('Asia/Jakarta');

// Start the session
session_start();

#inisialisasi nilai failed
if (!isset($_SESSION["failed"]))
	$_SESSION["failed"]=0;
#jika sudah gagal 3x
else if ($_SESSION["failed"]>=10)
	#belum 15 menit berikutnya
	if (time() < $_SESSION["lasttime"]+15*60) {
		echo "Silakan Tunggu 15 menit berikutnya!";
		die();
	}
	else
		$_SESSION["failed"]=0;

$con = openConnection();

if (isset($_REQUEST['logout'])) {
	session_destroy();
	header('Location: index.php');
	die();
}
else if (isset($_REQUEST['userid']) && isset($_REQUEST['password'])) {
	$_SESSION['userid']=$_REQUEST['userid'];
	$_SESSION['password']=$_REQUEST['password'];
}

try {
	if (!isLogin()) {		
		throw new Exception("Please Login");		
	}
	else if(!isValidUser()){
		#tambahkan counter kegagalan
		$_SESSION["failed"]+=1;
		#catat waktu terakhir gagal
		$_SESSION["lasttime"]=time();
		throw new Exception("Please Login");
	}
	else {
		$_SESSION["failed"]=0;
	}
}
catch (Exception $e) {
	loginPage("Login", $e->getMessage());
	die();
}
?>