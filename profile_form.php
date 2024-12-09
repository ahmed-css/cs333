<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$dbname = "room_booking";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $_SESSION['user_id'] = $user['id'];

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picocss@1.5.0/dist/pico.min.css">
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <form method="POST" action="profile.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= isset($user['name']) ? htmlspecialchars($user['name']) : '' ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required><br>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>