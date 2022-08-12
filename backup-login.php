<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "girish");

    $email = $password = $error = '';
    $erremail = $errpassword = $success = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['submit'])) {
            $data = $_POST;
            if (empty($_POST['email'])) {
                $error = "Email is empty";
            } else {
                $email = $_POST['email'];
            }
            if (empty($_POST['password'])) {
                $error = "Enter your password";
            } else {
                $password = md5($_POST['password']);
            }

            $sql = "SELECT * from `contact`";
            $data = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($data)) {
                if (!empty($row)) {
                    $db_pass = $row["password"];
                    $db_email = $row['email'];
                    if ($password === $db_pass && $email === $db_email) {
                        $_SESSION['user_id'] = $row['id'];
                        header("Location: ./profile.php");
                    } else {
                        $error = "Incorrect username and password!";
                    }
                }
            }
        }
    }

    mysqli_close($conn);
    ?>


    <div class="container style">
        <h3 class="center">Login</h3>
        <span class="error"><?php echo $error; ?></span>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
        </form>
        <div class="link">
            <a href="./reg.php">New user register here </a>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>