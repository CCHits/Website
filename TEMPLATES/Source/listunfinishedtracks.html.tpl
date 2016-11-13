<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>List Unfinished Tracks: {$ServiceName} - {$Slogan}</title>
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
							<h4>My unfinished tracks <small><a href="{$baseURL}admin">Go back to the admin page.</a></small></h4>
							<ul class="list-group">
{foreach from=$tracks key=id item=track}
								<li class="list-group-item"><a href="{$baseURL}admin/addtrack/{$track.intProcessingID}">{$track.strTrackName} by {$track.strArtistName}</a> (<a href="{$baseURL}admin/deltrack/{$track.intProcessingID}">Delete</a>)</li>
{/foreach}
							</ul>
						</div><!-- .panel-body -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-8 .col-md-offset-2 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
