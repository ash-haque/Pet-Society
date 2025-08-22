<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$message = "";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['vet_name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO vets (name, specialization, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss",$name,$specialization,$phone);

    if($stmt->execute()) $message = "Vet added successfully!";
    else $message = "Error: ".$stmt->error;
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register Vet</title>
<style>
body{font-family:Arial;background:#f0f8ff;padding:20px;}
.container{max-width:500px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 12px rgba(0,0,0,0.1);}
input,button{width:100%;padding:10px;margin:5px 0;border-radius:5px;border:1px solid #ccc;}
button{background:#0073e6;color:white;border:none;cursor:pointer;}
button:hover{background:#005bb5;}
.message{text-align:center;color:green;font-weight:bold;}
</style>
</head>
<body>
<div class="container">
<h2>Register Vet</h2>
<?php if($message!="") echo "<div class='message'>$message</div>"; ?>
<form method="post">
<input type="text" name="vet_name" placeholder="Vet Name" required>
<input type="text" name="specialization" placeholder="Specialization" required>
<input type="text" name="phone" placeholder="Phone" required>
<button type="submit">Add Vet</button>
</form>
</div>
</body>
</html>
