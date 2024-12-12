<?php

use App\Database\Database;
use App\Model\Employee;
use Faker\Factory;

require_once __DIR__ . "/php/bootstrap.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");


class Api
{
    public function __construct()
    {
        $route = str_replace('/Api.php/', '/', $_SERVER['REQUEST_URI']);
        
        // Split the route into components
        $parts = explode('/', $route);
        array_shift($parts);
        // Get the class name and method name, setting defaults
        $methodName = $parts[0] ?: 'index'; // Default to index
        array_shift($parts);

        // Check if method exists
        if (method_exists($this, $methodName)) {
            // Call the method with remaining parts as parameters
            echo json_encode($this->$methodName($parts));
            exit();
        } else {
            echo json_encode([
                'code' => 404,
                'message' => "Path ($methodName) not found",
                'data' => []
            ]);
            exit();
        }
    }

    public function index()
    {
        echo json_encode(['message' => 'Welcome to the API']);
        exit();
    }

    public function getEmployees()
    {
        $employee = new Employee();
        $employees = $employee->getAll();
        echo json_encode($employees);
        exit();
    }

    public function createEmployee()
    {
        $employee = new Employee();

        // Example data, replace with input from POST request
        $data = [
            'name' => 'Jane Doe',
            'title' => 'Manager',
            'parent_id' => 1,
            'photo_url' => '/assets/img/jane.jpg'
        ];

        $employeeId = $employee->create($data);
        echo json_encode(['message' => 'Employee created', 'id' => $employeeId]);
        exit();
    }
    public function fakeEmployee()
    {
        $faker = Factory::create();
        $db = new Database();

        // SQL for inserting sample data into the employees table
        $sql = "INSERT INTO employees (name, title, parent_id, photo_url) VALUES (:name, :title, :parent_id, :photo_url)";

        // Insert 10 sample employee records into the database
        $db->query($sql)
            ->bind(':name', $faker->name())
            ->bind(':title', $faker->jobTitle())
            ->bind(':parent_id', $faker->randomElement([null, rand(1, 5)])) // Random parent_id (null or random existing ID)
            ->bind(':photo_url', $faker->imageUrl(200, 200, 'people')) // Generate a random image URL for the photo
            ->execute();
        echo json_encode(['message' => 'Employee created', 'id' => $db->lastInsertId()]);
        exit();
    }
}
new Api();
