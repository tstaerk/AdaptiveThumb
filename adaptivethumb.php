<?php

/*
    adaptivethumb - a mediawiki extension that allows sizeable pictures 
    (and thumbnails) in a mediawiki page, e.g. with the line
    <pic src="/mindmap.jpg" size=30% align=right caption="this is a mindmap" />
    
    Copyright (C) 2016 by Thorsten Staerk <spam@staerk.de>
    http://www.staerk.de/thorsten

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/


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
    $title=htmlentities($argv['title']);
    $alt=htmlentities($argv['alt']);
    $margin=htmlentities($argv['margin']);
    if (!empty($link)) {$linkopen="<a href=$link>"; $linkclose="</a>";};
    if (empty($align)) {$align="right";};
    if (empty($width)) {$width="100%";};
    if (!is_numeric($border)) {$border=0;};
    $myimage="<img src=$src width=$width title=\"$title\" alt=\"$alt\" align=$align style=\"margin-right:$margin;margin-left:$margin;margin-top:$margin;margin-bottom:$margin\" />";
    if (!empty($caption)) 
    {
      $parsedcaption=$parser->parse($caption, $parser->mTitle, $parser->mOptions, false, false);
      $parsedcaptiontext=$parsedcaption->getText();
      $tableopen="<table width=$width border=$border align=$align>
        <tr border=0><td style=\"border:0px\">"; // table rows do not have an extra border
      $tableclose="</td><tr><td style=\"border:0px\" align=center> 
		".$parsedcaptiontext."</td></tr></table>"; // table cells do not have an extra border
      $myimage="<img src=$src width=100% title=\"$title\" alt=\"$alt\" />"; // the table width is already scaled down so the image width must be 100%. The alignment is already with the table.
    } 
    else 
    {
      $tableopen=""; $tableclose="";
    }
    $result="$tableopen$linkopen$myimage$linkclose$tableclose";
    global $wgAllowExternalImages;
    if ($wgAllowExternalImages==false) {$result="";};
    $result=preg_replace("/\n/","",$result);
    return $result;
  }
}


