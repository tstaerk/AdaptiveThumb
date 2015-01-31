<?php

// <pic src="/mindmap.jpg" size=30% align=right caption="this is a mindmap" />

$wgExtensionFunctions[] = "wfpicextension";

function wfpicextension()
{
  global $wgParser;
  $wgParser->setHook("pic", "pichtml");
}

function pichtml($code, $argv)
{
  $align=htmlentities($argv['align']);
  $width=htmlentities($argv['width']); // needn't be numeric, could be "50%"
  $src=htmlentities($argv['src']);
  $caption=htmlentities($argv['caption']);
  $mode=htmlentities($argv['mode']);
  if (empty($align)) {$align="right";};
  if (empty($width)) {$width="100%";};
  if (!empty($caption)) 
  {
    $mytable1="<table width=$width border=2 align=$align>
      <tr border=0><td style=\"border:0px\">";
    $mytable2="</td><tr><td style=\"border:0px\" align=center>
      $caption</td></tr></table>";
    $myimage="<img src=$src width=100% />";
  } 
  else 
  {
    $mytable1=""; $mytable2="";
    $myimage="<img src=$src width=$width align=$align />";
  }
  $result="<div style=\"border:1px;solid;color=\"cccccc\"> $mytable1 $myimage $mytable2 </div>";
  $result=preg_replace("/\n/","",$result);
  return $result;
}

