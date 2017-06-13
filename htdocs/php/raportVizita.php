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

session_start();

$var1 = $_POST['cod'];
$var2 = $_POST['rezumat'];
$var3 = $_POST['ore'];
$var4 = $_POST['ob1'];
$var5 = $_POST['ob2'];
$var6 = $_POST['spirit'];

if (is_numeric($var1) == true) {
    $stid = oci_parse($connection, 'SELECT COUNT(ID) as "count" FROM CEREREVIZITE WHERE ID=:cod');
    if (!$stid) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    oci_bind_by_name($stid, ':cod', $var1);

    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);
    $count = $row['count'];

    if ($count == 0) {
        $message = "Vizita cu id-ul " . $var1 . " nu exista.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../raportVizita.html\";</script>";
    } else {
        $stid3 = oci_parse($connection, 'SELECT COUNT(ID) as "count" FROM RAPORTVIZITE WHERE ID=:cod');
        if (!$stid3) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid3, ':cod', $var1);

        $r3 = oci_execute($stid3);
        if (!$r3) {
            $e = oci_error($stid3);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $row3 = oci_fetch_array($stid3, OCI_ASSOC + OCI_RETURN_LOBS);
        $count2 = $row3['count'];
        if ($count2 == 0) {
            $stid2 = oci_parse($connection, 'INSERT INTO RAPORTVIZITE VALUES (:id,:rezumat,:ore,:ob1,:ob2,:spirit,:angajat)');
            if (!$stid2) {
                $e = oci_error($connection);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }


            oci_bind_by_name($stid2, ':id', $var1);
            oci_bind_by_name($stid2, ':rezumat', $var2);
            oci_bind_by_name($stid2, ':ore', $var3);
            oci_bind_by_name($stid2, ':ob1', $var4);
            oci_bind_by_name($stid2, ':ob2', $var5);
            oci_bind_by_name($stid2, ':spirit', $var6);
            oci_bind_by_name($stid2, ':angajat', $_SESSION['login_user']);

            $r1 = oci_execute($stid2);
            if (!$r1) {
                $e = oci_error($stid2);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            $message = "Datele au fost inregistrate cu succes. ";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../panouAngajati.html\";</script>";
        } else {
            $message = "Exista deja un raport cu acest id! ";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../raportVizita.html\";</script>";
        }
    }
} else {
    $message = "Cod invalid! ";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../raportVizita.html\";</script>";
}

oci_close($connection);
?>