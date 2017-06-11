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
                ['Intre 20 - 30',    5 ],
                ['Intre 30 - 40',    2 ],
                ['Intre 40 - 50',    0 ],
                ['Intre 50 - 60',    2 ],
                ['Intre 60 - 70',    1 ],
                ['Intre 70 - 80',    0 ],
                ['Intre 80 - 90',    0 ],
                ['Intre 90 - 100',   0 ]
            ]);

            var options = {
                title: 'Statistica varstei detinutilor',
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
