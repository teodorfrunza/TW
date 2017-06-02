
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
    if(is_numeric($var) == true) {
        $stid4 = oci_parse($connection, 'SELECT COUNT(ID) as "count" FROM DETINUTI WHERE ID=:codD');

        if (!$stid4) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid4, ':codD', $var4);
        $r4 = oci_execute($stid4);
        if (!$r4) {
            $e = oci_error($stid4);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $row4 = oci_fetch_array($stid4, OCI_ASSOC + OCI_RETURN_LOBS);
        $count2 = $row4['count'];

        if ($count2 >=1) {
            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_LOBS);
            $nume = $row['NUME'];
            $prenume = $row['PRENUME'];
            $varsta = $row['DATA_NASTERE'];
            $cod = $row['ID'];
            $durata = $row['DURATA_PEDEAPSA'];
            $motiv = $row['MOTIV'];
            $sanatate = $row['SANATATE'];
            $poza = $row['POZA'];
        }
        else {
            $message = "Nu exista nici un detinut cu acest cod! ";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = \"CodDetinuti.html\";</script>";
        }
    }
    else {
        $message = "Cod invalid! ";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"CodDetinuti.html\";</script>";
    }
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