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
$var3 = $_POST['intrebare'];
$var4 = $_POST['raspuns'];

$stid = oci_parse($connection, 'SELECT COUNT(USERNAME) as "count" FROM LOGIN WHERE USERNAME LIKE :username');
if (!$stid) {
    $e = oci_error($connection);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

oci_bind_by_name($stid, ':username', $var1);

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);
$count = $row['count'];

if($count == 1){
    $stid2 = oci_parse($connection, 'SELECT INTREBARE as "intrebare", RASPUNS as "raspuns" FROM LOGIN WHERE USERNAME LIKE :username');
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
    $intrebare = $row2['intrebare'];
    $raspuns = $row2['raspuns'];

    if($intrebare == $var3 && $raspuns == $var4){
        $stid3 = oci_parse($connection, 'UPDATE LOGIN SET PASSWORD=:parola WHERE USERNAME LIKE :username');
        if (!$stid3) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid3, ':username', $var1);
        oci_bind_by_name($stid3, ':parola', $var2);

        $r3 = oci_execute($stid3);
        if (!$r3) {
            $e = oci_error($stid3);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $message = "Parola schimbata cu succes!";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"angajati.html\";</script>";

    }
    else{
        $message = "Intrebarea sau raspunsul este gresit!";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"forgotPass.html\";</script>";
    }

}
else{
    $message = "EROARE! Username-ul nu a fost gasit!";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"forgotPass.html\";</script>";
}


oci_close($connection);
?>