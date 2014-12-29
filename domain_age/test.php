<?php
$url = " http://en.wikipedia.org/wiki/Search_engine_optimization";
echo get_domain($url);
exit;

function get_domain($url)
{
   $url=  trim($url);
  $pieces = parse_url($url);
  $domain = isset($pieces['host']) ? $pieces['host'] : '';
  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    return $regs['domain'];
  }
  return 10;
}



$pieces = parse_url($url);
$aDomains = explode('.', $pieces['host']);
if(count($aDomains) >= 3)
{
    $domain = $aDomains[1]."".$aDomains[2];
}
echo $domain;
//echo $restofdomain;
?>