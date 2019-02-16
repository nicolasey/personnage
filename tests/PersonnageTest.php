<?php
namespace Nicolasey\Personnage\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class PersonnageTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @return mixed
     */
    public function it_works()
    {
        $this->assertEquals(true, true);
    }
}