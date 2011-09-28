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
{if isset($notuploader) or isset($notadmin) or isset($notyourtrack) or isset($notyourshow)}
		<p>You've been brought back to this page, as you may need to log in with an account which provides different access to the system than the access you have requested.</p>
{if isset($notuploader)}
		<p>This account does not have permission to upload to or amend tracks with this service.</p>
{/if}
{if isset($notadmin)}
		<p>This account does not have permission to create or amend shows on this service.</p>
{/if}
{if isset($notyourtrack)}
		<p>You have tried to edit a track which you did not upload.</p>
{/if}
{if isset($notyourshow)}
		<p>You have tried to edit a show which is not linked to your account.</p>
{/if}
		<p>Please feel free to log back in using the credentials you used to log in initially, or if you feel the above is incorrect, please contact the <a href="{$config['Contact EMail'][0]}">{$config['Contact Name'][0]}</a>.</p>
{/if}
		<table>
			<tr>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://www.google.com/accounts/o8/id" />
						<input type="submit" value="Login with your Google Account" />
					</form>
				</td>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://yahoo.com" />
						<input type="submit" value="Login with your Yahoo Account" />
					</form>
				</td>
				<td>
					<form method="post" action="{$baseURL}openid/">
						<input type="hidden" name="id" value="http://myspace.com" />
						<input type="submit" value="Login with your MySpace Account" />
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<form method="post" action="{$baseURL}openid/">
						<input type="text" name="id" size="30" value="http://" />
						<input type="submit" value="Login with your own OpenID Provider" />
					</form>
				</td>
			</tr>
		</table>
		