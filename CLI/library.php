<?php

/**
 * TODO: Merge library.php into an appropriate class
 */
function make_sable($input, $output, $remove_sources = true) {
    if (file_exists($input)) {
        $cmd = 'text2wave -o "' . Configuration::getWorkingDir() . '/tmp.wav' . '" "' . $input . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n";
            if (file_exists($output)) {
                unlink($output);
            }
            return false;
        }
        if ($remove_sources == true) {
            unlink($input);
        }
        if (make_wav(Configuration::getWorkingDir() . '/tmp.wav', $output)) {
            unlink(Configuration::getWorkingDir() . '/tmp.wav');
            return true;
        } else {
            unlink(Configuration::getWorkingDir() . '/tmp.wav');
            return false;
        }
    } else {
        return false;
    }
}

function make_silence($duration, $output) {
    $cmd = 'sox -q -n -r 44100 -c 2 "' . $output . '" trim 0.0 ' . $duration;
    exec($cmd, $result, $exit_code);
    echo $cmd . "\r\n";
    if ($exit_code != 0) {
        echo "Exit status: $exit_code\r\n";
        if (file_exists($output)) {
            unlink($output);
        }
        return false;
    }
    return true;
}

function track_concatenate($first, $second, $output, $remove_sources = true) {
    if (file_exists($first) and file_exists($second)) {
        $cmd = 'sox -q --combine concatenate -r 44100 -c 2 "' . $first . '" -r 44100 -c 2 "' . $second . '" -r 44100 -c 2 "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n";
            if (file_exists($output)) {
                unlink($output);
            }
            return false;
        } elseif ($remove_sources == true) {
            unlink($first);
            unlink($second);
        }
        return true;
    } else {
        return false;
    }
}

function track_merge($first, $second, $output, $remove_sources = true) {
    if (file_exists($first) and file_exists($second)) {
        $cmd = 'sox -q -m -r 44100 -c 2 "' . $first . '" -r 44100 -c 2 "' . $second . '" -r 44100 -c 2 "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            if (file_exist($output)) {
                unlink($output);
            }
            return false;
        } elseif ($remove_sources == true) {
            unlink($first);
            unlink($second);
        }
        return true;
    } else {
        return false;
    }
}

function track_reverse($in, $out, $remove_sources = true) {
    if (file_exists($in)) {
        $cmd = 'sox -q "' . $in . '" "' . $out . '" reverse';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            if (file_exist($out)) {
                unlink($out);
            }
            return false;
        } elseif ($remove_sources == true) {
            unlink($in);
        }
        return true;
    } else {
        return false;
    }
}

function track_length($input) {
    if (file_exists($input)) {
        $cmd = 'soxi -D "' . $input . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            return 0;
        }
        return $content;
    } else {
        return 0;
    }
}

function json_add($original, $key, $value, $cumulative = true) {
    $array = mkarray(json_decode($original));
    if ($cumulative == true && count($array) > 0) {
        foreach ($array as $array_key=>$array_value) {
            // A dirty way to get the last key=>value pair
        }
        $key = (float) $array_key + (float) $key;
    }
    $array[(string) $key] = $value;
    return json_encode($array);
}

function make_wav($input, $output) {
    if (file_exists($input)) {
        $cmd = 'sox -q "' . $input . '" -r 44100 -c 2 -t wavpcm "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            unlink($output);
            return false;
        }
        return true;
    } else {
        return false;
    }
}

function mkarray($json) {
    $array = (array) $json;
    foreach($array as $array_key => $array_item) {
        if (is_object($array_item)) {
            $array[(string) $array_key] = mkarray($array_item);
        }
    }
    return $array;
}

function download_file($url) {
  echo "Downloading file: $url\r\n";
  $get = curl_get($url);
  if($get[1]['http_code'] == 200) {
    return $get[0];
  } else {
    echo "Download failed. Error code: " . $get[1]['http_code'] . "\r\n";
    unlink($get[0]);
    return false;
  }
}

function make_output($input, $output_root, $arrMetadata) {
    // eyeD3 doesn't support "MP3 Extended" (AKA MP3 with Chapter Support), but it has been requested.
    make_output_mp3($input, $output_root . 'mp3', $arrMetadata);
    // Chapter information thanks to this page: http://code.google.com/p/subler/wiki/ChapterTextFormat
    make_output_oga($input, $output_root . 'oga', $arrMetadata);
    make_output_m4a($input, $output_root . 'm4a', $arrMetadata);
//    unlink($input);
}

function make_output_mp3($input, $output, $arrMetadata) {
    if (file_exists($input)) {
        $cmd = 'sox "' . $input . '" -t mp3 "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            unlink($output);
            return false;
        }
        $cmd = 'eyeD3';
        if (isset($arrMetadata['Artist']) and $arrMetadata['Artist'] != '') {
            $cmd .= ' --artist="' . $arrMetadata['Artist'] . '"';
        }
        if (isset($arrMetadata['Title']) and $arrMetadata['Title'] != '') {
            $cmd .= ' --title="' . $arrMetadata['Title'] . '"';
        }
        if (isset($arrMetadata['AlbumArt']) and $arrMetadata['AlbumArt'] != '') {
            $cmd .= ' --add-image "' . $arrMetadata['AlbumArt'] . '":FRONT_COVER';
        }
        $cmd .= ' "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n";
            unlink($output);
            return false;
        }
        return true;
    } else {
        return false;
    }
}

function make_output_oga($input, $output, $arrMetadata) {
    if (file_exists($input)) {
        $cmd = 'sox "' . $input . '" -t ogg "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            unlink($output);
            return false;
        }
        $content = '';
        if (isset($arrMetadata['AlbumArt']) and $arrMetadata != false) {
            $in = fopen($arrMetadata['AlbumArt'], "r");
            $content .= "METADATA_BLOCK_PICTURE=";
            $imgbinary = fread($in, filesize($arrMetadata['AlbumArt']));
            fclose($in);
            $content .= base64_encode($imgbinary) . "\r\n";
        }
        if (isset($arrMetadata['Title'])) {
            $content .= "TITLE={$arrMetadata['Title']}\r\n";
        }
        if (isset($arrMetadata['Artist'])) {
            $content .= "ARTIST={$arrMetadata['Artist']}\r\n";
        }
        if (isset($arrMetadata['RunningOrder']) && is_array($arrMetadata['RunningOrder']) && count($arrMetadata['RunningOrder']) > 0) {
            $chapter_no = 0;
            foreach ($arrMetadata['RunningOrder'] as $timestamp => $chapter) {
                $chapter_no++;
                $content .= 'CHAPTER';
                $content .= str_pad($chapter_no, 2, '0', STR_PAD_LEFT);
                $content .= '=';
                $content .= str_pad(intval(intval($timestamp) / 3600), 2, '0', STR_PAD_LEFT) . ':';
                $content .= str_pad(bcmod((intval($timestamp) / 60), 60), 2, '0', STR_PAD_LEFT) . ':';
                $content .= str_pad(bcmod(intval($timestamp), 60), 2, '0', STR_PAD_LEFT) . '.';
                $content .= $timestamp - intval($timestamp) . "\r\n";
                $content .= 'CHAPTER';
                $content .= str_pad($chapter_no, 2, '0', STR_PAD_LEFT);
                $content .= 'NAME=';
                if (is_array($chapter)) {
                    $content .= $chapter['strTrackName'] . ' by ' . $chapter['strArtistName'] . "\r\n";
                } else {
                    $content .= $chapter . "\r\n";
                }
            }
        }
        $out = fopen(Configuration::getWorkingDir() . '/oga_comments', 'w');
        fwrite($out, $content);
        fclose($out);
        $cmd = 'vorbiscomment --write "' . $output . '" --raw --commentfile "' . Configuration::getWorkingDir() . '/oga_comments' . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        unlink(Configuration::getWorkingDir() . '/oga_comments');
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            unlink($output);
            return false;
        }
        return true;
    } else {
        return false;
    }
}

function make_output_m4a($input, $output, $arrMetadata) {
    if (file_exists($input)) {
        $cmd = 'faac';
        if (isset($arrMetadata['Artist']) and $arrMetadata['Artist'] != '') {
            $cmd .= ' --artist="' . $arrMetadata['Artist'] . '"';
        }
        if (isset($arrMetadata['Title']) and $arrMetadata['Title'] != '') {
            $cmd .= ' --title="' . $arrMetadata['Title'] . '"';
        }
        $cmd .= ' -o "' . $output . '" "' . $input . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        $content = '';
        if (count($result) > 0) {
            foreach ($result as $line) {
                if ($content != '') {
                    $content .= "\r\n";
                }
                $content .= $line;
            }
        }
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n$content\r\n";
            unlink($output);
            return false;
        }
        if (isset($arrMetadata['AlbumArt']) and $arrMetadata['AlbumArt'] != '') {
            $cmd = 'mp4art --add "' . $arrMetadata['AlbumArt'] . '" ' . $output;
            exec($cmd, $result, $exit_code);
            echo $cmd . "\r\n";
            $content = '';
            if (count($result) > 0) {
                foreach ($result as $line) {
                    if ($content != '') {
                        $content .= "\r\n";
                    }
                    $content .= $line;
                }
            }
            if ($exit_code != 0) {
                echo "Exit status: $exit_code\r\n$content\r\n";
                unlink($output);
                return false;
            }
        }
        if (isset($arrMetadata['RunningOrder']) && is_array($arrMetadata['RunningOrder']) && count($arrMetadata['RunningOrder']) > 0) {
            $chapter_no = 0;
            foreach ($arrMetadata['RunningOrder'] as $timestamp => $chapter) {
                $chapter_no++;
                $content .= str_pad(intval(intval($timestamp) / 3600), 2, '0', STR_PAD_LEFT) . ':';
                $content .= str_pad(bcmod((intval($timestamp) / 60), 60), 2, '0', STR_PAD_LEFT) . ':';
                $content .= str_pad(bcmod(intval($timestamp), 60), 2, '0', STR_PAD_LEFT) . '.';
                $content .= str_pad(substr($timestamp - intval($timestamp), 0, 3), 3, '0', STR_PAD_RIGHT) . " ";
                if (is_array($chapter)) {
                    $content .= $chapter['strTrackName'] . ' by ' . $chapter['strArtistName'] . "\r\n";
                } else {
                    $content .= $chapter . "\r\n";
                }
            }
        }
        $out = fopen($output . '.chapters.txt', 'w');
        fwrite($out, $content);
        fclose($out);
        $cmd = 'mp4chaps --import "' . $output . '"';
        exec($cmd, $result, $exit_code);
        echo $cmd . "\r\n";
        unlink($output . '.chapters.txt');
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n";
            unlink($output);
            return false;
        }
        return true;
    } else {
        return false;
    }
}


/*==================================
Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)

This function derived from code at http://www.php.net/manual/en/ref.curl.php#93163

@return  array[0]    content or filename to process
         array[1]    array of response headers
==================================*/
function curl_get($url, $as_file = 1, $javascript_loop = 0, $timeout = 10000, $max_loop = 10) {
  $url = str_replace("&amp;", "&", urldecode(trim($url)));
  $cookie = tempnam(sys_get_temp_dir(), "CURLCOOKIE_");
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

  if($as_file == 1) {
    $tmpfname = tempnam(sys_get_temp_dir(), "UP_");
    $out = fopen($tmpfname, 'wb');
    if($out == FALSE) {
      die("Unable to write to $tmpfname\r\n");
    }
    curl_setopt($ch, CURLOPT_FILE, $out);
  }

  $content = curl_exec($ch);
  $response = curl_getinfo($ch);
  if(curl_errno($ch)) {
    $error_text = curl_error($ch);
    $error = 1;
  }
  curl_close($ch);
  if($as_file == 1) {
    fclose($out);
  }

  if(isset($error)) {
      return false;
  }

  if($response['http_code'] == 301 or $response['http_code'] == 302) {
    if($headers = get_headers($response['url'])) {
      foreach($headers as $value) {
        if(substr(strtolower($value), 0, 9) == "location:") {
          echo "Redirecting to " . trim(substr($value, 9, strlen($value))) . "\r\n";
          return get_url(trim(substr($value, 9, strlen($value))), $as_file);
        }
      }
    }
  }

  if($as_file == 0 and
     (preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) or
      preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) and
     $javascript_loop < $max_loop) {
    return get_url($value[1], 0, $javascript_loop+1, $max_loop);
  } else {
    if($as_file == 1) {
      return array($tmpfname, $response);
    } else {
      return array($content, $response);
    }
  }
}

function curl_post($url, $arrPost) {
  $timeout = 10000;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_AUTOREFERER, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPost);
  $result = curl_exec($ch);
  $response = curl_getinfo($ch);
  curl_close($ch);
  if($response['http_code'] != 200) {
    $state = false;
  } else {
    $state = true;
  }
  return array($state, $result, $response);
}
