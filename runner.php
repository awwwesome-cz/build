<?php

declare(strict_types=1);

function run_build_script(string $command, string $workingDir): int
{
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($command, $descriptors, $pipes, $workingDir);

    if (!is_resource($process)) {
        http_response_code(500);
        echo "Nepodařilo se spustit proces.\n";
        return 1;
    }

    fclose($pipes[0]);

    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    while (true) {
        $read = [];
        if (!feof($pipes[1])) {
            $read[] = $pipes[1];
        }
        if (!feof($pipes[2])) {
            $read[] = $pipes[2];
        }

        if (empty($read)) {
            break;
        }

        $write = null;
        $except = null;
        if (stream_select($read, $write, $except, 1) > 0) {
            foreach ($read as $stream) {
                $chunk = fread($stream, 4096);
                if ($chunk !== false && $chunk !== '') {
                    echo $chunk;
                    flush();
                }
            }
        }
    }

    fclose($pipes[1]);
    fclose($pipes[2]);

    return proc_close($process);
}
