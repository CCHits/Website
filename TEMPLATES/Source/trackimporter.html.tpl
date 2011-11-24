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
		<p>On this page, you will edit a track details before it is downloaded.</p>
{if $track.duplicateTracks != false}
		<p>There were suspected duplicate tracks. Please verify none of these are actually duplicated tracks.</p>
		<ul>
{foreach from=$track.duplicateTracks item=$duplicate}
			<li><a href="{$baseURL}track/{$duplicate.intTrackID}">"{$duplicate.strTrackName}" by "{$duplicate.strArtistName}"</a></li>
{/foreach}
		</ul>
{/if}
{if $track.intArtistID == 0}
{if count($artists) > 0}
                <p>Is this previously created artist the creator of this work?</p>
		<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
                        <select name="intArtistID">
{foreach $artists item=artist}
                            <option value="{$artist.intArtistID}">"{$artist.strArtistName}" from {$artist.strArtistUrl}</option>
{/foreach} 
                        </select>
			<input type="submit" value="Select this Artist" />
		</form>
{else}
                <p>Do you want to create the artist based on these details?</p>
                <table>
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
                            <ul>
{foreach $track.arrArtistName item=Artistname name=artists}
                                <li>{$Artistname}</li>
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
                            <ul>
{foreach $track.arrArtistUrl item=ArtistUrl}
                                <li>{$ArtistUrl}</li>
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
{/if}
{/if}
{if isset($error)}
		<p>There was an error.{if $error != ''} The error was: "{$error->getMessage()}"{/if}</p>
{if $error->getCode() == 243}
		<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
			<input type="hidden" name="forceTrackNameDuplicate" value="true">
			<input type="submit" value="Force duplicate track names" />
		</form>
{/if}
{if $error->getCode() == 242}
		<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
			<input type="hidden" name="forceTrackUrlDuplicate" value="true">
			<input type="submit" value="Force duplicate track URLs" />
		</form>
{/if}
{if $error->getCode() == 241}
		<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
			<input type="hidden" name="forceMD5Duplicate" value="true">
			<input type="submit" value="Force duplicate MD5 hashes" />
		</form>
{/if}
{/if}
{if isset($errorcode)}
        <p>There was an error in the import. The errorcode is {$errorcode}.</p>
{/if}
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<select name="strTrackName_preferred">
{foreach $track.arrTrackName item=trackname}
								<option value="{$trackname}"{if $trackname == $track.strTrackName} selected="selected"{/if}>{$trackname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<input type="text" name="strTrackName" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<select name="strTrackUrl_preferred">
{foreach $track.arrTrackUrl item=trackurl}
								<option value="{$trackurl}"{if $trackurl == $track.strTrackUrl} selected="selected"{/if}>{$trackurl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<input type="text" name="strTrackUrl" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<select name="strArtistName_preferred">
{foreach $track.arrArtistName item=Artistname}
								<option value="{$Artistname}"{if $Artistname == $track.strArtistName} selected="selected"{/if}>{$Artistname}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<input type="text" name="strArtistName" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<select name="strArtistUrl_preferred">
{foreach $track.arrArtistUrl item=Artisturl}
								<option value="{$Artisturl}"{if $Artisturl == $track.strArtistUrl} selected="selected"{/if}>{$Artisturl}</option>
{/foreach} 
							</select>
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<input type="text" name="strArtistUrl" length="20">
							<input type="submit" value="Go" />
						</form>
					</td>
					<td>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
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
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							Safe <input type="radio" name="nsfw" value="false" {if $track.isNSFW == false}checked="selected"{/if}><br />
							Not Safe<input type="radio" name="nsfw" value="true" {if $track.isNSFW == true}checked="selected"{/if}>
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>License
					</th>
					<td colspan=3>
						<form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
							<select name="enumTrackLicense">
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
							<input type="submit" value="Go" />
						</form>
					</td>
				</tr>
				<tr>
					<th>File upload
					</th>
					<td colspan=3>
                                                <form method="post" action="{$baseURL}admin/addtrack/{$track.intProcessingID}">
                                                    Replace file with new or edited version: <input type="file" name="file" size="30"><br />
                                                    <input type="submit" value="Go" />
                                                </form>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>