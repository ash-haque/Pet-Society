<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$sql = "SELECT * FROM volunteers ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>View Volunteers</title>
<style>
body{font-family:Arial;background:#f0f0f0;padding:20px;}
.container{max-width:800px;margin:auto;background:white;padding:20px;border-radius:10px;}
table{width:100%;border-collapse:collapse;}
th,td{border:1px solid #ccc;padding:10px;text-align:left;}
th{background:#ff6b6b;color:white;}
tr:nth-child(even){background:#f2f2f2;}
</style>
</head>
<body>
<div class="container">
<h2>All Volunteers</h2>
<table>
<tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th></tr>
<?php
if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
        echo "<tr><td>".$row['id']."</td><td>".htmlspecialchars($row['name'])."</td><td>".htmlspecialchars($row['phone'])."</td><td>".htmlspecialchars($row['email'])."</td></tr>";
    }
} else echo "<tr><td colspan='4'>No volunteers found</td></tr>";
$conn->close();
?>
</table>
</div>
</body>
</html>
