<?php

use App\Model\Migration;
use App\Model\Seeder;

require_once __DIR__ . '/../bootstrap.php';

$command = $argv[1] ?? null;
$action = $argv[2] ?? null;

$migration = new Migration();
$seeder = new Seeder();

switch ($command) {
    case 'make':
        if ($action === 'migrate') {
            $migration->make($argv[3] ?? 'new_migration');
        } elseif ($action === 'seed') {
            $seeder->make($argv[3] ?? 'new_seeder');
        } else {
            echo "Usage: php script.php make migrate|seed [name]\n";
        }
        break;

    case 'migrate':
        $migration->migrate();
        break;

    case 'seed':
        $seeder->seed($argv[2] ?? null);
        break;

    default:
        echo "Usage: php script.php make|migrate|seed [name]\n";
        break;
}
