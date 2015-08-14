<?php
require("config.php");
if(!empty($_POST))
{
    // Check if the username is already taken
    $query = "
             SELECT
                 1
             FROM employees
             WHERE
                 Username = :username
         ";
     $query_params = array( ':username' => $_POST['username'] );
     try
     {
         $stmt = $db->prepare($query);
         $result = $stmt->execute($query_params);

     }
     catch(PDOException $ex)
     {
         //die("Failed to run query: " . $ex->getMessage());
         $errorMessage="Uncertain problem occured!! Please inform RVS";
     }
     $row = $stmt->fetch();
     if($row)
     {
         $errorMessage="Sorry!! The username is already taken.";
         //die("This username is already in use");
     }

     $target_dir='';
    //Upload the image
    if(empty($errorMessage) && !empty($_FILES["fileToUpload"]["name"]))
    {
    
        $target_dir = "profile_pics/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"]))
        {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $errorMessage = "Alert!! The file is not an image ";
                $uploadOk = 0;
            }
        }
    }

    // Check file size 5 MB Image only
    if(empty($errorMessage) && !empty($_FILES["fileToUpload"]["name"]))
    {

        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $errorMessage = "Sorry!! The file should be less than 5 MB in size";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            $errorMessage = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {
            $errorMessage = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } 
        else {
            if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
            {
                $errorMessage = "Sorry, there was an error uploading your file.";
                
            }
            else
            	$target_dir="profile_pics/" . $_FILES["fileToUpload"]["name"];
        }
    }
   /* print($_POST['firstname']);
    print($_POST['lastname']);
    print($_POST['username']);
    print($_POST['password']);
    print($_POST['address']);
    print($_POST['phone']);
    print($_POST['email']);
    print($_POST['gender']);
    print($_POST['department']);
    print("profile_pics/". $_FILES["fileToUpload"]["name"]);*/

    if(empty($errorMessage))
    {
        // Add row to database
        $query = "
            INSERT INTO employees (
                FirstName,
                LastName,
                Username,
                Password,
                Address,
                Phone,
                Email,
                Gender,
                Department,
                ProfilePic
            ) VALUES (
                :firstname,
                :lastname,
                :username,
                :password,
                :address,
                :phone,
                :email,
                :gender,
                :department,
                :profilepic
            )";
          
	echo $target_dir;
        $query_params = array(
            ':firstname' => $_POST['firstname'],
            ':lastname' => $_POST['lastname'],
            ':username' => $_POST['username'],
            ':password' => $_POST['password'],
            ':address' => $_POST['address'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':gender' => $_POST['gender'],
            ':department' => $_POST['department'],
            ':profilepic' => $target_dir
        );

        try
        {
            $stmt = $db->prepare($query);
            $result = $stmt->execute($query_params);
            $check_registered = true;
            unset($_POST);
            //header("Location: index.php");

        }
        catch (PDOException $ex)
        {
            $errorMessage="Please report this problem to RVS";
            //die("Failed to run query: " . $ex->getMessage());
        }

        //die("Redirecting to index.php");
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

</head>
<body>
<form name="form1" action="register.php" method="post" enctype="multipart/form-data">
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
            </div>
        </div>
    </nav>
    <section class="container">
        <div class="container-page">
            <div class="mainbox col-md-9 col-md-offset-1 col-sm-8 col-sm-offset-2e">
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Registration</div>
                        <div style="float:right; position: relative;  top:-20px"><a href="index.php">Already member? </a></div>
                    </div>
                    <div style="padding-top:30px" class="panel-body" >

                        <?php
                        if(!empty($errorMessage))
                        {
                        ?>
                        <div class="form-group col-lg-12">
                            <div class="alert alert-danger" id="error"  role="alert"><strong><?php echo $errorMessage ?> </strong></div>
                        </div>
                        <?php
                        }
                        ?>
                        <?php
                        if(!empty($check_registered))
                        {
                        ?>
                            <div class="form-group col-lg-12">
                                <div class="alert alert-success" id="success"  role="alert"><strong>Success!!You are successfully registered.</strong></div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="form-group col-lg-6">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control" id="firstname" value="<?php if(isset($_POST['firstname'])) { echo htmlentities ($_POST['firstname']); }?>" required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="lastname" value="<?php if(isset($_POST['lastname'])) { echo htmlentities ($_POST['lastname']); }?>"  required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" id="username" value="<?php if(isset($_POST['username'])) { echo htmlentities ($_POST['username']); }?>"  required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="email">Email Address</label>
                            <input type="email" placeholder="example@example.com" name="email" class="form-control" value="<?php if(isset($_POST['email'])) { echo htmlentities ($_POST['email']); }?>"  id="email" required>
                        </div>



                        <div class="form-group col-lg-6">
                            <label>Password</label>
                            <input type="password" onblur="validatePassword();" name="password" class="form-control" pattern="^(?=.*\d)(?=.*[a-z]).{8,30}$" placeholder="" id="password" required>
                            <span id="valPassMsg" class="confirmMessage"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Repeat Password</label>
                            <input type="password" name="rpassword" class="form-control" onkeyup="matchPassword();" pattern="^(?=.*\d)(?=.*[a-z]).{8,30}$"  id="rpassword" required>
                            <span id="confirmMessage" class="confirmMessage"></span>
                            <br>
                        </div>

                        <div class="form-group col-lg-12">
                            <label>Home Address</label>
                            <input type="text" name="address" class="form-control" id="address" value="<?php if(isset($_POST['address'])) { echo htmlentities ($_POST['address']); }?>"  required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Phone Number</label>
                            <input type="text" maxlength="10" min placeholder="Must be 10 digits" pattern="\d{10}$" class="form-control bfh-phone" name="phone"  id="phone" value="<?php if(isset($_POST['phone'])) { echo htmlentities ($_POST['phone']); }?>"  required>
                        </div>


                        <div class="form-group col-lg-6">
                            <label>Department</label>
                            <select class="form-control" name="department" id="department" required>
                                <option selected value=""> Select One</option>
                                <option value="Customer Service">Customer Service</option>
                                <option value="Sales">Sales</option>
                                <option value="Technical Support">Technical Support</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Gender : </label>
                            <select class="form-control" name="gender" id="gender" >
                                <option value="" selected> Select One</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>Profile photo</label>
                            <input id="fileToUpload" name="fileToUpload" type="file" class="file" onchange="checkExtension(this.value);" >
                            <span id="fileMessage" class="confirmMessage"></span>
                            <br>
                        </div>

                        <div class="col-lg-12">
                            <h3 class="dark-grey">Terms and Conditions</h3>
                            <p>
                                By clicking on "Register" you agree to The Company's
                                <a href = "TermsAndConditions.php" target="_blank"> Terms and Conditions</a>
                            </p>
                            <p align="center"> <button  type="submit" class="btn btn-primary">Register</button></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <nav class="navbar navbar-default"  role="navigation">
        <div class="container">
            <div class="navbar-header" style="float: left; padding: 15px; text-align: center;width: 100%;">
                <div class="navbar-brand navbar-brand-centered" style="float:none;">Copyright © 2015 | Rapid Voice Support</div>
            </div>

        </div>
    </nav>
</div>
</form>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
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
        var re = /^(?=.*\d)(?=.*[a-z]).{8,30}$/;
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

</body>
</html>