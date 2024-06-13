<?php
include_once "library.php";

function changePasswd($message="") {
?>
<!doctype html>
<html>
<head>
<title>Ubah Password</title>
</head>
<body>
<h1>Ubah Password</h1>
<p><?php echo $message ?></p>
<p>UserId: <?php echo $_SESSION["userid"] ?></p>
<form action="?state=1" method="POST"">
	<p>Password Lama:<input type="password" name="oldpassword" maxlength="30"
		placeholder="Isikan Password Lama" value=""/>
	</p>
	<p>Password Baru:<input type="password" name="newpassword1" maxlength="30"
		placeholder="Isikan Password Baru" value=""/>
	</p>
	<p>Ketik Kembali:<input type="password" name="newpassword2" maxlength="30"
		placeholder="Isikan Password Baru" value=""/>
	</p>
	<p><input type="submit" value="Simpan"/></p>
</form>
</body>
</html>
<?php
}

if (!isset($_REQUEST["newpassword2"]))
	changePasswd("Ketikan password lama dan baru");
else if (strlen($_REQUEST["newpassword1"])<5)
	changePasswd("Password baru minimal 5 karakter.");
else if ($_REQUEST["oldpassword"]==$_REQUEST["newpassword1"])
	changePasswd("Pengetikan lama sama dengan password baru");
else if (strlen($_REQUEST["newpassword1"])<5 || $_REQUEST["newpassword1"]!=$_REQUEST["newpassword2"])
	changePasswd("Pengetikan password baru tidak konsisten");
else {				
	$sSql= <<<EOD
update zoperator set password=:newpassword1, modiby=:userid, moditime=now()
	where userid=:userid and password=:oldpassword;
EOD;
	$values = array("oldpassword"=>sha1($_REQUEST["oldpassword"]),
		"newpassword1"=>sha1($_REQUEST["newpassword1"]),
		"userid"=>$_SESSION["userid"]);
	
	$con = openConnection();
	if (updateRow($con, $sSql, $values)>0) {
		session_destroy();
		header('Location: index.php');
		die();
	}
	else
		changePasswd("Password gagal diganti");
}
?>