<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        if(!$_SESSION['is_super_user']){
            header('Location: ../index.php');
        }
        $user_id = $_SESSION['user_id'];
        include('../connection.php');
        if($conn){
            if(isset($_POST['submit'])){
                $title = htmlspecialchars($_POST['title']);
                $bg_img = $_FILES['bg_image'];
                $genre = htmlspecialchars($_POST['genre']);
                $cast = htmlspecialchars($_POST['cast']);
                $file = $_FILES['file'];
                print_r($file);
                $duration = htmlspecialchars($_POST['duration']);
                $price = htmlspecialchars($_POST['price']);
                $about = htmlspecialchars($_POST['about']);

                $img_name = $bg_img['name'];
                $img_tpName = $bg_img['tmp_name'];
                $img_error = $bg_img['error'];
                $img_size = $bg_img['size'];
                $img_type = $bg_img['type'];

                $imgExt = explode('.', $img_name);
                $imgActualExt = strtolower(end($imgExt));

                $allowed = array('jpg', 'jpeg', 'png');
                if(in_array($imgActualExt, $allowed)){
                    if($img_error === 0){
                        $imgNewName = "img" . date("dym") . time() . "." . $imgActualExt;
                        $imgDestinationPath = '../uploads/img/'.$imgNewName;
                    }
                }

                $file_name = $file['name'];
                $file_tpName = $file['tmp_name'];
                $file_error = $file['error'];
                $file_size = $file['size'];
                $file_type = $file['type'];

                $fileExt = explode('.', $file_name);
                $fileActualExt = strtolower(end($fileExt));

                $allowed = array('mp4', 'webm', 'wmv');
                if(in_array($fileActualExt, $allowed)){
                    if($file_error === 0){
                        $fileNewName = "stream" . date("dym") . time() . "." . $fileActualExt;
                        $fileDestinationPath = '../uploads/streams/'.$fileNewName;
                    }
                }

                $sql = "INSERT INTO streams (title, bg_image_url, genre, s_cast, file_url, duration, price, about, user_id) 
                    VALUES ('$title', '$imgDestinationPath', '$genre', '$cast', '$fileDestinationPath', '$duration', '$price', '$about', $user_id)";

                if(mysqli_query($conn, $sql)){
                    move_uploaded_file($img_tpName, $imgDestinationPath);
                    move_uploaded_file($file_tpName, $fileDestinationPath);
                    //header('Location: index.php');
                }
            }
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
        <link rel="stylesheet" href="css/master_addClipStyle.css">
        <link rel="stylesheet" href="../css/footerStyle.css">
        <title>add clip</title>
    </head>
    <body>
        <?php include('navbar.php') ?>

        <div class="myClip">
            <div class="clipCOnt">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                    <label for="title">Title</label><br>
                    <input type="text" name="title" required><br>

                    <div class="fileUpload">
                        <input type="file" class="upload" name="bg_image" required>
                        <span>Upload stream cover</span>
                    </div>

                    <label for="genre">Genre</label><br>
                    <input type="text" name="genre" required><br>

                    <label for="cast">cast</label><br>
                    <input type="text" name="cast" required><br>

                    <div class="fileUpload">
                        <input type="file" class="upload" id="stream_file" name="file">
                        <span>Upload stream file</span>
                    </div>

                    <div class="progress" id="progress_bar" style="display:none; ">
                        <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%"></div>
                    </div>

                    <label for="duration">Duration</label><br>
                    <input type="datetime" name="duration" required><br>

                    <label for="price">price</label><br>
                    <input type="text" name="price" required><br>

                    <label for="about">about</label><br>
                    <textarea name="about" cols="30" rows="10" required></textarea><br>

                    <input type="hidden" id="submit" name="submit" value="post stream">
                </form>
            </div>
        </div>

        <?php include('../footer.php') ?>

        <script>
            function _(element){
                return document.getElementById(element);
            }

        _('stream_file').onchange = function(event){        
            var form_data = new FormData();        
            var image_number = 1;        
            var error = '';
        
            for(var count = 0; count < _('stream_file').files.length; count++){
                error += '<div class="alert alert-danger"><b>'+image_number+'</b> Selected File must be .jpg or .png Only.</div>';
                form_data.append("images[]", _('stream_file').files[count]);            
                image_number++;
            }
        

            _('progress_bar').style.display = 'block';
            var ajax_request = new XMLHttpRequest();        
            ajax_request.open("POST", "master_addClip.php");

            ajax_request.upload.addEventListener('progress', function(event){                
                var percent_completed = Math.round((event.loaded / event.total) * 100);                
                _('progress_bar_process').style.width = percent_completed + '%';                
                _('progress_bar_process').innerText = percent_completed + '% completed';                
            });
            
            ajax_request.addEventListener('load', function(event){                
                //_('stream_file').value = '';
                _('submit').getAttributeNode('type').value = "submit";
            });
            
            ajax_request.send(form_data); 
        };
        </script>
        
    </body>
</html>