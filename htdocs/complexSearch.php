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


$data1 = NULL;
$data2 = NULL;
$cod = NULL;
$flagData1 = -1;
$flagData2 = -1;
$flagCod = -1;
$data1 = $_REQUEST['data1'];
$data2 = $_REQUEST['data2'];
$cod = $_REQUEST['cod'];


if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
    //we give the value of the starting row to 0 because nothing was found in URL
    $startrow = 0;
    //otherwise we take the value from the URL
} else {
    $startrow = (int)$_GET['startrow'];
}

if ($cod !=NULL) {
    if ((is_numeric($cod) == true)) {
        $stid3 = oci_parse($connection, 'SELECT COUNT(*) as "count" FROM CEREREVIZITE WHERE COD_DETINUT=:cod');

        if (!$stid3) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        oci_bind_by_name($stid3, ':cod', $cod);

        $r3 = oci_execute($stid3);

        if (!$r3) {
            $e = oci_error($stid3);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $row3 = oci_fetch_array($stid3, OCI_ASSOC + OCI_RETURN_LOBS);
        $count3 = $row3['count'];
    } else $count3 = 0;

    if ($count3 == 0) {
        $message = "EROARE! Cod invalid! ";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../administrare.html\";</script>";
        exit ();
    } else $flagCod = 1;
} else $flagCod = 0;

if ($data1 == NULL) $flagData1 = 0;
    else $flagData1 = parse_data($data1);

if ($data2 == NULL) $flagData2 = 0;
    else $flagData2 = parse_data($data2);

if ($flagData1 == -1) {
    $message = "EROARE! Data1 invalida ";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../administrare.html\";</script>";
    exit ();
}

if ($flagData2 == -1) {
    $message = "EROARE! Data2 invalida ";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../administrare.html\";</script>";
    exit ();
}

// CAZURI:


if ($flagCod == 0 AND $flagData1 == 0 AND $flagData2 ==0){
    $message = "DATE INEXISTENTE SAU GRESITE! ";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"../administrare.html\";</script>";
    exit ();
}

if ($flagCod == 0 AND $flagData1 == 0 AND $flagData2 ==1) {
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\')');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data2', $data2);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
    if ($count == 0) {
        $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
    }
    else{
        ?>

        <html>
        <head>
            <title>TABLE</title>
            <link rel="stylesheet" type="text/css" href="detinutiDB.css">
        </head>
        <body content="width=device-width, initial-scale=1.0">
        <table border="1">
            <tr>
                <td><b>ID</b></td>
                <td><b>NUME</b></td>
                <td><b>PRENUME</b></td>
                <td><b>CNP</b></td>
                <td><b>COD DETINUT</b></td>
                <td><b>RELATIE</b></td>
                <td><b>NATURA VIZITA</b></td>
                <td><b>DATA VIZITA</b></td>
                <td><b>ORA</b></td>
                <td><b>POZA</b></td>
            </tr>

            <?php
            $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow) WHERE DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND ROWNUM<30');
            oci_bind_by_name($stid, ':startrow', $startrow);
            oci_bind_by_name($stid, ':data2', $data2);

            if (!$stid) {
                $e = oci_error($connection);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $count2 = 0;
            while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
                $count2 = $count2 + 1;
                ?>
                <tr>
                    <td><?php echo $row[0] ?></td>
                    <td><?php echo $row[1] ?></td>
                    <td><?php echo $row[2] ?></td>
                    <td><?php echo $row[3] ?></td>
                    <td><?php echo $row[4] ?></td>
                    <td><?php echo $row[5] ?></td>
                    <td><?php echo $row[6] ?></td>
                    <td><?php echo $row[7] ?></td>
                    <td><?php echo $row[8] ?></td>
                    <td><?php echo $row[9] ?></td>
                </tr>

                <?php
            }
            ?>
        </table>
        <div class="Container_butoane">
        <button class="back" onclick="history.go(-1);">Back</button>

        <?php
        $prev = $startrow - 30;
        if ($prev >= 0) {
			$params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
			$params['startrow'] = $prev;
			$query = http_build_query($params);
            echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
        }
        if ($count >= 30) {
			$params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
			$params['startrow'] = $startrow+30;
			$query = http_build_query($params);
            echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
        }
    }
}

if ($flagCod == 0 AND $flagData1 == 1 AND $flagData2 ==0){
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\')');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data1', $data1);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
    if ($count == 0) {
        $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
    }
    else{
        ?>

        <html>
        <head>
            <title>TABLE</title>
            <link rel="stylesheet" type="text/css" href="detinutiDB.css">
        </head>
    <body content="width=device-width, initial-scale=1.0">
        <table border="1">
            <tr>
                <td><b>ID</b></td>
                <td><b>NUME</b></td>
                <td><b>PRENUME</b></td>
                <td><b>CNP</b></td>
                <td><b>COD DETINUT</b></td>
                <td><b>RELATIE</b></td>
                <td><b>NATURA VIZITA</b></td>
                <td><b>DATA VIZITA</b></td>
                <td><b>ORA</b></td>
                <td><b>POZA</b></td>
            </tr>

            <?php
            $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow) WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND ROWNUM<30');
            oci_bind_by_name($stid, ':startrow', $startrow);
            oci_bind_by_name($stid, ':data1', $data1);

            if (!$stid) {
                $e = oci_error($connection);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }

            $r = oci_execute($stid);
            if (!$r) {
                $e = oci_error($stid);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $count2 = 0;
            while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
                $count2 = $count2 + 1;
                ?>
                <tr>
                    <td><?php echo $row[0] ?></td>
                    <td><?php echo $row[1] ?></td>
                    <td><?php echo $row[2] ?></td>
                    <td><?php echo $row[3] ?></td>
                    <td><?php echo $row[4] ?></td>
                    <td><?php echo $row[5] ?></td>
                    <td><?php echo $row[6] ?></td>
                    <td><?php echo $row[7] ?></td>
                    <td><?php echo $row[8] ?></td>
                    <td><?php echo $row[9] ?></td>
                </tr>

                <?php
            }
            ?>
        </table>
        <div class="Container_butoane">
        <button class="back" onclick="history.go(-1);">Back</button>
        <?php
    $prev = $startrow - 30;
    //only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
    }
}

if ($flagCod == 0 AND $flagData1 == 1 AND $flagData2 ==1){
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\')');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data1', $data1);
    oci_bind_by_name($stid2, ':data2', $data2);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
if ($count == 0) {
    $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
}
else{
    ?>

    <html>
    <head>
        <title>TABLE</title>
        <link rel="stylesheet" type="text/css" href="detinutiDB.css">
    </head>
<body content="width=device-width, initial-scale=1.0">
    <table border="1">
        <tr>
            <td><b>ID</b></td>
            <td><b>NUME</b></td>
            <td><b>PRENUME</b></td>
            <td><b>CNP</b></td>
            <td><b>COD DETINUT</b></td>
            <td><b>RELATIE</b></td>
            <td><b>NATURA VIZITA</b></td>
            <td><b>DATA VIZITA</b></td>
            <td><b>ORA</b></td>
            <td><b>POZA</b></td>
        </tr>

        <?php
        $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:Startrow) WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND ROWNUM<30');
        oci_bind_by_name($stid, ':startrow', $startrow);
        oci_bind_by_name($stid, ':data1', $data1);
        oci_bind_by_name($stid, ':data2', $data2);

        if (!$stid) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $count2 = 0;
        while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
            $count2 = $count2 + 1;
            ?>
            <tr>
                <td><?php echo $row[0] ?></td>
                <td><?php echo $row[1] ?></td>
                <td><?php echo $row[2] ?></td>
                <td><?php echo $row[3] ?></td>
                <td><?php echo $row[4] ?></td>
                <td><?php echo $row[5] ?></td>
                <td><?php echo $row[6] ?></td>
                <td><?php echo $row[7] ?></td>
                <td><?php echo $row[8] ?></td>
                <td><?php echo $row[9] ?></td>
            </tr>

            <?php
        }
        ?>
    </table>
<div class="Container_butoane">
    <button class="back" onclick="history.go(-1);">Back</button>
    <?php
    $prev = $startrow - 30;

    ///only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
}
}

if ($flagCod == 1 AND $flagData1 == 0 AND $flagData2 ==0){
    ?>
    <html>
<head>
    <title>TABLE</title>
    <link rel="stylesheet" type="text/css" href="vizitaPerDetinut.css">
</head>
<body content="width=device-width, initial-scale=1.0">
<table border="1">
    <tr>
        <td><b>ID</b></td>
        <td><b>NUME</b></td>
        <td><b>PRENUME</b></td>
        <td><b>CNP</b></td>
        <td><b>COD DETINUT</b></td>
        <td><b>RELATIE</b></td>
        <td><b>NATURA VIZITA</b></td>
        <td><b>DATA VIZITA</b></td>
        <td><b>ORA</b></td>
        <td><b>POZA</b></td>
    </tr>
    <?php
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE COD_DETINUT=:cod');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':cod', $cod);
    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];

    $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:Startrow) WHERE COD_DETINUT=:cod AND ROWNUM<30');
    oci_bind_by_name($stid, ':startrow', $startrow);
    oci_bind_by_name($stid, ':cod', $cod);
    if (!$stid) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $r = oci_execute($stid);
    if (!$r) {
        $e = oci_error($stid);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $count2 = 0;
    while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
        $count2 = $count2 + 1;
        ?>
        <tr>
            <td><?php echo $row[0]?></td>
            <td><?php echo $row[1]?></td>
            <td><?php echo $row[2]?></td>
            <td><?php echo $row[3]?></td>
            <td><?php echo $row[4]?></td>
            <td><?php echo $row[5]?></td>
            <td><?php echo $row[6]?></td>
            <td><?php echo $row[7]?></td>
            <td><?php echo $row[8]?></td>
            <td><?php echo $row[9]?></td>
        </tr>

        <?php
    }
    ?>
</table>
<div class="Container_butoane">
    <button class="back" onclick="history.go(-1);">Back</button>
    <?php
    $prev = $startrow - 30;
    //only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
}

if ($flagCod == 1 AND $flagData1 == 0 AND $flagData2 ==1){
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND COD_DETINUT=:cod');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data2', $data2);
    oci_bind_by_name($stid2, ':cod', $cod);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
if ($count == 0) {
    $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
}
else{
    ?>

    <html>
    <head>
        <title>TABLE</title>
        <link rel="stylesheet" type="text/css" href="detinutiDB.css">
    </head>
<body content="width=device-width, initial-scale=1.0">
    <table border="1">
        <tr>
            <td><b>ID</b></td>
            <td><b>NUME</b></td>
            <td><b>PRENUME</b></td>
            <td><b>CNP</b></td>
            <td><b>COD DETINUT</b></td>
            <td><b>RELATIE</b></td>
            <td><b>NATURA VIZITA</b></td>
            <td><b>DATA VIZITA</b></td>
            <td><b>ORA</b></td>
            <td><b>POZA</b></td>
        </tr>

        <?php
        $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow) WHERE DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND COD_DETINUT=:cod AND ROWNUM<30');
        oci_bind_by_name($stid, ':startrow', $startrow);
        oci_bind_by_name($stid, ':data2', $data2);
        oci_bind_by_name($stid, ':cod', $cod);

        if (!$stid) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $count2 = 0;
        while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
            $count2 = $count2 + 1;
            ?>
            <tr>
                <td><?php echo $row[0] ?></td>
                <td><?php echo $row[1] ?></td>
                <td><?php echo $row[2] ?></td>
                <td><?php echo $row[3] ?></td>
                <td><?php echo $row[4] ?></td>
                <td><?php echo $row[5] ?></td>
                <td><?php echo $row[6] ?></td>
                <td><?php echo $row[7] ?></td>
                <td><?php echo $row[8] ?></td>
                <td><?php echo $row[9] ?></td>
            </tr>

            <?php
        }
        ?>
    </table>
<div class="Container_butoane">
    <button class="back" onclick="history.go(-1);">Back</button>
    <?php
    $prev = $startrow - 30;
    //only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
}
}

if ($flagCod == 1 AND $flagData1 == 1 AND $flagData2 ==0){
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND COD_DETINUT=:cod');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data1', $data1);
    oci_bind_by_name($stid2, ':cod', $cod);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
if ($count == 0) {
    $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
}
else{
    ?>

    <html>
    <head>
        <title>TABLE</title>
        <link rel="stylesheet" type="text/css" href="detinutiDB.css">
    </head>
<body content="width=device-width, initial-scale=1.0">
    <table border="1">
        <tr>
            <td><b>ID</b></td>
            <td><b>NUME</b></td>
            <td><b>PRENUME</b></td>
            <td><b>CNP</b></td>
            <td><b>COD DETINUT</b></td>
            <td><b>RELATIE</b></td>
            <td><b>NATURA VIZITA</b></td>
            <td><b>DATA VIZITA</b></td>
            <td><b>ORA</b></td>
            <td><b>POZA</b></td>
        </tr>

        <?php
        $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow) WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND COD_DETINUT=:cod AND ROWNUM<30');
        oci_bind_by_name($stid, ':startrow', $startrow);
        oci_bind_by_name($stid, ':data1', $data1);
        oci_bind_by_name($stid, ':cod', $cod);

        if (!$stid) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $count2 = 0;
        while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
            $count2 = $count2 + 1;
            ?>
            <tr>
                <td><?php echo $row[0] ?></td>
                <td><?php echo $row[1] ?></td>
                <td><?php echo $row[2] ?></td>
                <td><?php echo $row[3] ?></td>
                <td><?php echo $row[4] ?></td>
                <td><?php echo $row[5] ?></td>
                <td><?php echo $row[6] ?></td>
                <td><?php echo $row[7] ?></td>
                <td><?php echo $row[8] ?></td>
                <td><?php echo $row[9] ?></td>
            </tr>

            <?php
        }
        ?>
    </table>
<div class="Container_butoane">
    <button class="back" onclick="history.go(-1);">Back</button>
    <?php
    $prev = $startrow - 30;
    //only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
}
}

if ($flagCod == 1 AND $flagData1 == 1 AND $flagData2 ==1){
    $stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND COD_DETINUT=:cod');
    if (!$stid2) {
        $e = oci_error($connection);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_bind_by_name($stid2, ':data1', $data1);
    oci_bind_by_name($stid2, ':data2', $data2);
    oci_bind_by_name($stid2, ':cod', $cod);

    $r2 = oci_execute($stid2);
    if (!$r2) {
        $e = oci_error($stid2);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $row2 = oci_fetch_array($stid2);
    $count = $row2[0];
if ($count == 0) {
    $message = "NU EXISTA VIZITE IN INTERVALUL CERUT.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"administrare.html\";</script>";
}
else{
    ?>

    <html>
    <head>
        <title>TABLE</title>
        <link rel="stylesheet" type="text/css" href="detinutiDB.css">
    </head>
<body content="width=device-width, initial-scale=1.0">
    <table border="1">
        <tr>
            <td><b>ID</b></td>
            <td><b>NUME</b></td>
            <td><b>PRENUME</b></td>
            <td><b>CNP</b></td>
            <td><b>COD DETINUT</b></td>
            <td><b>RELATIE</b></td>
            <td><b>NATURA VIZITA</b></td>
            <td><b>DATA VIZITA</b></td>
            <td><b>ORA</b></td>
            <td><b>POZA</b></td>
        </tr>

        <?php
        $stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:Startrow) WHERE DATA_VIZITA>=TO_DATE(:data1,\'MM/DD/YYYY\') AND DATA_VIZITA<=TO_DATE(:data2,\'MM/DD/YYYY\') AND COD_DETINUT=:cod AND ROWNUM<30');
        oci_bind_by_name($stid, ':startrow', $startrow);
        oci_bind_by_name($stid, ':data1', $data1);
        oci_bind_by_name($stid, ':data2', $data2);
        oci_bind_by_name($stid, ':cod', $cod);

        if (!$stid) {
            $e = oci_error($connection);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $count2 = 0;
        while (($row = oci_fetch_array($stid)) != false || $count2 < 29) {
            $count2 = $count2 + 1;
            ?>
            <tr>
                <td><?php echo $row[0] ?></td>
                <td><?php echo $row[1] ?></td>
                <td><?php echo $row[2] ?></td>
                <td><?php echo $row[3] ?></td>
                <td><?php echo $row[4] ?></td>
                <td><?php echo $row[5] ?></td>
                <td><?php echo $row[6] ?></td>
                <td><?php echo $row[7] ?></td>
                <td><?php echo $row[8] ?></td>
                <td><?php echo $row[9] ?></td>
            </tr>

            <?php
        }
        ?>
    </table>
<div class="Container_butoane">
    <button class="back" onclick="history.go(-1);">Back</button>
    <?php
    $prev = $startrow - 30;
    //only print a "Previous" link if a "Next" was clicked
    if ($prev >= 0) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $prev;
        $query = http_build_query($params);
        echo '<a class="da" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Previous</a>';
    }
    if ($count >= 30) {
        $params = array('data1' => $data1, 'data2' => $data2, 'cod' => $cod);
        $params['startrow'] = $startrow+30;
        $query = http_build_query($params);
        echo '<a class="da2" href="' . $_SERVER['PHP_SELF'] . '?' . $query . '">Next</a>';
    }
}
}
?>


<?php

/**
 * @param $data
 */
function parse_data ($data){
    if ($data !=0) {

        $flag = 1;

        if (substr($data, 0, 2) > "12") $flag = -1;

        if ((substr($data, 0, 2) == "02" AND substr($data, 3, 2) > "28") OR ((substr($data, 0, 2) == "01" OR substr($data, 0, 2) == "03" OR substr($data, 0, 2) == "05" OR substr($data, 0, 2) == "07" OR substr($data, 0, 2) == "08" OR substr($data, 0, 2) == "10" OR substr($data, 0, 2) == "12") AND substr($data, 3, 2) > "31") OR (((substr($data, 0, 2) == "04" OR substr($data, 0, 2) == "06" OR substr($data, 0, 2) == "09" OR substr($data, 0, 2) == "11")) AND substr($data, 3, 2) > "30")) $flag = -1;

        if (substr($data, 6, 4) > "2017" OR substr($data, 6, 4) < "1900") $flag = -1;

        if (strlen($data)>10) $flag=-1;
    }

    return $flag;
}
?>