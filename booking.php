<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to make a booking.");
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "room_booking";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available rooms
$query = "SELECT * FROM rooms";
$result = $conn->query($query);

$rooms = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $booking_date = $_POST['booking_date'];
    $timeslot = $_POST['timeslot'];

    // Validate input
    if (!empty($room_id) && !empty($booking_date) && !empty($timeslot)) {
        $query = "INSERT INTO bookings (user_id, room_id, booking_date, timeslot) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("iiss", $user_id, $room_id, $booking_date, $timeslot);

            if ($stmt->execute()) {
                $success = "Booking successful!";
            } else {
                $error = "Error: " . $stmt->error;
            }
        } else {
            $error = "Error preparing query: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/picocss@1.5.0/dist/pico.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #1e90ff, #87cefa);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            background: white;
            padding: 20px;
            max-width: 400px;
            width: 90%;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            font-size: 1.8rem;
            color: #444;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            text-align: left;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        select, input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #0078d7;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #005bb5;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            color: white;
            background-color: #00796b;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: #00594c;
        }
        .message {
            font-size: 1rem;
            margin-top: 15px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Room Booking</h1>

        <form method="POST">
            <label for="room_id">Select Room:</label>
            <select name="room_id" id="room_id" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['id']; ?>">
                        <?php echo htmlspecialchars($room['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="booking_date">Booking Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>

            <label for="timeslot">Time Slot:</label>
            <input type="text" name="timeslot" id="timeslot" placeholder="e.g., 10:00 AM - 12:00 PM" required>

            <button type="submit">Book</button>
            <a href="cancel_booking.php">Cancel a Booking</a>
        </form>

        <?php if (isset($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
