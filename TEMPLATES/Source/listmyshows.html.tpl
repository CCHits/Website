<html>
	<head>
		<title>List All My Shows: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>My shows</h3>
{if isset($shows) and is_array($shows) and count($shows) > 0}
		<ul>
{foreach from=$shows key=id item=show}
			<li><a href="{$baseURL}admin/show/{$show.intShowID}">{$show.strShowName}</a> {if $show.countTracks == 0}(<a href="{$baseURL}admin/delshow/{$show.intShowID}">Delete</a>){else}({$show.countTracks} tracks){/if}</li>
{/foreach}
		</ul>
{else}
                <p>No shows</p>
{/if}
	</body>
</html>

