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
  $border=htmlentities($argv['border']);
  if (empty($align)) {$align="right";};
  if (empty($width)) {$width="100%";};
  if (!is_numeric($border)) {$border=0;};
  if (!empty($caption)) 
  {
    $tableopen="<table width=$width border=$border align=$align>
      <tr border=0><td style=\"border:0px\">"; // table rows do not have an extra border
    $tableclose="</td><tr><td style=\"border:0px\" align=center> 
      $caption</td></tr></table>"; // table cells do not have an extra border
    $myimage="<img src=$src width=100% />"; // the table width is already scaled down so the image width must be 100%
  } 
  else 
  {
    $tableopen=""; $tableclose="";
    $myimage="<img src=$src width=$width align=$align />";
  }
  $result="$tableopen$myimage$tableclose";
  $result=preg_replace("/\n/","",$result);
  return $result;
}

