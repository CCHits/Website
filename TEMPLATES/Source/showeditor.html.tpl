<html>
	<head>
		<title>Show Editor: {$ServiceName} - {$Slogan}</title>
		<link rel="stylesheet" type="text/css" href="{$baseURL}STYLE/site.css" />	
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<h3>Track Editor</h3>
		<p>On this page you will modify or create a show.</p>
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
			</tr>
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
			</tr>
{/foreach}
		</table>
		
	</body>
</html>