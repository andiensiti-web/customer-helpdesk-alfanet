<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class MlPriorityService
{
    public function predict(string $jenisGangguan, string $deskripsi): string
    {
        $python = base_path('.venv\\Scripts\\python.exe');
        $script = base_path('ml\\predict.py');

        $process = new Process([
            $python,
            $script,
            $jenisGangguan,
            $deskripsi,
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                'Gagal menjalankan Machine Learning: '.$process->getErrorOutput()
            );
        }

        return trim($process->getOutput());
    }
}