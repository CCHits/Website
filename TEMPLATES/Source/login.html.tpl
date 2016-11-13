<!DOCTYPE html>
<html lang="en">
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<title>Login: {$ServiceName} - {$Slogan}</title>
                <link rel="stylesheet" href="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/css/bootstrap.min.css">
                <link rel="stylesheet" href="{$baseURL}CSS/cchits.css">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
							<h4>{$Slogan}</h4>
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12">
									<h3>Login to upload and retrieve tracks, to edit existing tracks and to associate tracks to shows.</h3>
									<p>We use OpenID to authenticate you. OpenID means you don't need to remember a new username and password combination to login, or store a new one somewhere... you just need to remember the login details for the accounts you probably use every day!</p>
{if isset($notuploader) or isset($notadmin) or isset($notyourtrack) or isset($notyourshow)}
									<p class="error"><b>You've been brought back to this page, as you may need to log in with an account which provides different access to the system than the access you have requested. For your reference, your UserID is {$user.intUserID}</b></p>
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
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12 col-md-6">
									<form method="post" action="{$baseURL}google/">
										<input type="submit" class="btn btn-default" style="width: 100%; color: #ffffff; background-color: #D34836;" value="Login with your Google Account" />
									</form>
								</div><!-- .col-xs-12 .col-md-6 -->
								<div class="col-xs-12 col-md-6">
									<form method="post" action="{$baseURL}openid/">
										<input type="hidden" name="id" value="http://yahoo.com" />
										<input type="submit" class="btn btn-default" style="width: 100%; color: #ffffff; background-color: #7B0099;" value="Login with your Yahoo Account" />
									</form>
								</div><!-- .col-xs-12 .col-md-6 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12">
									<form method="post" action="{$baseURL}openid/">
										<div class="form-group">
											<input type="text" class="form-control" id="id" name="id" placeholder="http://">
										</div>
										<input type="submit" class="btn btn-primary" style="width: 100%;" value="Login with your own OpenID Provider" />
									</form>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
						</div><!-- .panel-body -->
						<div class="panel-footer">
							<div class="row">
								<div class="col-xs-12">
									<div>Examples of OpenID providers :</div>
									<ul>
										<li>https://launchpad.net/~&lt;your user name&gt;</li>
										<li>https://www.flickr.com/photos/&lt;your user name&gt;</li>
										<li>https://&lt;your user name&gt;.startssl.com</li>
									</ul>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
						</div><!-- .panel-footer -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-6 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
