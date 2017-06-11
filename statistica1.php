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
                ['Sanatosi',    5 ],
                ['GRIPAT',      1],
                ['SINDROM TOURETTE',  1],
                ['TUBERCULOZA', 1],
                ['HEMOROIZI', 1],
                ['HEPATITCA C',    1]
            ]);

            var options = {
                title: 'Statistica sanatate detinuti',
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
