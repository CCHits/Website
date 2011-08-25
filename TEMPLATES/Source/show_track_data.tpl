				    	<td>{$position} {$track.strPositionYesterday} {include file="sparkline.tpl"}</td>
				    	<td>"<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>"</td>
				    	<td>{$track.long_enumTrackLicense}{if $track.isNSFW == true}, Not Work/Family Safe{/if}, <a href="{$baseURL}track/{$track.intTrackID}">More Details...</a></td>
