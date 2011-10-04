<html>
	<head>
		<title>List Unfinished Tracks: </title>
	</head>
	<body>
{foreach from=$tracks key=id item=track}
	<a href="{$baseURL}admin/addtrack/{$track.intProcessingID}">{$track.strTrackName} by {$track.strArtistName}</a>
{/foreach}
	</body>
</html>