<div id="jquery_jplayer_{$player_id}" class="jp-jplayer"></div>
			<div class="jp-audio">
				<div class="jp-type-playlist">
				<div id="jp_interface_{$player_id}" class="jp-interface">
					<ul class="jp-controls">
						<li><a href="#" class="jp-play" tabindex="1">Play Media</a></li>
						<li><a href="#" class="jp-pause" tabindex="1">Pause Media</a></li>
						<li><a href="#" class="jp-stop" tabindex="1">Stop Media</a></li>
						<li><a href="#" class="jp-mute" tabindex="1">Mute Media</a></li>
						<li><a href="#" class="jp-unmute" tabindex="1">Unmute Media</a></li>
{if isset($playlist.player_data)}{elseif is_array($playlist)}
						<li><a href="#" class="jp-previous" tabindex="1">Previous Track</a></li>
						<li><a href="#" class="jp-next" tabindex="1">Next Track</a></li>
{/if}
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
				</div>
				<div id="jp_playlist_{$player_id}" class="jp-playlist">
					<ul>
{if isset($playlist.player_data)}
                        <li>{$playlist.player_data.name} ({if isset($playlist.player_data.mp3)}<a href="{$playlist.player_data.mp3}">mp3</a> | {/if}{if isset($playlist.player_data.oga)}<a href="{$playlist.player_data.oga}">ogg</a> | {/if}<a href="{$playlist.player_data.link}">link</a>)</li>
{elseif is_array($playlist)}{foreach $playlist as $listitem}
                        <li>{$listitem.player_data.name} ({if isset($listitem.player_data.mp3)}<a href="{$listitem.player_data.mp3}">mp3</a> | {/if}{if isset($listitem.player_data.oga)}<a href="{$listitem.player_data.oga}">ogg</a> | {/if}<a href="{$listitem.player_data.link}">link</a>)</li>
{/foreach}{/if}
					</ul>
				</div>
			</div>
		</div>