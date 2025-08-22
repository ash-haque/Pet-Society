<?php
$conn = new mysqli("localhost", "root", "", "animal_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_GET['id'])) { header("Location: view_animals.php"); exit; }
$id = intval($_GET['id']);
$message = "";

// Fetch current animal info safely
$stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows == 0) { header("Location: view_animals.php"); exit; }
$animal = $res->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['animal_name'];
    $type = $_POST['animal_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $owner_name = $_POST['owner_name'];
    $owner_contact = $_POST['owner_contact'];
    $medical_notes = $_POST['medical_notes'];

    $image_path = $animal['image_path'];

    // Handle image upload
    if (isset($_FILES['animal_image']) && $_FILES['animal_image']['error'] == 0) {
        if (!empty($image_path) && file_exists($image_path)) unlink($image_path);
        $uploads_dir = "uploads";
        if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);
        $filename = time() . "_" . basename($_FILES['animal_image']['name']);
        $image_path = $uploads_dir . "/" . $filename;
        move_uploaded_file($_FILES['animal_image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("UPDATE animals 
        SET animal_name=?, animal_type=?, breed=?, age=?, gender=?, owner_name=?, owner_contact=?, medical_notes=?, image_path=? 
        WHERE id=?");
    $stmt->bind_param("sssssssssi", $name, $type, $breed, $age, $gender, $owner_name, $owner_contact, $medical_notes, $image_path, $id);

    if ($stmt->execute()) {
        $message = "✅ Updated successfully!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
    $stmt->close();

    // Refresh updated animal data
    $stmt = $conn->prepare("SELECT * FROM animals WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $animal = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Animal</title>
<style>
body{font-family:Arial,sans-serif;background:#f0f0f0;padding:20px;}
.container{max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 15px rgba(0,0,0,0.1);}
h2{text-align:center;color:#004080;}
input,select,textarea{width:100%;padding:10px;margin:5px 0 12px;border-radius:5px;border:1px solid #ccc;}
button{padding:12px;width:100%;background:#4CAF50;color:white;border:none;border-radius:5px;cursor:pointer;}
button:hover{background:#45a049;}
.message{text-align:center;font-weight:bold;color:green;}
</style>
</head>
<body>
<div class="container">
    <h2>Edit Animal</h2>
    <?php if($message!="") echo "<div class='message'>$message</div>"; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="animal_name" value="<?= htmlspecialchars($animal['animal_name']) ?>" required>
        <input type="text" name="animal_type" value="<?= htmlspecialchars($animal['animal_type']) ?>" required>
        <input type="text" name="breed" value="<?= htmlspecialchars($animal['breed']) ?>" required>
        <input type="text" name="age" value="<?= htmlspecialchars($animal['age']) ?>" required>
        <select name="gender" required>
            <option value="Male" <?= $animal['gender']=='Male'?'selected':'' ?>>Male</option>
            <option value="Female" <?= $animal['gender']=='Female'?'selected':'' ?>>Female</option>
        </select>
        <input type="text" name="owner_name" value="<?= htmlspecialchars($animal['owner_name']) ?>" required>
        <input type="text" name="owner_contact" value="<?= htmlspecialchars($animal['owner_contact']) ?>" required>
        <textarea name="medical_notes" rows="3"><?= htmlspecialchars($animal['medical_notes']) ?></textarea>
        <p>Current Image:</p>
        <img src="<?= !empty($animal['image_path'])? $animal['image_path']:'https://via.placeholder.com/200x150?text=No+Image' ?>" width="200" alt="Animal Image">
        <input type="file" name="animal_image" accept="image/*">
        <button type="submit">Update Animal</button>
    </form>
</div>
</body>
</html>

