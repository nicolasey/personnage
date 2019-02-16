<?php
namespace Nicolasey\Personnage\Tests;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\TestCase as Base;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends Base
{
    protected $tempDirectory = __DIR__."/tmp";

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function checkRequirements()
    {
        parent::checkRequirements();

        collect($this->getAnnotations())->filter(function ($location) {
            return in_array('!Travis', array_get($location, 'requires', []));
        })->each(function ($location) {
            getenv('TRAVIS') && $this->markTestSkipped('Travis will not run this test.');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            \Nicolasey\Personnage\PersonnageServiceProvider::class,
            \Nicolasey\Personnage\PersonnageEventServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->tempDirectory.'/database.sqlite',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('personnage.owner.class', User::class);

        // File system config
        $app['config']->set('filesystems.default', 'local');
        $app['config']->set('filesystems.disks.local.driver', 'local');
        $app['config']->set('filesystems.disks.local.root', __DIR__."/storage");
        $app['config']->set('medialibrary.disk_name', "local");
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();
        $this->createTables("users");
        $this->createPersonnageTables();
        $this->createAvatarsTable();
    }

    protected function resetDatabase()
    {
        file_put_contents($this->tempDirectory.'/database.sqlite', null);
    }

    protected function createPersonnageTables()
    {
        include_once __DIR__.'/../database/migrations/2018_11_27_205625_create_personnages.php';
        (new \CreatePersonnages())->up();
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            $this->app['db']->connection()->getSchemaBuilder()->create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    protected function createAvatarsTable()
    {
        include_once __DIR__."/migrations/2018_12_09_164721_create_media_table.php";
        (new \CreateMediaTable())->up();
    }
}