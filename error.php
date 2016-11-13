<?php

switch(getenv("REDIRECT_STATUS")) {

	# "400 - Bad Request"
	case 400:
	$error_code = "400 - Bad Request";
	break;

	# "401 - Unauthorized"
	case 401:
	$error_code = "401 - Unauthorized";
	break;

	# "403 - Forbidden"
	case 403:
	$error_code = "403 - Forbidden";
	break;

	# "404 - Not Found"
	case 404:
	$error_code = "404 - Not Found";
	$explanation = "The requested resource '" . $page_redirected_from . "' could not be found on this server.  Please verify the address and try again.";
	break;

	# "500 - Internal Server Error"
	case 500:
	$error_code = "500 - Internal Server Error";
	break;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

</head>
<body>

<h1>Error Code <?php print ($error_code); ?></h1>

<?php print ("Sorry, an error has occured which prevents us from delivering the requested page. Our team is working on this, and the site should hopefully be available soon. Thanks for your patience."); ?>

</body>
</html>





