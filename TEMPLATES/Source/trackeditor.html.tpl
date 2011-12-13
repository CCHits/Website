<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<title>Track Editor: {$ServiceName} - {$Slogan}</title>
		<link rel="stylesheet" type="text/css" href="{$baseURL}STYLE/site.css" />	
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>Track Editor</h3>
		<p>On this page, you will edit a previously submitted track.</p>
{if isset($error) and $error}
		<p>There was an error.</p>
{/if}
		<form method="post" action="{$baseURL}admin/show">
			Associate this track with a show?
			<input type="hidden" name="intTrackID" value="{$track.intTrackID}">
			<input type="submit" value="Go" />
		</form>
		<table>
			<thead>
				<tr>
					<th>Heading</th>
					<th>Set default</th>
					<th>Add new value</th>
					<th>Delete value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>
						<p>Track Name:</p>
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
					</th>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="strTrackName_preferred">
{foreach $track.arrTrackName item=trackname}
								<option value="{$trackname}"{if $trackname == $track.strTrackName} selected="selected"{/if}>{$trackname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strTrackName" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="del_strTrackName">
{foreach $track.arrTrackName item=trackname}
								<option value="{$trackname}">{$trackname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Track Name Sounds:</p>
						<div class="More TrackNameSounds">
							<p>The way that festival will try to pronounce this track's name. It will be copied from the track name unless otherwise specified.</p>
							<p>Some examples of good entries:</p>
							<ul>
								<li>Sal-vee-yah (instead of "Salvia")</li>
								<li>The blue mix of Stop (instead of "Stop (Blue Mix)")</li> 
							</ul>
						</div>
					</th>
					<td colspan=3>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strTrackNameSounds" length="20" value="{$track.strTrackNameSounds}">
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Track URL:</p>
						<div class="More TrackUrl">
							<p>The best download location for this track, for example, if you got it from Soundcloud.com, but you can also get it from the artist's website, you may want to update the link to the artist's website, instead of using the SoundCloud URL.</p>
						</div>
					</th>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="strTrackUrl_preferred">
{foreach $track.arrTrackUrl item=trackurl}
								<option value="{$trackurl}"{if $trackurl == $track.strTrackUrl} selected="selected"{/if}>{$trackurl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strTrackUrl" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="del_strTrackUrl">
{foreach $track.arrTrackUrl item=trackurl}
								<option value="{$trackurl}">{$trackurl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>Artist Name:</th>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="strArtistName_preferred">
{foreach $track.arrArtistName item=Artistname}
								<option value="{$Artistname}"{if $Artistname == $track.strArtistName} selected="selected"{/if}>{$Artistname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strArtistName" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="del_strArtistName">
{foreach $track.arrArtistName item=Artistname}
								<option value="{$Artistname}">{$Artistname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Artist Name Sounds:</p>
						<div class="More ArtistName">
							<p>As with the Track Name Sounds, this field defines how Festival pronounces this Artist's name.</p>
						</div>
					</th>
					<td colspan="3">
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strArtistNameSounds" length="20" value="{$track.strArtistNameSounds}">
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Artist URL:</p>
						<div class="More ArtistUrl">
							<p>The best URL to find out most information about this artist.</p>
						</div>
					</th>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="strArtistUrl_preferred">
{foreach $track.arrArtistUrl item=Artisturl}
								<option value="{$Artisturl}"{if $Artisturl == $track.strArtistUrl} selected="selected"{/if}>{$Artisturl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="strArtistUrl" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<select name="del_strArtistUrl">
{foreach $track.arrArtistUrl item=Artisturl}
								<option value="{$Artisturl}">{$Artisturl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Is this track Safe for Family or Office listening?</p>
						<div class="More NSFW">
							<p>It is safe if:</p>
							<ul>
							    <li>The track does not contain any swear words or derogatory words for gender, race, preference, or if it does contain them, they are hard to make out or distinguish.</li>
							    <li>The track does not contain any obvious direct references to drug use.</li>
							    <li>The track does not contain any obvious sexual references, including suggestive sounds.</li>
							    <li>The track does not advocate crime or gun use (which is a criminal act in some countries).</li>
						    </ul>
					    </div>
					</th>
					<td colspan=3>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							Safe <input type="radio" name="nsfw" value="false" {if $track.isNSFW == false}checked="selected"{/if}><br />
							Not Safe<input type="radio" name="nsfw" value="true" {if $track.isNSFW == true}checked="selected"{/if}>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>
						<p>Duplicate Track? Enter the TrackID of the track it is duplicated with.</p>
						<p>Be VERY CAREFUL with this - there is no undo on this!</p>
					</th>
					<td colspan=3>
						<form method="post" action="{$baseURL}admin/track/{$track.intTrackID}">
							<input type="text" name="duplicate" size="5">
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
