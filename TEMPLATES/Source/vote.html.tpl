<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>{$ServiceName}</title>
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
							<p>Do you like <strong>{$track.strTrackName}</strong> by <strong>{$track.strArtistName}</strong> ?</p>
{if $show != false}
							<p>You're voting on it from when it was on "<strong>{$show.strShowName}</strong>".</p>
{/if}
							<form action="?go" method="post">
					  			<input type="submit" class="btn btn-primary" style="width: 100%;" name="go" value="I like it!" />
							</form>
						</div><!-- .panel-body -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-8 .col-md-offset-2 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
