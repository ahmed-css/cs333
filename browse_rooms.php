<?php
// Database connection
$host = "localhost";
$dbname = "room_booking";
$username = "root";
$password = "";
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Fetch all rooms
$query = "SELECT * FROM rooms";
$stmt = $conn->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Rooms</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picocss@1.5.0/dist/pico.min.css">
    <style>
        body {
            background-color: #f7f9fc;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5em;
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 15px 0;
            padding: 15px;
            background: #e0f7fa;
            border: 1px solid #b2ebf2;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        li a {
            text-decoration: none;
            color: #00796b;
            font-weight: bold;
            font-size: 1.2em;
        }
        li a:hover {
            color: #004d40;
        }
        .room-thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .room-info {
            flex: 1;
        }
        .details {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Rooms</h1>
        <ul>
            <?php foreach ($rooms as $room): ?>
                <li>
                    <img 
                        src="<?= htmlspecialchars($room['image_url']) ?>" 
                        alt="Room Thumbnail" 
                        class="room-thumbnail"
                    >
                    <div class="room-info">
                        <a href="room_details.php?id=<?= $room['id'] ?>">
                            <?= htmlspecialchars($room['name']) ?>
                        </a>
                        <div class="details">Capacity: <?= $room['capacity'] ?></div>
                    </div>
                    <a href="room_details.php?id=<?= $room['id'] ?>" class="button">View Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
