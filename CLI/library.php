<?php

/**
 * TODO: Merge library.php into an appropriate class
 */
function make_sable($input, $output) {
    if (file_exists($input)) {
        $cmd = 'text2wave -o "' . Configuration::getWorkingDir() . '/tmp.wav' . '" "' . $input . '"';
        exec($cmd, $result, $exit_status);
        echo $cmd . "\r\n";
        if ($exit_status != 0) {
            echo "Exit status: $exit_status\r\n";
            if (file_exists($output)) {
                unlink($output);
            }
            return false;
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
    exec($cmd, $result, $exit_status);
    echo $cmd . "\r\n";
    if ($exit_status != 0) {
        echo "Exit status: $exit_status\r\n";
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
        exec($cmd, $result, $exit_status);
        echo $cmd . "\r\n";
        if ($exit_status != 0) {
            echo "Exit status: $exit_status\r\n";
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
        exec($cmd, $result, $exit_status);
        echo $cmd . "\r\n";
        if ($exit_status != 0) {
            echo "Exit status: $exit_status\r\n";
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
        exec($cmd, $result, $exit_status);
        echo $cmd . "\r\n";
        if ($exit_status != 0) {
            echo "Exit status: $exit_status\r\n";
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
        if ($exit_code != 0) {
            echo "Exit status: $exit_code\r\n";
            return 0;
        }
        return $result;
    } else {
        return 0;
    }
}

function json_add($original, $key, $value, $cumulative = true) {
    $array = (array) json_decode($original);
    if ($cumulative == true && count($array) > 0) {
        foreach ($array as $array_key=>$array_value) {
            // A dirty way to get the last key=>value pair
        }
        $key = $array_key + $key;
    }
    $array[(string) $key] = $value;
    return json_encode($array);
}

function make_wav($input, $output) {
    if (file_exists($input)) {
        $cmd = 'sox -q "' . $input . '" -r 44100 -c 2 "' . $output . '"';
        exec($cmd, $result, $exit_status);
        echo $cmd . "\r\n";
        if ($exit_status != 0) {
            echo "Exit status: $exit_status\r\n";
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