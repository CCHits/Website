{include file="partials/header.html.tpl"}
<div class="container" id="stats">
    <div class="row row-header">
        <div class="col">
            <header>Site statistics</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-12 col-md-9">
            <div class="stats-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-lg-3 stats-number-of-tracks">
                        <div class="stats-legend">
                            Number of tracks
                        </div>
                        <div class="stats-data">
                            {$stats.numberOfTracks}
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3 stats-number-of-artists">
                        <div class="stats-legend">
                            Number of artists
                        </div>
                        <div class="stats-data">
                            {$stats.numberOfArtists}
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-lg-6 stats-average-number-of-tracks-per-artist">
                        <div class="stats-legend">
                            Average number of tracks per artists
                        </div>
                        <div class="stats-data">
                            {$stats.averageNumberOfTracksPerArtist}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="stats-legend">
                            Number of track per license       
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY : {$stats.numberOfTracksPerLicense["cc-by"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY-SA : {$stats.numberOfTracksPerLicense["cc-by-sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-SA : {$stats.numberOfTracksPerLicense["cc-sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY-NC : {$stats.numberOfTracksPerLicense["cc-by-nc"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-NC : {$stats.numberOfTracksPerLicense["cc-nc"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY-ND : {$stats.numberOfTracksPerLicense["cc-by-nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-ND : {$stats.numberOfTracksPerLicense["cc-nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY-NC-SA : {$stats.numberOfTracksPerLicense["cc-by-nc-sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-NC-SA : {$stats.numberOfTracksPerLicense["cc-nc-sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-BY-NC-ND : {$stats.numberOfTracksPerLicense["cc-by-nc-nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-NC-ND : {$stats.numberOfTracksPerLicense["cc-nc-nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-SAMPLING+ : {$stats.numberOfTracksPerLicense["cc-sampling+"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-NC-SAMPLING+ : {$stats.numberOfTracksPerLicense["cc-nc-sampling+"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-lg-3">
                        <div class="stats-data">
                            CC-0 : {$stats.numberOfTracksPerLicense["cc-0"]}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="stats-legend">
                            Number of track per license criteria
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +BY : {$stats.numberOfTracksPerLicenseCriteria["by"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -BY : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["nc"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +NC : {$stats.numberOfTracksPerLicenseCriteria["by"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -NC : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["nc"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +ND : {$stats.numberOfTracksPerLicenseCriteria["nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -ND : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["nd"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +SA : {$stats.numberOfTracksPerLicenseCriteria["sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -SA : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["sa"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +SAMPLING+ : {$stats.numberOfTracksPerLicenseCriteria["sampling+"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -SAMPLING+ : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["sampling+"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            +0 : {$stats.numberOfTracksPerLicenseCriteria["0"]}
                        </div>
                    </div>
                    <div class="col-6 col-sm-3 col-lg-3">
                        <div class="stats-data">
                            -0 : {$stats.numberOfTracks - $stats.numberOfTracksPerLicenseCriteria["0"]}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="stats-legend">
                            Top 10 longest running tracks at #1 position
                        </div>
                    </div>
                    {foreach $stats.top10LongestRunningTracksAtNumberOnePosition key=index item=data}
                    <div class="col-12">
                        <div class="stats-data">
                            <b>#{$index + 1}</b> : "<a href="{$baseURL}track/{$data['track'].intTrackID}">{$data['track'].strTrackName}</a>" by {$data['track'].strArtistName}, {$data['numberOfDaysAtPosition1']} days
                        </div>
                    </div>
                    {/foreach}
                </div>                    
            </div>
        </div>
        <!--
            <div>Top 10 longuest running tracks at #1 position</div>
            {foreach $stats.top10LongestRunningTracksAtNumberOnePosition key=index item=data}
            <div><b>#{$index + 1}</b> : "{$data['track'].strTrackName}" by {$data['track'].strArtistName}, {$data['numberOfDaysAtPosition1']} (possibly non consecutive) days</div>
            {/foreach}
        -->
        {include file="partials/sideplayers.html.tpl"}
    </dv>
</div>
<script type="text/javascript">{literal}//<![CDATA[
    $(document).ready(function() {{/literal}
        {include file="player2.js.tpl" player_id="1" playlist=$daily_player_json}
        {include file="player2.js.tpl" player_id="2" playlist=$weekly_player_json}
        {include file="player2.js.tpl" player_id="3" playlist=$monthly_player_json}
        {literal}
    });{/literal}//]]>
</script>
{include file="partials/footer.html.tpl"}
