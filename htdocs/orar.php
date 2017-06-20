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
			
			if (!isset($_GET['startrow']) or !is_numeric($_GET['startrow'])) {
			  //we give the value of the starting row to 0 because nothing was found in URL
			  $startrow = 0;
			//otherwise we take the value from the URL
			} else {
			  $startrow = (int)$_GET['startrow'];
			}
			
			$stid2 = oci_parse($connection, 'SELECT COUNT(*) FROM CEREREVIZITE WHERE DATA_VIZITA=TO_DATE(TO_CHAR(SYSDATE,\'MM/DD/YYYY\'),\'MM/DD/YYYY\')');
			if (!$stid2) {
				$e = oci_error($connection);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$r2 = oci_execute($stid2);
			if (!$r2) {
				$e = oci_error($stid2);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$row2 = oci_fetch_array($stid2);
			$count = $row2[0];
			
			$stid = oci_parse($connection, 'SELECT * FROM (SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow+30 MINUS SELECT * FROM CEREREVIZITE WHERE ROWNUM<:startrow) WHERE DATA_VIZITA=TO_DATE(TO_CHAR(SYSDATE,\'MM/DD/YYYY\'),\'MM/DD/YYYY\') AND ROWNUM<30');
			oci_bind_by_name($stid,':startrow',$startrow);
			if (!$stid) {
				$e = oci_error($connection);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$r = oci_execute($stid);
			if (!$r) {
				$e = oci_error($stid);
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
			$count2=0;
			 while (($row = oci_fetch_array($stid)) != false || $count2<29) {
				 $count2=$count2+1;
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
             <button class="back" onclick="history.go(-1);">Back </button>
             <?php
             $prev = $startrow - 30;

             //only print a "Previous" link if a "Next" was clicked
             if ($prev >= 0)
                 echo '<a class="da" href="'.$_SERVER['PHP_SELF'].'?startrow='.$prev.'">Previous</a>';

             if($count>=30)
             {echo '<a class="da2" href="'.$_SERVER['PHP_SELF'].'?startrow='.($startrow+30).'">Next</a>';}
             ?>
        </div>
    </body>
</html>