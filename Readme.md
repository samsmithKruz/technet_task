# Interactive Organization Chart
This project allows you to create and display an interactive organization chart using PHP, JavaScript, and a MySQL database.

## Requirements
* PHP 7.4 or later
* Composer (package manager for PHP)
* MySQL database server
## Installation
1. **Clone the repository:**
```bash
git clone https://github.com/your-username/org-chart.git
```
2. **Install Dependencies:**
Navigate to the project directory and run:
```bash
cd org-chart # navigate to the Project Folder
composer install
```
3. **Create database and user**
Create a MySQL database named org_chart and a user with appropriate permissions to access it.
4. **Update configuration:**
Edit the file php/config.php and update the database connection details:
```bash
$db_host = 'localhost';
$db_name = 'org_chart';
$db_user = 'your_username';
$db_password = 'your_password';
```
*Replace the placeholders with your actual database credentials.*
5. **Migrate and seed data:**
```bash
cd php/database #from project root
php script.php migrate
```
*This will create the necessary tables in your database and populate it with the sample data.*

6.  **Start the application**
Open a terminal in the project root directory and run:
```bash
php -S localhost:8000
```
*This will start a built-in PHP development server on port 8000.*

7. **Access the application:**
Open your web browser and navigate to http://localhost:8000. You should see the interactive organization chart displayed.

***
*You can add more users via the http://localhost:8000/api/createEmployee*
