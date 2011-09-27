<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<title>Login: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>Login to upload and retrieve tracks, to edit existing tracks and to associate tracks to shows.</h3>
		<p>We use OpenID to authenticate you. OpenID means you don't need to remember a new username and password combination to login, or store a new one somewhere... you just need to remember the login details for the accounts you probably use every day!</p>
		<table>
			<tr>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://www.google.com/accounts/o8/id"></input>
						<input type="submit" value="Login with your Google Account"></input>
					</form>
				</td>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://yahoo.com"></input>
						<input type="submit" value="Login with your Yahoo Account"></input>
					</form>
				</td>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://myspace.com"></input>
						<input type="submit" value="Login with your MySpace Account"></input>
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<form method="post" action="{$baseURL}openid/">
						<input type="text" name="id" size="30" value="http://"></input>
						<input type="submit" value="Login with your own OpenID Provider"></input>
					</form>
				</td>
			</tr>
		</table>
		