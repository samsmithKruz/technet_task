<?php

namespace App\Model;

use App\Database\Database;
use Exception;

class Migration extends Database
{
    private $migrationsDir = __DIR__ . '/../database/migrations/';
    public function __construct()
    {
        parent::__construct();

        $this->query("
        CREATE TABLE IF NOT EXISTS migrations(
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        run_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
        ")->execute();
    }
    public function make($name)
    {
        $timestamp = date('YmdHis');
        $filename = "{$timestamp}_{$name}.php";
        $filepath = $this->migrationsDir . $filename;

        $template = <<<PHP
<?php
// Example SQL query: Modify this query for the specific migration.
return "
CREATE TABLE example_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;
";
PHP;

        if (file_put_contents($filepath, $template)) {
            echo "Migration created: $filename\n";
        } else {
            echo "Failed to create migration file.\n";
        }
    }
    public function migrate()
    {
        // Get already applied migrations
        $appliedMigrations = $this->query("SELECT migration FROM migrations")
            ->resultSet();
        $appliedMigrations = array_column($appliedMigrations, 'migration');

        // Get migration files
        $files = array_diff(scandir($this->migrationsDir), ['.', '..']);
        $pendingMigrations = array_diff($files, $appliedMigrations);

        foreach ($pendingMigrations as $migration) {
            $sql = include $this->migrationsDir . $migration;
            if ($sql) {
                try {
                    $this->beginTransaction();
                    $this->query($sql)->execute();
                    $this->query("INSERT INTO migrations (migration) VALUES (:migration)")
                        ->bind(':migration', $migration)
                        ->execute();
                    $this->commitTransaction();
                    echo "Migration applied: $migration\n";
                } catch (Exception $e) {
                    $this->rollbackTransaction();
                    echo "Failed to apply migration: $migration\n";
                    echo $e->getMessage() . "\n";
                }
            }
        }

        if (empty($pendingMigrations)) {
            echo "All migrations are up-to-date.\n";
        }
    }
}
