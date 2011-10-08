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
{foreach from=$shows item=show}
	<item>
		<title>{$show.strShowName}</title>
		<link>{$show.strShowUrl}</link>
		<guid>{$show.strShowUrl}</guid>
		<description><![CDATA[<h1>{$show.strShowName}</h1>
{foreach from=$show.arrTracks item=track}
		<form action="{$baseURL}vote/{$track.intTrackID}/{$show.intShowID}?go" method="post">
			<p><img src="{$track.qrcode}" alt="QR Code for this page" /> "<a href="{$track.strTrackUrl}">{$track.strTrackName}</a>" by "<a href="{$track.strArtistUrl}">{$track.strArtistName}</a>" <input type="submit" name="go" value="I like this track!" /></p>
		</form>
{/foreach}
]]></description>
{if isset($show.player_data.m4a)}
		<enclosure url="{$show.player_data.m4a}" length="{$show.player_data.m4a_len}" type="audio/mp4a-latm" />
{/if}
		<category>Music</category>
		<pubDate>{$show.datDateAdded}</pubDate>
		<author>{$feedOwner}</author>
		<itunes:author>{$feedOwner}</itunes:author>
		<itunes:explicit>{if $show.isNSFW == true}yes{else}no{/if}</itunes:explicit>
		<itunes:subtitle>{$show.strShowName}</itunes:subtitle>
		<itunes:keywords>Free {$feedWhen} Creative Commons Licensed Music</itunes:keywords>
		<itunes:duration>{$show.timeLength}</itunes:duration>
{if isset($show.player_data.m4a)}
		<mrss-plus:fullwork url="{$show.player_data.m4a}" fileSize="{$show.player_data.m4a_len}" type="audio/mp4a-latm" medium="audio" isDefault="true" expression="full" duration="{$show.timeLength}" lang="en">
{if isset($show.player_data.oga) and $show.player_data.oga != ''}
            <mrss-plus:alternate-fullwork url="{$show.player_data.oga}" fileSize="{$show.player_data.oga_len}" type="audio/oga" medium="audio" isDefault="false" expression="full" duration="{$show.timeLength}" lang="en" description="OGG/Vorbis version" />
{/if}
{if isset($show.player_data.mp3) and $show.player_data.mp3 != ''}
            <mrss-plus:alternate-fullwork url="{$show.player_data.mp3}" fileSize="{$show.player_data.mp3_len}" type="audio/mpeg" medium="audio" isDefault="false" expression="full" duration="{$show.timeLength}" lang="en" description="MPEG3-Audio version" />
{/if}
			<mrss-plus:hash algo="sha1">{$show.shaHash}</mrss-plus:hash>
{if $show.strCommentUrl != ''}
			<mrss-plus:comments url="{$show.strCommentUrl}" />
{/if}
{if isset($show.arrShowLayout)}
{foreach from=$show.arrShowLayout item=$track}
			<mrss-plus:partwork src="{$track.strTrackUrl}" startTime="{$track.start}" endTime="{$track.stop}">
{if $track.strLicenseUrl != ''}
				<mrss-plus:licenses>
					<mrss-plus:license type="text/html" href="{$track.strLicenseUrl}">{$track.strLicenseName}</mrss-plus:license>
				</mrss-plus:licenses>
{/if}
				<mrss-plus:credit role="artist" scheme="urn:music" url="{$track.strArtistUrl}">{$track.strArtistName}</mrss-plus:credit>
				<mrss-plus:title type="plain" url="{$track.strTrackUrl}">{$track.strTrackName}</mrss-plus:title>
			</mrss-plus:partwork>
{/foreach}
{/if}
		</mrss-plus:fullwork>
{/if}
	</item>
{/foreach}
	</channel>
</rss>