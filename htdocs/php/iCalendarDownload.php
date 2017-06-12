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

$c = $_POST['cod'];
if (is_numeric($c) == true) {
    $stid2 = oci_parse($connection, 'SELECT COUNT(ID) as "count" FROM DETINUTI WHERE ID=:cod');

    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':cod', $c);

    $r = oci_execute($stid2);
    if (!$r) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2, OCI_ASSOC + OCI_RETURN_LOBS);
    $count = $row2['count'];
    if ($count == 1) {

        include 'iCalendar.php';

        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename=invite.ics');


        $stid = oci_parse($connection, 'SELECT D.ID AS "detid",D.NUME AS "detnum",D.PRENUME AS "detpren",DURATA_PEDEAPSA AS "durped",C.ID "cerid",DATA_VIZITA AS "datv" FROM DETINUTI D JOIN CEREREVIZITE C ON D.ID=COD_DETINUT WHERE D.ID=:cod');
        if (!$stid) {
            $e = oci_error($conn);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        oci_bind_by_name($stid, ':cod', $c);

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS)) != false) {
            $ics = new iCalendar(array(
                'detinut_id' => $row['detid'],
                'detinut_nume' => $row['detnum'],
                'detinut_prenume' => $row['detpren'],
                'durata_pedeapsa' => $row['durped'],
                'cerere_vizita_id' => $row['cerid'],
                'data_vizita_id' => $row['datv']

            ));
            echo $ics->to_string();
            echo '
';
            echo '=======================================================================================';
            echo '
';
        }
    } else {
        $message = "Eroare! Nu exista nici un detinut avand codul " . $c;
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../iCalendar.html\";</script>";
    }
} else {
    $message = "Eroare! Cod invalid!";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../iCalendar.html\";</script>";
}


oci_close($connection);


