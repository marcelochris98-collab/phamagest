<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    protected $signature = 'database:backup';

    protected $description = 'Create a compressed private backup of the MySQL database';

    public function handle(): int
    {
        $database = config('database.connections.'.config('database.default'));
        if (! in_array(config('database.default'), ['mysql', 'mariadb'], true)) {
            $this->error('Database backups currently support MySQL and MariaDB only.');

            return self::FAILURE;
        }

        $directory = storage_path('app/'.config('backup.directory'));
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            throw new RuntimeException('Unable to create the private backup directory.');
        }

        $filename = 'phamagest-'.now()->format('Ymd-His').'.sql.gz';
        $path = $directory.'/'.$filename;
        $output = gzopen($path, 'wb9');
        if ($output === false) {
            throw new RuntimeException('Unable to create the compressed backup file.');
        }

        try {
            $process = new Process([
                'mysqldump',
                '--single-transaction',
                '--routines',
                '--triggers',
                '--host='.$database['host'],
                '--port='.$database['port'],
                '--user='.$database['username'],
                $database['database'],
            ], base_path(), ['MYSQL_PWD' => $database['password'] ?? ''], 3600);

            $process->run(function (string $type, string $buffer) use ($output) {
                if ($type === Process::OUT && gzwrite($output, $buffer) === false) {
                    throw new RuntimeException('Unable to write the database backup.');
                }
            });
            gzclose($output);
            $output = false;

            if (! $process->isSuccessful()) {
                throw new RuntimeException('Database backup failed: '.$process->getErrorOutput());
            }

            chmod($path, 0600);

            $diskName = config('backup.disk');
            if ($diskName !== 'local') {
                $stream = fopen($path, 'rb');
                $configPath = trim(config('backup.directory'), '/').'/'.$filename;
                if ($stream === false || ! Storage::disk($diskName)->put($configPath, $stream)) {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }

                    throw new RuntimeException('Unable to store the backup on the configured backup disk.');
                }
                fclose($stream);
                unlink($path);
                $this->info("Backup stored on {$diskName}: {$configPath}");
            } else {
                $this->info("Backup created: {$path}");
            }

            $this->pruneBackups($diskName);

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            if (is_resource($output)) {
                gzclose($output);
            }
            if (is_file($path)) {
                unlink($path);
            }

            throw $exception;
        }
    }

    private function pruneBackups(string $diskName): void
    {
        $cutoff = now()->subDays(config('backup.retention_days'))->getTimestamp();
        $disk = Storage::disk($diskName);
        $directory = trim(config('backup.directory'), '/');

        foreach ($disk->files($directory) as $backup) {
            if (preg_match('/^phamagest-\d{8}-\d{6}\.sql\.gz$/', basename($backup))
                && $disk->lastModified($backup) < $cutoff) {
                $disk->delete($backup);
            }
        }
    }
}
