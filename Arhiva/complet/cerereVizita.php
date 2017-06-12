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

$var1 = $_POST['nume'];
$var2 = $_POST['prenume'];
$var3 = $_POST['cnp'];
$var4 = $_POST['cod-detinut'];
$var5 = $_POST['relatie'];
$var6 = $_POST['natura-vizitei'];
$var7 = $_POST['data'];
$var8 = $_POST['ora'];


$target_dir = "C:/Apache24/htdocs/dwnds/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check != false) {
    $uploadOk = 1;
} else {
    $message = "EROARE! Fisierul incarcat nu este o imagine";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    $message = "EROARE! Exista deja un fisier cu numele " . $_FILES["fileToUpload"]["name"] . " .";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 20971520) {
    $message = "EROARE! Fisier prea mare.";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    $message = "EROARE! Se accepta doar fisiere .jpg .png .jpeg .";
    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error

// if everything is ok, try to upload file

if($uploadOk == 1) {
    if (is_numeric($var4) == true) {
//stid4
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

        if (is_numeric($var3) == true AND strlen($var3) == 13) {

//stid2
            $stid2 = oci_parse($connection, 'SELECT COUNT(*) AS "count" FROM CEREREVIZITE WHERE CNP = :cnp AND DATA_VIZITA = to_date(:data_vizita,\'MM/DD/YYYY\')');

            if (!$stid2) {
                $e = oci_error($connection);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            oci_bind_by_name($stid2, ':cnp', $var3);
            oci_bind_by_name($stid2, ':data_vizita', $var7);

            $r2 = oci_execute($stid2);
            if (!$r2) {
                $e = oci_error($stid2);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $row2 = oci_fetch_array($stid2, OCI_ASSOC + OCI_RETURN_LOBS);
            $count = $row2['count'];

//stid3

            $stid3 = oci_parse($connection, 'SELECT MAX(ID) AS "ID" FROM CEREREVIZITE');

            if (!$stid3) {
                $e = oci_error($connection);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $r3 = oci_execute($stid3);
            if (!$r3) {
                $e = oci_error($stid3);
                trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
            }
            $row3 = oci_fetch_array($stid3, OCI_ASSOC + OCI_RETURN_LOBS);
            $max = $row3['ID'];

            if ($count2 == 0) {
                $message = "Detinutul cu id-ul " . $var4 . " nu exista.";
                echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
            } else {
                if ($count == 0) {

                    //stid1

                    $stid1 = oci_parse($connection, 'INSERT INTO CEREREVIZITE VALUES (:id+1,:nume,:prenume,:cnp,:cod_detinut,:relatie,:natura,to_date(:data_vizita,\'MM/DD/YYYY\'),:ora,:poza)');

                    if (!$stid1) {
                        $e = oci_error($connection);
                        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                    }

                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        oci_bind_by_name($stid1, ':id', $max);
                        oci_bind_by_name($stid1, ':nume', $var1);
                        oci_bind_by_name($stid1, ':prenume', $var2);
                        oci_bind_by_name($stid1, ':cnp', $var3);
                        oci_bind_by_name($stid1, ':cod_detinut', $var4);
                        oci_bind_by_name($stid1, ':relatie', $var5);
                        oci_bind_by_name($stid1, ':natura', $var6);
                        oci_bind_by_name($stid1, ':data_vizita', $var7);
                        oci_bind_by_name($stid1, ':ora', $var8);
                        oci_bind_by_name($stid1, ':poza', $_FILES["fileToUpload"]["name"]);

                        $r1 = oci_execute($stid1);
                        if (!$r1) {
                            $e = oci_error($stid1);
                            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                        }
                    } else {
                        $message = "EROARE! Nu s-a putut incarca fisierul.";
                        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
                    }

                    $message = "Datele au fost inregistrate cu succes. ";
                    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"guest.html\";</script>";
                } else {

                    $message = "EROARE! Aveti deja o vizita inregistrata in data de " . $var7;
                    echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
                }
            }
        } else {
            $message = "EROARE! CNP invalid.";
            echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
        }
    } else {
        $message = "EROARE! Cod invalid! ";
        echo "<script type='text/javascript'>alert('$message'); window.location.href = \"cerereV.html\";</script>";
    }
}
oci_close($connection);
?>