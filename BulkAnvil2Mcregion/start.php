<?php

$start = microtime(true);

cli_set_process_title("Legoboy's Bulk Map Converter Ver 0.1");

echo "Scanning directory...\n\n";

$worldDir = getcwd() . '\\worlds';

if(!file_exists($worldDir)){
	@mkdir('worlds');
}

$files = scandir($worldDir);

if(($key = array_search('.', $files)) !== false){
	unset($files[$key]);
}
if(($key = array_search('..', $files)) !== false){
	unset($files[$key]);
}

$count = count($files);
if($count === 0){
	echo "There are no maps to convert!\n";
	exit(1);
}
echo "Total number of worlds: $count\n\n";

foreach($files as $folder){
	if(!is_dir($worldDir . '\\' . $folder) || $folder === '.' || $folder === '..'){
		continue;
	}
	echo "Started conversion of world '$folder'.\n\n";
	exec("java -jar AnvilToRegion.jar $worldDir\\$folder", $output, $error);
	if($error !== 0){
		echo "There was a problem converting '$folder'. Maybe it is not a valid Anvil world?\n\n";
		unset($output);
		continue;
	}
	foreach($output as $line){
		echo $line . PHP_EOL;
	}
	unset($output);
	echo "Done with the world '$folder'!\n\n";
}
echo "All worlds have been converted!\n";
echo "Conversion took " . (microtime(true) - $start) . " seconds.\n";

echo "Do you want to delete all .mca files and level.dat_avl files? Type 'yes' to delete and anything else to save them.\n";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if(strtolower(trim($line)) === 'yes'){
    array_map('unlink', glob("$worldDir\\*\\region\\*.mca"));
    array_map('unlink', glob("$worldDir\\*\\level.dat_avl"));
}
fclose($handle);
echo "Done!\n";
exit(1);
