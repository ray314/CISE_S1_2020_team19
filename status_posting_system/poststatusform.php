<!--
Description: PHP document consisting of a dynamic form for user input and links
-->
<!DOCTYPE html>
<html>
	<head>
		<title>Post Status Form</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="css/bootstrap.css">
	    <link rel="stylesheet" href="css/style.css">
	    <script src="js/bootstrapjquery.js" defer></script>
	    <script src="js/popper.js" defer></script>
	    <script src="js/bootstrap.js" defer></script>
	    <script src="js/javascript.js" defer></script>
		<style>
			h1 {
				color: #333333;
				text-shadow: 2px 2px 0px #FFFFFF, 5px 4px 0px rgba(0,0,0,0.15);
			}
		</style>
	</head>
	<body>
		<header>
			<nav class="navbar navbar-light" style="background-color: #a6f1a6;">
				<div class="container">
					<h1>Status Posting System</h1>
				</div>
			</nav>
		</header>
		<div class="container"><br>
			<!-- NB: all forms in this assignment use Bootstrap classes" -->
			<!-- class='needs-validation' below: calls function in javascript.js when form is submitted -->
			<!-- 'novalidate' attribute below: input data should not be validated by browser when form is submitted -->
			<form action="poststatusprocess.php" method="post" class="needs-validation" novalidate>
				<div class="row row-cols-12"> <!-- class "rows-cols-12": use full page width i.e. 12/12 columns of the page width -->
					<div class="col-2-half col-form-label"> <!-- class "col-2-half": use 2.5/12 colums of the page width -->
						<label class="form-label">Status Code (required):</label>
					</div>
					<div class="col-4">	<!-- "pattern" attribute below: initial character is 'S' followed by 4 integer characters -->
						<input type="text" name="status_code" class="form-control" pattern="^[S]{1}[0-9]{4}$" maxlength="5" minlength="5" required>
						<small class="form-text text-muted">Status Code must be unique</small> <!-- form help text to guide the user -->
						<div class="invalid-feedback">
							Input format:'S' followed by 4 integers <!-- text displayed if input is invalid -->
						</div>
					</div>
				</div>
				<div class="row row-cols-12">
					<div class="col-2-half col-form-label">
						<label class="form-label">Status (required):</label>
					</div>
					<div class="col-8"> <!-- "pattern" attribute below: any letter, number, '.', '!', '?' or space ' ' -->
						<input type="text" name="status" class="form-control" pattern="[A-Za-z0-9,.!? ]*" maxlength="50" minlength="1" required>
						<div class="invalid-feedback">
							Can contain: letters, numbers, spaces, ',', '!', '?'.<br>
							Cannot be null or whitespace.
						</div>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Share:</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" name="share" value="public"> <!-- attribute name can be either null, "public", "friends" or "only_me" -->
						<label class="form-check-label radio-label">Public</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" name="share" value="friends">
						<label class="form-check-label  radio-label">Friends</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" name="share" value="only_me" checked>
						<label class="form-check-label  radio-label">Only Me</label>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Date:</label>
					</div>
					<div class="col-3 form-check-inline">
						<?php
							date_default_timezone_set('Pacific/Auckland'); //sets the date timezone
							$date = date("Y-m-d"); //gets year as four digits, month as '01' - '12' and day as '03' - '31'
							echo (('<input type="date" name="date" value="') . $date . ('" class="form-control date"  required>'));
						?>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Permission Type:</label>
					</div>
					<div class="form-check-inline">
						<input type="hidden" name="allow_like" value="0">       <!-- if checkbox not checked, returns value=0 -->
						<input type="checkbox" name="allow_like" value="1">     <!-- if checkbox checked, returns value=1 -->
						<label class="form-check-label check-label">Allow Like</label>
					</div>
					<div class="form-check-inline">
						<input type="hidden" name="allow_comment" value="0">
						<input type="checkbox" name="allow_comment" value="1">
						<label class="form-check-label check-label">Allow Comment</label>
					</div>
					<div class="form-check-inline">
						<input type="hidden" name="allow_share" value="0">
						<input type="checkbox" name="allow_share" value="1">
						<label class="form-check-label check-label">Allow share	</label>
					</div>
				</div>
				<div class="row">
					<div class="col-1 col-form-label">
						<button type="submit" class="btn btn-primary btn-default" value="post" id="post-button1" >Post</button>
					</div>
					<div class="col-1 col-form-label"> <!-- button below removes form input submitted by the user -->
						<button type="reset" class="btn btn-secondary btn-default" value="reset" id="post-button2">Reset</button>
					</div>
				</div>
			</form>
			<br>
		</div>
		<footer class="footer fixed-bottom" style="background-color: #b8f4b8;"> <!-- add footer to page -->
			<div class="container" style="padding:8px 6px;">
				<a href="index.html">Return to Home Page</a>
			</div>
		</footer>
	</body>
</html>
