{extends file="partials/_layout.html.tpl"}
{block name=title}About: {$ServiceName} - {$Slogan}{/block}
{block name=content}
	<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
	<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.min.js"></script>
	{if isset($playlist.player_data.mp3) or isset($playlist.player_data.oga) or isset($playlist.player_data.m4a)}
		{include file="player.js.tpl" player_id="1" playlist=$playlist_json}
	{/if}
	<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
	<h2>{$Slogan}</h2>
	<h3><a href="{$show.strShowUrl}">{$show.strShowName}</a></h3>
	{if isset($playlist.player_data.mp3) or isset($playlist.player_data.oga) or isset($playlist.player_data.m4a)}
		{include file="player.html.tpl" player_id=$show.intShowID playlist=$show}
	{/if}
	{if isset($show.arrTracks) and count($show.arrTracks) > 0}
		{foreach from=$show.arrTracks item=track}
			<form action="{$baseURL}vote/{$track.intTrackID}/{$show.intShowID}?go" method="post">
				<p><a href="{$baseURL}track/{$track.intTrackID}"><img src="{$track.qrcode}" alt="QR Code for this track" /></a> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
			</form>
		{/foreach}
	{/if}
{/block}