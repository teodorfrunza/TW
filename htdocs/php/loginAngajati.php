<?php
//Oracle DB user name
$username = 'TW';

// Oracle DB user password
$password = 'TW';

// Oracle DB connection string
$connection_string = 'localhost/xe';

//Connect to an Oracle database
$connection = oci_connect(
    $username,
    $password,
    $connection_string
);

$var1 = $_POST['USERNAME'];
$var2 = $_POST['PASSWORD'];

$stid = oci_parse($connection, 'SELECT COUNT(*) AS "count" FROM LOGIN WHERE USERNAME LIKE :username AND PASSWORD LIKE :password');
if (!$stid) {
    $e = oci_error($connection);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
oci_bind_by_name($stid, ':username', $var1);
oci_bind_by_name($stid, ':password', $var2);

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS);
$count = $row['count'];
if ($count == 1 && $var1 != 'admin') {
	session_start();
	$_SESSION['login_user'] = $var1;
    header("location: ../panouAngajati.html");
}
else if (($count == 1 && $var1 == 'admin'))
    {
        session_start();
        $_SESSION['login_user'] = $var1;
        header("location: ../panouAdmin.html");
    }
    else{
    $message = "Nume sau parola gresita. Va rog incercati din nou!";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../loginAngajati.html\";</script>";
}

oci_close($connection);
?>