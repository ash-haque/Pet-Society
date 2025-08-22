<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Fetch appointments with animal and vet names
$sql = "SELECT a.id, an.animal_name, v.vet_name, a.appointment_date, a.appointment_time, a.notes, a.status
        FROM appointments a
        JOIN animals an ON a.animal_id = an.id
        JOIN vets v ON a.vet_id = v.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Appointments</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f8ff;padding:20px;}
.container{max-width:900px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 15px rgba(0,0,0,0.1);}
h2{text-align:center;color:#0073e6;margin-bottom:20px;}
table{width:100%;border-collapse:collapse;}
th, td{padding:10px;text-align:left;border-bottom:1px solid #ccc;}
th{background:#0073e6;color:#fff;}
tr:hover{background:#f1f9ff;}
</style>
</head>
<body>
<div class="container">
<h2>All Appointments</h2>
<table>
<tr>
<th>ID</th>
<th>Animal</th>
<th>Vet</th>
<th>Date</th>
<th>Time</th>
<th>Notes</th>
<th>Status</th>
</tr>
<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['animal_name']}</td>
                <td>{$row['vet_name']}</td>
                <td>{$row['appointment_date']}</td>
                <td>{$row['appointment_time']}</td>
                <td>{$row['notes']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
}else{
    echo "<tr><td colspan='7' style='text-align:center'>No appointments found</td></tr>";
}
?>
</table>
</div>
</body>
</html>

