<html>
	<head>
		<title>Show Editor</title>
	</head>
	<body>
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
{foreach $show.arrTracks item=track}



<!-- Do stuff here -->



{/foreach}
		</table>
	</body>
</html>