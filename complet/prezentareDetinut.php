
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

    $var = $_POST['COD'];
    $stid = oci_parse($connection, 'SELECT ID,NUME,PRENUME,DATA_NASTERE,DURATA_PEDEAPSA,MOTIV,SANATATE,POZA FROM DETINUTI WHERE ID= :myid');
    if (!$stid) {
        $e = oci_error($conn);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid, ':myid', $var);
    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS);
    $nume=$row['NUME'];
    $prenume=$row['PRENUME'];
    $varsta=$row['DATA_NASTERE'];
    $cod=$row['ID'];
    $durata=$row['DURATA_PEDEAPSA'];
    $motiv=$row['MOTIV'];
    $sanatate=$row['SANATATE'];
    $poza=$row['POZA']
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Detinut</title>
    <link rel="stylesheet" type="text/css" href="prezentareDetinut.css">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0">
</head>
<body>

<div class="Container">
    <img src="<?php echo htmlspecialchars($poza);?>" alt="Person" class="imagine">

    <li>
        <ul><b>Nume: </b><?php echo htmlspecialchars($nume);?></ul>
        <ul><b>Prenume: </b><?php echo htmlspecialchars($prenume);?></ul>
        <ul><b>Data nastere: </b><?php echo htmlspecialchars($varsta);?></ul>
        <ul><b>Cod detinut: </b><?php echo htmlspecialchars($cod);?></ul>
        <ul><b>Durata pedepsei: </b><?php echo htmlspecialchars($durata);?></ul>
        <ul><b>Motivul: </b><?php echo htmlspecialchars($motiv);?></ul>
        <ul><b>Stare de sanatate: </b><?php echo htmlspecialchars($sanatate);?></ul>
    </li>

    <a href="CodDetinuti.html" class="Buton2">Inapoi</a>
    <img class="Poza" src="asd.jpg" alt="Cadran">
</div>
</body>
</html>

<?php
    oci_close($connection);
?>