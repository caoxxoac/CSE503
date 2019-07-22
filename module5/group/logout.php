<?php
session_start();

// destroy the session, so that the user truly logs out and go back to the log in page
session_destroy();
echo json_encode(array(
  "message" => "You have logged out successfully!"
));
?>
