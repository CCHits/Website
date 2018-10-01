<!DOCTYPE html>
<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY3/{$jquery3}/jquery.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.js"></script>
		<link rel="stylesheet" href="{$baseURL}EXTERNALS/BOOTSTRAP4/{$bootstrap4}/css/bootstrap.min.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits-extra.css" />
		<style>
			.table {
				width: initial;
			}
			a:hover {
				background-color: initial;
				color: initial;
				text-decoration: underline;
			}
			a {
				text-decoration: underline;
			}
		</style>
		<title>{$ServiceName}</title>
	</head>
	<body>
	<div class="container-fluid">

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
		<div class="col-12 col-md-3">
			<div class="row">
				<div class="col col-player" id="single">
					{include file="player2.html.tpl" player_id="1" playlist=$single_player_json}
				</div>
			</div>
		</div>
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
	</div>
		<script type="text/javascript">
			$(function() {
				$('.inlinesparkline').sparkline();
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				{include file="player2.js.tpl" player_id="1" playlist=$single_player_json}
            });
		</script>
		<script src="{$baseURL}EXTERNALS/FONTAWESOME/{$fontawesome}/svg-with-js/js/fontawesome-all.js"></script>
		<script src="{$baseURL}EXTERNALS/JPLAYER29/{$jplayer29}/jplayer/jquery.jplayer.js"></script>
		<script src="{$baseURL}EXTERNALS/JPLAYER29/{$jplayer29}/add-on/jplayer.playlist.js"></script>
	</body>
</html>
