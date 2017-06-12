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

$S1 = 'SANATOS';
$query= oci_parse($connection, 'SELECT COUNT(ID) as "count" FROM DETINUTI WHERE SANATATE LIKE :S1 ');



$rows = array();
$table = array();
$table['cols'] = array(

    array('label' => 'sanatate', 'type' => 'varchar2'),
    array('label' => 'nume', 'type' => 'varchar2')

);

if (is_array($query)) {
    foreach($query as $que) {

        $temp = array();
        $temp[] = array('v' => (string) $que['durata_pedeapsa']);
        $temp[] = array('v' => (string) $que ['nume']);
        $rows[] = array('c' => $temp);
    }}

$table['rows'] = $rows;


$jsonTable = json_encode($table);

//print $jsonTable;
//echo $jsonTable;



?>



<html>
<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['Task', ''],
                ['1 AN',  0],
                ['2 ANI', 2],
                ['3 ANI', 3],
                ['4 ANI', 1],
                ['5 ANI', 1],
                ['6 ANI', 0],
                ['7 ANI', 2],
                ['8 ANI', 0],
                ['9 ANI', 0],
                ['10 ANI', 0],
            ]);

            var options = {
                title: 'Statistica pedepsei detinutului',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div id="piechart" style="width: 140vw;  height: 100vh;"></div>
</body>
</html>
