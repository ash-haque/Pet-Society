<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "animal_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['animal_name'];
    $type = $_POST['animal_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $owner_name = $_POST['owner_name'];
    $owner_contact = $_POST['owner_contact'];
    $medical_notes = $_POST['medical_notes'];

    // Handle image upload
    $image_path = "";
    if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] == 0) {
        $uploads_dir = "uploads";
        if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);
        $filename = time() . "_" . basename($_FILES['animal_image']['name']);
        $image_path = $uploads_dir . "/" . $filename;
        move_uploaded_file($_FILES['animal_image']['tmp_name'], $image_path);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO animals (animal_name, animal_type, breed, age, gender, owner_name, owner_contact, medical_notes, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $name, $type, $breed, $age, $gender, $owner_name, $owner_contact, $medical_notes, $image_path);

    if ($stmt->execute()) $message = "Animal registered successfully!";
    else $message = "Error: " . $stmt->error;

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Animal Registration</title>
<style>
body{font-family:Arial,sans-serif; background:#eaf6ff; margin:0; padding:0;}
.header{background:#4CAF50;color:white;padding:15px;text-align:center;font-size:1.5rem;font-weight:bold;}
.container{max-width:700px;margin:30px auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 6px 15px rgba(0,0,0,0.1);}
h2{text-align:center;margin-bottom:20px;}
form{display:flex;flex-direction:column;gap:12px;}
input,select,textarea{padding:10px;font-size:1rem;border-radius:5px;border:1px solid #ccc;width:100%;}
button{padding:12px;font-size:1rem;border:none;border-radius:5px;background:#4CAF50;color:white;cursor:pointer;}
button:hover{background:#45a049;}
.message{text-align:center;font-weight:bold;color:green;margin-bottom:12px;}
</style>
</head>
<body>
<div class="header">Animal Care Centre — Registration</div>
<div class="container">
    <h2>Register Your Animal</h2>
    <?php if($message!="") echo "<div class='message'>$message</div>"; ?>
    <form action="animal_registration.php" method="post" enctype="multipart/form-data">
        <input type="text" name="animal_name" placeholder="Animal Name" required>
        <input type="text" name="animal_type" placeholder="Animal Type (Dog, Cat, etc.)" required>
        <input type="text" name="breed" placeholder="Breed" required>
        <input type="text" name="age" placeholder="Age" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="text" name="owner_name" placeholder="Owner Name" required>
        <input type="text" name="owner_contact" placeholder="Owner Contact" required>
        <textarea name="medical_notes" placeholder="Medical Notes" rows="3"></textarea>
        <input type="file" name="animal_image" accept="image/*">
        <button type="submit">Register Animal</button>
    </form>
</div>
</body>
</html>
