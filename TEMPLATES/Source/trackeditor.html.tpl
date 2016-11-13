<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>Track Editor: {$ServiceName} - {$Slogan}</title>
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
							<h4>Track Editor <small><a href="{$baseURL}admin">Go back to the admin page.</a></small></h4>
							<p>On this page, you will edit a previously submitted track.</p>
{if isset($error) and $error}
							<div class="row bg-danger">
								<div class="col-xs-12">
									<p>There was an error.</p>
								</div><!-- .col-xs-12 -->
							</div><!-- .row .bg-danger -->
{/if}
							<div class="row">
								<div class="col-xs-12">
									<form method="post" action="{$baseURL}admin/show">
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" value="Associate this track with a show ?">
											<input type="hidden" name="intTrackID" value="{$track.intTrackID}">
											<span class="input-group-btn">
												<input type="submit" class="btn btn-default" value="Go">
											</span>
										</div>
									</form>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row bg-info">
								<div class="col-xs-12">
									<p><strong>Track Name:</strong></p>
									<p>Whoever uploaded the track may have added some "interesting" tweaks to the track name. You should fix these here.</p>
									<div class="More TrackName">
										<p>Wherever possible, leave the *original* track name in here as an option, as it stops duplications from occuring in the track database.</p>
										<p>Some examples of track adjustments to be encouraged:</p>
										<ul>
											<li>A track name (was "A track name - The Artist")</li>
											<li>A track name (was "A track name - http://where.to.get.it.from.tld/artist/blah")</li>
											<li>A track name (was "http://www.theartistsite.com/track/a_track_name.mp3")</li>
										</ul>
									</div>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
												<th>Add new value</th>
												<th>Delete value</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="strTrackName_preferred">
{foreach $track.arrTrackName item=trackname}
																<option value="{$trackname}"{if $trackname == $track.strTrackName} selected="selected"{/if}>{$trackname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div><!-- .input-group -->
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input type="text" name="strTrackName" class="form-control">
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default"/>
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="del_strTrackName">
{foreach $track.arrTrackName item=trackname}
																<option value="{$trackname}">{$trackname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table><!-- .table .table-condensed -->
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12">
									<p><strong>Track Name Sounds:</strong></p>
									<div class="More TrackNameSounds">
										<p>The way that festival will try to pronounce this track's name. It will be copied from the track name unless otherwise specified.</p>
										<p>Some examples of good entries:</p>
										<ul>
											<li>Sal-vee-yah (instead of "Salvia")</li>
											<li>The blue mix of Stop (instead of "Stop (Blue Mix)")</li> 
										</ul>
									</div>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strTrackNameSounds" value="{$track.strTrackNameSounds}">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row bg-info">
								<div class="col-xs-12">
									<p><strong>Track URL:</strong></p>
									<div class="More TrackUrl">
										<p>The best download location for this track, for example, if you got it from Soundcloud.com, but you can also get it from the artist's website, you may want to update the link to the artist's website, instead of using the SoundCloud URL.</p>
									</div>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
												<th>Add new value</th>
												<th>Delete value</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="strTrackUrl_preferred">
{foreach $track.arrTrackUrl item=trackurl}
																<option value="{$trackurl}"{if $trackurl == $track.strTrackUrl} selected="selected"{/if}>{$trackurl}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input type="text" name="strTrackUrl" class="form-control">
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default"/>
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select name="del_strTrackUrl" class="form-control">
{foreach $track.arrTrackUrl item=trackurl}
																<option value="{$trackurl}">{$trackurl}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default" />
															</span>
														</div>
													</form>
												</td>
											</tbody>
										</tr>
									</table><!-- .table .table-condensed -->
								</div><!-- col-xs-12 -->
							</div><!-- .row .bg-info -->
							<div class="row">
								<div class="col-xs-12">
									<p><strong>Artist Name:</strong></p>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
												<th>Add new value</th>
												<th>Delete value</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select name="strArtistName_preferred" class="form-control">
{foreach $track.arrArtistName item=Artistname}
																<option value="{$Artistname}"{if $Artistname == $track.strArtistName} selected="selected"{/if}>{$Artistname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default"/>
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input type="text" name="strArtistName" class="form-control">
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default"/>
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select name="del_strArtistName" class="form-control">
{foreach $track.arrArtistName item=Artistname}
																<option value="{$Artistname}">{$Artistname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- col-xs-12 -->
							</div><!-- .row -->
							<div class="row bg-info">
								<div class="col-xs-12">
									<p><strong>Artist Name Sounds:</strong></p>
									<div class="More ArtistName">
										<p>As with the Track Name Sounds, this field defines how Festival pronounces this Artist's name.</p>
									</div>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strArtistNameSounds" value="{$track.strArtistNameSounds}">
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- col-xs-12 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12">
									<p><strong>Artist URL:</strong></p>
									<div class="More ArtistUrl">
										<p>The best URL to find out most information about this artist.</p>
									</div>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
												<th>Add new value</th>
												<th>Delete value</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="strArtistUrl_preferred">
{foreach $track.arrArtistUrl item=Artisturl}
																<option value="{$Artisturl}"{if $Artisturl == $track.strArtistUrl} selected="selected"{/if}>{$Artisturl}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strArtistUrl" length="20">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="del_strArtistUrl">
{foreach $track.arrArtistUrl item=Artisturl}
																<option value="{$Artisturl}">{$Artisturl}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- col-xs-12 -->
							</div><!-- .row -->
							<div class="row bg-info">
								<div class="col-xs-12">
									<p><strong>Is this track Safe for Family or Office listening?</strong></p>
									<div class="More NSFW">
										<p>It is safe if:</p>
										<ul>
										    <li>The track does not contain any swear words or derogatory words for gender, race, preference, or if it does contain them, they are hard to make out or distinguish.</li>
										    <li>The track does not contain any obvious direct references to drug use.</li>
										    <li>The track does not contain any obvious sexual references, including suggestive sounds.</li>
										    <li>The track does not advocate crime or gun use (which is a criminal act in some countries).</li>
										</ul>
									</div>
                                                                        <form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
                                                                        <table class="table table-condensed">
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th>Set default</th>
                                                                                                <th></th>
                                                                                                <th></th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                        <tr>
                                                                                                <td>
                                                                                                        <div class="radio">
                                                                                                                <label>
                                                                                                                        <input type="radio" name="nsfw" value="false" {if $track.isNSFW == false}checked="selected"{/if}> Safe
                                                                                                                </label>
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                        <div class="radio">
                                                                                                                <label>
                                                                                                                        <input type="radio" name="nsfw" value="true" {if $track.isNSFW == true}checked="selected"{/if}> Not Safe
                                                                                                                </label>
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td>
                                                                                                        <input class="btn btn-default" type="submit" value="Go" />
                                                                                                </td>
                                                                                        </tr>
                                                                                </tbody>
                                                                        </table>
                                                                        </form>
								</div><!-- col-xs-12 -->
							</div><!-- .row .bg-info -->
							<div class="row">
								<div class="col-xs-12">
									<p><strong>Duplicate Track?</strong> Enter the TrackID of the track it is duplicated with.</p>
									<p>Be VERY CAREFUL with this - there is no undo on this!</p>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="duplicate" size="5">
															<span class="input-group-btn">
																<input type="submit" value="Go" class="btn btn-default" />
															<span class="input-group-btn">
														<div class="input-group input-group-sm">
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- col-xs-12 -->
							</div><!-- .row .bg-info -->
						</div><!-- .panel-body -->
					</div><!-- .panel -->
				</div><!-- .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
