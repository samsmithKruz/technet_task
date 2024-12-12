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
    \$sql = "INSERT INTO employees (name, title, parent_id, photo_url) VALUES (:name, :title, :parent_id, :photo_url)";
    
    for (\$i = 0; \$i < 10; \$i++) {
         \$db->query(\$sql)
        ->bind(':name', \$faker->name())
        ->bind(':title', \$faker->jobTitle())
        ->bind(':parent_id', \$faker->randomElement([null, rand(1, 5)])) // Random parent_id (null or random existing ID)
        ->bind(':photo_url', \$faker->imageUrl(200, 200, 'people')) // Generate a random image URL for the photo
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
