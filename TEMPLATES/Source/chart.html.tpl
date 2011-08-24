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
		<table>
			<thead>
				<tr>
					<th>Position</th>
					<th>Track</th>
					<th>60 Days Of Movement</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$chart item=track key=id name=chart}
				{strip} 
				<tr class="{cycle values="row_odd,row_even"}{if not $smarty.foreach.shows.first and $smarty.foreach.shows.iteration <= $smarty.foreach.shows.total - 3} more_rows{/if}">
			    	<td>{$id} {$track.strPositionYesterday}</td>
			    	<td>"<a href="{$baseURL}track/{$track.intTrackID}">{$track.strTrackName}</a>" by "{$track.strArtistName}"</td>
			    	<td>{include file='sparkline.tpl'}</td>
			    </tr>
				{/strip}
				{/foreach}
			</tbody>
		</table>
		{if $previous_page == true}<a href="{$arrUri.no_params}{if $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={$arrUri.parameters.page + 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
	</body>
	<script type="text/javascript">
		$(function() {
			$('.inlinesparkline').sparkline();
		});
	</script>
</html>