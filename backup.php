<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registration </title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>

<body>
	<?php
	$fname = $lname = $dob = $gender = $email = $password = $confirm_password = "";
	$errfname = $errlname = $errdob = $errgender = $erremail = $errpassword = $errconfirm_password = "";

	$conn = mysqli_connect("localhost", "root", "", "girish");

	if (!$conn) {
		die("Connect error" . mysqli_connect_error());
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST['submit'])) {

			if (empty($_POST['fname'])) {
				$errfname = "First name is empty";
			} else {
				$fname = $_POST['fname'];
			}

			if (empty($_POST['lname'])) {
				$errlname = "Last name is empty";
			} else {

				$lname = $_POST['lname'];
			}

			$dob = $_POST['day'] . "/" . $_POST['month'] . "/" . $_POST['year'];

			if (empty($_POST['gender'])) {
				$errgender = "Gender not selected";
			} else {
				$gender = $_POST['gender'];
			}

			if (empty($_POST['email'])) {
				$erremail = "Email is empty";
			} else {
				$email = $_POST['email'];
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$erremail = "Invalid email format";
				}
			}

			if (empty($_POST['password'])) {
				$errpassword = "Enter your password";
			} else {
				$password = md5($_POST['password']);
			}

			if (empty($_POST['confirm_password'])) {
				$errconfirm_password = "Confirm password empty";
			} else {
				if (md5($_POST["password"]) != md5($_POST["confirm_password"])) {
					$errconfirm_password = "Password does not match";
				} else {
					$confirm_password = md5($_POST['confirm_password']);
				}
			}


			$sql = "INSERT INTO `contact` (`fname`, `lname`, `dob`, `gender`, `email`, `password`) VALUES ('$fname', '$lname', '$dob', '$gender', '$email', '$password')";

			if (mysqli_query($conn, $sql)) {
				echo "<script> alert('data inserted') </script>";
			}
		}
		mysqli_close($conn);
	}
	?>

	<div class="container style">
		<h2>Registration from</h2>
		<form action="reg.php" method="post">
			<div class="mb-3">

				<label class="form-label">First Name</label>
				<span class="error">* <?php echo $errfname; ?></span>
				<input type="text" class="form-control" name="fname">

				<label class="form-label">Last Name</label>
				<span class="error">* <?php echo $errlname; ?></span>
				<input type="text" class="form-control" name="lname">

				<label class="form-label">D.O.B</label>
				<select name="day">
					<option default>Day</option>
					<?php
					for ($day = 1; $day <= 31; $day++)
						echo "<option value = '" . $day . "'>" . $day . "</option>";
					?>
				</select>
				<select name="month">
					<option default>Month</option>
					<?php
					for ($month = 1; $month <= 12; $month++)
						echo "<option value = '" . $month . "'>" . $month . "</option>";
					?>
				</select>

				<select name="year">
					<option default>Year</option>
					<?php
					for ($year = 1950; $year <= 2100; $year++)
						echo "<option value = '" . $year . "'>" . $year . "</option>";
					?>
				</select>

				<br>

				<label class="form-label">Gender</label>
				
				<span class="error">* <?php echo $errgender; ?></span>
				<br>
				Male <input type="radio" name="gender" value="male">
				Female <input type="radio" name="gender" value="female">
				<br><br>


				<label class="form-label">Email address</label>
				<span class="error">* <?php echo $erremail; ?></span>
				<input type="text" class="form-control" name="email" placeholder="name@example.com">

				<div class="mb-3">
					<label class="form-label">Password</label>
					<span class="error">* <?php echo $errpassword; ?></span>
					<input type="password" class="form-control" name="password">
				</div>

				<div class="mb-3">
					<label class="form-label">Confirm Password</label>
					<span class="error">* <?php echo $errconfirm_password; ?></span>
					<input type="password" class="form-control" name="confirm_password">
				</div>

				<button type="submit" class="btn btn-primary" name="submit">Submit</button>
			</div>
		</form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>