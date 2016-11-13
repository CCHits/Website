{assign var='realshow' value=$show}
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>{$ServiceName}</title>
		<link rel="stylesheet" href="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/css/bootstrap.min.css">
		<link rel="stylesheet" href="{$baseURL}CSS/cchits.css">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2 col-xs-12">
					<div class="panel panel-default">
						<div class="panel-heading">
                					<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
							<h4>{$Slogan}</h4>
						</div><!-- .panel-heading -->
						<div class="panel-body">
							<div class="row">
								<div class="col-xs-12">
									Thank you for voting for <strong>"<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>"</strong> by <strong>"<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</strong>{if $show != false} from <strong>"<a href="{$show.strShowUrl}">{$show.strShowName}</a>"</strong>.{/if}
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
							<div class="row">
								<div class="col-xs-12">
									<div class="ThisTrack">
										<h4>About this track</h4>
{include file="track_detail.tpl"}
									</div>
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
{if $realshow != false}
							<div class="row">
								<div class="col-xs-12">
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
								</div><!-- .col-xs-12 -->
							</div><!-- .row -->
{/if}
						</div><!-- .panel-body -->
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-8 .col-md-offset-2 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<script type="text/javascript"> $(function() { $('.inlinesparkline').sparkline(); }); </script> 
	</body>
</html>
