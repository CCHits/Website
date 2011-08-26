<html>
	<head>
		<link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.min.js"></script>
		<script type="text/javascript" src="{$baseURL}JAVASCRIPT/playlist.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<script type="text/javascript">{literal}//<![CDATA[
		$(document).ready(function() {{/literal}
			$('.inlinesparkline').sparkline();
			{foreach from=$shows key=id item=show}
{include file="player.js.tpl" player_id=$show.intShowID playlist=$show_playlists}
			{/foreach}
		{literal}});{/literal}//]]></script>
		<title>{$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		{foreach from=$shows key=id item=show}
		<h3><a href="{$baseURL}show/{$show.intShowID}">{$show.strShowName}</a></h3>
{include file="player.html.tpl" player_id=$show.intShowID playlist=$show}
		{foreach from=$show.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
		{/foreach}
		{/foreach}
	</body>
</html>