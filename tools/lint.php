<?php

declare(strict_types=1);

$directories = [__DIR__ . '/../src', __DIR__ . '/../tests'];
$failed = false;

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        $command = escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname());
        passthru($command, $exitCode);

        if ($exitCode !== 0) {
            $failed = true;
        }
    }
}

exit($failed ? 1 : 0);
