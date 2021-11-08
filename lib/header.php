<?php

/**
 * 
 * @header.php
 * 
 * Output the required html head tags.
 * Display a different navbar depending on whether the user is logged in.
 */

require_once 'config.php';

$user = "";
$loggedin = FALSE;

?>
<!DOCTYPE html>


<head lang="en">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>IMARE</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/e706e1aa38.js" crossorigin="anonymous"></script>

	<!-- LEAFLET -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
	<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
	<link rel="stylesheet" href="/~mp/diss/www/inc/css/markerCluster.default.css">

	<link rel="stylesheet" href="/~mp/diss/www/inc/css/style.css">
	<script src="/~mp/diss/www/inc/js/functions.js"></script>

</head>

<body>
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


	<?php
	if (isset($_SESSION['user'])) {
		$user = $_SESSION['user'];
		$loggedin = TRUE;
	}

	?>


	<nav class="navbar fixed-top navbar-light bg-light">
		<a class="navbar-brand" href="/~mp/diss/www/index.php">IMARE</a>

		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="/~mp/diss/www/index.php">
					<img src="/~mp/diss/www/inc/svg/home-run.svg" alt="">
					<span>Home</span>
					<span class="sr-only">(current)</span>
				</a>
			</li>

			<?php
			if ($loggedin) {
			?>

		</ul>
		<a class="nav-link pull-right" href="/~mp/diss/lib/profile.php">Profile</a>
		<a class="btn btn-outline-danger btn-sm" href="/~mp/diss/lib/logout.php">Sign out</a>
	</nav>

	<button id="upload-btn" class="btn btn-info" type="button" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload"></i></button>

	<!-- UPLOAD MODAL -->
	<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="layout">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-muted" id="uploadModalLabel"><i class="fas fa-upload"></i> Upload a file</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../lib/upload.php" id="upload-form" method="POST" enctype="multipart/form-data">
                    <div class="modal-body" id="upload-bod">
                        <div class="form-group">
							<img id="preview-img" alt="img preview" src="">
                            <input type="file" id="uploadImg" name="uploadImg" aria-describedby="uploadHelp" accept=".jpg, .jpeg">
                            <!--
								<label class="" for="inputGroupFile01" name="actualFile" id="upload-file">Choose file</label>
							-->
                            <small id="uploadHelp" class="form-text text-muted">Supported file types: jpg, jpeg</small>

                        </div>
                        <div class="form-group" id="img-attr">
                            <label class="text-secondary" for="img_name">Image name</label>
                            <input type="text" class="form-control" id="img_name" name="img_name" placeholder="Name your image">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger btn-sm mr-auto" data-dismiss="modal" onclick="clearImgInp()">Close</button>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
	<!-- UPLOAD MODAL end-->

<?php
			} else {
?>

	</ul>
	<form class="form-inline my-2 my-lg-0">
		<button class="btn btn-outline-success my-sm-0" type="button" data-toggle="modal" data-target="#loginModal"><i class="far fa-user"></i> Login</button>
	</form>
	</nav>

	<!-- Login Modal -->

	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
		<div id="login-mdl-dialog" class="modal-dialog" role="log-in">
			<div class="modal-content" id="login-content">
				<div class="modal-header">
					<h5 class="modal-title text-muted" id="loginModalLabel"><i class="fas fa-id-card"></i> Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="../lib/login.php" id="login-form" method="post" autocomplete="off">
					<div class="modal-body" id="login-bod">
						<div class="form-group">
							<label class="text-secondary" for="user">Username</label>
							<input type="text" class="form-control" id="user" onblur="" aria-describedby="userHelp" placeholder="Enter user">
							<small id="userHelp" class="form-text text-muted">Please enter your username.</small>
						</div>
						<div class="form-group">
							<label class="text-secondary" for="pass">Password</label>
							<input type="password" class="form-control" id="pass" placeholder="Password">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
						<small id="notUser" class="text-muted">Not registered to IMARE?</small>
						<button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#signupModal" data-dismiss="modal">Sign Up</button>
						<button type="submit" class="btn btn-success" name="login-submit">Log in</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Login Modal end -->


	<!-- Signup Modal -->

	<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="log-in">
			<div id="sign-content" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-muted" id="signupModalLabel"><i class="fas fa-id-card mr-3"></i>Please enter your details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form class="needs-validation" action="../lib/signup.php" id="signup-form" method="post" autocomplete="off" novalidate>
					<div class="modal-body" id="sign-bod">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label class="text-secondary" for="userFName">First name</label>
								<input type="text" class="form-control" id="userFName" name="f_name" aria-describedby="nameHelp" placeholder="Enter first name">
								<small id="nameHelp" class="form-text text-muted">Please enter your first and last name.</small>
							</div>
							<div class="form-group col-md-6">
								<label class="text-secondary" for="userLName">Last name</label>
								<input type="text" class="form-control" id="userLName" name="l_name" aria-describedby="nameHelp" placeholder="Enter last name">
							</div>
						</div>
						<div class="form-group">
							<label class="text-secondary" for="userEmail">Email</label>
							<input type="email" class="form-control" id="userEmail" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
							<div class="invalid-feedback">
								<small id="emailHelp" class="form-text">Please enter a valid email.</small>
							</div>
						</div>
						<div class="form-group">
							<label class="text-secondary" for="user">Username</label>
							<input id="signUser" type="text" class="form-control" name="usname" aria-describedby="userHelp" placeholder="Enter user" required>
							<small id="userMsg" class="form-text">Please enter a username.</small>
						</div>
						<div class="form-group">
							<label class="text-secondary" for="pass">Password</label>
							<input type="password" class="form-control" id="pass" name="pass" aria-describedby="passHelp" placeholder="Password" required>
							<div class="invalid-feedback">
								<small id="passHelp" class="form-text">Please enter a password.</small>
							</div>
							<input type="password" class="form-control mt-2" id="pass2" name="repeat_pass" aria-describedby="pass2Help" placeholder="Repeat Password" required>
							<div class="invalid-feedback">
								<small id="pass2Help" class="form-text">Please repeat the password.</small>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger btn-sm mr-auto" data-dismiss="modal">Close</button>
						<button id="signup-submit" type="submit" class="btn btn-success" name="signup-submit">Sign up</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Signup Modal end-->
<?php
			}
