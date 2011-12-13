<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<title>Administration: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
{if $user.isUploader = 1}
		<form method="post" action="{$baseURL}admin/addtrack" enctype="multipart/form-data">
			Track URL: <input type="text" name="trackurl" size="30"><br />
			AND/OR<br />
			File: <input type="file" name="file" size="30"><br />
			<input type="submit" value="Retrieve or Upload a track."></input>
			<p>Acceptable source domains are: alonetone.com, ccmixter.org, freemusicarchive.org, jamendo.com, macjams.com, riffworld.com, sectionz.com, soundcloud.com, sutros.com, vimeo.com</p>
		</form>
		<form method="post" action="{$baseURL}admin/listtracks">
			<input type="submit" value="Show a list of outstanding unprocessed tracks."></input>
		</form>
{/if}
{if $user.isAdmin = 1}
		<form method="post" action="{$baseURL}admin/addshow">
			Show Notes URL: <input type="text" name="strShowUrl" size="30"><br />
			Show Name (optional): <input type="text" name="strShowName" size="30"><br />
			<input type="submit" value="Create a new show."></input>
		</form>
		<form method="post" action="{$baseURL}admin/listshows">
			<input type="submit" value="Show a list of the shows I created."></input>
		</form>
{/if}
		<form method="post" action="{$baseURL}admin/basicauth">
			<input type="submit" value="Amend my scripting credentials (power users only)."></input>
		</form>	
	</body>
</html>
