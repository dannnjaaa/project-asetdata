<?php
$path = __DIR__ . '/../resources/views/asset/index.blade.php';
$lines = file($path);
$stack = [];
function pushStack(&$stack, $type, $line){ $stack[] = ['type'=>$type,'line'=>$line]; echo "PUSH {$type} at {$line}\n"; }
function popStack(&$stack, $expected=null, $line){
    $found = array_pop($stack);
    echo "POP at {$line} -> ".($found['type'] ?? 'NONE')." opened at ".($found['line'] ?? 'N/A')."\n";
}

foreach($lines as $i=>$line){
    $ln = $i+1;
    if(preg_match('/@push\s*\(/', $line)) pushStack($stack,'push',$ln);
    if(preg_match('/@endpush\b/', $line)) popStack($stack,'push',$ln);
    if(preg_match('/@section\s*\(/', $line)) pushStack($stack,'section',$ln);
    if(preg_match('/@endsection\b/', $line)) popStack($stack,'section',$ln);
    if(preg_match('/@php\b/', $line)) pushStack($stack,'php',$ln);
    if(preg_match('/@endphp\b/', $line)) popStack($stack,'php',$ln);
    if(preg_match('/@if\s*\(/', $line)) pushStack($stack,'if',$ln);
    if(preg_match('/@endif\b/', $line)) popStack($stack,'if',$ln);
}

if(!empty($stack)){
    echo "\nRemaining stack:\n";
    foreach($stack as $s) echo " - {$s['type']} opened at {$s['line']}\n";
} else echo "\nNo remaining stack\n";
