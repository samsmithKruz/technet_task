<?php

// Example Seeder File
// This function will be executed when the seeder is run.
// $db is the database instance and $faker is the Faker instance.

return function($db, $faker) {
    $sql = "INSERT INTO employees (name, title, parent_id, photo_url) VALUES (:name, :title, :parent_id, :photo_url)";
    
    for ($i = 0; $i < 10; $i++) {
         $db->query($sql)
        ->bind(':name', $faker->name())
        ->bind(':title', $faker->jobTitle())
        ->bind(':parent_id', $faker->randomElement([null, rand(1, 5)])) // Random parent_id (null or random existing ID)
        ->bind(':photo_url', $faker->imageUrl(200, 200, 'people')) // Generate a random image URL for the photo
        ->execute();
    }
};
