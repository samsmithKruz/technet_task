<?php
namespace App\Model;
use App\Database\Database;
use Exception;
use Faker\Factory;

class Seeder
{
    private $faker;
    private $db;
    private $seedersDir = __DIR__ . '/../database/seeders/';

    public function __construct()
    {
        $this->db = new Database();
        $this->faker = Factory::create();
    }

    public function make($name)
    {
        $timestamp = date('YmdHis');
        $filename = "{$timestamp}_{$name}.php";
        $filepath = $this->seedersDir . $filename;

        $template = <<<PHP
<?php

// Example Seeder File
// This function will be executed when the seeder is run.
// \$db is the database instance and \$faker is the Faker instance.

return function(\$db, \$faker) {
    \$sql = "INSERT INTO table_name (column1, column2, column3) VALUES (:value1, :value2, :value3)";
    
    for (\$i = 0; \$i < 10; \$i++) {
        \$db->query(\$sql)
            ->bind(':value1', \$faker->randomElement(['Option1', 'Option2', 'Option3']))
            ->bind(':value2', \$faker->date())
            ->bind(':value3', \$faker->name())
            ->execute();
    }
};

PHP;

        if (file_put_contents($filepath, $template)) {
            echo "Seeder created: $filename\n";
        } else {
            echo "Failed to create seeder file.\n";
        }
    }

    public function seed($name = null)
    {
        $files = array_diff(scandir($this->seedersDir), ['.', '..']);
        foreach ($files as $seeder) {
            if ($name && strpos($seeder, $name) === false) {
                continue;
            }

            $callback = include $this->seedersDir . $seeder;
            if (is_callable($callback)) {
                try {
                    $callback($this->db, $this->faker);
                    echo "Seeder applied: $seeder\n";
                } catch (Exception $e) {
                    echo "Failed to apply seeder: $seeder\n";
                    echo $e->getMessage() . "\n";
                }
            }
        }
    }
}
