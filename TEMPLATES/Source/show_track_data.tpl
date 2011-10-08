				    	<td>{$position} {$track.strPositionYesterday} {include file="sparkline.tpl"}</td>
				    	<td><a name="{$track.intTrackID}"></a><a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</td>
				    	<td>{$track.enumTrackLicense}{if $track.isNSFW == 1}, Not Work/Family Safe{/if}, <a href="{$baseURL}track/{$track.intTrackID}">More Details...</a></td>
