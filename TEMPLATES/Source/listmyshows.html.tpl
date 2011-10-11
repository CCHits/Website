<html>
	<head>
		<title>List Unfinished Tracks: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>My shows</h3>
		<ul>
{foreach from=$shows key=id item=show}
			<li><a href="{$baseURL}admin/show/{$show.intShowID}">{$show.strShowName}</a> {if $show.countTracks == 0}(<a href="{$baseURL}admin/delshow/{$show.intShowID}">Delete</a>){else}({$show.countTracks} tracks){/if}</li>
{/foreach}
		</ul>
	</body>
</html>

