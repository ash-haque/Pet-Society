<?php
if (!isset($_GET['id'])) {
    header("Location: view_animals.php");
    exit;
}

$id = intval($_GET['id']);

$conn = new mysqli("localhost", "root", "", "animal_care");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Delete image file if exists
$sqlImg = "SELECT image_path FROM animals WHERE id = $id";
$res = $conn->query($sqlImg);
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if (!empty($row['image_path']) && file_exists($row['image_path'])) unlink($row['image_path']);
}

// Delete record
$sql = "DELETE FROM animals WHERE id = $id";
$conn->query($sql);

$conn->close();
header("Location: view_animals.php");
exit;
?>
