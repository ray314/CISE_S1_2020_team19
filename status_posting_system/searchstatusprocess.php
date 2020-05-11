<!--
Description: PHP document displays database query results from searchstatusform.html and displays a link
-->
<!DOCTYPE html>
<html>
	<head>
		<title>Search Information</title>
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
					<h1>Status Information</h1>
				</div>
			</nav>
		</header>
		<div class="container"><br>
			<?php
				//prevent user from being able to load this page without submitting a form using method="get"
				if ($_SERVER['REQUEST_METHOD'] != 'GET'){  //redirects to 'searchstatusform.html'
					header("Location:http://jameas03.cmslamp14.aut.ac.nz/status_posting_system/searchstatusform.html");
				}

				$check_not_null = false; //1 check $_GET["status"] does not contain only "null"
				$check_whitespace = false; //2 check "$_GET["status"] doesn't contain only whitespace
				$check_query_return = false;  //3 check that database query returned at least 1 row
				$search_successful = false; //4 confirm that above tests all passed

				//1 - test for $check_not_null
				$status = $_GET["status"];	//get form value where name="status"
				$status_lowercase = strtolower($status);
				if ($status_lowercase != "null")
					$check_not_null = true;

				//2 - test for $check_whitespaces
				if (!ctype_space($status)){ //ctype_space() reutrns true if all characters in parameter string are spaces
					$check_whitespace = true;
				}

				if ($check_not_null  && $check_whitespace){
					@require_once('../private/database_credentials.php'); //access database constants

					$connection = @mysqli_connect(DB_HOST, DB_USER, DB_PASS)	//connect to db server
					or die("<h5 style=\"color:#DC3545;\">Unable to connect to database server<h5>"
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

					$status = $_GET["status"];
					// %{status}% : % means any number of characters in before or after variable
					$query_string = "SELECT * FROM status_post WHERE status LIKE '%{$status}%' ORDER BY date;";

					//query database
					$results = @mysqli_query($connection, $query_string)
					or die("<h5 style=\"color:#DC3545;\">Unable to query database</h5><br>"
					//add footer and links to 'poststatusform.php' and 'index.html' in error message
					. "<footer class=\"footer fixed-bottom\" style=\"background-color: #b8f4b8;\">"
					. "<div class=\"container\" style=\"padding:8px 6px;\">"
					. "<a href=\"poststatusform.php\">Post a new status</a>"
					. "<a href=\"index.html\" id=\"float-right\">Return to Home Page</a>"
					. "</div>"
					. "</footer>");

					//3 - test for $check_query_return
					//4 - test for $search_successful
					if (mysqli_num_rows($results) != 0){	//if query returns data
						$check_query_return = true;			//3 - query contains at least 1 row of data
						$search_successful = true;			//4 - all tests have passed
					}
				}

				//fuction create_permissions_string produces a string representing assigned permissions for an entity instance
				if ($search_successful){
					function create_permissions_string($permissions){
						$permissions_string = null;
						if ($permissions[0])
							$permissions_string .= "allow like, ";

						if ($permissions[1])
							$permissions_string .= "allow comment, ";

						if ($permissions[2])
							$permissions_string .= "allow share, ";

						if (strlen($permissions_string) != 0){
							$permissions_string[0] = "A"; //convert first character to uppercase i.e. to capital A
							$return_string = substr($permissions_string, 0, strlen($permissions_string)-2); //remove ', ' from the end of the string
						} else {
							$return_string = "None"; //if no permissions are assignmed
						}

						return $return_string;
					}

					// print all search matches returned from the database inside a while loop
					$row = mysqli_fetch_assoc($results)
					while($row){
						$date = date_create_from_format("Y-m-j", $row['date']); //converts $row['date'] type String to $date type DateTime object
						$formatted_date = date_format($date,"F j, Y") . "<br>"; //changes $date format to 'Month dd, YYY'

						$permissions = array($row['allow_like'], $row['allow_comment'], $row['allow_share']);
						$permissions_available = create_permissions_string($permissions); //callsfunction above to produce a permissions string

						//print unordered list of results in HTML format using Bootstrap classes:
						echo "<ul class=\"list-group\">"
							. "<li class=\"list-group-item list-group-item-primary\"><b>Status:</b>&nbsp" . $row['status'] . "</li>"
							. "<li class=\"list-group-item list-group-item-secondary\"><b>Status Code:</b>&nbsp" . $row['status_code'] . "</li>"
							. "<li class=\"list-group-item list-group-item-light\"><b>Share:</b>&nbsp" . $row['share'] . "</li>"
							. "<li class=\"list-group-item list-group-item-light\"><b>Date Posted:</b>&nbsp" . $formatted_date . "</li>"
							. "<li class=\"list-group-item list-group-item-light\"><b>Permission:</b>&nbsp" . $permissions_available .  "</li>"
							. "</ul>"
							. "<br>";
					}
				} else { //else means: $search_successful == false
					echo "<h5 style=\"color:#DC3545;\">Status search unsuccessful</h5>"; //display unsuccessful message
					//display error message or messages:
					if (!$check_not_null)
						echo "<p>Status cannot contain only null</p><br>";
					if (!$check_whitespace)
						echo "<p>Status cannot contain only whitespace</p><br>";
					if (!$check_query_result)
						echo "<p>Status search returned no results</p><br>";
				}
				@mysqli_free_result($results);
				@mysqli_close($connection);
	        ?>
		</div>
		<br>
		<footer class="footer fixed-bottom" style="background-color: #b8f4b8;">
			<div class="container" style="padding:8px 6px;">
				<a href="searchstatusform.html">Search status</a>
				<a href="index.html" id="float-right">Return to Home Page</a>
			</div>
		</footer>
    </body>
</html>
