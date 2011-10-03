<html>
	<head>
		<link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.min.js"></script>
{if isset($playlist.player_data.mp3) or isset($playlist.player_data.ogg)}{include file="player.js.tpl" player_id="1" playlist=$playlist_json}{/if}
		<title>{$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3><a href="{$show.intShowID}">{$show.strShowName}</a></h3>
{if isset($playlist.player_data.mp3) or isset($playlist.player_data.ogg)}{include file="player.html.tpl" player_id=$show.intShowID playlist=$show}{/if}
		{foreach from=$show.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}/{$show.intShowID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
		{/foreach}
	</body>
</html>