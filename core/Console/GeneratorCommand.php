<?php

namespace Core\Console;


class GeneratorCommand implements CommandInterface
{
    private $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function execute()
    {
        $option = explode(':', $this->token[0]);
        if (count($option) > 1) {
            switch ($option[1]) {
                case 'controller':
                    $this->generateController();
                    break;
                case 'filter':
                    $this->generateFilter();
                    break;
                case 'model':
                    $this->generateModel();
                    break;
                case 'migration':
                    $this->generateMigration();
                    break;
                default:
                    echo "Option `{$option[1]}` is not defined, try run `lightning help generator`\n";
            }
        } else {
            echo "Command `{$this->token[0]}` is not defined, try run `lightning list`\n";
        }
    }

    /**
     * Write to file.
     *
     * @param $output
     * @param $content
     */
    private function writeFile($output, $content)
    {
        if (file_exists($output)) {
            echo "File {$output} already exist!\n";
            exit();
        }

        $fh = fopen($output, 'a');
        fwrite($fh, $content);
        fclose($fh);
    }

    /**
     * Generate controller class.
     */
    public function generateController()
    {
        $className = $this->token[1];
        $controllerFile = "app/Controllers/{$className}.php";
        $content = <<<CONTROLLER
<?php

namespace App\Controllers;

use Core\Base\Controller;
use Core\Base\View;

class {$className} extends Controller
{
    /**
     * {$className} page scaffolding.
     */
    public function index()
    {
        // TODO: Implement index() method.
    }

}
CONTROLLER;

        $this->writeFile($controllerFile, $content);
        echo "Controller {$className} is generated.\n";
    }

    /**
     * Generate filter class.
     */
    public function generateFilter()
    {
        $className = $this->token[1];
        $controllerFile = "app/Filters/{$className}.php";
        $content = <<<FILTER
<?php

namespace App\Filters;

use Core\Request\FilterInterface;

class {$className} implements FilterInterface
{

    public function before()
    {
        // TODO: Implement before() method.
    }

    public function after()
    {
        // TODO: Implement after() method.
    }
}
FILTER;

        $this->writeFile($controllerFile, $content);
        echo "Filter {$className} is generated.\n";
    }

    /**
     * Generate model class.
     */
    public function generateModel()
    {
        $className = $this->token[1];
        $controllerFile = "app/Models/{$className}.php";
        $tableName = strtolower($className);
        $content = <<<MODEL
<?php

namespace App\Models;

use Core\Base\Model;

class {$className} extends Model
{
    protected static \$table = '{$tableName}';
}
MODEL;

        $this->writeFile($controllerFile, $content);
        echo "Model {$className} is generated.\n";
    }

    /**
     * Generate migration class.
     */
    public function generateMigration()
    {
        $tableName = '';
        if (isset($this->token[2])) {
            $argument = explode('=', $this->token[2]);
            $tableName = end($argument);
        }
        $className = $this->token[1];

        $order = date('Ymd_His');
        $migrationFile = "app/Migrations/{$order}_{$className}.php";

        if (empty($tableName)) {
            $upContent = '// TODO: Implement up() method.';
            $downContent = '// TODO: Implement down() method.';
        } else {
            $upContent = <<<UP
Builder::create('{$tableName}', [
            'id' => ['type' => 'int', 'length' => 11, 'primary' => true, 'ai' => true],
            'is_deleted' => ['type' => 'boolean', 'default' => false],
            'created_at' => ['type' => 'timestamp', 'null' => false],
            'last_update' => ['type' => 'timestamp'],
        ]);
UP;
            $downContent = "Builder::drop('{$tableName}');";
        }

        $content = <<<MIGRATION
<?php

namespace App\Migrations;

use Core\Database\Builder;
use Core\Database\Migration;

class {$className} extends Migration
{
    public function up()
    {
        {$upContent}
    }

    public function down()
    {
        {$downContent}
    }

}
MIGRATION;

        $this->writeFile($migrationFile, $content);
        echo "Migration {$className} is generated.\n";
    }

    /**
     * Description generator command.
     */
    public function description()
    {
        $content = <<<TOC
Usage: Generate a resource class for the given name
    
    generate:controller      Create a new controller class
    generate:filter          Create a new filter or middleware class
    generate:model           Create a new model class
    generate:migration       Create a new migration table class
         

TOC;
        echo $content;
    }
}