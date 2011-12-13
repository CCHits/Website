{assign var='realshow' value=$show}
<html>
	<head>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<title>{$ServiceName}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
		<h3>Thank you for voting for "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"{if $show != false} from "<a href="{$show.strShowUrl}">{$show.strShowName}</a>".{/if}</h3>
		<div class="ThisTrack">
			<h4>About this track</h4>
{include file="track_detail.tpl"}
		</div>
{if $realshow != false}
		<div class="OtherShowTracks">
			<h4>Other tracks on that show</h4>
{assign var='before' value='true'}
{foreach from=$realshow.arrTracks item=track name=tracks}
{if $track.intTrackID != $arrUri.path_items.1}
{if $smarty.foreach.tracks.first}
			<h5>Before the track you've voted for</h5>
{/if}
{include file="show_track_votelinks.tpl"}
{else}
{assign var='before' value='false'}
			<h5>After the track you've voted for</h5>
{/if}
{/foreach}
		</div>
{/if}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script> 
</html>
