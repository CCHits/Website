<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<title>Changes: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
		<div id="changes">
			<h3>Changes on {$changes.strChangeDate}</h3>
{foreach from=$changes.tracks key=trackid item=track}
			<h4><a href="{$baseURL}track/{$track.intTrackID}">{$track.strTrackName} by {$track.strArtistName}</a></h4>
			<p>Due to:</p>
			<ul>
{if isset($track.reasons.vote)}				<li>This track received {$track.reasons.vote} vote{if ($track.reasons.vote > 0)}s{/if} today</li>{/if}
{if isset($track.reasons.show)}				<li>This track was listed in {$track.reasons.show} show{if ($track.reasons.show > 0)}s{/if} today</li>{/if}
{if isset($track.reasons.move)}				<li>This track moved {if ($track.reasons.move.from < $track.reasons.move.to)}down{else}up{/if} in the charts from {$track.reasons.move.from} to {$track.reasons.move.to}</li>{/if}
			</ul>
{/foreach}
		</div>
	</body>
</html>
