<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('Location: ../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/navbarStyle.css">
        <link rel="stylesheet" href="css/live_streamStyle.css">
        <link rel="stylesheet" href="../css/footerStyle.css">
        <title>live stream</title>
    </head>
    <body>
        <?php include('navbar.php') ?>

        <div class="shCont">
            <div class="shTitle">
                <h3><?php echo $_SESSION['user_name'] ?></h3>
            </div>

            <div class="myshow">
                <video id="vid" autoplay>
                    Your browser does not support HTML5 video.
                </video>
            </div>
        </div>

        <?php include('../footer.php') ?>

        <script>
            navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                document.getElementById("vid").srcObject = stream;
            })
        </script>

    </body>
</html>