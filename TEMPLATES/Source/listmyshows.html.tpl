<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name=viewport content="width=device-width, initial-scale=1">
		<title>List All My Shows: {$ServiceName} - {$Slogan}</title>
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
							<h4>My shows <small><a href="{$baseURL}admin">Go back to the admin page.</a></small></h4>
{if isset($shows) and is_array($shows) and count($shows) > 0}
							<ul class="list-group">
{foreach from=$shows key=id item=show}
								<li class="list-group-item"><a href="{$baseURL}admin/show/{$show.intShowID}">{$show.strShowName}</a> {if $show.countTracks == 0}(<a href="{$baseURL}admin/delshow/{$show.intShowID}">Delete</a>){else}({$show.countTracks} tracks){/if}</li>
{/foreach}
							</ul>
{else}
					                <p>No shows</p>
{/if}
						</div><!-- .panel-body -->
{if isset($shows) and is_array($shows) and count($shows) > 0}
						<div class="panel-footer">
							<nav>
								<ul class="pager">
{if $previous_page == true}						<li class="previous"><a href="{$arrUri.no_params}{if isset($arrUri.parameters.page) and $arrUri.parameters.page - 1 > 0}?page={$arrUri.parameters.page - 1}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}{else}{if isset($arrUri.parameters.size)}?size={$arrUri.parameters.size}{/if}{/if}"><span>&larr;</span> Previous page</a></li>
{/if}
{if $next_page == true}
									<li class="next"><a href="{$arrUri.no_params}?page={if isset($arrUri.parameters.page)}{$arrUri.parameters.page + 1}{else}1{/if}{if isset($arrUri.parameters.size)}&size={$arrUri.parameters.size}{/if}">Next page <span>&rarr;</span></a></li>
{/if}
								</ul>
							</nav>
						</div><!-- .panel-footer -->
{/if}
					</div><!-- .panel .panel-default -->
				</div><!-- .col-md-6 .col-md-offset-3 .col-xs-12 -->
			</div><!-- .row -->
		</div><!-- .container -->
		<script type="text/javascript" src="{$baseURL}EXTERNALS/JQUERY/{$jquery}/jquery.min.js"></script>
                <script type="text/javascript" src="{$baseURL}EXTERNALS/BOOTSTRAP/{$bootstrap}/js/bootstrap.min.js"></script>
	</body>
</html>

