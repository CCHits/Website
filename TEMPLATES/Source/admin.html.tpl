<!DOCTYPE html>
<html lang="en">
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<title>Administration: {$ServiceName} - {$Slogan}</title>
		<link rel="stylesheet" href="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/css/bootstrap.min.css">
		<link rel="stylesheet" href="{$baseURL}CSS/cchits.css">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
							<h4>{$Slogan}</h4>
						</div><!-- .panel-heading -->
						<div class="panel-body">
							<ul class="nav nav-tabs nav-justified">
{if $user.isUploader = 1}
								<li role="presentation" class="active">
									<a href="#" id="admin-track-tab" class="admin-tab-button" data-target="#admin-track-panel">Import/Retrieve track</a>
								</li>
{/if}
{if $user.isAdmin = 1}
								<li role="presentation">
									<a href="#" id="admin-show-tab" class="admin-tab-button" data-target="#admin-show-panel">Create/Edit show</a>
								</li>
{/if}
								<li role="presentation">
									<a href="#" id="admin-account-tab" class="admin-tab-button" data-target="#admin-account-panel">My account</a>
								</li>
							</ul>
{if $user.isUploader = 1}
							<div class="col-xs-12 admin-panel" id="admin-track-panel">
								<div class="row">
									<div class="col-xs-12">
										<form method="post" action="{$baseURL}admin/addtrack" enctype="multipart/form-data">
											<div class="form-group">
												<label for="trackurl">Track URL</label>
												<input class="form-control" type="text" name="trackurl" id="trackurl" placeholder="http://...">
											</div>
											<div class="form-group">
												<label for="file">And/or file</label>
												<input class="form-control" type="file" name="file" id="file">
											</div>
											<input type="submit" class="btn btn-primary" style="width: 100%;" value="Retrieve or Upload a track."></input>
											<p>Acceptable source domains are:</p>
											<ul>
												<li>alonetone.com</li>
												<li>ccmixter.org</li>
												<li>freemusicarchive.org</li>
												<li>jamendo.com</li>
												<li>macjams.com</li>
												<li>riffworld.com</li>
												<li>sectionz.com</li>
												<li>soundcloud.com</li>
												<li>sutros.com</li>
												<li>vimeo.com</li>
											</ul>
										</form>
									</div><!-- .col-xs-12 -->
								</div><!-- .row -->
								<div class="row">	
								<form method="post" action="{$baseURL}admin/listtracks">
									<input type="submit" class="btn btn-primary" style="width: 100%;" value="Show a list of outstanding unprocessed tracks."></input>
								</form>
								</div>
							</div><!-- .col-xs-12 .admin-panel #admin-track-panel -->
{/if}
{if $user.isAdmin = 1}
							<div class="col-xs-12 admin-panel" id="admin-show-panel">
								<div class="row">
									<div class="col-xs-12">
										<form method="post" action="{$baseURL}admin/addshow">
											<div class="form-group">
												<label for="strShowUrl">Show Notes URL</label>
												<input type="text" name="strShowUrl" id="strShowUrl" class="form-control">
											</div>
											<div class="form-group">
												<label for="strShowName">Show Name (optional)</label>
												<input type="text" class="form-control" name="strShowName" id="strShowName">
											</div>
											<input type="submit" class="btn btn-primary" style="width: 100%;" value="Create a new show."></input>
										</form>
									</div><!-- .col-xs-12 -->
								</div><!-- .row -->
								<div class="row">
									<div class="col-xs-12">
										<form method="post" action="{$baseURL}admin/listshows">
											<input type="submit" class="btn btn-primary" style="width: 100%; "value="Show a list of the shows I created."></input>
										</form>
									</div><!-- .col-xs-12 -->
								</div><!-- .row -->
							</div><!-- .col-xs-12 .admin-panel #admin-show-panel -->
{/if}
							<div class="col-xs-12 admin-panel" id="admin-account-panel">
								<div class="row">
									<div class="col-xs-12">
										<form method="post" action="{$baseURL}admin/basicauth">
											<input type="submit" class="btn btn-danger" style="width: 100%;" value="Amend my scripting credentials (power users only)."></input>
										</form>	
									</div><!-- .col-xs-12 -->
								</div><!-- .row -->
								<div class="row">
									<div class="col-xs-12">
										<a href="{$baseURL}admin/logout" style="width: 100%;" class="btn btn-primary">Log out</a>
									</div><!-- .col-xs-12 -->
								</div><!-- .row -->
							</div><!-- .col-xs-12 .admin-panel #admin-account-panel -->
						</div><!-- panel-body -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-6 col-md-offset-3 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="{$baseURL}JAVASCRIPT/cchits.js"></script>
	</body>
</html>
