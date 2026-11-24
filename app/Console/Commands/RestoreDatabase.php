<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;

class RestoreDatabase extends Command
{
    protected $signature = 'database:restore {file : Name of a backup in the private backup directory}';

    protected $description = 'Restore a MySQL database from a private compressed backup';

    public function handle(): int
    {
        if (! $this->input->isInteractive()) {
            $this->error('Restore requires an interactive operator confirmation.');

            return self::FAILURE;
        }

        $filename = $this->argument('file');
        if (! preg_match('/^phamagest-\d{8}-\d{6}\.sql\.gz$/', $filename)) {
            $this->error('The requested backup file was not found in the private backup directory.');

            return self::FAILURE;
        }

        $databaseName = config('database.default');
        if (! in_array($databaseName, ['mysql', 'mariadb'], true)) {
            $this->error('Restore currently supports MySQL and MariaDB only.');

            return self::FAILURE;
        }

        $diskName = config('backup.disk');
        $disk = Storage::disk($diskName);
        $backupKey = trim(config('backup.directory'), '/').'/'.$filename;
        if (! $disk->exists($backupKey)) {
            $this->error('The requested backup file was not found in the configured backup disk.');

            return self::FAILURE;
        }

        $temporaryPath = null;
        if ($diskName === 'local') {
            $backupDirectory = realpath(storage_path('app/'.config('backup.directory')));
            $path = realpath($disk->path($backupKey));
            if (! $backupDirectory || ! $path || ! str_starts_with($path, $backupDirectory.DIRECTORY_SEPARATOR)) {
                $this->error('The requested backup path is not a private file in the backup directory.');

                return self::FAILURE;
            }
        } else {
            $path = $this->downloadToPrivateTemporaryFile($disk->readStream($backupKey));
            $temporaryPath = $path;
        }

        try {
            $check = new Process(['gzip', '-t', $path]);
            $check->mustRun();

            $this->warn('WARNING: this will overwrite the selected database and cannot be undone.');
            if (! $this->confirm('Create an automatic pre-restore backup and continue?')) {
                return self::SUCCESS;
            }

            if (Artisan::call('database:backup') !== self::SUCCESS) {
                $this->error('Automatic pre-restore backup failed; restore was cancelled.');

                return self::FAILURE;
            }
            $this->line(Artisan::output());

            $database = config('database.connections.'.$databaseName);
            $restore = new Process([
                'mysql',
                '--host='.$database['host'],
                '--port='.$database['port'],
                '--user='.$database['username'],
                $database['database'],
            ], base_path(), ['MYSQL_PWD' => $database['password'] ?? ''], 3600);
            $restore->setInput($this->decompressedSql($path));
            $restore->mustRun();

            $this->info('Database restored successfully.');

            return self::SUCCESS;
        } finally {
            if ($temporaryPath && is_file($temporaryPath)) {
                unlink($temporaryPath);
            }
        }
    }

    private function downloadToPrivateTemporaryFile(mixed $source): string
    {
        if (! is_resource($source)) {
            throw new RuntimeException('Unable to read the backup from the configured storage disk.');
        }

        $directory = storage_path('app/private');
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            fclose($source);
            throw new RuntimeException('Unable to create the private restore directory.');
        }

        $path = tempnam($directory, 'phamagest-restore-');
        if ($path === false) {
            fclose($source);
            throw new RuntimeException('Unable to create a private temporary restore file.');
        }
        chmod($path, 0600);

        $target = fopen($path, 'wb');
        if ($target === false) {
            fclose($source);
            unlink($path);
            throw new RuntimeException('Unable to open the private temporary restore file.');
        }

        try {
            if (stream_copy_to_stream($source, $target) === false) {
                throw new RuntimeException('Unable to download the backup from the configured storage disk.');
            }
        } catch (\Throwable $exception) {
            unlink($path);
            throw $exception;
        } finally {
            fclose($source);
            fclose($target);
        }

        return $path;
    }

    private function decompressedSql(string $path): \Generator
    {
        $input = gzopen($path, 'rb');
        if ($input === false) {
            throw new RuntimeException('Unable to read the selected backup.');
        }

        try {
            while (! gzeof($input)) {
                $chunk = gzread($input, 8192);
                if ($chunk === false) {
                    throw new RuntimeException('The selected backup could not be decompressed.');
                }
                yield $chunk;
            }
        } finally {
            gzclose($input);
        }
    }
}
