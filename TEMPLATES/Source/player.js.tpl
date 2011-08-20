                      var audioPlaylist{$player_id} = new Playlist(
                                                      "{$player_id}",
                                                      {$playlist},
                                                      {literal} {
                                                          ready: function() {{/literal}
                                                              audioPlaylist{$player_id}.displayPlaylist();
                                                              audioPlaylist{$player_id}.playlistInit(false);{literal}
                                                          },
                                                          ended: function() {{/literal}
                                                              audioPlaylist{$player_id}.playlistNext();{literal}
                                                          },
                                                          play: function() {
                                                              $(this).jPlayer("pauseOthers");
                                                          },
                                                          timeupdate: function(event) {
                                                          	  currentTime=event.jPlayer.status.currentTime*1000; 
                                                          },
                                                          {/literal}swfPath: "{$baseURL}EXTERNALS/JPLAYER/{$jplayer}/",{literal}
                                                          supplied: "mp3,oga"
                                                      });{/literal}
