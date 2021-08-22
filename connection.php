<?php
    $conn = mysqli_connect('localhost', 'root', '', 'master_entertain');
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
?>