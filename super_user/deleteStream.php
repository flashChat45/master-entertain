<?php
    session_start();
    if(isset($_SESSION['user_id']) and isset($_SESSION['is_super_user'])){
        if(isset($_GET['stream_id'])){
            $stream_id = (int)$_GET['stream_id'];
            include('../connection.php');
            
            $stream_sql = "SELECT * FROM streams WHERE stream_id = $stream_id";
            $result = mysqli_query($conn, $stream_sql);
            $stream = mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            $bg_img = $stream['bg_image_url'];
            $file_url = $stream['file_url'];

            $del_downloads = "DELETE FROM stream_downloads WHERE stream_id = $stream_id";
            $del_watches = "DELETE FROM stream_watches WHERE stream_id = $stream_id";
            $del_stream = "DELETE FROM streams WHERE stream_id = $stream_id";
            mysqli_query($conn, $del_downloads);
            mysqli_query($conn, $del_watches);
            mysqli_query($conn, $del_stream);

            unlink($bg_img);
            unlink($file_url);

            header('Location: index.php');
        }
    }
    else{
        header('Location: ../index.php');
    }
?>