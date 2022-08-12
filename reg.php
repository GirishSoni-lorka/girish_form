<?php
session_start();


if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
}

// if(isset($_SESSION['registered']) && $_SESSION['registered']== true){
// 	header('Location: login.php');
// }


?>

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
	require_once('conn.config.php');
	$error = $fname = $lname = $dob = $gender = $email = $formDone = $password = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// On form submission...
		if (isset($_POST['submit'])) {
			$data = $_POST;
			$data['dob'] = $data['year'] . "-" . $data['month'] . "-" . $data['day'];
			unset($_POST['submit']);

			// Registration-form Velidations...
			if (empty($_POST['fname'])) {
				$error = "First name is empty";
				$formDone = 0;
			} else if (empty($_POST['lname'])) {
				$error = "Last name is empty";
				$formDone = 0;
			} else if (empty($_POST['day']) && $_POST['day'] == 0) {
				$error = "Day not set";
				$formDone = 0;
			} else if (empty($_POST['month']) && $_POST['month'] == 0) {
				$error = "Month not set";
				$formDone = 0;
			} else if (empty($_POST['year']) && $_POST['year'] == 0) {
				$error = "Year not set";
				$formDone = 0;
			} else if (empty($_POST['gender'])) {
				$error = "Gender not selected";
				$formDone = 0;
			} else if (empty($_POST['email'])) {
				$error = "Email is empty";
				$formDone = 0;
			} else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				$error = "Invalid email format";
				$formDone = 0;
			} else if (empty($_POST['password'])) {
				$error = "Enter your password";
				$formDone = 0;
			} else if (empty($_POST['confirm_password'])) {
				$error = "Confirm password empty";
				$formDone = 0;
			} else if ($_POST["password"] != $_POST["confirm_password"]) {
				$error = "Password does not match";
				$formDone = 0;
			} else {
				$formDone = 1;
			}

			// Check email exist in database or not...
			if (isset($_POST['email'])) {
				$sql1 = "SELECT `email` from `contact`";
				$data1 = mysqli_query($conn, $sql1);
				while($row1 = mysqli_fetch_assoc($data1)){
					if ($_POST['email'] == $row1['email']) {
						$error = "Email already exist! Please ender new email id.";
						$formDone = 0;
					} else $formDone = 1;
				}					
			}

			// if all the velidation pass it will execute & store data into database...
			if ($formDone) {
				$token = rand(1000, 9999);
				require_once('mail.php'); // mail.php file code...
				$_SESSION['user_email'] = $data['email']; 
				$fname = $data['fname'];
				$lname = $data['lname'];
				$dob = $data['dob'];
				$gender = $data['gender'];
				$email = $data['email'];
				$password = md5($data['password']);

				// Inserting registration-form data.
				$sql = "INSERT INTO `contact` (`fname`, `lname`, `dob`, `gender`, `email`, `password`, `verified`, `token`) VALUES ('$fname', '$lname', '$dob', '$gender', '$email', '$password', '0', '$token')";

				if (mysqli_query($conn, $sql)) {
					header("Location: verification.php");
					// $_SESSION['reg_id'] = 1; //optional
				}
			}
		}
		mysqli_close($conn);
	}
	?>

	<!-- html code here....  -->
	<div class="container style">
		<h2>Registration from</h2>
		<span class="error"><?php echo $error  ?></span>
		<form action="reg.php" method="post">
			<div class="mb-3">

				<label class="form-label">First Name</label>
				<input type="text" class="form-control" name="fname" value="<?php if (isset($_POST['fname'])) echo $_POST['fname']; ?>">

				<label class="form-label">Last Name</label>
				<input type="text" class="form-control" name="lname" value="<?php if (isset($_POST['lname'])) echo $_POST['lname']; ?>">

				<label class="form-label">D.O.B</label>
				<select name="day">
					<option value="0">Day</option>
					<?php
					for ($day = 1; $day <= 31; $day++) { ?>
						<option <?php if (isset($data['day']) && $data['day'] == $day) { ?> selected='selected' <?php } ?> value='<?= $day ?>'> <?= $day ?></option>
					<?php } ?>
				</select>

				<select name="month">
					<option value="0">Month</option>
					<?php
					for ($month = 1; $month <= 12; $month++) { ?>
						<option <?php if (isset($data['month']) && $data['month'] == $month) { ?> selected='selected' <?php } ?> value='<?= $month ?>'> <?= $month ?></option>
					<?php } ?>
				</select>

				<select name="year">
					<option value="0">Year</option>
					<?php for ($year = 1980; $year <= 2022; $year++) { ?>

						<option <?php if (isset($data['year']) && $data['year'] == $year) { ?> selected <?php } ?> value='<?= $year ?>'> <?= $year ?>
						</option>
					<?php } ?>
				</select>

				<br>

				<label class="form-label">Gender</label>

				<br>
				<input type="radio" name="gender" <?php if (isset($data['gender']) && $data['gender'] == "male") { ?> <?= "checked";
																													} ?> value="male">Male

				<input type="radio" name="gender" <?php if (isset($data['gender']) && $data['gender'] == "female") { ?> <?= "checked";
																													} ?> value="female">Female
				<br><br>

				<label class="form-label">Email address</label>
				<input type="text" class="form-control" name="email" placeholder="name@example.com" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">

				<div class="mb-3">
					<label class="form-label">Password</label>
					<input type="password" class="form-control" name="password">
				</div>

				<div class="mb-3">
					<label class="form-label">Confirm Password</label>
					<input type="password" class="form-control" name="confirm_password">
				</div>

				<button type="submit" class="btn btn-primary" name="submit">Submit</button>
			</div>
		</form>

		<a href="login.php">Already user login here</a>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>