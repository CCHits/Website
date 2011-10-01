<html>
	<head>
		<title>Add Track To Show</title>
	</head>
	<body>
		<p>Associate track "{$track.strTrackName}" by "{$track.strArtistName}" to one of the following shows:</p>
		<table>
			<tr>
				<th>Show Name</th>
				<th>Associate?</th>
			</tr>
			<tr>
				<form method="post" action="{$baseURL}admin/addshow/">
					<td>Show Url: <input type="text" name="strShowUrl" size="15"> Show Name (Optional): <input type="text" name="strShowName" size="15"></td>
					<td><input type="submit" value="Go" /></td>
				</form>
			</tr>
{foreach from=$shows item=show name=shows}
			<tr>
				<td>{$show.strShowName}</td>
				<td>
					<form method="post" action="{$baseURL}admin/show/{$show.intShowID}">
						<input type="hidden" name="intTrackID" value="{$track.intTrackID}">
						<input type="submit" value="Go" />
					</form>
				</td>
			</tr>
{/foreach}
		</table>
	</body>
</html>