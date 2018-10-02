{extends file="partials/_layout.html.tpl"}
{block name=title}About: {$ServiceName} - {$Slogan}{/block}
{block name=content}
	<h1><a href="{$baseURL}">Welcome to {$ServiceName}</a></h1>
	<h2>{$Slogan}</h2>
	<h3>About CCHits.net, the FAQ and more</h3>
	<ul class="TOC">
		<li><a href="#goals">Goals</a></li>
		<li><a href="#source">Source</a></li>
		<li><a href="#database">Database</a></li>
		<li><a href="#api">API</a></li>
		<li><a href="#voteadjust">Vote Adjustments</a></li>
		<li><a href="#trends">Trending Data</a></li>
		<li><a href="#theme">Theme</a></li>
		<li><a href="#nsfw">Not Safe for Work or Family Listening - an explanation</a></li>
	</ul>
	<div class="FAQItem">
		<a name="goals"></a>
		<h3>Goals</h3>
		<p>CCHits.net is a site promoting and featuring <a href="http://creativecommons.org/about/licenses/">Creative Commons</a> licensed music and the podcasts that play them. The site was designed with more than just this in mind. Here are some of the highlights</p>
	
		<ul>
			<li>
				<h4>Encourage and Discover Great Music</h4>
				<p>There's a lot of great Creative Commons Licensed Music out there, and not enough people know just what you can get hold of! To help ease the burdon of this issue, there are three things that we do:</p>
				<ul>
					<li>By linking directly to artist's home sites rather than to our own holding pages for artists, we ensure that the artists get maximum exposure for their own material, without having to update our site when their own information changes!</li>
					<li>By linking to the source of the individual track, gives listeners a greater awareness of music sources, which hopefully should increase the exposure for sites who promote and list Creative Commons licensed music.</li>
					<li>By linking to podcasts which play Creative Commons licensed music, we give listeners the opportunity to find other shows that play the music they like - ultimately giving listeners a greater fountain of great music to select from, and hopefully giving them the opportunity to discover new artists and genres to add to their personal list of favourites.</li>
				</ul>
			</li>
			<li>
				<h4>Support Communities</h4>
				<p>An attendor at various social groups, the original author of the code which drives cchits.net was unable to provide consistent, suitable background music for events he was involved in organising or just attending. This site was originally designed to find tracks which are generally acceptable for public play, and are available under a suitable license for public performance (which Creative Commons music should be!) By asking all submitters of music to identify the license under which the tracks are made available, as well as selecting whether tracks may not be suitable for work or family listening, it should be possible (once the code is in-place) to request from the site a suitable selection of music for playback at venues such as hackspaces, youth centres, or even just hold music for a business. Note that this site is not being created to build a re-licensing business, but instead to promote awareness of great music - there are other, better sites, that can advise and assist in the selection of Creative Commons music which are suitable for your business endeavour, but if you just want something for backing music for an hour or a whole day, this site might be (eventually!) just the thing for you.</p>
			</li>
			
			<li>
				<h4>Create Podcasts and Improve Coding Techniques</h4>
				<p>At the time of writing, cchits.net is the work of one person. For several months, <a href="http://jon.sprig.gs">Jon "The Nice Guy" Spriggs</a> had been considering starting a podcast, however, he's not exactly known for finishing projects! By making a system which is automated enough to create a daily podcast, a weekly podcast and a monthly podcast, playing music that he likes to hear, he thought it might encourage him to stick to it - especially when there are other amazing goals (see above) which come out as a side benefit. He normally has described himself as a writer of "bad PHP code", and each project he starts improves the techniques he has learned.</p>
				<p>In this instance, CCHits.net has introduced Jon to the concept of writing an API that works, a system of remote execution of code, the generation of synthesized speech and the generation of an audio track, entirely in code! Never being shy of criticism from the community, especially where code is concerned, the code has all been released under a license which encourages reuse and requires the code is re-released under the same license.</p>
			</li>
		</ul>
	</div>
	<div class="GetInvolved">
		<a name="getinvolved"></a>
		<h3>Get Involved</h3>
		<p>If you want to get involved, please contact <a href="mailto:show@cchits.net">show@cchits.net</a> to talk about submitting tracks, creating shows and generally doing more with CCHits.net</p>
	</div>
	<div class="FAQItem">
		<a name="source"></a>
		<h3>Source</h3>
		<p>The source code for everything driving this site is available in the Git Repositories at <a href="http://github.com/cchits/website">Github</a>.</p>
		<p>Patches to either can either be e-mailed to <a href="mailto:code@cchits.net">code@cchits.net</a>, or you can clone a repository, make your changes and then raise a merge request through the site.</p>
		<h3>Developers</h3>
		<p>Thinking of working with the source code, API or website? Have a look at <a href="https://github.com/CCHits/Website/wiki">the development site</a>.</p>
	</div>
	<div class="FAQItem">
		<a name="database"></a>
		<h3>Database</h3>
		<p>I was inspired by the <a href="http://ur1.ca">ur1.ca</a> folk, that giving away access to your database is almost as powerful as the service you're already providing. To try and achieve what they do, from this page, you can request an export of the database, albeit with one factor sanitized... the users table. There are two columns, containing the OpenID Claimed Identity page, and the Username and Password hash used to perform API calls. Both of these will be hashed before sending, to help keep user records secure.</p>
		<form action="{$baseURL}about/database" method="post">
		<input type="submit" name="go" value="Give me the database!" />
		</form>
		<p><b>This DATABASE and it's DATA is made available under the <a href="http://creativecommons.org/publicdomain/zero/1.0/">Creative Commons Zero license</a>.</b></p>
	</div>
	<div class="FAQItem">
		<a name="api"></a>
		<h3>API</h3>
		<p>Please see the <a href="https://github.com/CCHits/Website/wiki/Using-the-API">full API documentation</a>.</p>
	</div>
	<div class="FAQItem">
		<a name="voteadjust"></a>
		<h3>Vote Adjustments</h3>
		<p>CCHits.net adjusts the votes accrued when weekly review shows and monthly chart shows are released. In both cases, this is to try and ensure that generated shows don't constantly repeat the same tracks that became popular at the start of the system.</p>
		<p>Each time a track appears on a weekly or monthly show, it is adjusted down by 5%. No plays by external podcasts or the daily exposure show will influence the votes received by that track.</p>
		<p>To see the shows that have played a track, you can click on the track listing for the track on any of the show listings, and it will list all the shows we know about.</p>
	</div>
	<div class="FAQItem">
		<a name="trends"></a>
		<h3>Trending Data</h3>
		<p>CCHits.net monitors trending information about tracks which are being voted upon. This is done by a very simple formula - the votes for each 24 hour period is collated and multiplied by an incrementing number to correspond with the number of days the surveyed sample covers. A single vote placed on each of the 7 days in the searched period will be equivelent to 1 vote on the first day, 2 on the second, 3 on the third and so on. Each search covers 7 days.</p>
	</div>
	<div class="FAQItem">
		<a name="theme"></a>
		<h3>Theme</h3>
		<p>The theme is an exerpt from a track, sourced from <a href="http://ccmixter.org/">ccMixter</a>. The Track is called <a href="http://ccmixter.org/files/scottaltham/19726">GMZ</a> and was created by <a href="http://ccmixter.org/people/scottaltham">scottaltham</a>.</p>
	</div>
	<div class="FAQItem">
		<a name="nsfw"></a>
		<h3>Not Safe for Work or Family Listening - an explanation</h3>
		<p>As this project was originally supposed to create audio to be used at events, I wanted to be clear about the demarkation between "Work-or-Family Safe" music, and non... so I drew up these guidelines. A track is safe for work-or-family listening if:</p>
		<ul>
			<li>The track does not contain any swear words or derogatory words for gender, race, preference, or if it does contain them, they are hard to make out or distinguish.</li>
			<li>The track does not contain any obvious direct references to drug use.</li>
			<li>The track does not contain any obvious sexual references, including suggestive sounds.</li>
			<li>The track does not advocate crime or gun use (which is a criminal act in some countries).</li>
		</ul>
		<p>Obviously, not everyone agrees with this definition, and I don't upload every track. Some people will mark a track as being non-work-safe if it contains language they don't understand (in case it breaches one of the recommendations), or will mark a track as being work-or-family safe even if it breaches one of the above recommendations. If you disagree with how the track is marked, please feel free to contact <a href="mailto:show@cchits.net">show@cchits.net</a> and I'll re-listen to the track, and possibly change it's flag, if I think it's appropriate.</p>
	</div>
{/block}
