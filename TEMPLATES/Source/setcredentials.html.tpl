<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<title>Set Credentials: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>Set Credentials Page</h3>
		<p>On this page you can create an account to use the API, or you can amend your username and password.</p>
{if $error}
		<p>There was an error updating your credentials. Please try again, or contact the <a href="{$config['Contact EMail'][0]}">{$config['Contact Name'][0]}</a>.</p>
{/if}
		<form method="post" action="{$baseURL}admin/basicauth/">
			Username: <input type="text" name="strUsername" size="30" value="{if isset($user.strUserName)}{$user.strUserName}{/if}" /><br />
			Password: <input type="password" name="strPassword" size="30" value="" /><br />
			<input type="submit" value="{if !isset($user.strUsername) or ($user.strUserName == '')}Create{else}Amend{/if} Login Details" />
		</form>
	</body>
</html>