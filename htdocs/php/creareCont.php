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
$var1 = $_POST['username'];
$var2 = $_POST['parola'];
$var3 = $_POST['nume'];
$var4 = $_POST['prenume'];
$var5 = $_POST['cnp'];
$var6 = $_POST['data'];
$var7 = $_POST['intrebare'];
$var8 = $_POST['raspuns'];

$stid = oci_parse($connection, 'SELECT COUNT(USERNAME) as "count" FROM LOGIN WHERE CNP=:cnp');
if (!$stid) {
    $e = oci_error($connection);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_bind_by_name($stid, ':cnp', $var5);

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);
$count = $row['count'];

if ($count == 0) {

    $stid2 = oci_parse($connection, 'SELECT COUNT(USERNAME) as "countU" FROM LOGIN WHERE USERNAME = :username ');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    oci_bind_by_name($stid2, ':username', $var1);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $row2 = oci_fetch_array($stid2, OCI_ASSOC + OCI_RETURN_LOBS);
    $count2 = $row2['countU'];

    if ($count2 == 0) {
        $stid3 = oci_parse($connection, 'INSERT INTO LOGIN VALUES(:username,:parola,:nume,:prenume,:cnp,to_date(:data,\'MM-DD-YYYY\'),:intrebare,:raspuns) ');
        if (!$stid3) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid3, ':username', $var1);
        oci_bind_by_name($stid3, ':parola', $var2);
        oci_bind_by_name($stid3, ':nume', $var3);
        oci_bind_by_name($stid3, ':prenume', $var4);
        oci_bind_by_name($stid3, ':cnp', $var5);
        oci_bind_by_name($stid3, ':data', $var6);
        oci_bind_by_name($stid3, ':intrebare', $var7);
        oci_bind_by_name($stid3, ':raspuns', $var8);

        $r3 = oci_execute($stid3);
        if (!$r3) {
            $e = oci_error($stid3);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $message = "Contul a fost inregistrat cu succes! ";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../panouAdmin.html\";</script>";
    } else {
        $message = "Exista deja un cont creat cu acest username!";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../creareCont.html\";</script>";
    }
} else {
    $message = "Exista deja un cont creat cu acest cnp!";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../creareCont.html\";</script>";
}
