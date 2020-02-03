<?php

namespace Christophrumpel\LaravelFactoriesReloaded\Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Christophrumpel\LaravelFactoriesReloaded\LaravelFactoriesReloadedServiceProvider;

class FactoryCommandTest extends TestCase
{

    /** @test */
    public function it_fails_if_no_models_found()
    {
        $this->expectException(\LogicException::class);

        // Set to a path with no models given
        Config::set('factories-reloaded.models_path', __DIR__.'/');

        $this->artisan('make:factoryReloaded');
    }

    /** @test */
    public function it_creates_factory_for_chosen_model()
    {
        $this->artisan('make:factoryReloaded')
            ->expectsQuestion('Please pick a model',
                '<href=file://'.__DIR__.'/models/Group.php>Christophrumpel\LaravelFactoriesReloaded\Tests\Models\Group</>')
            ->assertExitCode(0);

        $this->assertTrue(File::exists(__DIR__.'/factories/tmp/GroupFactory.php'));
    }

    /**
     * @test
     **/
    public function it_replaces_the_the_dummy_code_in_the_new_factory_class()
    {
        $this->artisan('make:factoryReloaded')
            ->expectsQuestion('Please pick a model',
                '<href=file://'.__DIR__.'/models/Group.php>Christophrumpel\LaravelFactoriesReloaded\Tests\Models\Group</>')
            ->assertExitCode(0);

        $generatedFactoryContent = file_get_contents(__DIR__.'/factories/tmp/GroupFactory.php');

        $this->assertTrue(Str::contains($generatedFactoryContent, [
            'GroupFactory',
            'Christophrumpel\LaravelFactoriesReloaded\Tests\Models\Group',
            'Group',
        ]));
    }
    //
    ///** @test */
    //public function it_fails_if_factory_already_given()
    //{
    //    $this->artisan('make:factoryReloaded')
    //        ->expectsQuestion('For which model do you want to create a Factory?',
    //            'Christophrumpel\LaravelFactoriesReloaded\Tests\Recipe');
    //
    //    $this->artisan('make:factoryReloaded')
    //        ->expectsQuestion('For which model do you want to create a Factory?',
    //            'Christophrumpel\LaravelFactoriesReloaded\Tests\Recipe')
    //        ->expectsOutput('Factory already exists!')
    //        ->assertExitCode(0);
    //}
}