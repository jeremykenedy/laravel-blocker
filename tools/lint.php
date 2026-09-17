<?php

$failed = false;
foreach (['src', 'tests', 'tools'] as $directory) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../'.$directory));
    foreach ($files as $file) {
        if ($file->isFile() && substr($file->getFilename(), -4) === '.php' && strpos($file->getPathname(), '/vendor/') === false && substr($file->getFilename(), -10) !== '.blade.php') {
            exec(escapeshellarg(PHP_BINARY).' -l '.escapeshellarg($file->getPathname()).' 2>&1', $output, $status);
            if ($status !== 0) {
                echo implode("\n", $output)."\n";
                $failed = true;
            }
            $output = [];
        }
    }
}
echo $failed ? "PHP lint failed.\n" : "PHP syntax checks passed.\n";
exit($failed ? 1 : 0);
