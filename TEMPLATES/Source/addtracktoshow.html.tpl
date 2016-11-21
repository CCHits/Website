<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta name=viewport content="width=device-width, initial-scale=1">
		<title>Add Track To Show: {$ServiceName} - {$Slogan}</title>
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
							<h2>{$Slogan}</h2>
						</div><!-- .panel-heading -->
						<div class="panel-body">
                			<h4>Add a track to a show <small><a href="{$baseURL}admin">Go back to the admin page.</a></small></h4>
							<div>Associate track "{$track.strTrackName}" by "{$track.strArtistName}" to one of the following shows:</div>
							<div class="row">
								<div class="col-xs-12">
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Show URL</th>
												<th>Show Name</th>
												<th>Associate?</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<form method="post" action="{$baseURL}admin/addshow/">
													<td>
														<div class="input-group input-group-sm" style="width: 100%;">
															<input type="hidden" name="intTrackID" value="{$track.intTrackID}">
															<input class="form-control" type="text" name="strShowUrl">
														</div>
													</td>
													<td>
														<div class="input-group input-group-sm" style="width: 100%;">
															<input class="form-control" type="text" name="strShowName">
														</div>
													</td>
													<td>
														<div class="input-group input-group-sm" style="width: 100%;">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</td>
												</form>
											</tr>
{foreach from=$shows item=show name=shows}
											<tr>
												<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
													<td>
														<div class="input-group input-group-sm">
															{$show.strShowName}
														</div>
													</td>
													<td>
														<div class="input-group input-group-sm">
															<input type="hidden" name="intTrackID" value="{$track.intTrackID}">
														</div>
													</td>
													<td>
														<div class="input-group input-group-sm" style="width: 100%;">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</td>
												</form>
											</tr>
{/foreach}
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
