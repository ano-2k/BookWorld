<?php
session_start();
 
// Check if user is logged in
$response = array();
if (isset($_SESSION['user_id'])) {
    $response['logged_in'] = true;
} else {
    $response['logged_in'] = false;
}
 
header('Content-Type: application/json');
echo json_encode($response);
?>