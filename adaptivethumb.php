<?php

// <pic src="/mindmap.jpg" size=30% align=right />

$wgExtensionFunctions[] = "wfpicextension";

function wfpicextension()
{
  global $wgParser;
  $wgParser->setHook("pic", "pichtml");
}

function pichtml($code, $argv)
{
  $param1=reset($argv); # get first value of array
  $param2=next($argv);
  $param3=next($argv);
  $param4=next($argv);
  $result="<div style=\"border:1px;solid;color=\"cccccc\">
<table width=$param2 border=1 align=$param3>
<tr border=0>
<td style=\"border:0px\">
<img src=$param1 width=100% />
</td>
</tr>
<tr>
<td style=\"border:0px\" align=center>
$param4
</td>
</tr>
</table>
</div>
";
  $result=preg_replace("/\n/","",$result);
  return $result;
}

