<html>
	<head>
		<link href="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jquery.jplayer.js"></script>
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY.SPARKLINE/{$jquerysparkline}/jquery.sparkline.min.js"></script>
		<title>{$ServiceName} - {$Slogan}</title>
	</head>
	<body>
		<h1>Welcome to {$ServiceName}</h1>
		<h2>{$Slogan}</h2>
		{if $previous_page == true}<a href="{$arrUri.no_params}{if $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={$arrUri.parameters.page + 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
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
{foreach $chart as $position=>$track}{strip} 
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
		{if $previous_page == true}<a href="{$arrUri.no_params}{if $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={$arrUri.parameters.page + 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script>
</html>