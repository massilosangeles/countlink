<?php
// Function for take content between two delimeter 
function extract_unit($string, $start, $end)
{
  $pos        = stripos($string, $start);
  $str        = substr($string, $pos);
  $str_two    = substr($str, strlen($start));
  $second_pos = stripos($str_two, $end);
  $str_three  = substr($str_two, 0, $second_pos);
  $unit       = trim($str_three); // remove whitespaces
  return $unit;
}
// FORM MODE
extract($_POST);
// Verification of the captcha here ...
$privatekey = "6LeLTSQUAAAAACX4j177jA6L6Nl5aPraDvyvzuSq";
$response   = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $privatekey . "&response=" . $captcha_response . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
if ($response == false) {
  echo 'You are a robot!';
  
} else {
  if ($site_url == '' && $mail == '' && $newsletter == '' && $service == '') {
    echo "empty input";
  } else {
    // TEST MODE
    //$site_url = "www.example.com";
    // Create a tab with all parti of the url
    $tab_url     = explode(".", $site_url);
    $count       = 1;
    $intern_link = '';
    $add_http    = false;
    // Create the intern url compare
    foreach ($tab_url as $value) {
      if ($count == sizeof($tab_url)) {
        if ($add_http) {
          $intern_link = "http://" . $intern_link;
        }
        break;
      }
      if ($count == 1) {
        if ($value != "http://www") {
          $add_http = true;
        }
      }
      $intern_link = $intern_link . $value . ".";
      $count++;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Write the source page in resultat and close curl
    $site = curl_exec($ch);
    curl_close($ch);
    //Just see in the body
    $site = extract_unit($site, "<body", "</body>");
    
    $links_all    = substr_count($site, "href");
    $links_intern = substr_count($site, 'href="' . substr($intern_link, 0, -1));
    $links_intern += substr_count($site, "href='" . substr($intern_link, 0, -1));
    $link_extern = $links_all - $links_intern;
    
    print "There is " . $links_intern . " internal link in that page. And " . $link_extern . " external link";
  }
}

?>