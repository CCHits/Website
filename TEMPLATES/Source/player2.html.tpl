<div id="jquery_jplayer_{$player_id}" class="jp-jplayer"></div>
<div id="jp_container_{$player_id}" class="jp-audio" role="application" aria-label="media player">
  <div class="jp-type-playlist">
			<div class="jp-gui jp-interface">
			<div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-controls-holder">
				<div class="jp-controls">
					{if isset($playlist.player_data)}{elseif is_array($playlist)}
					<button class="jp-previous" role="button" tabindex="0">previous</button>
					{/if}
					<button class="jp-play" role="button" tabindex="0"><i class="fa fa-play"></i></button>
					<button class="jp-stop" role="button" tabindex="0"><i class="fa fa-stop"></i></button>
					{if isset($playlist.player_data)}{elseif is_array($playlist)}
					<button class="jp-next" role="button" tabindex="0">next</button>
					{/if}
				</div>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-toggles">
					<button class="jp-repeat" role="button" tabindex="0">repeat</button>
					{if isset($playlist.player_data)}{elseif is_array($playlist)}
					<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
					{/if}
				</div>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
  </div>
</div>
