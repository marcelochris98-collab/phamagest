<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupCommandsTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_backup_refuses_unsupported_database_drivers(): void
    {
        config(['database.default' => 'sqlite']);

        $this->artisan('database:backup')
            ->expectsOutput('Database backups currently support MySQL and MariaDB only.')
            ->assertExitCode(1);
    }

    public function test_database_restore_refuses_non_interactive_execution(): void
    {
        $this->artisan('database:restore', [
            'file' => 'phamagest-20261124-020000.sql.gz',
            '--no-interaction' => true,
        ])
            ->expectsOutput('Restore requires an interactive operator confirmation.')
            ->assertExitCode(1);
    }
}
