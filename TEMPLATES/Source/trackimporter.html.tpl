<!DOCTYPE html>
<html>
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
							<p>On this page, you will edit a track details before it is downloaded.</p>
{if $track.duplicateTracks != false}
							<div class="row bg-warning">
								<div class="col-xs-12">
									<p>There were suspected duplicate tracks. Please verify none of these are actually duplicated tracks.</p>
									<ul class="list-group">
{foreach from=$track.duplicateTracks item=duplicate}
										<li class="list-group-item"><a href="{$baseURL}track/{$duplicate.intTrackID}">"{$duplicate.strTrackName}" by "{$duplicate.strArtistName}"</a></li>
{/foreach}
									</ul>
								</div>
							</div>
{/if}
{if $track.intArtistID == 0}
{if count($artists) > 0}
							<div class="row bg-warning">
								<div class="col-xs-12">
							                <p>Is this previously created artist the creator of this work? Note, if you have a conflict because the artist name as supplied conflicts with another in the site, add something to the conflicting field (artist name or URL) and then edit it once you've selected the artist. This is a bit of a work-around for a flagged issue. It will be picked up in a near-future release.</p>
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							                        <select class="form-control" name="intArtistID">
{foreach $artists item=artist}
					                		            <option value="{$artist.intArtistID}">({$artist.intArtistID}) "{$artist.strArtistName}" from {$artist.strArtistUrl}</option>
{/foreach} 
							                        </select>
										<input type="submit" class="btn btn-primary" style="width: 100%;" value="Select this Artist" />
									</form>
								</div>
							</div>
{else}
							<div class="row">
								<div class="col-xs-12">
							                <p>Do you want to create the artist based on these details?</p>
							                <table class="table table-condensed">
										<tr>
											<th>Preferred Artist Name</th>
								                        <th>Variations of Artist Names</th>
											<th>Sounding of Artist's Name</th>
											<th>Preferred Artist URL</th>
											<th>Alternate URLs for the artist</th>
										</tr>
										<tr>
								                        <td>{$track.strArtistName}</td>
								                        <td>
{if count($track.arrArtistName) > 1}
								                            <ul class="list-group">
{foreach $track.arrArtistName item=Artistname name=artists}
						       	 					<li class="list-group-item">{$Artistname}</li>
{/foreach}
								                            </ul>
{else}
{foreach $track.arrArtistName item=Artistname name=artists}
								                            {$Artistname}
{/foreach}
{/if}
								                        </td>
								                        <td>{$track.strArtistNameSounds}</td>
								                        <td>{$track.strArtistUrl}</td>
											<td>
{if count($track.arrArtistUrl) > 1}
								                            <ul class="list-group">
{foreach $track.arrArtistUrl item=ArtistUrl}
 class="list-group"							                                <li class="list-group-item">{$ArtistUrl}</li>
{/foreach}
								                            </ul>
{else}
{foreach $track.arrArtistUrl item=ArtistUrl}
								                            {$ArtistUrl}
{/foreach}
{/if}
								                        </td>
										</tr>
									</table>
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							                        <input type="hidden" name="action" value="createartist">
										<input type="submit" value="Create Artist From These Values" />
									</form>
								</div>
							</div>
{/if}
{/if}
{if isset($error)}
							<div class="row bg-danger">
								<div class="col-xs-12">
							
									<p>There was an error.{if $error != ''} The error was: "{$error->getMessage()}"{/if}</p>
{if $error->getCode() == 243}
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
										<input type="hidden" name="forceTrackNameDuplicate" value="true">
										<input type="submit" class="btn btn-primary" style="width: 100%;" value="Force duplicate track names" />
									</form>
{/if}
{if $error->getCode() == 242}
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
										<input type="hidden" name="forceTrackUrlDuplicate" value="true">
										<input type="submit" class="btn btn-primary" style="width: 100%;" value="Force duplicate track URLs" />
									</form>
{/if}
{if $error->getCode() == 241}
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
										<input type="hidden" name="forceMD5Duplicate" value="true">
										<input type="submit" class="btn btn-primary" style="width: 100%;" value="Force duplicate MD5 hashes" />
									</form>
{/if}
								</div>
							</div>
{/if}
{if isset($errorcode)}
							<div class="row bg-danger">
								<div class="col-xs-12">
								        <p>There was an error in the import. The errorcode is {$errorcode}.</p>
								</div>
							</div>
{/if}
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="strTrackName_preferred">
{foreach $track.arrTrackName item=trackname}
																<option value="{$trackname}"{if $trackname == $track.strTrackName} selected="selected"{/if}>{$trackname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input type="text" class="form-control" name="strTrackName" length="20">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
									</table>
								</div>
							</div>
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input type="text" class="form-control" name="strTrackNameSounds" length="20" value="{$track.strTrackNameSounds}">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>	
								</div>
							</div>
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strTrackUrl" length="20">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="del_strTrackUrl">
{foreach $track.arrTrackUrl item=trackurl}
																<option value="{$trackurl}">{$trackurl}</option>
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
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<p><strong>Artist Name:</strong></p>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
												<th>Add new valuer</th>
												<th>Delete value</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="strArtistName_preferred">
{foreach $track.arrArtistName item=Artistname}
																<option value="{$Artistname}"{if $Artistname == $track.strArtistName} selected="selected"{/if}>{$Artistname}</option>
{/foreach} 
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>	
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strArtistName" length="20">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="del_strArtistName">
{foreach $track.arrArtistName item=Artistname}
																<option value="{$Artistname}">{$Artistname}</option>
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
									</table>
								</div>
							</div>
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strArtistNameSounds" length="20" value="{$track.strArtistNameSounds}">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										<tbody>
									</table>
								</div>
							</div>
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<input class="form-control" type="text" name="strArtistUrl" length="20">
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															<span class="input-group-btn">
														</div>
													</form>
												</td>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="del_strArtistUrl">
{foreach $track.arrArtistUrl item=Artisturl}
																<option value="{$Artisturl}">{$Artisturl}</option>
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
									</table>
								</div>
							</div>
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
									<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<p><strong>License</strong></p>
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
														<div class="input-group input-group-sm">
															<select class="form-control" name="enumTrackLicense">
																<option value="cc-sa"{if $track.enumTrackLicense == "cc-sa"} selected="selected"{/if}>cc-sa</option>
																<option value="cc-nc"{if $track.enumTrackLicense == "cc-nc"} selected="selected"{/if}>cc-nc</option>
																<option value="cc-nd"{if $track.enumTrackLicense == "cc-nd"} selected="selected"{/if}>cc-nd</option>
																<option value="cc-nc-sa"{if $track.enumTrackLicense == "cc-nc-sa"} selected="selected"{/if}>cc-nc-sa</option>
																<option value="cc-nc-nd"{if $track.enumTrackLicense == "cc-nc-nd"} selected="selected"{/if}>cc-nc-nd</option>
																<option value="cc-by"{if $track.enumTrackLicense == "cc-by"} selected="selected"{/if}>cc-by</option>
																<option value="cc-by-sa"{if $track.enumTrackLicense == "cc-by-sa"} selected="selected"{/if}>cc-by-sa</option>
																<option value="cc-by-nc"{if $track.enumTrackLicense == "cc-by-nc"} selected="selected"{/if}>cc-by-nc</option>
																<option value="cc-by-nd"{if $track.enumTrackLicense == "cc-by-nd"} selected="selected"{/if}>cc-by-nd</option>
																<option value="cc-by-nc-sa"{if $track.enumTrackLicense == "cc-by-nc-sa"} selected="selected"{/if}>cc-by-nc-sa</option>
																<option value="cc-by-nc-nd"{if $track.enumTrackLicense == "cc-by-nc-nd"} selected="selected"{/if}>cc-by-nc-nd</option>
																<option value="cc-sampling+"{if $track.enumTrackLicense == "cc-sampling+"} selected="selected"{/if}>cc-sampling+</option>
																<option value="cc-nc-sampling+"{if $track.enumTrackLicense == "cc-nc-sampling+"} selected="selected"{/if}>cc-nc-sampling+</option>
																<option value="cc-0"{if $track.enumTrackLicense == "cc-0"} selected="selected"{/if}>cc-0</option>
																<option value="none selected"{if $track.enumTrackLicense == "none selected"} selected="selected"{/if}>None Selected</option>
															</select>
															<span class="input-group-btn">
																<input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
													</form>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row bg-info">
								<div class="col-xs-12">
									<p><strong>File upload</strong></p>
                                                    			Replace file with new or edited version.
									<table class="table table-condensed">
										<thead>
											<tr>
												<th>Set default</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
							                                                <form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
                                                    								<div class="input-group input-group-sm">
															<input class="form-control" type="file" name="file" size="30">
															<span class="input-group-btn">
									                                                    <input class="btn btn-default" type="submit" value="Go" />
															</span>
														</div>
							                                                </form>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div><!-- .panel-body -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-6 .col-md-offset-3 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>
