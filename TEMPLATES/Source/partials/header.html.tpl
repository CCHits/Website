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
		<script src="{$baseURL}EXTERNALS/JQUERY3/{$jquery3}/jquery.js"></script>
	</head>
	<body>
		<div class="container-fluid" id="topnav">
			<div class="container">
				<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
					<a class="navbar-brand" href="{$baseURL}">{$ServiceName}</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<div class="navbar-nav mr-auto">
							<a class="nav-item nav-link" href="{$baseURL}about">About {$ServiceName}</a>
							<a class="nav-item nav-link" href="{$baseURL}daily">Daily shows</a>
							<a class="nav-item nav-link" href="{$baseURL}weekly">Weekly shows</a>
							<a class="nav-item nav-link" href="{$baseURL}monthly">Monthly shows</a>
							<a class="nav-item nav-link" href="{$baseURL}statistics">Stats</a>
							<a class="nav-item nav-link" href="{$baseURL}developer">Developer</a>
						</div>
						<div class="navbar-nav">
							<a class="nav-item nav-link" href="https://twitter.com/cchits">Twitter</a>
							<a class="nav-item nav-link" href="https://www.facebook.com/cchits">Facebook</a>
						</div>
					</div>
				</nav>				
			</div>
		</div>
