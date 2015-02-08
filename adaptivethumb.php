<?php

// <pic src="/mindmap.jpg" size=30% align=right caption="this is a mindmap" />

$wgExtensionFunctions[] = "wfpicextension";

function wfpicextension()
{
  new picextension();
}

class picextension 
{

  public function __construct()
  {
    global $wgParser;
    $wgParser->setHook('pic', array(&$this, 'pichtml'));
  }

  function pichtml($code, $argv, $parser)
  {
    $align=htmlentities($argv['align']);
    $width=htmlentities($argv['width']); // needn't be numeric, could be "50%"
    $src=htmlentities($argv['src']);
    $caption=htmlentities($argv['caption']);
    $border=htmlentities($argv['border']);
    $link=htmlentities($argv['link']);
    if (!empty($link)) {$linkopen="<a href=$link>"; $linkclose="</a>";};
    if (empty($align)) {$align="right";};
    if (empty($width)) {$width="100%";};
    if (!is_numeric($border)) {$border=0;};
    if (!empty($caption)) 
    {
      $parsedcaption=$parser->parse($caption, $parser->mTitle, $parser->mOptions, false, false);
      $parsedcaptiontext=$parsedcaption->getText();
      $tableopen="<table width=$width border=$border align=$align>
        <tr border=0><td style=\"border:0px\">"; // table rows do not have an extra border
      $tableclose="</td><tr><td style=\"border:0px\" align=center> 
		".$parsedcaptiontext."</td></tr></table>"; // table cells do not have an extra border
      $myimage="<img src=$src width=100% />"; // the table width is already scaled down so the image width must be 100%
    } 
    else 
    {
      $tableopen=""; $tableclose="";
      $myimage="<img src=$src width=$width align=$align />";
    }
    $result="$tableopen$linkopen$myimage$linkclose$tableclose";
    $result=preg_replace("/\n/","",$result);
    return $result;
  }
}


