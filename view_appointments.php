<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Fetch all appointments with animal and vet names
$sql = "SELECT a.id, an.animal_name, v.name AS vet_name, a.appointment_date, a.appointment_time, a.notes, a.status
        FROM appointments a
        LEFT JOIN animals an ON a.animal_id = an.id
        LEFT JOIN vets v ON a.vet_id = v.id
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
.table-wrapper{overflow-x:auto;}
table{border-collapse: collapse;width:100%;}
th,td{padding:10px;text-align:left;border:1px solid #ccc;}
th{background:#0073e6;color:white;}
tr:nth-child(even){background:#f9f9f9;}
.status{padding:5px 10px;border-radius:5px;color:white;font-weight:bold;}
.Scheduled{background:#0073e6;}
.Completed{background:#28a745;}
.Cancelled{background:#ff6b6b;}
</style>
</head>
<body>
<div class="container">
    <h2>Appointments List</h2>
    <div class="table-wrapper">
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
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['animal_name']}</td>";
                echo "<td>{$row['vet_name']}</td>";
                echo "<td>{$row['appointment_date']}</td>";
                echo "<td>{$row['appointment_time']}</td>";
                echo "<td>{$row['notes']}</td>";
                echo "<td class='status {$row['status']}'>{$row['status']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No appointments found.</td></tr>";
        }
        ?>
    </table>
    </div>
</div>
</body>
</html>
