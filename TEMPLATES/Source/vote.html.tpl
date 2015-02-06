<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<title>{$ServiceName}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
		<h3>Do you like {$track.strTrackName} by {$track.strArtistName}?{if $show != false} You're voting on it from when it was on "{$show.strShowName}".{/if}</h3>
		<form action="?go" method="post">
  			<input type="submit" name="go" value="I like it!" />
		</form>
	</body>
</html>
