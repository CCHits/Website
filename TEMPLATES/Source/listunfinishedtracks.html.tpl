<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<title>List Unfinished Tracks: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
                <p><a href="{$baseURL}admin">Go back to the admin page.</a></p>
		<h3>My unfinished tracks</h3>
		<ul>
{foreach from=$tracks key=id item=track}
			<li><a href="{$baseURL}admin/addtrack/{$track.intProcessingID}">{$track.strTrackName} by {$track.strArtistName}</a> (<a href="{$baseURL}admin/deltrack/{$track.intProcessingID}">Delete</a>)</li>
{/foreach}
		</ul>
	</body>
</html>