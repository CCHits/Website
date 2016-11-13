<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>Show Editor: {$ServiceName} - {$Slogan}</title>
		<link rel="stylesheet" type="text/css" href="{$baseURL}STYLE/site.css" />	
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
							<div class="row">
								<div class="col-xs-12">
									<h4>Show Editor <small><a href="{$baseURL}admin">Go back to the admin page.</a></small></h4>
									<div>On this page you will modify or create a show. (<a href="{$baseURL}admin/listshows">Go to your shows</a>)</div>
{if isset($error) and $error}
									<div>There was an error.</div>
{/if}
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12">
									<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
										<div class="form-group">
											<label for="strShowName">Show Name</label>
											<div class="input-group">
												<input type="text" class="form-control" name="strShowName" id="strShowName" value="{$show.strShowName}">
												<span class="input-group-btn">
													<input type="submit" class="btn btn-default" value="Go">
												</span>
											</div><!-- .input-group -->
										</div><!-- .form-group -->
									</form>
								</div><!-- .col-xs-12 -->
								<div class="col-xs-12">
									<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
										<div class="form-group">
											<label for="strShowUrl">Show URL</label>
											<div class="input-group">
												<input type="text" class="form-control" name="strShowUrl" id="strShowUrl" value="{$show.strShowUrl}">
												<span class="input-group-btn">
													<input type="submit" class="btn btn-default" value="Go">
												</span>
											</div><!-- .input-group -->
										</div><!-- .form-group -->
									</form>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
						</div><!-- .panel-body -->
{if isset($show.arrTracks) and count($show.arrTracks) > 0}
						<table class="table">
							<thead>
								<th>#</th>
								<th>Track Name</th>
								<th>Artist</th>
								<th>Vote URL</th>
							</thead>
							<tfoot>
								<th>#</th>
								<th>Track Name</th>
								<th>Artist</th>
								<th>Vote URL</th>
							</tfoot>
							<tbody>
{foreach from=$show.arrTracks key=key item=track name=tracks}
								<tr>
									<td>{$key}</td>
									<td>
										<div>{$track.strTrackName}</div>
										<div class="table-menu">
											<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
												<input type="hidden" name="moveup" value="{$track.intTrackID}">
												<input type="submit" class="btn btn-xs" value="&uarr;"{if $smarty.foreach.tracks.first} disabled="disabled"{/if} />
											</form>
											<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
												<input type="hidden" name="movedown" value="{$track.intTrackID}">
												<input type="submit" class="btn btn-xs" value="&darr;"{if $smarty.foreach.tracks.last} disabled="disabled"{/if} />
											</form>
											<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
												<input type="hidden" name="remove" value="{$track.intTrackID}">
												<input type="submit" class="btn btn-xs" value="&#10007" />
											</form>
											<form class="inline" method="get" action="{$baseURL}admin/track/{$track.intTrackID}">
												<input type="submit" class="btn btn-xs" value="Edit" />
											</form>
										<div>
									</td>
									<td>{$track.strArtistName}</td>
					                                <td>{$baseURL}vote/{$track.intTrackID}/{$show.intShowID}</td>
								</tr>
{/foreach}
							</tbody>
						</table>
{/if}
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-6 .col-md-offset-3 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
