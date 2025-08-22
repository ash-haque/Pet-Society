<?php
$conn = new mysqli("localhost","root","","animal_care");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$message = "";
if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['volunteer_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO volunteers (name, phone, email) VALUES (?,?,?)");
    $stmt->bind_param("sss",$name,$phone,$email);

    if($stmt->execute()) $message = "Volunteer added successfully!";
    else $message = "Error: ".$stmt->error;
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register Volunteer</title>
<style>
body{font-family:Arial;background:#f0f8ff;padding:20px;}
.container{max-width:500px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 12px rgba(0,0,0,0.1);}
input,button{width:100%;padding:10px;margin:5px 0;border-radius:5px;border:1px solid #ccc;}
button{background:#ff6b6b;color:white;border:none;cursor:pointer;}
button:hover{background:#e05555;}
.message{text-align:center;color:green;font-weight:bold;}
</style>
</head>
<body>
<div class="container">
<h2>Register Volunteer</h2>
<?php if($message!="") echo "<div class='message'>$message</div>"; ?>
<form method="post">
<input type="text" name="volunteer_name" placeholder="Volunteer Name" required>
<input type="text" name="phone" placeholder="Phone" required>
<input type="email" name="email" placeholder="Email" required>
<button type="submit">Add Volunteer</button>
</form>
</div>
</body>
</html>
