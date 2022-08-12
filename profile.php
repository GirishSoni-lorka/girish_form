<?php
session_start();
$id = '';
$_SESSION['img_path'] = '';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/profile.css">
</head>

<body>
    <?php
    $fname = $error = $id = $lname = $dob = $gender = $email = $password = "";
    require_once('conn.config.php');

    // Fetching user data from database with a particular ID...
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];

        $sql = "SELECT * from `contact` where id like $id";

        $data = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($data);
        $fname = $row['fname'];
        $lname = $row['lname'];
        $dob = $row['dob'];
        $gender = $row['gender'];
        $email = $row['email'];
        $password = $row['password'];
        $db_image = $row['image'];
    }

    // Image upload code here...
    if (isset($_POST['upload'])) {
        $target_path = "uploads/";
        $target_path = $target_path . basename($_FILES['file']['name']);
        $file_type = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
        $allowTypes = array('jpg', 'png', 'jpeg');
        if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg') {
            $error = "Sorry, only JPG, JPEG & PNG files are allowed.";
        } else if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            $error = "Sorry, file not uploaded, please try again!";
        }
        $sql = "UPDATE `contact` SET `image` = '$target_path' WHERE  `contact`.`id` = $id";
        if (!mysqli_query($conn, $sql)) {
            $error = "Upload error";
        } 
        else $error = "Picture updated";
    }

    // Profile update code here...
    if (isset($_POST['update'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $password = md5($_POST['password']);
        $update = "UPDATE `contact` SET `fname` = '$fname', `lname` = '$lname', `gender` = '$gender', `password` = '$password', `image` = '$image' WHERE `contact`.`id` = $id";
        if (!mysqli_query($conn, $update)) {
            $error = "Update error";
        } 
        else $error = "Update Success";
    }

    // LogOut button code here...
    if (isset($_POST['logout'])) {
        header("Location: ./login.php");
        session_unset();
        session_destroy();
    }

    mysqli_close($conn);
    ?>
    <!--  Html code here...  -->
    <div class="container style">
        <div class="d-flex justify-content-between">
            <h2>Profile</h2>
            <!-- All error show here...  -->
            <span class="error"><?php echo $error  ?></span>
            <!-- LogOut button form...  -->
            <form action="profile.php" method="post">

                
                <button class="btn btn-primary me-1" name="verify_check">Verify Email</button>
                <button class="btn btn-primary" name="logout">Logout</button>
            </form>
            <!-- form ends here...  -->
        </div>
        <hr>

        <!-- Update button Form start here...  -->
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <div class="d-flex row">
                <div class="img-box col  justify-content-center">
                    <div class="img-wrapper">
                        <div class="img">
                            <?php
                            if (isset($target_path) && !empty($target_path)) { ?>
                                <img name="image" src="./<?php echo $target_path; ?>" alt="">
                            <?php } else if (isset($db_image) && !empty($db_image)) { ?>
                                <img name="image" src="./<?php echo $db_image; ?>" alt="">
                            <?php } else { ?>
                                <img name="image" src="uploads/250gray.png" alt="">
                            <?php } ?>
                        </div>
                    </div>
                    <input class="file" type="file" name="file">
                    <button class="btn btn-primary btn-style" name="upload">Upload image</button>
                </div>
                <div class="form-data col">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="fname" value="<?php echo $fname ?>">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lname" value="<?php echo $lname ?>">
                        <br>
                        <label class="form-label">D.O.B</label>
                        <input type="text" class="form-control" name="dob" value="<?php echo $dob ?>" disabled>
                        <br>
                        <label class="form-label">Gender</label><br>
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">Male
                        <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">Female
                        <br><br>
                        <label class="form-label">Email address</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $email ?>" disabled>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" value="<?php echo $password ?>">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary d-inline" name="update">Update</button>
            </div>
        </form>
        <!-- form ends here...  -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>