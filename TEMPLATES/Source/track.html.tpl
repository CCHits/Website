<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<title>{$ServiceName}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
{if $user.isUploader}
		<p><a href="{$baseURL}admin/track/{$track.intTrackID}">Edit track</a></p>
{/if}
{if $user.isAdmin}
		<p><a href="{$baseURL}admin/show/?intTrackID={$track.intTrackID}">Add track to show</a></p>
{/if}
{if $track.isNSFW}
		<p><a href="{$baseURL}about#nsfw">This track may not be suitable for family or office listening.</a></p>
{/if}
		<img src="{$track.qrcode}" alt="QR Code for this page" />
		<h3>"<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</h3>
		<form action="{$baseURL}vote/{$track.intTrackID}?go" method="post">
  			<input type="submit" name="go" value="I like this track!" />
		</form>
		{include file="track_detail.tpl"}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script> 
</html>
