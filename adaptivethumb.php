<?php

// <pic src="/mindmap.jpg" size=30% align=right />
// TODO: avoid code injection with this secure string

$wgExtensionFunctions[] = "wfpicextension";

function wfpicextension()
{
  global $wgParser;
  $wgParser->setHook("pic", "pichtml");
}

function pichtml($code, $argv)
{
  $align=$argv['align'];
  $width=$argv['width'];
  $src=$argv['src'];
  $caption=$argv['caption'];
  if (empty($align)) {$align="right";};
  if (empty($width)) {$width="100%";};
  $result="<div style=\"border:1px;solid;color=\"cccccc\">
<table width=$width border=1 align=$align>
<tr border=0>
<td style=\"border:0px\">
<img src=$src width=100% />
</td>
</tr>
<tr>
<td style=\"border:0px\" align=center>
$caption
</td>
</tr>
</table>
</div>
";
  $result=preg_replace("/\n/","",$result);
  return $result;
}

