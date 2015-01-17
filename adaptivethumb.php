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
  $result="<img src=$param1 width=$param2 align=$param3 />";
  //$result=implode($argv);
  //$result="result ".$argv[0];
  return $result;
}

