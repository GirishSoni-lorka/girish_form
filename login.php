<?php
session_start();

if (isset($_SESSION['email'])) {
    header('Location: profile.php');
}

// if(isset($_SESSION('verified')) && $_SESSION['verified'] == 1){
//     header('Location: login.php');
// }

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
    require_once('conn.config.php');
    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['submit'])) {
            if (empty($_POST['email'])) {
                $error = "Email is empty";
                $formDone = 0;
            } else if (empty($_POST['password'])) {
                $error = "Enter your password";
                $formDone = 0;
            } else $formDone = 1;

            if ($formDone) {
                $sql = "SELECT * FROM `contact`";
                $result = mysqli_query($conn, $sql);
                while ($row1 = mysqli_fetch_assoc($result)) {
                    if (!empty($row1)) {
                        if (md5($_POST['password']) == $row1["password"] && $_POST['email'] == $row1['email']) {
                            $_SESSION['user_id'] = $row1['id'];
                            header("Location: ./profile.php");
                        } else $error = "Incorrect username and password!";
                    }
                }
            }
        }

        if(isset($_POST['sign_up'])){
            header('Location: reg.php');
            session_unset();
            session_destroy();
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
                <input type="email" class="form-control" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
        </form>
        <div class="link mt-3">
            <form action="login.php" method="post">
                <button class="btn btn-secondary" name="sign_up">Sign Up</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>