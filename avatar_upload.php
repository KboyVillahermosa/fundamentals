<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sessionId']) && $_SESSION['id'] == $_POST['sessionId']) {
        // Get the avatar data
        $avatarData = $_POST['avatar'];
        
        // Update avatar for the current user in the database
        $id = $_SESSION['id'];
        $query = "UPDATE login_data SET avatar = '$avatarData' WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            // Respond with success status
            http_response_code(200);
        } else {
            // Respond with error status
            http_response_code(500);
        }
    } else {
        // Respond with unauthorized status
        http_response_code(401);
    }
} else {
    // Respond with bad request status
    http_response_code(400);
}
?>
