<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$sql = "SELECT * FROM vets ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>View Vets</title>
<style>
body{font-family:Arial;background:#f0f0f0;padding:20px;}
.container{max-width:800px;margin:auto;background:white;padding:20px;border-radius:10px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:10px;text-align:left;}
th{background:#0073e6;color:white;}
tr:nth-child(even){background:#f2f2f2;}
</style>
</head>
<body>
<div class="container">
<h2>All Vets</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Specialization</th><th>Phone</th></tr>
<?php
if($result->num_rows>0){
    while($row = $result->fetch_assoc()){
        echo "<tr><td>".$row['id']."</td><td>".htmlspecialchars($row['name'])."</td><td>".htmlspecialchars($row['specialization'])."</td><td>".htmlspecialchars($row['phone'])."</td></tr>";
    }
} else echo "<tr><td colspan='4'>No vets found</td></tr>";
$conn->close();
?>
</table>
</div>
</body>
</html>
