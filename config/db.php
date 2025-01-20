<?php
    $db = new mysqli("p:localhost", "root", "", "management_system");

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>