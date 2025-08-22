<?php echo "Current time: " . date("H:i:s"); ?>



<?php
$conn = new mysqli("localhost", "root", "", "animal_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT * FROM animals ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registered Animals</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        .animal { border-bottom: 1px solid #ccc; padding: 10px 0; display: flex; gap: 15px; }
        .animal img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
        .info { flex: 1; }
    </style>
</head>
<body>
<div class="container">
    <h2>Registered Animals</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='animal'>";
            $imagePath = $row['image_path'];
            if (!empty($imagePath) && file_exists($imagePath)) {
                echo "<img src='$imagePath' alt='Animal Image'>";
            } else {
                echo "<img src='https://via.placeholder.com/100' alt='No Image'>";
            }

            echo "<div class='info'>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($row['animal_name']) . "</p>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($row['animal_type']) . "</p>";
            echo "<p><strong>Breed:</strong> " . htmlspecialchars($row['breed']) . "</p>";
            echo "<p><strong>Age:</strong> " . htmlspecialchars($row['age']) . "</p>";
            echo "<p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>";
            echo "<p><strong>Owner:</strong> " . htmlspecialchars($row['owner_name']) . " (" . htmlspecialchars($row['owner_contact']) . ")</p>";
            echo "<p><strong>Medical Notes:</strong> " . nl2br(htmlspecialchars($row['medical_notes'])) . "</p>";
            echo "</div></div>";
        }
    } else {
        echo "<p>No animals found.</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>





