		<form action="{$baseURL}vote/{$track.intTrackID}/{$realshow.intShowID}?go" method="post"{if isset($before)}{if $before == 'true'} class="before"{else} class="after"{/if}{/if}>
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /> (<abbr title="{$track.strLicenseName}">{$track.enumTrackLicense}</abbr>)</p>
		</form>
