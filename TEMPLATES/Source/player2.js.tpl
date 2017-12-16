new jPlayerPlaylist({literal} {{/literal}
    jPlayer: "#jquery_jplayer_{$player_id}",
    cssSelectorAncestor: "#jp_container_{$player_id}",
}, {$playlist}, {literal} {{/literal}
    swfPath: "{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/jplayer",
    supplied: "mp3,oga",
    wmode: "window",
    useStateClassSkin: true,
    autoBlur: false,
    smoothPlayBar: true,
    keyEnabled: true
});

    