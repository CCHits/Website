<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom">
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
		<item>
			<title>{$track.strTrackName}</title>
			<link>{$baseURL}track/{$track.intTrackID}</link>
			<guid>{$baseURL}chart/{$chart.intChartDate}</guid>
			<description><![CDATA[<h1>Chart for {$chart.strChartDate}</h1>
<ul>
{foreach from=$chart.position key=position item=track}
{if $track.strPositionYesterday != 'equal'}
	<li>{$position} {$track.strPositionYesterday} <a href="{$baseURL}track/{$track.intTrackID}">{$track.strTrackName} by {$track.strArtistName}</a></li>
{/if}
{/foreach}
</ul>]]></description>
			<pubDate>{$chart.strChartDate}</pubDate>
			<author>{$feedOwner}</author>
		</item>
	</channel>
</rss>