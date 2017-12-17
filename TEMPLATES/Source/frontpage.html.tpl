<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>{$ServiceName}</title>
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}daily/rss" title="The {$ShowDaily}" />
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}weekly/rss" title="The {$ShowWeekly}" />
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}monthly/rss" title="The {$ShowMonthly}" />
		<link rel="stylesheet" href="{$baseURL}EXTERNALS/BOOTSTRAP4/{$bootstrap4}/css/bootstrap.min.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits-extra.css" />
	</head>
	<body>
		<div class="container-fluid" id="topnav">
			<div class="container inner">
				<span id="brand"><a href="{$baseURL}">{$ServiceName}</a></span>
				<div class="shows-nav">
					<ul>
						<li><a href="{$baseURL}about">About {$ServiceName}</a></li>
						<li><a href="{$baseURL}daily">Daily shows</a></li>
						<li><a href="{$baseURL}weekly">Weekly shows</a></li>
						<li><a href="{$baseURL}monthly">Monthly shows</a></li>
					</ul>
				</div>
				<div class="socials">
					<ul>
						<li><a href="https://twitter.com/cchits">Twitter</a></li>
						<li><a href="https://www.facebook.com/cchits">Facebook</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container" id="chart">
			<div class="row row-header">
				<div class="col">
					<header>The Chart</header>
				</div>
			</div>
			<div class="row" id="main">
				<div class="col-9">
					<div class="chart-body">
						<div class="row chart-legend">
							<div class="col-1 chart-progression">
								Prog.	
							</div>
							<div class="col-2 chart-position">
								Position
							</div>
							<div class="col-4 chart-info">
								Track
							</div>
							<div class="col-3 chart-graph">
								60 days movement
							</div>
							<div class="col-2 chart-licences">
								License
							</div>
						</div>
						{foreach $chart key=position item=track}{strip} 
						<div class="row chart-track">
							<div class="col-1 chart-progression">
								{if $track.strPositionYesterday == "up"}
								<i class="fa fa-arrow-up"></i>
								{/if}
								{if $track.strPositionYesterday == "equal"}
								<i class="fa fa-arrow-right"></i>
								{/if}
								{if $track.strPositionYesterday == "down"}
								<i class="fa fa-arrow-down"></i>
								{/if}
							</div>
							<div class="col-2 chart-position">
								<div class="chart-position-current">{$position}</div>
								<div class="chart-position-before">Yesterday : {$track['arrChartData'][1]['intPositionID']}</div>
							</div>
							<div class="col-4 chart-info">
								<div class="chart-track-title">
									{$track.strTrackName}
								</div>
								<div class="chart-track-artist">
									{$track.strArtistName}
                                </div>
                                <div class="chart-position-high-low">
                                    Highest : {$track.60dayhighest}, Lowest : {$track.60daylowest}
                                </div>
							</div>
							<div class="col-3 chart-graph">
								<canvas id="graph-{$position}" style="height: 50px;"></canvas>
							</div>
							<div class="col-2 chart-licences">
								<div class="license-icons">
									<div {if $track.strIsByLicense == "active"}title="By attribution" {/if}class="license-icon license-by license-{$track.strIsByLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsNcLicense == "active"}title="Non commercial" {/if}class="license-icon license-nc license-{$track.strIsNcLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsNdLicense == "active"}title="Non derivative" {/if}class="license-icon license-nd license-{$track.strIsNdLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsSaLicense == "active"}title="Share alike" {/if}class="license-icon license-sa license-{$track.strIsSaLicense} license-{$track.enumTrackLicense}"></div>									
									<div {if $track.strIsSamplingPlusLicense == "active"}title="Sampling+" {/if}class="license-icon license-sp license-{$track.strIsSamplingPlusLicense} license-{$track.enumTrackLicense}"></div>									
									<div {if $track.strIsZeroLicense == "active"}title="CC-0" {/if}class="license-icon license-ze license-{$track.strIsZeroLicense} license-{$track.enumTrackLicense}"></div>									
								</div>
							</div>
						</div>
						{/strip}{/foreach}
						<div class="row chart-legend">
							<div class="col-1 chart-progression">
								Prog.	
							</div>
							<div class="col-2 chart-position">
								Position
							</div>
							<div class="col-4 chart-info">
								Track
							</div>
							<div class="col-3 chart-graph">
								60 days movement
							</div>
							<div class="col-2 chart-licences">
								License
							</div>
						</div>
					</div>
				</div>
				<div class="col-3">
					<div class="row">
						<div class="col">
							<div class="shows-legend">The most recent ...</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-player" id="daily">
							<header>Daily Exposure show</header>
							{include file="player2.html.tpl" player_id="1" playlist=$daily}
							<footer><a href="{$baseURL}daily">More...</a> | <a href="{$baseURL}daily/rss">Feed</a></footer>
						</div>
					</div>
					<div class="row">
						<div class="col col-player" id="weekly">
							<header>Weekly Review show</header>
							{include file="player2.html.tpl" player_id="2" playlist=$weekly}
							<footer><a href="{$baseURL}weekly">More...</a> | <a href="{$baseURL}weekly/rss">Feed</a></footer>
						</div>
					</div>	
					<div class="row">
						<div class="col col-player" id="daily">
							<header>Monthly Chart show</header>
							{include file="player2.html.tpl" player_id="3" playlist=$monthly}
							<footer><a href="{$baseURL}monthly">More...</a> | <a href="{$baseURL}monthly/rss">Feed</a></footer>
						</div>						
					</div>
				</div>
			</dv>
		</div>
		<script src="{$baseURL}EXTERNALS/JQUERY3/{$jquery3}/jquery.js"></script>
		<script src="{$baseURL}EXTERNALS/POPPERJS/{$popperjs}/popper.js"></script>
        <script src="{$baseURL}EXTERNALS/BOOTSTRAP4/{$bootstrap4}/js/bootstrap.js"></script>
		<script src="{$baseURL}EXTERNALS/CHARTJS/{$chartjs}/Chart.bundle.js"></script>
		<script src="{$baseURL}EXTERNALS/FONTAWESOME/{$fontawesome}/svg-with-js/js/fontawesome-all.js"></script>
		<script src="{$baseURL}EXTERNALS/JPLAYER29/{$jplayer29}/jplayer/jquery.jplayer.js"></script>
		<script src="{$baseURL}EXTERNALS/JPLAYER29/{$jplayer29}/add-on/jplayer.playlist.js"></script>
		{include file="show_chartjs.tpl"}
		<script type="text/javascript">{literal}//<![CDATA[
			$(document).ready(function() {{/literal}
				{include file="player2.js.tpl" player_id="1" playlist=$daily_player_json}
				{include file="player2.js.tpl" player_id="2" playlist=$weekly_player_json}
				{include file="player2.js.tpl" player_id="3" playlist=$monthly_player_json}
				{literal}
			});{/literal}//]]>
		</script>
    </body>
</html>
