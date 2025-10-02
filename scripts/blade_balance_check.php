<?php
$path = __DIR__ . '/../resources/views/asset/index.blade.php';
$lines = file($path);
$stack = [];
$pairs = [
    'if' => ['open'=>'@if', 'close'=>'@endif'],
    'push' => ['open'=>'@push', 'close'=>'@endpush'],
    'section' => ['open'=>'@section', 'close'=>'@endsection'],
    'php' => ['open'=>'@php', 'close'=>'@endphp'],
];

function pushStack(&$stack, $type, $line){ $stack[] = ['type'=>$type,'line'=>$line]; }
function popStack(&$stack, $expected=null){
    if(empty($stack)) return ['ok'=>false,'found'=>null];
    $last = array_pop($stack);
    if($expected && $last['type'] !== $expected) return ['ok'=>false,'found'=>$last];
    return ['ok'=>true,'found'=>$last];
}

foreach($lines as $i=>$line){
    $ln = $i+1;
    // detect @push('...') or @push(
    if(preg_match('/@push\s*\(/', $line)){
        pushStack($stack,'push',$ln);
    }
    if(preg_match('/@endpush\b/', $line)){
        $res = popStack($stack);
        $foundType = $res['found']['type'] ?? 'NONE';
        if(!$res['ok'] || $foundType !== 'push'){
            echo "MISMATCH: @endpush at line $ln does not match open (found: {$foundType})\n";
        }
    }
    if(preg_match('/@section\s*\(/', $line)){
        pushStack($stack,'section',$ln);
    }
    if(preg_match('/@endsection\b/', $line)){
        $res = popStack($stack);
        $foundType = $res['found']['type'] ?? 'NONE';
        if(!$res['ok'] || $foundType !== 'section'){
            echo "MISMATCH: @endsection at line $ln does not match open (found: {$foundType})\n";
        }
    }
    if(preg_match('/@php\b/', $line)){
        pushStack($stack,'php',$ln);
    }
    if(preg_match('/@endphp\b/', $line)){
        $res = popStack($stack);
        $foundType = $res['found']['type'] ?? 'NONE';
        if(!$res['ok'] || $foundType !== 'php'){
            echo "MISMATCH: @endphp at line $ln does not match open (found: {$foundType})\n";
        }
    }
    // @if/@elseif/@else/@endif
    if(preg_match('/@if\s*\(/', $line)){
        pushStack($stack,'if',$ln);
    }
    if(preg_match('/@endif\b/', $line)){
        $res = popStack($stack);
        $foundType = $res['found']['type'] ?? 'NONE';
        if(!$res['ok'] || $foundType !== 'if'){
            echo "MISMATCH: @endif at line $ln does not match open (found: {$foundType})\n";
        }
    }
}

if(!empty($stack)){
    echo "UNMATCHED OPENING DIRECTIVES:\n";
    foreach($stack as $item){
        echo " - {$item['type']} opened at line {$item['line']}\n";
    }
} else {
    echo "All directives matched.\n";
}

// Also print counts
$txt = file_get_contents($path);
$counts = [
    'if' => preg_match_all('/@if\s*\(/', $txt),
    'endif' => preg_match_all('/@endif\b/', $txt),
    'push' => preg_match_all('/@push\s*\(/', $txt),
    'endpush' => preg_match_all('/@endpush\b/', $txt),
    'section' => preg_match_all('/@section\s*\(/', $txt),
    'endsection' => preg_match_all('/@endsection\b/', $txt),
    'php' => preg_match_all('/@php\b/', $txt),
    'endphp' => preg_match_all('/@endphp\b/', $txt),
];

echo "\nCounts:\n";
foreach($counts as $k=>$v) echo "$k: $v\n";
