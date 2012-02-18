<html>
	<head>
		<title>List All My Shows: {$ServiceName} - {$Slogan}</title>
	</head>
	<body>
                <h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
		<h2>{$Slogan}</h2>
                <p><a href="{$baseURL}admin">Go back to the admin page.</a></p>
		<h3>My shows</h3>
{if isset($shows) and is_array($shows) and count($shows) > 0}
		{if $previous_page == true}<a href="{$arrUri.no_params}{if isset($arrUri.parameters.page) and $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}">&lt;- Previous page</a>{/if}
		{if $next_page == true}<a href="{$arrUri.no_params}?page={if isset($arrUri.parameters.page)}{$arrUri.parameters.page + 1}{else}1{/if}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page -&gt;</a>{/if}
		<ul>
{foreach from=$shows key=id item=show}
			<li><a href="{$baseURL}admin/show/{$show.intShowID}">{$show.strShowName}</a> {if $show.countTracks == 0}(<a href="{$baseURL}admin/delshow/{$show.intShowID}">Delete</a>){else}({$show.countTracks} tracks){/if}</li>
{/foreach}
		</ul>
{else}
                <p>No shows</p>
{/if}
	</body>
</html>

