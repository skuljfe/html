<!DOCTYPE html>
<html>
<head>
<title>Pametna hiša</title>
<script src="../js.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body>

<?php
include '../functions.php';
$real_out_chart=getdata('Nibe_topics','real_out',1);
$DM=getdata('Nibe_topics','DM',1);
?>

<div class="chart_temp" width="100%"></div>

<script>
console.log("1");
var real_out_chart=<?php echo json_encode($real_out_chart);?>;
console.log("2");
draw(real_out_chart,'line','.chart_temp', 'Temperatura');
</script>


</body>
</html>