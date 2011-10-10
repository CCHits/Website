<?php

/**
 * This file is only here to serve as a pointer to the outstanding tasks.
 *
 * Front page - Done
 *
 * Chart - HTML - Partial
 * Chart - API
 * NEXTRELEASE: Chart API calls
 * Chart - RSS
 * FIXME: Check why /chart/day (aka /chart/rss) and /chart/changes RSS Feeds are returning as HTML
 *
 * Changes - (One per change per day)
 * TODO: Separate out code from Chart RSS feed.
 *
 * Trend Data - HTML
 * Trend Data - API
 * Trend Data - RSS
 * NEXTRELEASE: All Trend pages and calls
 *
 * Daily Show - Generate
 * TODO: Finish Daily Show Generator
 * Daily Show - HTML - Done
 * Daily Show - RSS - Done
 *
 * Weekly Show - Generate
 * TODO: Finish Weekly Show Generator
 * Weekly Show - HTML - Done
 * Weekly Show - RSS - Done
 *
 * Monthly Show - Generate
 * TODO: Finish Monthly Show Generator
 * Monthly Show - HTML - Done
 * Monthly Show - RSS - Done
 *
 * External Show - HTML - Done
 * External Show - API - Done
 * External Show - RSS of Changes (other shows, votes and track positions)
 * External Show - RSS of Changes (other shows, votes and track positions) by URL base
 * NEXTRELEASE: Write RSS page for Show with votes, track positions and other shows data - optionally specifying the URL base
 * External Show - Searches (By URL)
 * NEXTRELEASE: Write HTML search tool to find shows by URL
 *
 * Track - HTML
 * Track - API
 * TODO: Check why track 55 returns just the daily show (91), when it's also been on show 96 for the HTML and API.
 * Track - Search (By Name, source, Duration, license and Work-Safe status)
 * NEXTRELEASE: Write HTML search tool to find shows by various criteria
 * Track - RSS of Changes (Shows, votes, positions)
 * Track - Identify NSFW error
 * NEXTRELEASE: Write function to flag NSFW on tracks.
 * Track - Identify Non-CC licensed or contention
 * NEXTRELEASE: Write function to flag license contention on tracks.
 * Track - De-duplicate (via pointer) - done
 * Track - Download (Ogg/MP3/M4A) - Re-set ID3 tags with current accurate artist, title, original download location or track location, QR code as artwork for CCHits track page.
 * NEXTRELEASE: Amend RemoteSources to create transcode to .ogg, .mp3 and .m4a for sourced files. Set ID3/vorbiscomment/mp4tags with artist, title from CCHits DB. Add comment to show download location or track location. Set QRCode as Artwork, pointing to the CCHits.net/track/trackid page.
 *
 * Artist - Search (By Name, source, URL)
 * TODO: Needed for Remotesources functions.
 * Artist - HTML (like external show)
 * Artist - API (like external show)
 * Artist - RSS of changes (Tracks, votes, positions)
 * Artist - De-duplicate (via pointer)
 * TODO: Write Artist content (like external show)
 *
 * Vote - HTML - Done
 * Vote - API - Done
 *
 * Post-vote - From Show - show rest of tracks from that show + other shows that track has been on - Done
 * Post-vote - From track - other shows that track has been on - Done
 *
 * Statistics - count & time By license, NSFW - Tracks, Artists
 * Statistics - Count total votes, average
 * NEXTRELEASE: Write Statistics
 *
 * Admin console - upload file - Done
 * Admin console - download from remote sources - Done
 * TODO: Fix post-add on Admin console to search for prior examples of that track (not just by URL) and Artist. Create new entry for both if not exist.
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
