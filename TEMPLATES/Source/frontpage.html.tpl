<html>
	<head>
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}/daily/rss" title="The {$ShowDaily}" />
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}/weekly/rss" title="The {$ShowWeekly}" />
		<link rel="alternate" type="application/rss+xml" href="{$baseURL}/monthly/rss" title="The {$ShowMonthly}" />
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
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<div id="chart">
			<h3>The Chart</h3>
			<table>
				<thead>
					<tr>
						<th>Position</th>
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
						<td colspan="3"><a href="{$baseURL}chart">More...</a></td>
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