<?php 
namespace App\Model;
use App\Database\Database;
class Employee extends Database{
    public function getAll(){
        return$this->query("SELECT * FROM employees")->resultSet();
    }
    public function create($data)
    {
        $this->query("INSERT INTO employees (name, title, parent_id, photo_url) VALUES (:name, :title, :parent_id, :photo_url)")
            ->bind(':name', $this->sanitize($data['name']))
            ->bind(':title', $this->sanitize($data['title']))
            ->bind(':parent_id', $this->sanitize($data['parent_id']))
            ->bind(':photo_url', $this->sanitize($data['photo_url']))
            ->execute();
        return $this->lastInsertId();
    }
}