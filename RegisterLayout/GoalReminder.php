<?php
include "conn.php";

if ($_conn->connect_error) {
    die("Connection failed: " . $_conn->connect_error);
}

// Function to check if notification already exists

