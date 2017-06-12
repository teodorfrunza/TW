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

$c = $_POST['data'];
$date = $c;
list($m, $d, $yy) = explode("/", $date);
if (checkdate($m, $d, $yy)) {
    include 'iCalendar2.php';

    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename=invite.ics');


    $stid = oci_parse($connection, 'SELECT ID AS "id", NUME AS "nume", PRENUME AS "prenume", CNP AS "cnp", COD_DETINUT AS "cod_detinut", RELATIE AS "relatie", NATURA_VIZITA AS "natura_vizita", DATA_VIZITA AS "data_vizita", ORA AS "ora", POZA AS "poza" from CEREREVIZITE where DATA_VIZITA=to_date(:data,\'MM-DD-YYYY\')');
    if (!$stid) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid, ':data', $c);
    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    while (($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS)) != false) {
        $ics = new iCalendar2(array(
            'vizita_id' => $row['id'],
            'nume_vizitator' => $row['nume'],
            'prenume_vizitator' => $row['prenume'],
            'cnp_vizitator' => $row['cnp'],
            'cod_detinut' => $row['cod_detinut'],
            'relatie' => $row['relatie'],
            'natura_vizita' => $row['natura_vizita'],
            'data_vizita' => $row['data_vizita'],
            'ora' => $row['ora'],
            'poza' => $row['poza']
        ));
        echo $ics->to_string();
        echo '
';
        echo '=======================================================================================';
        echo '
';
    }
} else {
    $message = "Eroare! Data invalida!";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../iCalendar.html\";</script>";
}


oci_close($connection);


