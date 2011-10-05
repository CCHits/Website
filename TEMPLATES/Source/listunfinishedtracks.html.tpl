<html>
	<head>
		<title>List Unfinished Tracks: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>My unfinished tracks</h3>
		<ul>
{foreach from=$tracks key=id item=track}
			<li><a href="{$baseURL}admin/addtrack/{$track.intProcessingID}">{$track.strTrackName} by {$track.strArtistName}</a> (<a href="{$baseURL}admin/deltrack/{$track.intProcessingID}">Delete</a>)</li>
{/foreach}
		</ul>
	</body>
</html>