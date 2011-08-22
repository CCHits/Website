<html>
	<head>
		<link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.min.js"></script>
		<script type="text/javascript" src="{$baseURL}JAVASCRIPT/playlist.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<title>{$ServiceName}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		<img src="{$track.qrcode}" alt="QR Code for this page" />
		<h3>"<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</h3>
		<form action="{$baseURL}vote/{$track.intTrackID}?go" method="post">
  			<input type="submit" name="go" value="I like this track!" />
		</form>
		<p>This track has {$track.decVoteAdj} adjusted votes, which is <a href="{$baseURL}about#voteadj">{$track.decAdj * 100}% of</a> {$track.intVote} votes.</p>
		<p>Chart positions: <span class="inlinesparkline" values="{foreach from=$track.arrChartData item=item name=sparkline}{if not $smarty.foreach.sparkline.first},{/if}-{$item.intPositionID}{/foreach}">{foreach from=$track.arrChartData item=item name=sparkline}{if not $smarty.foreach.sparkline.first},{/if} {$item.datChart}:{$item.intPositionID}{/foreach}</span> (H:{$track.30dayhighest}, L:{$track.30daylowest})</p>
		<p>If you want to download this file, please visit the link to the track above. If that link is not working, you can download it <a href="{$track.localSource}">here</a>.</p>
		<p>This track has also been played on the following shows:</p>
		<table>
			<thead>
				<tr>
					<th>Show</th>
					<th>Votes</th>
				</tr>
			</thead>
			<tbody>
				{foreach $track.arrShows as $showData}
				{strip} 
				<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
			    	<td>{if isset($showData.intShowID)}<a href="{$baseURL}shows/{$showData.intShowID}">{/if}<span class="{$showData.enumShowType}">{$showData.strShowName}</span>{if isset($showData.intShowID)}</a>{/if}</td>
			    	<td>{$showData.decVoteAdj} (<a href="{$baseURL}about#voteadj">{$track.decAdj * 100}% of</a> {$showData.intVote})</td>
			    </tr>
				{/strip}
				{/foreach}
			</tbody>
		</table>
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script> 
</html>