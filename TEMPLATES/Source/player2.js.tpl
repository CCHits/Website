var x = {$playlist};
$( "#jquery_jplayer_{$player_id}" ).jPlayer( {
    ready: function() {
        console.log( "jPlayer {$player_id} is ready to accept media" );
    },
    cssSelectorAncestor: "#jp_container_{$player_id}",
    remainingDuraction: true,
    toggleDuration: false
} );
