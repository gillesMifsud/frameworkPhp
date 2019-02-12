<?php

namespace Tests;

use PDO;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class DatabaseTestCase extends TestCase
{
    /**
     * @var PDO;
     */
    protected $pdo;

    /**
     * @var Manager
     */
    private $manager;

    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $createTables = "CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY, 
            name VARCHAR, 
            slug VARCHAR,
            content LONGTEXT,
            updated_at DATETIME,
            created_at DATETIME)";

        $pdo->exec($createTables);


        $configArray = require('phinx.php');
        $configArray['environments']['test'] = [
            'adapter' => 'sqlite',
            'name' => 'frameworkphp',
            'connexion' => $pdo
        ];

        $config = new Config($configArray);

        $manager = new Manager($config, new StringInput(' '), new NullOutput());
        $manager->migrate('test');
        $this->manager = $manager;
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->pdo = $pdo;
    }

    public function seedDatabase()
    {
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        $this->manager->seed('test');
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
}
