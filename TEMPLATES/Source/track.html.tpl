<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
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
{if not $track.isNSFW}
{if not $track.needsReview}
		<form action="{$baseURL}report/{$track.intTrackID}?go" method="post">
			<input type="submit" name="go" value="Report this track as not safe for family or work" />
		</form>
{else}
		<div><b style="color: red">This track has been reported as not safe for family or work, it will be reviewed by a moderator.</b></div>
{if $user.isAdmin}
		<br/>
		<form style="color: blue;" action="{$baseURL}review/{$track.intTrackID}?go" method="post">
			<b>
			Is this track safe for family or work ?
			<input type="radio" name="isNSFW" value="no"> Yes
			<input type="radio" name="isNSFW" value="yes" checked> No
			<input type="submit" name="go" value="Review this track" />
			</b>
		</form>
{/if}
{/if}
{/if}
		{include file="track_detail.tpl"}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script> 
</html>
