<?php
session_start();


?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Verification</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/style.css">
</head>

<body>
	<?php
	require_once('conn.config.php');
	$error = '';
	if (isset($_POST['submit'])) {

		$user_email = $_SESSION['user_email'];
		$sql = "SELECT `token` FROM `contact` where `email` = '$user_email'";
		$data = mysqli_query($conn, $sql);
		$row2 = mysqli_fetch_assoc($data);
		if ($_POST['vcode'] == $row2['token']) {
			$token = $row2['token'];
			$sql = "UPDATE `contact` SET `verified` = '1' where `token` = '$token'";
			if (mysqli_query($conn, $sql)) {
				header("Location: login.php");
				$_SESSION['verified'] = 1;
			}
		} else $error = "Incorrect key!";


		// while ($row2 = mysqli_fetch_assoc($data)) {
		// 	if ($_POST['vcode'] == $row2['token']) {
		// 		$token = $row2['token'];
		// 		$sql = "UPDATE `contact` SET `verified` = '1' where `token` = '$token'";
		// 		if (mysqli_query($conn, $sql)) {
		// 			header("Location: login.php");
		// 			$_SESSION['verified'] = 1;
		// 		}
		// 	} else $error = "Incorrect key!";
		// }
	}
	mysqli_close($conn);
	?>

	<div class="container style code">
		<h3 class="center">Email Verification</h3>
		<span class="error"><?php echo $error; ?></span>
		<form action="verification.php" method="post">
			<div class="mb-3">
				<label class="form-label">Enter your verification code here</label>
				<input type="text" class="form-control" name="vcode">
			</div><br>
			<button type="submit" class="btn btn-primary" name="submit">Submit</button>
		</form>
		<a href="login.php">Skip to login page.</a>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>