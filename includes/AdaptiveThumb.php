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

class AdaptiveThumb 
{
  public static function onParserFirstCallInit(&$parser)
  {
    $parser->setHook("pic", [ __CLASS__, "pichtml"]);
    return true;
  }

  public static function pichtml($input, array $argv, Parser $parser, PPFrame $frame)
  {
    global $wgAllowExternalImages;
      
    $align = htmlentities($argv['align']);
    $width = htmlentities($argv['width']); // needn't be numeric, could be "50%"
    $fileName = htmlentities($argv['file']);
    $src = htmlentities($argv['src']);
    $caption = htmlentities($argv['caption']);
    $border = htmlentities($argv['border']);
    $link = htmlentities($argv['link']);
    $title = htmlentities($argv['title']);
    $alt = htmlentities($argv['alt']);
    $margin = htmlentities($argv['margin']);
    
    $prefix = "";
    
    // media viewer needs a file in a page to be triggered, include an invisible file tag
    if (!empty($fileName) && empty($link)) {
        // class="metadata": don't display this image itself (otherwise it will appear twice in media viewer
        $dummyMarkup = "<span class=\"metadata\" style=\"display:none\">[[File:$fileName]]</span>";
        $prefix = $parser->recursiveTagParse($dummyMarkup, $frame);
    }
    
    // either a trigger for the media viewer or a custom link
    $linkopen = empty($link) ?  "<a class=\"image\" style=\"cursor:pointer\">" : "<a href=$link>";
    $linkclose="</a>";
    
    if (empty($align))
      $align="right";
    if (empty($width))
      $width="100%";
    if (!is_numeric($border))
      $border=0;
    
    // file property takes precedence over src proprety
    if (strlen($fileName) != 0 && wfFindFile($fileName)) 
      $src=wfFindFile($fileName)->getFullUrl();
    
    // if we use a src url, external image urls must be allowed
    else if (!$wgAllowExternalImages || strlen($src) == 0)
      return "";
        
    $myimage="<img src=$src width=\"$width\" height=\"100%\" title=\"$title\" alt=\"$alt\" align=\$align style=\"margin-right:$margin;margin-left:$margin;margin-top:$margin;margin-bottom:$margin\" />";
    if (!empty($caption)) 
    {
      $parsedcaption=$parser->parse($caption, $parser->mTitle, $parser->mOptions, false, false);
      $parsedcaptiontext=$parsedcaption->getText();
      
      // table rows do not have an extra border
      $tableopen="<table width=$width border=$border align=$align><tr border=0><td style=\"border:0px\">"; 
      
      // table cells do not have an extra border
      $tableclose="</td><tr><td style=\"border:0px\" align=center>$parsedcaptiontext</td></tr></table>"; 
      
      // the table width is already scaled down so the image width must be 100%. The alignment is already with the table.
      $myimage="<img src=$src width=100% title=\"$title\" alt=\"$alt\" />"; 
    } 
    else 
    {
      $tableopen=""; 
      $tableclose="";
    }
    $result="$prefix$tableopen$linkopen$myimage$linkclose$tableclose";
    
    $result=preg_replace("/\n/","",$result);

    return $result;
  }
}