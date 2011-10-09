<?php

/**
 * This file is only here to serve as a pointer to the outstanding tasks.
 *
 * Front page - Done
 *
 * Chart - HTML - Partial
 * Chart - API
 * TODO: Chart API calls
 * Chart - RSS of Changes (One per change)
 * TODO: Check why /chart/day (aka /chart/rss) RSS Feed is returning as HTML
 * Chart - RSS of Changes (One entry per day)
 * TODO: Check why /chart/changes RSS Feed is returning as HTML
 * TODO: Check why intVote, decVoteAdj and arrShows is not appearing in /chart/changes
 * TODO: RSS broker on Chart HTML pages
 *
 * Trend Data - HTML
 * Trend Data - API
 * Trend Data - RSS
 * TODO: All Trend pages and calls
 *
 * Daily Show - Generate
 * TODO: Finish Daily Show Generator
 * Daily Show - HTML - Done
 * Daily Show - API
 * TODO: Check API call for Daily Show
 * Daily Show - RSS - Done
 *
 * Weekly Show - Generate
 * TODO: Finish Weekly Show Generator
 * Weekly Show - HTML - Done
 * Weekly Show - API
 * TODO: Check API call for Weekly Show
 * Weekly Show - RSS - Done
 *
 * Monthly Show - Generate
 * TODO: Finish Monthly Show Generator
 * Monthly Show - HTML - Done
 * Monthly Show - API
 * TODO: Check API call for Monthly Show
 * Monthly Show - RSS - Done
 *
 * External Show - HTML - Done
 * External Show - API - Done
 * External Show - RSS of Changes (other shows, votes and track positions)
 * External Show - RSS of Changes (other shows, votes and track positions) by URL base
 * TODO: Write RSS page for Show with votes, track positions and other shows data - optionally specifying the URL base
 * External Show - Searches (By URL)
 * TODO: Write HTML search tool to find shows by URL
 *
 * Track - HTML
 * Track - API
 * TODO: Check why track 55 returns just the daily show (91), when it's also been on show 96 for the HTML and API.
 * Track - Search (By Name, source, Duration, license and Work-Safe status)
 * TODO: Write HTML search tool to find shows by various criteria
 * Track - RSS of Changes (Shows, votes, positions)
 * Track - Identify NSFW error
 * TODO: Write function to flag NSFW on tracks.
 * Track - Identify Non-CC licensed or contention
 * TODO: Write function to flag license contention on tracks.
 * Track - De-duplicate (via pointer) - done
 * Track - Download (Ogg/MP3/M4A) - Re-set ID3 tags with current accurate artist, title, original download location or track location, QR code as artwork for CCHits track page.
 * TODO: Amend RemoteSources to create transcode to .ogg, .mp3 and .m4a for sourced files. Set ID3/vorbiscomment/mp4tags with artist, title from CCHits DB. Add comment to show download location or track location. Set QRCode as Artwork, pointing to the CCHits.net/track/trackid page.
 *
 * Artist - Search (By Name, source, URL)
 * Artist - HTML (like external show)
 * Artist - API (like external show)
 * Artist - RSS of changes (Tracks, votes, positions)
 * Artist - De-duplicate (via pointer)
 * TODO: Write Artist content (like external show)
 *
 * Vote - HTML
 * Vote - API
 * TODO: Figure out why the UserBroker/NewUserObject returns a new item *every time* for the same object!
 *
 * Post-vote - From Show - show rest of tracks from that show + other shows that track has been on - Done
 * Post-vote - From track - other shows that track has been on - Done
 *
 * Admin console - upload file - Done
 * Admin console - download from remote sources - Done
 * Admin console - create show - Done
 * Admin console - edit show - Done
 * Admin console - change basic auth parameters - Done
 *
 * PHP version 5
 *
 * @category Documentation
 * @package  CCHitsClass
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
