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

$stid = oci_parse($connection, 'SELECT sanatate as "Sanatate" , COUNT(sanatate) as "Count" FROM DETINUTI group by sanatate');
if (!$stid) {
    $e = oci_error($connection);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

# set heading
$data[0] = array('Sanatate','Count');
$i=1;
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
    $san = $row['Sanatate'];
    $count = $row['Count'];
    $data[$i] = array($san,(int)$count);
    $i = $i +1;
}
echo json_encode($data);
oci_close($connection);
?>



