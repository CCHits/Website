# Welcome to CCHits
[![All Contributors](https://img.shields.io/badge/all_contributors-4-orange.svg?style=flat-square)](#contributors)

Where *you* make the charts

# Project status

[![Build Status](https://travis-ci.org/CCHits/Website.svg?branch=master)](https://travis-ci.org/CCHits/Website)

# Goals

CCHits.net is a site promoting and featuring [Creative Commons](http://creativecommons.org/about/licenses/) licensed music and the podcasts that play them. The site was designed with more than just this in mind. Here are some of the highlights
* Encourage and Discover Great Music

    There's a lot of great Creative Commons Licensed Music out there, and not enough people know just what you can get hold of! To help ease the burdon of this issue, there are three things that we do:

    * By linking directly to artist's home sites rather than to our own holding pages for artists, we ensure that the artists get maximum exposure for their own material, without having to update our site when their own information changes!

    * By linking to the source of the individual track, gives listeners a greater awareness of music sources, which hopefully should increase the exposure for sites who promote and list Creative Commons licensed music.

    * By linking to podcasts which play Creative Commons licensed music, we give listeners the opportunity to find other shows that play the music they like - ultimately giving listeners a greater fountain of great music to select from, and hopefully giving them the opportunity to discover new artists and genres to add to their personal list of favourites.

* Support Communities

    An attendor at various social groups, the original author of the code which drives cchits.net was unable to provide consistent, suitable background music for events he was involved in organising or just attending. This site was originally designed to find tracks which are generally acceptable for public play, and are available under a suitable license for public performance (which Creative Commons music should be!) By asking all submitters of music to identify the license under which the tracks are made available, as well as selecting whether tracks may not be suitable for work or family listening, it should be possible (once the code is in-place) to request from the site a suitable selection of music for playback at venues such as hackspaces, youth centres, or even just hold music for a business. Note that this site is not being created to build a re-licensing business, but instead to promote awareness of great music - there are other, better sites, that can advise and assist in the selection of Creative Commons music which are suitable for your business endeavour, but if you just want something for backing music for an hour or a whole day, this site might be (eventually!) just the thing for you.

* Create Podcasts and Improve Coding Techniques

    At the time of writing, cchits.net is the work of one person. For several months, [Jon "The Nice Guy" Spriggs](http://jon.sprig.gs/) had been considering starting a podcast, however, he's not exactly known for finishing projects! By making a system which is automated enough to create a daily podcast, a weekly podcast and a monthly podcast, playing music that he likes to hear, he thought it might encourage him to stick to it - especially when there are other amazing goals (see above) which come out as a side benefit. He normally has described himself as a writer of "bad PHP code", and each project he starts improves the techniques he has learned.

    In this instance, CCHits.net has introduced Jon to the concept of writing an API that works, a system of remote execution of code, the generation of synthesized speech and the generation of an audio track, entirely in code! Never being shy of criticism from the community, especially where code is concerned, the code has all been released under a license which encourages reuse and requires the code is re-released under the same license.

# Get Involved

If you want to get involved, please contact show@cchits.net to talk about submitting tracks, creating shows and generally doing more with CCHits.net

# Source

The source code for everything driving this site is available in the Git Repositories at [Github](http://github.com/cchits/website).

Patches to either can either be e-mailed to code@cchits.net, or you can clone a repository, make your changes and then raise a merge request through the site.

# API

Please see the [full API documentation](https://github.com/CCHits/Website/wiki/Using-the-API).

# Vote Adjustments

CCHits.net adjusts the votes accrued when weekly review shows and monthly chart shows are released. In both cases, this is to try and ensure that generated shows don't constantly repeat the same tracks that became popular at the start of the system.

Each time a track appears on a weekly or monthly show, it is adjusted down by 5%. No plays by external podcasts or the daily exposure show will influence the votes received by that track.

To see the shows that have played a track, you can click on the track listing for the track on any of the show listings, and it will list all the shows we know about.

# Trending Data

CCHits.net monitors trending information about tracks which are being voted upon. This is done by a very simple formula - the votes for each 24 hour period is collated and multiplied by an incrementing number to correspond with the number of days the surveyed sample covers. A single vote placed on each of the 7 days in the searched period will be equivelent to 1 vote on the first day, 2 on the second, 3 on the third and so on. Each search covers 7 days.

# Theme

The theme is an exerpt from a track, sourced from [ccMixter](http://ccmixter.org/). The Track is called [GMZ](http://ccmixter.org/files/scottaltham/19726) and was created by [scottaltham](http://ccmixter.org/people/scottaltham).

# Not Safe for Work or Family Listening - an explanation

As this project was originally supposed to create audio to be used at events, I wanted to be clear about the demarkation between "Work-or-Family Safe" music, and non... so I drew up these guidelines. A track is safe for work-or-family listening if:

* The track does not contain any swear words or derogatory words for gender, race, preference, or if it does contain them, they are hard to make out or distinguish.
* The track does not contain any obvious direct references to drug use.
* The track does not contain any obvious sexual references, including suggestive sounds.
* The track does not advocate crime or gun use (which is a criminal act in some countries).

Obviously, not everyone agrees with this definition, and I don't upload every track. Some people will mark a track as being non-work-safe if it contains language they don't understand (in case it breaches one of the recommendations), or will mark a track as being work-or-family safe even if it breaches one of the above recommendations. If you disagree with how the track is marked, please feel free to contact show@cchits.net and I'll re-listen to the track, and possibly change it's flag, if I think it's appropriate.
## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore -->
<table>
  <tr>
    <td align="center"><a href="https://frenchguy.ch"><img src="https://avatars3.githubusercontent.com/u/2527227?v=4" width="100px;" alt="Yannick Mauray"/><br /><sub><b>Yannick Mauray</b></sub></a><br /><a href="#ideas-ymauray" title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/CCHits/Website/issues?q=author%3Aymauray" title="Bug reports">🐛</a> <a href="https://github.com/CCHits/Website/commits?author=ymauray" title="Code">💻</a> <a href="#question-ymauray" title="Answering Questions">💬</a> <a href="#review-ymauray" title="Reviewed Pull Requests">👀</a> <a href="#userTesting-ymauray" title="User Testing">📓</a> <a href="https://github.com/CCHits/Website/commits?author=ymauray" title="Tests">⚠️</a> <a href="#maintenance-ymauray" title="Maintenance">🚧</a></td>
    <td align="center"><a href="https://thelovebug.org/"><img src="https://avatars0.githubusercontent.com/u/2915687?v=4" width="100px;" alt="Dave Lee"/><br /><sub><b>Dave Lee</b></sub></a><br /><a href="#ideas-thelovebug" title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/CCHits/Website/issues?q=author%3Athelovebug" title="Bug reports">🐛</a> <a href="#question-thelovebug" title="Answering Questions">💬</a> <a href="#review-thelovebug" title="Reviewed Pull Requests">👀</a> <a href="#userTesting-thelovebug" title="User Testing">📓</a> <a href="#talk-thelovebug" title="Talks">📢</a></td>
    <td align="center"><a href="http://jon.sprig.gs"><img src="https://avatars3.githubusercontent.com/u/228671?v=4" width="100px;" alt="Jon "The Nice Guy" Spriggs"/><br /><sub><b>Jon "The Nice Guy" Spriggs</b></sub></a><br /><a href="https://github.com/CCHits/Website/commits?author=JonTheNiceGuy" title="Code">💻</a> <a href="#ideas-JonTheNiceGuy" title="Ideas, Planning, & Feedback">🤔</a> <a href="#content-JonTheNiceGuy" title="Content">🖋</a> <a href="https://github.com/CCHits/Website/commits?author=JonTheNiceGuy" title="Documentation">📖</a> <a href="#infra-JonTheNiceGuy" title="Infrastructure (Hosting, Build-Tools, etc)">🚇</a> <a href="#review-JonTheNiceGuy" title="Reviewed Pull Requests">👀</a> <a href="#tool-JonTheNiceGuy" title="Tools">🔧</a> <a href="#talk-JonTheNiceGuy" title="Talks">📢</a></td>
    <td align="center"><a href="https://github.com/computamike"><img src="https://avatars2.githubusercontent.com/u/464876?v=4" width="100px;" alt="Mike Hingley"/><br /><sub><b>Mike Hingley</b></sub></a><br /><a href="https://github.com/CCHits/Website/commits?author=computamike" title="Code">💻</a></td>
  </tr>
</table>

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!