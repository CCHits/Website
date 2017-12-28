<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:mrss-plus="http://code.cchits.net/index.php?title=Mrss%2B" version="2.0">
	<channel>
	<title>{$feedName}</title>
	<description>{$feedDescription}</description>
	<link>{$showsLink}</link>
	<atom:link href="{$feedLink}" rel="self" type="application/rss+xml" />
	<language>en-gb</language>
	<copyright>{$siteCopyright}</copyright>
	<lastBuildDate>{$feedDate}</lastBuildDate>
	<pubDate>{$feedDate}</pubDate>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>
	<image>
		<url>{$baseURL}images/cchits_coverart.png</url>
		<title>{$feedName}</title>
		<link>{$showsLink}</link>
		<width>144</width>
		<height>144</height>
	</image>
	<managingEditor>{$feedOwner}</managingEditor>
	<webMaster>{$feedOwner}</webMaster>
	<itunes:author>{$contactName}</itunes:author>
	<itunes:summary>{$feedDescription}</itunes:summary>
	<itunes:owner>
		<itunes:name>{$contactName}</itunes:name>
		<itunes:email>{$contactEmail}</itunes:email>
	</itunes:owner>
	<itunes:explicit>Yes</itunes:explicit>
	<itunes:image href="{$baseURL}images/cchits_coverart.png"/>
	<itunes:category text="Music" />
	<item>
		<title>{$daily.strShowName}</title>
		<link>{$daily.strShowUrl}</link>
		<guid>{$daily.strGuid}</guid>
		<description><![CDATA[<h1>{$daily.strShowName}</h1>
{foreach from=$daily.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}/{$daily.intShowID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
{/foreach}
]]></description>
		<enclosure url="{$daily.player_data.mp3}" length="{$daily.player_data.mp3_len}" type="audio/mpeg" />
		<category>Music</category>
		<pubDate>{$daily.datDateAdded}</pubDate>
		<author>{$feedOwner}</author>
		<itunes:author>{$feedOwner}</itunes:author>
		<itunes:explicit>{if $daily.isNSFW == true}yes{else}no{/if}</itunes:explicit>
		<itunes:subtitle>{$daily.strShowName}</itunes:subtitle>
		<itunes:keywords>Free {$feedWhen} Creative Commons Licensed Music</itunes:keywords>
		<itunes:duration>{$daily.timeLength}</itunes:duration>
		<mrss-plus:fullwork url="{$daily.player_data.mp3}" fileSize="{$daily.player_data.mp3_len}" type="audio/mpeg" medium="audio" isDefault="true" expression="full" duration="{$daily.timeLength}" lang="en">
{if $daily.player_data.oga != ''}			<mrss-plus:alternate-fullwork url="{$daily.player_data.oga}" fileSize="{$daily.player_data.oga_len}" type="audio/oga" medium="audio" isDefault="false" expression="full" duration="{$daily.timeLength}" lang="en" description="OGG/Vorbis version" />{/if}
			<mrss-plus:hash algo="sha1">{$daily.shaHash}</mrss-plus:hash>
{if $daily.strCommentUrl != ''}
			<mrss-plus:comments url="{$daily.strCommentUrl}" />
{/if}
{if isset($daily.arrShowLayout)}{foreach from=$daily.arrShowLayout item=$track}
			<mrss-plus:partwork src="{$track.strTrackUrl}" startTime="{$track.start}" endTime="{$track.stop}">
				{if $track.strLicenseUrl != ''}<mrss-plus:licenses>
					<mrss-plus:license type="text/html" href="{$track.strLicenseUrl}">{$track.strLicenseName}</mrss-plus:license>
				</mrss-plus:licenses>{/if}
				<mrss-plus:credit role="artist" scheme="urn:music" url="{$track.strArtistUrl}">{$track.strArtistName}</mrss-plus:credit>
				<mrss-plus:title type="plain" url="{$track.strTrackUrl}">{$track.strTrackName}</mrss-plus:title>
			</mrss-plus:partwork>
{/foreach}{/if}
		</mrss-plus:fullwork>
	</item>
	<item>
		<title>{$weekly.strShowName}</title>
		<link>{$weekly.strShowUrl}</link>
		<guid>{$weekly.strShowUrl}</guid>
		<description><![CDATA[<h1>{$weekly.strShowName}</h1>
{foreach from=$weekly.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}/{$weekly.intShowID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
{/foreach}
]]></description>
		<enclosure url="{$weekly.player_data.mp3}" length="{$weekly.player_data.mp3_len}" type="audio/mpeg" />
		<category>Music</category>
		<pubDate>{$weekly.datDateAdded}</pubDate>
		<author>{$feedOwner}</author>
		<itunes:author>{$feedOwner}</itunes:author>
		<itunes:explicit>{if $weekly.isNSFW == true}yes{else}no{/if}</itunes:explicit>
		<itunes:subtitle>{$weekly.strShowName}</itunes:subtitle>
		<itunes:keywords>Free {$feedWhen} Creative Commons Licensed Music</itunes:keywords>
		<itunes:duration>{$weekly.timeLength}</itunes:duration>
		<mrss-plus:fullwork url="{$weekly.player_data.mp3}" fileSize="{$weekly.player_data.mp3_len}" type="audio/mpeg" medium="audio" isDefault="true" expression="full" duration="{$weekly.timeLength}" lang="en">
{if $weekly.player_data.oga != ''}			<mrss-plus:alternate-fullwork url="{$weekly.player_data.oga}" fileSize="{$weekly.player_data.oga_len}" type="audio/oga" medium="audio" isDefault="false" expression="full" duration="{$weekly.timeLength}" lang="en" description="OGG/Vorbis version" />{/if}
			<mrss-plus:hash algo="sha1">{$weekly.shaHash}</mrss-plus:hash>
{if $weekly.strCommentUrl != ''}
			<mrss-plus:comments url="{$weekly.strCommentUrl}" />
{/if}
{if isset($weekly.arrShowLayout)}{foreach from=$weekly.arrShowLayout item=$track}
			<mrss-plus:partwork src="{$track.strTrackUrl}" startTime="{$track.start}" endTime="{$track.stop}">
				{if $track.strLicenseUrl != ''}<mrss-plus:licenses>
					<mrss-plus:license type="text/html" href="{$track.strLicenseUrl}">{$track.strLicenseName}</mrss-plus:license>
				</mrss-plus:licenses>{/if}
				<mrss-plus:credit role="artist" scheme="urn:music" url="{$track.strArtistUrl}">{$track.strArtistName}</mrss-plus:credit>
				<mrss-plus:title type="plain" url="{$track.strTrackUrl}">{$track.strTrackName}</mrss-plus:title>
			</mrss-plus:partwork>
{/foreach}{/if}
		</mrss-plus:fullwork>
	</item>
	<item>
		<title>{$monthly.strShowName}</title>
		<link>{$monthly.strShowUrl}</link>
		<guid>{$monthly.strShowUrl}</guid>
		<description><![CDATA[<h1>{$monthly.strShowName}</h1>
{foreach from=$monthly.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}/{$monthly.intShowID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
{/foreach}
]]></description>
		<enclosure url="{$monthly.player_data.mp3}" length="{$monthly.player_data.mp3_len}" type="audio/mpeg" />
		<category>Music</category>
		<pubDate>{$monthly.datDateAdded}</pubDate>
		<author>{$feedOwner}</author>
		<itunes:author>{$feedOwner}</itunes:author>
		<itunes:explicit>{if $monthly.isNSFW == true}yes{else}no{/if}</itunes:explicit>
		<itunes:subtitle>{$monthly.strShowName}</itunes:subtitle>
		<itunes:keywords>Free {$feedWhen} Creative Commons Licensed Music</itunes:keywords>
		<itunes:duration>{$monthly.timeLength}</itunes:duration>
		<mrss-plus:fullwork url="{$monthly.player_data.mp3}" fileSize="{$monthly.player_data.mp3_len}" type="audio/mpeg" medium="audio" isDefault="true" expression="full" duration="{$monthly.timeLength}" lang="en">
{if $monthly.player_data.oga != ''}			<mrss-plus:alternate-fullwork url="{$monthly.player_data.oga}" fileSize="{$monthly.player_data.oga_len}" type="audio/oga" medium="audio" isDefault="false" expression="full" duration="{$monthly.timeLength}" lang="en" description="OGG/Vorbis version" />{/if}
			<mrss-plus:hash algo="sha1">{$monthly.shaHash}</mrss-plus:hash>
{if $monthly.strCommentUrl != ''}
			<mrss-plus:comments url="{$monthly.strCommentUrl}" />
{/if}
{if isset($monthly.arrShowLayout)}{foreach from=$monthly.arrShowLayout item=$track}
			<mrss-plus:partwork src="{$track.strTrackUrl}" startTime="{$track.start}" endTime="{$track.stop}">
				{if $track.strLicenseUrl != ''}<mrss-plus:licenses>
					<mrss-plus:license type="text/html" href="{$track.strLicenseUrl}">{$track.strLicenseName}</mrss-plus:license>
				</mrss-plus:licenses>{/if}
				<mrss-plus:credit role="artist" scheme="urn:music" url="{$track.strArtistUrl}">{$track.strArtistName}</mrss-plus:credit>
				<mrss-plus:title type="plain" url="{$track.strTrackUrl}">{$track.strTrackName}</mrss-plus:title>
			</mrss-plus:partwork>
{/foreach}{/if}
		</mrss-plus:fullwork>
	</item>
	</channel>
</rss>