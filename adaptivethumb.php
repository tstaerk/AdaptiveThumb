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

