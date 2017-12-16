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
		<link rel="stylesheet" href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/skin/blue.monday/css/jplayer.blue.monday.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits.css" />
		<link rel="stylesheet" href="{$baseURL}CSS/cchits-extra.css" />
	</head>
	<body>
		<div class="container-fluid" id="topnav">
			<div class="container inner">
				<span id="brand"><a href="{$baseURL}">{$ServiceName}</a></span>
				<div class="shows-nav">
					<ul>
						<li><a href="/about">About {$ServiceName}</a></li>
						<li><a href="#">Daily shows</a></li>
						<li><a href="#">Weekly shows</a></li>
						<li><a href="#">Monthly shows</a></li>
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
									<div class="license-icon license-by license-{$track.strIsByLicense} license-{$track.enumTrackLicense}"></div>
									<div class="license-icon license-nc license-{$track.strIsNcLicense} license-{$track.enumTrackLicense}"></div>
									<div class="license-icon license-nd license-{$track.strIsNdLicense} license-{$track.enumTrackLicense}"></div>
									<div class="license-icon license-sa license-{$track.strIsSaLicense} license-{$track.enumTrackLicense}"></div>									
									<div class="license-icon license-sp license-{$track.strIsSamplingPlusLicense} license-{$track.enumTrackLicense}"></div>									
									<div class="license-icon license-ze license-{$track.strIsZeroLicense} license-{$track.enumTrackLicense}"></div>									
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
						<div class="col col-player" id="daily">
							<header>The most recent Daily Exposure show</header>
							{include file="player2.html.tpl" player_id="1" playlist=$daily}
							<footer><a href="{$baseURL}daily">More...</a> | <a href="{$baseURL}daily/rss">Feed</a></footer>
						</div>
					</div>
					<div class="row">
						<div class="col col-player" id="weekly">
							<header>The most recent Weekly Review show</header>
							{include file="player2.html.tpl" player_id="2" playlist=$weekly}
							<footer><a href="{$baseURL}weekly">More...</a> | <a href="{$baseURL}weekly/rss">Feed</a></footer>
						</div>
					</div>	
					<div class="row">
						<div class="col col-player" id="daily">
							<header>The most recent Monthly Chart show</header>
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
		<script src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer/jquery.jplayer.js"></script>
		<script src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/add-on/jplayer.playlist.js"></script>
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
<!--
<html>
       <head>
    <meta name=viewport content="width=device-width, initial-scale=1">
               <link rel="alternate" type="application/rss+xml" href="{$baseURL}daily/rss" title="The {$ShowDaily}" />
               <link rel="alternate" type="application/rss+xml" href="{$baseURL}weekly/rss" title="The {$ShowWeekly}" />
               <link rel="alternate" type="application/rss+xml" href="{$baseURL}monthly/rss" title="The {$ShowMonthly}" />
               <script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
               <link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
               <script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.min.js"></script>
               <script type="text/javascript" src="{$baseURL}JAVASCRIPT/playlist.js"></script>
               <script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
               <script type="text/javascript">{literal}//<![CDATA[
               $(document).ready(function() {{/literal}
                       $('.inlinesparkline').sparkline();
{include file="player.js.tpl" player_id="1" playlist=$daily_player_json}
{include file="player.js.tpl" player_id="2" playlist=$weekly_player_json}
{include file="player.js.tpl" player_id="3" playlist=$monthly_player_json}
                        {literal}
               });{/literal}//]]></script>
               <title>{$ServiceName}</title>
       </head>
       <body>
               <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
               <h2>{$Slogan}</h2>
<div id="about"><a href="/about">About {$ServiceName}</a></div>
               <div id="chart">
                       <h3>The Chart</h3>
                       <table>
                               <thead>
                                       <tr>
                                               <th>Position</th>
                                               <th>Adj. Votes</th>
                                               <th>Movement</th>
                                               <th>60 Days Movement</th>
                                               <th>Track</th>
                                               <th>Status</th>
                                       </tr>
                               </thead>
                               <tbody>
{foreach $chart key=position item=track}{strip} 
                                       <tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
{include file="show_track_data.tpl"}
                                   </tr>
{/strip}{/foreach}
                                       <tr>
                                               <td colspan="6"><a href="{$baseURL}chart">More...</a></td>
                                       </tr>
                               </tbody>
                       </table>
               </div>
               <div id="daily">
                       <h3>The most recent Daily Exposure show</h3>
{include file="player.html.tpl" player_id="1" playlist=$daily}
                       <p><a href="{$baseURL}daily">More...</a> | <a href="{$baseURL}daily/rss">Feed</a></p>
               </div>
               <div id="weekly">
                       <h3>The most recent Weekly Review show</h3>
{include file="player.html.tpl" player_id="2" playlist=$weekly}
                       <p><a href="{$baseURL}weekly">More...</a> | <a href="{$baseURL}weekly/rss">Feed</a></p>
               </div>
               <div id="daily">
                       <h3>The most recent Monthly Chart show</h3>
{include file="player.html.tpl" player_id="3" playlist=$monthly}
                       <p><a href="{$baseURL}monthly">More...</a> | <a href="{$baseURL}monthly/rss">Feed</a></p>
               </div>
       </body>
</html>
