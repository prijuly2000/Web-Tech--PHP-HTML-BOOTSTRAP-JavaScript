<?php
require 'config.php';
if(!isset($_SESSION['user']))
{
    header("Location: index.php");
}

$row=$_SESSION['user'];

if(!empty($row['ProfilePic']) && file_exists($row['ProfilePic']))
    $profilePicPath=$row['ProfilePic'];
else
    $profilePicPath="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>

<div class="container">

    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <div class="navbar-brand navbar-brand-centered">
                    <img src="images/Icon.png" style="height: 220%; width: 75%;position:relative; top:-15px; left: 20px" >
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-brand-centered">
                <ul class="nav navbar-nav" >
                    <li><a href="http://www.rapidvoicesupport.com/">Home</a></li>
                    <li><a href="http://www.rapidvoicesupport.com/contact-us/">Contact Us</a></li>
                    <li><a href="http://www.rapidvoicesupport.com/about/">About Us</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right" style="float:right; position: relative;  right: 15px" >
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Welcome <?php echo $row['FirstName'].' ' .$row['LastName'] ?></div>
            <div style="float:right; position: relative;  top:-25px"><a href="editProfile.php" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning">Edit</a></div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-lg-3 thumbnail " align="center"> <img alt="User Pic" src="<?php echo $profilePicPath; ?>" height="200px" width="200px" class="img-circle"> </div>


                <div class="col-md-7 col-lg-8 ">
                    <table class="table table-user-information table-hover">
                        <tbody>

                        <?php

                            foreach($row as $column=>$value)
                            {
                                $parts = preg_split('/(?=[A-Z])/', $column, -1, PREG_SPLIT_NO_EMPTY);
                                $title='';
                                foreach($parts as $part)
                                    $title=$title." ".$part;

                                if($column=="Password" || $column=="ProfilePic" || $column=="Role")
                                    continue;
                                echo "<tr><td class='col-md-5'>$title</td><td class='col-md-10'><label  id='$column' data-placement='right' data-title='Enter username'>$value</label> ";
                                if($column=="Rating" ) {
                                    echo "%";
                                    echo "<div class='progress'>
                                            <div class='progress-bar progress-bar-success progress-bar-striped active' role='progressbar' style='width:" . $row['Rating'] . "%'></div>
                                            <div class='progress-bar progress-bar-danger progress-bar-striped ' style='width:" . (100 - $row['Rating']) . "%'></div>
                                          </div>";
                                }
                                echo"</td></tr>";

                            }
                        ?>
                        </tbody>
                    </table>

                    <!--<a href="#" class="btn btn-primary">My Sales Performance</a>
                    <a href="#" class="btn btn-primary">Team Sales Performance</a>-->
                </div>
            </div>
        </div>
        <div class="panel-footer">
                <span class="pull-right">


                </span>
        </div>
    </div>

    <nav class="navbar navbar-default"  role="navigation">
        <div class="container">
            <div class="navbar-header" style="float: left; padding: 15px; text-align: center;width: 100%;">
                <div class="navbar-brand navbar-brand-centered" style="float:none;">Copyright © 2015 | Rapid Voice Support</div>
            </div>

        </div>
    </nav>

</div>
<script>
    document.getElementById('department').value = "<?php if(isset($_POST['department'])) {echo $_POST['department'];}?>";
    document.getElementById('gender').value = "<?php if(isset($_POST['gender'])) {echo $_POST['gender'];}?>";

    function validatePassword()
    {
        var password = document.getElementById('password');
        var goodColor = "#66cc66";
        var badColor = "#ff6666";
        // at least one number, one lowercase and one uppercase letter
        // at least 8 characters that are letters, numbers or the underscore
        var re = /^(?=.*\d)(?=.*[a-z]).{8}$/;
        var message = document.getElementById('valPassMsg');
        if(password.value.length<8)
        {
            password.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Atleast 8 characters";
        }
        else if(re.test(password.value))
        {
            password.style.backgroundColor = goodColor;
            message.style.color = goodColor;
            message.innerHTML = "Strong Password!!";
        }
        else
        {
            password.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Must have 1 upper, 1 lower, 1 number " ;
        }
    }


    function checkExtension(filename)
    {
        var allowedExtensions =
        {
            '.JPG' : 1,
            '.JPEG': 1,
            '.PNG' : 1,
            '.png' : 1,
            '.jpg' : 1,
            '.jpeg': 1
        };

        var match = /\..+$/;
        var ext = filename.match(match);
        if (allowedExtensions[ext])
        {
            var goodcolor ="#66cc66";
            document.getElementById('fileToUpload').style.color=goodcolor;
            document.getElementById('fileMessage').style.color = goodcolor;
            document.getElementById('fileMessage').innerHTML='Good!!This file is allowed';
            return true;
        }
        else
        {
            var badcolor = "#ff6666";
            document.getElementById('fileToUpload').style.color=badcolor;
            document.getElementById('fileMessage').style.color = badcolor;
            document.getElementById('fileMessage').innerHTML='Only .JPG,.JPEG,.PNG files allowed';
            //location.reload();
            return false;
        }
    }

    function matchPassword()
    {
        var pass1 = document.getElementById('password');
        var pass2 = document.getElementById('rpassword');
        //Store the Confimation Message Object ...
        var message = document.getElementById('confirmMessage');
        //Set the colors we will be using ...
        var goodColor = "#66cc66";
        var badColor = "#ff6666";
        //Compare the values in the password field
        //and the confirmation field
        if(pass1.value == pass2.value){
            pass2.style.backgroundColor = goodColor;
            message.style.color = goodColor;
            message.innerHTML = "Passwords Match!"
        }else{
            pass2.style.backgroundColor = badColor;
            message.style.color = badColor;
            message.innerHTML = "Passwords Do Not Match!"
        }
    }
</script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

</body>
</html>