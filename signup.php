<?php

    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla",
    "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan",
    "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan",
    "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory",
    "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde",
    "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands",
    "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire",
    "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
    "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia",
    "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana",
    "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar",
    "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti",
    "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland",
    "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan",
    "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of",
    "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia",
    "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of",
    "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania",
    "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia",
    "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles",
    "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands",
    "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland",
    "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
    "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles",
    "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa",
    "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan",
    "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China",
    "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
    "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States",
    "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)",
    "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

    session_start();
    if(isset($_SESSION['user_id'])){
        if($_SESSION['is_user']){
            header('Location: user/');
        }
        else if($_SESSION['is_super_user']){
            header('Location: super_user/');
        }
    }
    include('connection.php');
    $username = $email = $tel = '';
    $error = array('username'=>'', 'email'=>'', 'tel'=>'', 'password'=>'');

    if ($conn){
        if(isset($_POST['submit'])){
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $tel = htmlspecialchars($_POST['tel']);
            $country = htmlspecialchars($_POST['country']);
            $province = htmlspecialchars($_POST['province']);
            $district = htmlspecialchars($_POST['district']);
            $myrole = $_POST['role'];
            $password1 = htmlspecialchars($_POST['password1']);
            $password2 = htmlspecialchars($_POST['password2']);


            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)){
                $error['username'] = "only letter, underscore and number is allow in username";
            }
            else{
                $n_sql = "SELECT * FROM users WHERE user_name = '$username'";
                $n_user = mysqli_query($conn, $n_sql);
                $ex_name = mysqli_fetch_all($n_user, MYSQLI_ASSOC);
                if(array_filter($ex_name)){
                    $error['username'] = "username is taken";
                }
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error['email'] = "email must be valid";
            }
            else{
                $e_sql = "SELECT * FROM users WHERE email = '$email'";
                $e_user = mysqli_query($conn, $e_sql);
                $ex_user = mysqli_fetch_all($e_user, MYSQLI_ASSOC);
                if(array_filter($ex_user)){
                    $error['email'] = "email is in use";
                }
            }

            if(!preg_match('/^[0-9]+$/', $tel)){
                $error['tel'] = "invalid telephone number";
            }

            if($password1 != $password2){
                $error['password'] = "password dismatch";
            }
            else if (strlen($password1) < 8){
                $error['password'] = "password must at least be of 8 character";
            }

            if(!array_filter($error)){
                $passcode = password_hash($password1, PASSWORD_DEFAULT);              
                $user_sql = "INSERT INTO users(user_name, email, telephone, country, province, district, passcode)
                 VALUES('$username', '$email', '$tel', '$country', '$province', '$district', '$passcode')";
 
                 if(mysqli_query($conn, $user_sql)){
                     $sql = "SELECT * FROM users WHERE email = '$email'";
                     $result = mysqli_query($conn, $sql);
                     $user = mysqli_fetch_all($result, MYSQLI_ASSOC);
                     $user_id = (int)$user[0]['user_id'];
                     
                    if($myrole == 'user'){
                        $newsql = "INSERT INTO user_role(is_user, user_id) VALUES(1, $user_id)";
                        $insert_role = mysqli_query($conn, $newsql);
                        if($insert_role){
                            header('location: index.php?mssg=acount created successful');
                        }
                    }
                    else if($myrole == 'super_user'){
                        $newsql = "INSERT INTO user_role(is_super_user, user_id) VALUES(1, $user_id)";
                        $insert_role = mysqli_query($conn, $newsql);
                        if($insert_role){
                             header('Location: index.php?mssg=account created successful');
                         }
                    }
                }
             
             }
         }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User_signup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/signupStyle.css">
        <link rel="stylesheet" href="css/footerStyle.css">
    </head>
    <body>
        <div class="nav">
            <h3>signup</h3>
        </div>
        <div class="loginForm">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset>
                    <div class="eCont">
                        <label for="username">username: </label><br>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($username) ?>" autofocus required><br>
                        <div class="error"><?php echo $error['username'] ?></div>

                        <label for="email">email: </label><br>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($email) ?>" required><br>
                        <div class="error"><?php echo $error['email'] ?></div>

                        <label for="tel">telephone: </label><br>
                        <input type="tel" name="tel" value="<?php echo htmlspecialchars($tel) ?>" required><br>
                        <div class="error"><?php echo $error['tel'] ?></div>

                        <label for="country">Country:</label><br>
                        <select name="country">
                            <?php foreach ($countries as $country):?>
                                <option ><?php echo $country ?></option>
                            <?php endforeach ?>
                        </select><br>

                        <label for="province">Province: </label><br>
                        <select name="province">
                            <option value="Eastern">Eastern</option>
                            <option value="Western">Western</option>
                            <option value="South">South</option>
                            <option value="North">North</option>
                            <option value="Kigali">Kigali</option>
                        </select><br>

                        <label for="district">District: </label><br>
                        <select name="district">
                            <option value="nyanza">nyanza</option>
                            <option value="ruyenzi">ruyenzi</option>
                            <option value="huye">huye</option>
                            <option value="gisenyi">gisenyi</option>
                            <option value="musanze">musanze</option>
                        </select><br>

                        <input type="radio" name="role" value="user" checked>
                        <label for="role">user</label><br>
                        <input type="radio" name="role" value="super_user">
                        <label for="role">super-user</label><br>
                        
                        <label for="password">create password: </label><br>
                        <input type="password" name="password1" required autocomplete="off"><br>

                        <label for="password">confirm password: </label><br>
                        <input type="password" name="password2" required autocomplete="off"><br>
                        <div class="error"><?php echo $error['password'] ?></div>

                        <input type="submit" name="submit" value="signup">
                        <div class="reg">
                            <p>already have account? <a href="index.php">login</a></p>
                        
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <?php include('footer.php')?>
    </body>
</html>