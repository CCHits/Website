<div class="col-12 col-md-3">
    <div class="row d-none d-sm-flex">
        <div class="col">
            <div class="shows-legend">The most recent ...</div>
        </div>
    </div>
    <div class="row">
        <div class="col col-player" id="daily">
            <header>Daily Exposure show</header>
            {include file="player2.html.tpl" player_id="1" playlist=$daily}
            <footer><a href="{$baseURL}daily">More...</a> | <a href="{$baseURL}daily/rss">Feed</a></footer>
        </div>
    </div>
    <div class="row">
        <div class="col col-player" id="weekly">
            <header>Weekly Review show</header>
            {include file="player2.html.tpl" player_id="2" playlist=$weekly}
            <footer><a href="{$baseURL}weekly">More...</a> | <a href="{$baseURL}weekly/rss">Feed</a></footer>
        </div>
    </div>	
    <div class="row">
        <div class="col col-player" id="daily">
            <header>Monthly Chart show</header>
            {include file="player2.html.tpl" player_id="3" playlist=$monthly}
            <footer><a href="{$baseURL}monthly">More...</a> | <a href="{$baseURL}monthly/rss">Feed</a></footer>
        </div>						
    </div>
</div>
