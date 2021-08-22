<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        if($_SESSION['is_super_user']){
            include('../connection.php');
            if($conn){
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM streams WHERE user_id = $user_id ORDER BY stream_id DESC";
                $result = mysqli_query($conn, $sql);
                $streams = mYsqli_fetch_all($result, MYSQLI_ASSOC);

                mysqli_free_result($result);
                mysqli_close($conn);
            }
        }
        else{
            header('Location: ../index.php');
        }
        
    }
    else{
        header('Location: ../index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/navbarStyle.css">
        <link rel="stylesheet" href="css/master_homeStyle.css">
        <link rel="stylesheet" href="../css/footerStyle.css">
        <title>master home</title>
    </head>
    <body>
        <?php include('navbar.php') ?>

        <div class="shCont">
            <div class="add">
                <a href="master_addClip.php"><span>&plus;</span> Add stream</a>
            </div>

            <?php if($streams):?>
                <?php foreach($streams as $stream): ?>
                    <div class="clipCont">

                        <div class="post">
                            <div class="sCover">
                                <a href="master_clip.php?stream_id=<?php echo $stream['stream_id'] ?>">
                                    <img src="<?php echo $stream['bg_image_url'] ?>" alt="cover">
                                </a>
                            </div>
                            <div class="sDet">
                                <div class="detCont">
                                    <h3 class="title"><?php echo $stream['title'] ?></h3>
                                    <p><strong>Genre: </strong><?php echo $stream['genre'] ?></p>
                                    <p><strong>duration: </strong> <?php echo $stream['duration'] ?>min</p>
                                    <p><strong>Cast: </strong> <?php echo $stream['s_cast'] ?></p>
                                    <p><strong>Price: </strong>$<?php echo $stream['price'] ?></p>                      
                                </div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>you have not posted any stream yet.<br>
            <?php endif; ?>

        </div>
        <?php include('../footer.php') ?>
        
    </body>
</html>