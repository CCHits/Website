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
{include file="player.js.tpl" player_id=1 playlist=$playlist_json}
		{literal}});{/literal}//]]></script>
		<title>{$ServiceName} - {$Slogan}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
{include file="player.html.tpl" player_id=1 playlist=$shows}
		{foreach from=$shows key=id item=show}
		<h3><a href="{$baseURL}show/{$show.intShowID}">{$show.strShowName}</a></h3>
		{foreach from=$show.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
		{/foreach}
		{/foreach}
	</body>
</html>
