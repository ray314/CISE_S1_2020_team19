<!--
Description: PHP document consisting of a static disabled form displaying user input (input columns are grey) and links
-->
<!DOCTYPE html>
<html>
	<head>
		<title>Post Status Process</title>
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
			<?php
				//prevent user from being able to load this page without submitting a form using method="post"
				if ($_SERVER['REQUEST_METHOD'] != 'POST'){ //redirects to 'poststatusform.php'
					header("Location:http://jameas03.cmslamp14.aut.ac.nz/status_posting_system/poststatusform.php" );
				}

				$check_unique = false; //1 check $_POST["status_code"] is unique.  NB:boolean false is converted to "" (empty string)
				$check_not_null = false; //2 check $_POST["status"] doesn't contain only "null"
				$check_whitespace = false; //3 check "$_POST["status"] doesn't contain only whitespace
				$post_successful = false; //4 confirm that tests 1,2 and 3 have all passed

				@require_once('../private/database_credentials.php'); //access database constants

				$connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASS)	//connect to db server
				or die("<h5 style=\"color:#DC3545;\">Unable to connect to database server</h5>"
					. "Error code " . mysqli_connect_errno() . ": " . mysqli_connect_error() . "<br>"
					//add footer and links to 'poststatusform.php' and 'index.html' in error message
					. "<footer class=\"footer fixed-bottom\" style=\"background-color: #b8f4b8;\">"
					. "<div class=\"container\" style=\"padding:8px 6px;\">"
					. "<a href=\"poststatusform.php\">Post a new status</a>"
					. "<a href=\"index.html\" id=\"float-right\">Return to Home Page</a>"
					. "</div>"
					. "</footer>");

				@mysqli_select_db($connection, DB_NAME)	//select table
				or die("<h5 style=\"color:#DC3545;\">Unable to select database</h5>"
					. "Error code " . mysqli_errno($connection)
					. ": " . mysqli_error($connection) . "<br>"
					//add footer and links to 'poststatusform.php' and 'index.html' in error message
					. "<footer class=\"footer fixed-bottom\" style=\"background-color: #b8f4b8;\">"
					. "<div class=\"container\" style=\"padding:8px 6px;\">"
					. "<a href=\"poststatusform.php\">Post a new status</a>"
					. "<a href=\"index.html\" id=\"float-right\">Return to Home Page</a>"
					. "</div>"
					. "</footer>");

				//1 - test for $check_unique
				$status_code = $_POST["status_code"];	//get form value where name="status_code"
				$query_string = "SELECT * FROM status_post WHERE status_code='{$status_code}';";
				$results = @mysqli_query($connection, $query_string)
					or die("<h5 style=\"color:#DC3545;\">Unable to query database</h5><br>"
					//add footer and links to 'poststatusform.php' and 'index.html' in error message
					. "<footer class=\"footer fixed-bottom\" style=\"background-color: #b8f4b8;\">"
					. "<div class=\"container\" style=\"padding:8px 6px;\">"
					. "<a href=\"poststatusform.php\">Post a new status</a>"
					. "<a href=\"index.html\" id=\"float-right\">Return to Home Page</a>"
					. "</div>"
					. "</footer>");

				if (mysqli_num_rows($results) == 0){	//if query doesn't return any data
					$check_unique = true;				//value doesn't exist in db as primary key, therefore it's unique
				}

				@mysqli_free_result($results);

				//2 - test for $check_not_null
				$status = $_POST["status"];	//get form value where name="status"
				$status_lowercase = strtolower($status);
				if ($status_lowercase != "null")
					$check_not_null = true;

				//3 - test for $check_whitespaces
				if (!ctype_space($status)) //ctype_space() reutrns true if all characters in parameter string are spaces
					$check_whitespace = true;

				//4 - if all tests are successful
				if ($check_unique && $check_not_null && $check_whitespace){
					$status = $_POST["status"];
					$share = $_POST["share"]; 							//returns either: 'public', 'friends', 'only_me'
					$date = $_POST["date"];								//returns 'yyyy-mm-dd'
					$allow_like = $_POST["allow_like"];					//returns '0' or '1'
					$allow_comment = $_POST["allow_comment"]; 			//returns '0' or '1'
					$allow_share = $_POST["allow_share"]; 				//returns '0' or '1'

					$query_string = "INSERT INTO status_post"
						. "(status_code, status, share, date, allow_like, allow_comment, allow_share) VALUES"
						. "('{$status_code}', '{$status}', '{$share}', '{$date}', $allow_like, $allow_comment, $allow_share);";

					//insert form data into database
					$result = @mysqli_query($connection, $query_string)
						or die("<h5 style=\"color:#DC3545;\">Unable to query database</h5><br>"
						//add footer and links to 'poststatusform.php' and 'index.html' in error message
						. "<footer class=\"footer fixed-bottom\" style=\"background-color: #b8f4b8;\">"
						. "<div class=\"container\" style=\"padding:8px 6px;\">"
						. "<a href=\"poststatusform.php\">Post a new status</a>"
						. "<a href=\"index.html\" id=\"float-right\">Return to Home Page</a>"
						. "</div>"
						. "</footer>");

					@mysqli_free_result($result);
					@mysqli_close($connection);
					$post_successful = true; //confirm all tests have been successful
				}

				if ($post_successful){ //all tests have been successful
					echo "<h5 style=\"color:#28A745;\">Status post successful</h5>"; //display successful message
				} else {
					echo "<h5 style=\"color:#DC3545;\">Status post unsuccessful</h5>"; //display unsuccessful message
				}
			?>
			<!--NB form below displays data submitted by user with input fields disabled and 'greyed out' -->
			<!-- if status post unsuccessful, specific error message/s are dislayed in form below -->
			<form novalidate>
				<div class="form-group">
					<div class="row row-cols-3">
						<div class="col-2-half col-form-label">
							<label>Status Code (required):</label>
						</div>
						<div class="col-4">
							<input type="text" class="form-control" placeholder="<?php echo $status_code; ?>"  disabled>
								<?php
									if (!$check_unique){ //if 'status_code' value not unique, print error message
										echo "<small style=\"color:#DC3545;\">"
											. "Status Code must be unique"
											. "</small>";
									}
								?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-cols-2">
						<div class="col-2-half col-form-label">
							<label>Status (required):</label>
						</div>
						<div class="col-8">
							<input type="text" class="form-control" placeholder="<?php echo $status; ?>" disabled>
							<?php
								if (!$check_not_null){ //if 'status' value contains only 'null', print error message
									echo "<small style=\"color:#DC3545;\">"
										. "Status must not be null"
										. "</small>";
								}
								if (!$check_whitespace){ //if 'status' value contains only whitespace, print error message
									echo "<small style=\"color:#DC3545;\">"
										. "Status must not be whitespace"
										. "</small>";
								}
							?>
						</div>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Share:</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" <?php if ($_POST['share']=="public") echo "checked"; ?> disabled>
						<label class="form-check-label radio-label">Public</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" <?php if ($_POST['share']=="friends") echo "checked"; ?> disabled>
						<label class="form-check-label radio-label">Friends</label>
					</div>
					<div class="form-check-inline">
						<input type="radio" <?php if ($_POST['share']=="only_me") echo "checked"; ?> disabled>
						<label class="form-check-label radio-label">Only Me</label>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Date:</label>
					</div>
					<div class="col-3 form-check-inline">
						<?php
							$date = $_POST['date'];
							echo (('<input type="date" name="date" value="') . $date . ('" class="form-control date"  disabled>'));
						?>
					</div>
				</div>
				<div class="row row-cols-5">
					<div class="col-2 col-form-label">
						<label class="form-check-label">Permission Type:</label>
					</div>
					<div class="form-check-inline">
						<input type="checkbox" <?php if ($_POST['allow_like']=="1") echo "checked"; ?> disabled>
						<label class="form-check-label check-label">Allow Like</label>
					</div>
					<div class="form-check-inline">
						<input type="checkbox" <?php if ($_POST['allow_comment']=="1") echo "checked"; ?> disabled>
						<label class="form-check-label check-label">Allow Comment</label>
					</div>
					<div class="form-check-inline">
						<input type="checkbox" <?php if ($_POST['allow_share']=="1") echo "checked"; ?> disabled>
						<label class="form-check-label check-labe">Allow share</label>
					</div>
				</div>
			</form>
			<br>
			<footer class="footer fixed-bottom" style="background-color: #b8f4b8;"> <!-- add footer to page -->
				<div class="container" style="padding:8px 6px;">
					<a href="index.html">Return to Home Page</a>
				</div>
			</footer>
		</div>
	</body>
</html>
