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

$stid = oci_parse($connection, 'SELECT to_number(substr(DATA_NASTERE,8,2)) as "Varsta" , COUNT(substr(DATA_NASTERE,8,2)) as "count" FROM DETINUTI group by substr(DATA_NASTERE,8,2)');
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
$data[0] = array('Varsta','count');
$i=1;
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS)) != false) {
    $Varsta = $row['Varsta'];
    $count = $row['count'];
    $data[$i] = array($Varsta,(int)$count);
    $i = $i +1;
}
echo json_encode($data);
oci_close($connection);

?>