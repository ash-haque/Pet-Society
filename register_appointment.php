<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$message = "";

// Handle form submission
if($_SERVER['REQUEST_METHOD']=="POST"){
    $animal_id = intval($_POST['animal_id']);
    $vet_id = intval($_POST['vet_id']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = $_POST['notes'];
    
    $stmt = $conn->prepare("INSERT INTO appointments (animal_id, vet_id, appointment_date, appointment_time, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $animal_id, $vet_id, $appointment_date, $appointment_time, $notes);
    
    if($stmt->execute()) $message = "Appointment registered successfully!";
    else $message = "Error: ".$stmt->error;
    $stmt->close();
}

// Fetch animals and vets for dropdown
$animals = $conn->query("SELECT id, animal_name FROM animals ORDER BY animal_name ASC");
$vet_list = $conn->query("SELECT id, name FROM vets ORDER BY name ASC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Appointment</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f8ff;padding:20px;}
.container{max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 15px rgba(0,0,0,0.1);}
h2{text-align:center;color:#0073e6;}
input,select,textarea,button{width:100%;padding:10px;margin:5px 0 12px;border-radius:5px;border:1px solid #ccc;}
button{background:#0073e6;color:white;border:none;cursor:pointer;}
button:hover{background:#005bb5;}
.message{text-align:center;font-weight:bold;color:green;}
</style>
</head>
<body>
<div class="container">
    <h2>Book an Appointment</h2>
    <?php if($message!="") echo "<div class='message'>$message</div>"; ?>
    <form method="post">
        <label>Select Animal</label>
        <select name="animal_id" required>
            <option value="">--Select Animal--</option>
            <?php while($a=$animals->fetch_assoc()) echo "<option value='{$a['id']}'>{$a['animal_name']}</option>"; ?>
        </select>

        <label>Select Vet</label>
        <select name="vet_id" required>
            <option value="">--Select Vet--</option>
            <?php while($v=$vet_list->fetch_assoc()) echo "<option value='{$v['id']}'>{$v['name']}</option>"; ?>
        </select>

        <label>Date</label>
        <input type="date" name="appointment_date" required>
        <label>Time</label>
        <input type="time" name="appointment_time" required>
        <label>Notes</label>
        <textarea name="notes" rows="3"></textarea>
        <button type="submit">Book Appointment</button>
    </form>
</div>
</body>
</html>
