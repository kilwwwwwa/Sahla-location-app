<?php
session_start();
session_unset();    // Remove all session variables
session_destroy();  // Destroy the session
header("Location: login_register_test_javascript.html");
exit();
?>