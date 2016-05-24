<html>
	<head>
    <meta name=viewport content="width=device-width, initial-scale=1">
		<link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<title>Chart: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
		{if $previous_page == true}<a href="{$arrUri.no_params}{if isset($arrUri.parameters.page) and $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={if isset($arrUri.parameters.page)}{$arrUri.parameters.page + 1}{else}1{/if}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
		<div id="chart">
			<h3>The Chart for {$chart.strChartDate}</h3>
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
{if isset($chart) and isset($chart.position)}
{foreach from=$chart.position key=position item=track}
{strip} 
					<tr bgcolor="{cycle values="#eeeeee,#dddddd"}">
						{include file="show_track_data.tpl"}
				    </tr>
{/strip}
{/foreach}
{/if}
				</tbody>
			</table>
		</div>
		{if $previous_page == true}<a href="{$arrUri.no_params}{if $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={$arrUri.parameters.page + 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script>
</html>
