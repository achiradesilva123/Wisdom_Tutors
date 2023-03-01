<?php
$corepage = explode('/', $_SERVER['PHP_SELF']);
$corepage = end($corepage);
if ($corepage !== 'admin.php') {
	if ($corepage == $corepage) {
		$corepage = explode('.', $corepage);
		header('Location: admin.php?page=' . $corepage[0]);
	}
}

if (isset($_POST['addstudent'])) {
	$sId = $_POST['sId'];
	$titleName = $_POST['titleName'];
	$name = $_POST['name'];
	$address = $_POST['address'];
	$dob = $_POST['dob'];
	$gender = $_POST['gender'];
	$nic = $_POST['nic'];
	$phone = $_POST['phone'];

	$photo = explode('.', $_FILES['photo']['name']);
	$photo = end($photo);
	$photo_name = $sId . '.' . $photo;

	$email = $_POST['email'];

	$key = rand(100, 100000);
	$password = $key . '@' . $key;

	$input_error = array();
	$perror = array();
	if (empty($sId)) {
		$input_error['sId'] = "Student ID is Required";
	}
	if (empty($titleName)) {
		$input_error['titleName'] = "Title is Required";
	}
	if (empty($name)) {
		$input_error['name'] = "Name is Required";
	}
	if (!preg_match("/^[a-zA-z]*$/", $name)) {
		$perror['name'] = "Only Alphabets and whitespace are Allowed";
	}
	if (empty($address)) {
		$input_error['address'] = "Address is Required";
	}
	if (empty($dob)) {
		$input_error['dob'] = "Date Of Birth is Required";
	}
	if (empty($gender)) {
		$input_error['gender'] = "Gender is Required";
	}
	if (empty($nic)) {
	} else {
		if (!preg_match("/^(?:19|20)?\d{2}[0-9]{10}|[0-9]{9}[x|X|v|V]$/", $nic)) {
			$perror['nic'] = "Invalid NIC Number";
		}
	}
	if (empty($phone)) {
		$input_error['phone'] = "Telephone is Required";
	}
	if (empty($email)) {
		$input_error['photo'] = "Photo is Required";
	}
	if (empty($email)) {
		$input_error['email'] = "Email is Required";
	} else {
		if (!preg_match("/[a-z0-9]+@[a-z]+\.[a-z]{2,3}/", $email)) {
			$perror['email'] = "Invalid Email";
		}
	}


	if (count($input_error) == 0 && count($perror) == 0) {

		$check_email = mysqli_query($db_con, "SELECT * FROM `student` WHERE `email`='$email';");

		if (mysqli_num_rows($check_email) == 0) {
			$check_sId = mysqli_query($db_con, "SELECT * FROM `student` WHERE `sId`='$sId';");
			if (mysqli_num_rows($check_sId) == 0) {
				// $password = sha1(md5($password));
				$query = "INSERT INTO `student`( `sId`, `titleName`, `name`, `address`, `dob`, `gender`, `nic`, `phone`, `password`, `photo`, `email`, `status`)  VALUES ('$sId','$titleName','$name', '$address', '$dob', '$gender','$nic','$phone','$password','$photo_name','$email','-1');";
				$result = mysqli_query($db_con, $query);
				if ($result) {
					$datainsert['insertsucess'] = '<p style="color: green;">Student Inserted!</p>';
					move_uploaded_file($_FILES['photo']['tmp_name'], '../userimages/' . $photo_name);
				} else {
					$datainsert['inserterror'] = '<p style="color: red;">Student Not Inserted, please input right informations!</p>';
				}
			} else {
				$sId_error = "This Student ID is already exists!";
			}
		} else {
			$email_error = "This Email is already exists!";
		}
	}
}
?>
<h1 class="text-primary"><i class="fas fa-user-plus"></i> Add Student<small class="text-warning"> Add New Student!</small></h1>
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item" aria-current="page"><a href="admin.php">Dashboard </a></li>
		<li class="breadcrumb-item active" aria-current="page">Add Student</li>
	</ol>
</nav>

<div class="row">

	<div class="col-sm-6">
		<?php if (isset($datainsert)) { ?>
			<div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade" data-autohide="true" data-animation="true" data-delay="2000">
				<div class="toast-header">
					<strong class="mr-auto">Student Insert Alert</strong>
					<small><?php echo date('d-M-Y'); ?></small>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="toast-body">
					<?php
					if (isset($datainsert['insertsucess'])) {
						echo $datainsert['insertsucess'];
					}
					if (isset($datainsert['inserterror'])) {
						echo $datainsert['inserterror'];
					}
					?>
				</div>
			</div>
		<?php } ?>

		<?php
		$qu = "SELECT `sId` FROM `student` LIMIT 0, 1";
		$re = mysqli_query($db_con, $qu);
		$re1 = mysqli_fetch_array($re);
		?>

		<form enctype="multipart/form-data" method="POST" action="">


			<div class="form-group">
				<label for="sId">Student ID</label>
				<input name="sId" type="text" class="form-control" id="sId" value="<?= isset($sId) ? $sId : ''; ?>">


				<?= isset($input_error['sId']) ? '<label  class="e">' . $input_error['sId'] . '</label>' : '' ?>
				<?= isset($perror['sId']) ? '<label  class="e">' . $perror['sId'] . '</label>' : '' ?>
				<?= isset($sId_error) ? '<label class="e">' . $sId_error . '</label>' : '';  ?>

			</div>

			<div class="example"><b>
					<?php echo "Example of Student ID :-  " . $re1[0]; ?>
				</b>
			</div>


			<br>

			<div class="form-group">
				<label for="titleName">Title</label>
				<select name="titleName" class="form-control" id="titleName">
					<option value=""><?= isset($titleName) ? $titleName : '' ?></option>

					<option value="Mr.">Mr.</option>
					<option value="Ms.">Ms.</option>
					<option value="Mrs.">Mrs.</option>
					<option value="Ven.">Ven.</option>
				</select>

				<div class="e">
					<?= isset($input_error['titleName']) ? '<label  class="error">' . $input_error['titleName'] . '</label>' : '' ?>
				</div>
			</div>

			<div class="form-group">
				<label for="name">Name</label>
				<input name="name" type="text" class="form-control" id="name" value="<?= isset($name) ? $name : ''; ?>">


				<?= isset($input_error['name']) ? '<label  class="e">' . $input_error['name'] . '</label>' : '' ?>
				<?= isset($perror['name']) ? '<label  class="error">' . $perror['name'] . '</label>' : '' ?>

			</div>

			<div class="form-group">
				<label for="address">Address</label>
				<input name="address" type="text" value="<?= isset($address) ? $address : ''; ?>" class="form-control" id="address">



				<?= isset($input_error['address']) ? '<label  class="e">' . $input_error['address'] . '</label>' : '' ?>

			</div>

			<div class="form-group">
				<label for="dob">Date Of Birth</label>
				<input name="dob" type="date" class="form-control" id="dob" value="<?= isset($dob) ? $dob : ''; ?>">


				<?= isset($input_error['dob']) ? '<label  class="e">' . $input_error['dob'] . '</label>' : '' ?>

			</div>

			<div class="form-group">
				<label for="gender">Gender</label>
				<select name="gender" class="form-control" id="gender">
					<option value=""><?= isset($gender) ? $gender : '' ?></option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Other">Other</option>
				</select>


				<?= isset($input_error['gender']) ? '<label  class="e">' . $input_error['gender'] . '</label>' : '' ?>

			</div>

			<div class="form-group">
				<label for="nic">NIC</label>
				<input name="nic" type="text" class="form-control" id="nic" value="<?= isset($nic) ? $nic : ''; ?>">

				<?= isset($perror['nic']) ? '<label  class="error">' . $perror['nic'] . '</label>' : '' ?>

			</div>

			<div class="form-group">
				<label for="phone">Contact NO</label>
				<input name="phone" type="text" class="form-control" id="phone" pattern="[0-9]{10}" value="<?= isset($phone) ? $phone : ''; ?>" placeholder="011........">
				
				<?= isset($input_error['phone']) ? '<label  class="e">' . $input_error['phone'] . '</label>' : '' ?>
			</div>


			<div class="form-group">
				<label for="photo">Photo</label>
				<input name="photo" type="file" class="form-control" id="photo">

				<?= isset($input_error['photo']) ? '<label class="e">' . $input_error['photo'] . '</label>' : '';  ?>

			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input name="email" type="text" class="form-control" id="email" value="<?= isset($email) ? $email : ''; ?>">


				<?= isset($input_error['email']) ? '<label class="e">' . $input_error['email'] . '</label>' : '';  ?>
				<?= isset($email_error) ? '<label class="e">' . $email_error . '</label>' : '';  ?>
				<?= isset($perror['email']) ? '<label  class="error">' . $perror['email'] . '</label>' : '' ?>

			</div>

			<div class="form-group text-center">
				<input name="addstudent" value="Add Student" type="submit" class="btn btn-danger">
			</div>
		</form>
	</div>
</div>