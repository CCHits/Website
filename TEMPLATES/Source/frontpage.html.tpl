{include file="partials/header.html.tpl"}
		<div class="container" id="chart">
			<div class="row row-header">
				<div class="col">
					<header>The Chart</header>
				</div>
			</div>
			<div class="row" id="main">
				<div class="col-12 col-md-9">
					<div class="chart-body">
						<div class="row chart-legend d-none d-sm-flex">
							<div class="d-none d-sm-flex col-sm-2 col-md-2 col-lg-1 col-xl-1 chart-progression">
								Prog.	
							</div>
							<div class="col-4 col-sm-2 col-md-2 col-lg-2 col-xl-2 chart-position">
								Position
							</div>
							<div class="col-8 col-sm-8 col-md-8 col-lg-4 col-xl-4 chart-info">
								Track
							</div>
							<div class="col-12 col-sm-9 col-md-9 col-lg-5 col-xl-3 chart-graph">
								60 days movement
							</div>
							<div class="col-12 col-sm-3 col-md-3 col-lg-4 offset-lg-3 col-xl-2 offset-xl-0 chart-licences">
								License
							</div>
						</div>
						{foreach $chart key=position item=track}{strip} 
						<div class="row chart-track">
							<div class="d-none d-sm-flex col-sm-2 col-md-2 col-lg-1 col-xl-1 chart-progression">
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
							<div class="col-4 col-sm-2 col-md-2 col-lg-2 col-xl-2 chart-position">
								<div class="chart-position-current">{$position}</div>
								<div class="chart-position-before">Yesterday : {$track['arrChartData'][1]['intPositionID']}</div>
							</div>
							<div class="col-8 col-sm-8 col-md-8 col-lg-4 col-xl-4 chart-info">
								<div class="chart-track-title">
									<a href="{$baseURL}track/{$track.intTrackID}">{$track.strTrackName}</a>
								</div>
								<div class="chart-track-artist">
									{$track.strArtistName}
                                </div>
                                <div class="chart-position-high-low">
                                    Highest : {$track.60dayhighest}, Lowest : {$track.60daylowest}
                                </div>
							</div>
							<div class="col-12 col-sm-9 col-md-9 col-lg-5 col-xl-3 chart-graph">
								<canvas id="graph-{$position}" style="height: 50px;"></canvas>
							</div>
							<div class="col-12 col-sm-3 col-md-3 col-lg-4 offset-lg-3 col-xl-2 offset-xl-0 chart-licences">
								<div class="license-icons d-flex flex-wrap justify-content-center">
									<div {if $track.strIsByLicense == "active"}title="By attribution" {/if}class="license-icon license-by license-{$track.strIsByLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsNcLicense == "active"}title="Non commercial" {/if}class="license-icon license-nc license-{$track.strIsNcLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsNdLicense == "active"}title="Non derivative" {/if}class="license-icon license-nd license-{$track.strIsNdLicense} license-{$track.enumTrackLicense}"></div>
									<div {if $track.strIsSaLicense == "active"}title="Share alike" {/if}class="license-icon license-sa license-{$track.strIsSaLicense} license-{$track.enumTrackLicense}"></div>									
									<div {if $track.strIsSamplingPlusLicense == "active"}title="Sampling+" {/if}class="license-icon license-sp license-{$track.strIsSamplingPlusLicense} license-{$track.enumTrackLicense}"></div>									
									<div {if $track.strIsZeroLicense == "active"}title="CC-0" {/if}class="license-icon license-ze license-{$track.strIsZeroLicense} license-{$track.enumTrackLicense}"></div>									
								</div>
							</div>
						</div>
						{/strip}{/foreach}
					</div>
				</div>
				{include file="partials/sideplayers.html.tpl"}
			</dv>
		</div>
		{include file="show_chartjs.tpl"}
		<script type="text/javascript">{literal}//<![CDATA[
			$(document).ready(function() {{/literal}
				{include file="player2.js.tpl" player_id="1" playlist=$daily_player_json}
				{include file="player2.js.tpl" player_id="2" playlist=$weekly_player_json}
				{include file="player2.js.tpl" player_id="3" playlist=$monthly_player_json}
				{literal}
			});{/literal}//]]>
		</script>
{include file="partials/footer.html.tpl"}