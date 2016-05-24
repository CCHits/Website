				    	<td>{$position}</td>
					<td>{$track['arrVotes']['total'] * $track['arrVotes']['adjust']}</td>
				    	<td>{$track.strPositionYesterday}</td>
				    	<td>{include file="sparkline.tpl"}</td>
				    	<td><a name="{$track.intTrackID}"></a>"<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</td>
				    	<td><abbr title="{$track.strLicenseName}">{$track.enumTrackLicense}</abbr>{if $track.isNSFW == 1}, <a href="{$baseURL}about#nsfw">Not Work/Family Safe</a>{/if}, <a href="{$baseURL}track/{$track.intTrackID}">More Details...</a></td>
