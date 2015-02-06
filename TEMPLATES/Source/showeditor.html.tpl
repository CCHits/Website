<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<title>Show Editor: {$ServiceName} - {$Slogan}</title>
		<link rel="stylesheet" type="text/css" href="{$baseURL}STYLE/site.css" />	
	</head>
	<body>
		<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
                <p><a href="{$baseURL}admin">Go back to the admin page.</a></p>
		<h3>Show Editor</h3>
		<p>On this page you will modify or create a show. (<a href="{$baseURL}admin/listshows">Go to your shows</a>)</p>
{if isset($error) and $error}
		<p>There was an error.</p>
{/if}
		<table>
			<tr>
				<td>Show Name</td>
				<td>
					<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="text" name="strShowName" value="{$show.strShowName}">
						<input type="submit" value="Go" />
					</form>
				</td>
			</tr>
			<tr>
				<td>Show URL</td>
				<td>
					<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="text" name="strShowUrl" value="{$show.strShowUrl}">
						<input type="submit" value="Go" />
					</form>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<th>Track Position</th>
				<th>Track Name</th>
				<th>Artist</th>
                                <th>Vote URL</th>
			</tr>
{if isset($show.arrTracks) and count($show.arrTracks) > 0}
{foreach from=$show.arrTracks key=key item=track name=tracks}
			<tr>
				<td>{$key}
					<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="hidden" name="moveup" value="{$track.intTrackID}">
						<input type="submit" value="&uarr;"{if $smarty.foreach.tracks.first} disabled="disabled"{/if} />
					</form>
					<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="hidden" name="movedown" value="{$track.intTrackID}">
						<input type="submit" value="&darr;"{if $smarty.foreach.tracks.last} disabled="disabled"{/if} />
					</form>
					<form class="inline" method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="hidden" name="remove" value="{$track.intTrackID}">
						<input type="submit" value="&#10007" />
					</form>
					<form class="inline" method="get" action="{$baseURL}admin/track/{$track.intTrackID}">
						<input type="submit" value="Edit" />
					</form>
				</td>
				<td>{$track.strTrackName}</td>
				<td>{$track.strArtistName}</td>
                                <td>{$baseURL}vote/{$track.intTrackID}/{$show.intShowID}</td>
			</tr>
{/foreach}
{/if}
		</table>
		
	</body>
</html>
