<?php
require("config.php");
if(!isset($_SESSION['user']))
{
    header("Location: index.php");
}
$user=$_SESSION["user"];

if(!empty($user['ProfilePic']) && file_exists($user['ProfilePic']))
    $profilePicPath=$user['ProfilePic'];
else
    $profilePicPath="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=100";

?>

<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
</head>
<body>
<form name="form1" method="post" enctype="multipart/form-data">
    <div class="container" >
        <nav class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <div class="navbar-brand navbar-brand-centered">
                        <img src="images/Icon.png" style="height: 220%; width: 75%;position:relative; top:-15px; left: 20px" >
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-brand-centered">
                    <ul class="nav navbar-nav">
                        <li><a href="http://www.rapidvoicesupport.com/">Home</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/contact-us/">Contact Us</a></li>
                        <li><a href="http://www.rapidvoicesupport.com/about/">About Us</a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right" style="float:right; position: relative;  right: 15px" >
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="panel panel-info">
            <div class="container-page">
                <div class="" align="center">
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Dashboard</div>
                        </div>

                        <div style="padding-top:30px" class="panel-body" >
                            <div class="container" >
                                <div class="row" align="center">
                                    <a href="profile.php" class="col-lg-3"><img class="img-circle" src="<?php echo $profilePicPath ?>" height="100px" width="100px"/><br>Profile</a>
                                    <a href="paymentinfo.php" class="col-lg-3"><img src="images/money.jpg" height="100px" width="100px"/><br>Payment Information</a>
                                    <a href="checkin.php" class="col-lg-3"><img src="images/checkin.jpg" height="100px" width="100px"/><br>Check In</a>
                                    <?php
                                    if($user["Role"]=="admin")
                                    {
                                    ?>
                                        <a href="admin/employeeinfo.php" class="col-lg-3"><img src="images/allemployee.jpg" height="100px" width="100px"/><br>Employees' Info</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
</form>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>