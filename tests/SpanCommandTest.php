<?php

namespace Laranext\Span\Tests;

use Illuminate\Support\Facades\File;

class SpanCommandTest extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_create_new_package()
    {
        File::deleteDirectory(base_path('packages/admin'));
        File::deleteDirectory(base_path('packages/my-admin-blog'));

        // $this->artisan('span:package admin')
        $this->artisan('span:package admin --namespace=Admin/Blog')
        // $this->artisan('span:package my-admin-blog')
            ->expectsConfirmation(
                'Would you like to update your composer package?',
                'no'
            );

        $this->assertTrue(File::isDirectory(base_path('packages/admin')));

        File::deleteDirectory(base_path('packages/admin'));
        File::deleteDirectory(base_path('packages/my-admin-blog'));
    }

    /** @test */
    public function install_command_can_publish_config_file()
    {
        // make sure we're starting from a clean state
        if (File::exists(config_path('span.php'))) {
            unlink(config_path('span.php'));
        }

        $this->assertFalse(File::exists(config_path('span.php')));

        $this->artisan('span:install');

        $this->assertTrue(File::exists(config_path('span.php')));
    }

    /** @test */
    public function it_can_generate_controller()
    {
        $this->artisan('span:controller PostController admin');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/PostController.php')));

        $this->artisan('span:controller ShowHomepage admin -i');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/ShowHomepage.php')));

        $this->artisan('span:controller Api/PostController admin --api');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/Api/PostController.php')));

        $this->artisan('span:controller JobController admin -r');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/JobController.php')));
    }

    /** @test */
    public function it_can_generate_controller_with_model()
    {
        if (File::exists(base_path('packages/admin/src/Http/Controllers/PhotoController.php'))) {
            unlink(base_path('packages/admin/src/Http/Controllers/PhotoController.php'));
            unlink(base_path('packages/admin/src/Models/Photo.php'));
        }

        $this->artisan('span:controller PhotoController admin --model=Photo')
            ->expectsConfirmation(
                'A Admin\Models\Photo model does not exist. Do you want to generate it?',
                'yes'
            );

        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/PhotoController.php')));
        $this->assertTrue(File::exists(base_path('packages/admin/src/Models/Photo.php')));
    }

    /** @test */
    public function it_can_generate_migration()
    {
        File::deleteDirectory(base_path('packages/admin/database/migrations'));

        $this->artisan('span:migration create_flights_table admin');
        $this->assertFalse(empty(File::files(base_path('packages/admin/database/migrations'))));
    }

    /** @test */
    public function it_can_generate_model()
    {
        File::deleteDirectory(base_path('packages/admin'));

        $this->artisan('span:model Flight admin --api -m')
            ->expectsConfirmation(
                'A Admin\Models\Flight model does not exist. Do you want to generate it?',
                'yes'
            );

        $this->assertTrue(File::exists(base_path('packages/admin/src/Models/Flight.php')));
    }

    /** @test */
    public function it_can_generate_controller_with_different_namespace()
    {
        $this->artisan('span:controller ProjectController my-admin --namespace=Admin/Abc/Other');
        $this->assertTrue(File::exists(base_path('packages/my-admin/src/Http/Controllers/ProjectController.php')));

        $contents = file_get_contents( base_path('packages/my-admin/src/Http/Controllers/ProjectController.php') );
        $this->assertNotFalse(strpos($contents, 'Admin\Abc\Other'));
    }

    /** @test */
    public function it_should_not_remove_base_controller_import_in_the_controller()
    {
        File::deleteDirectory(base_path('packages/admin'));

        $this->artisan('span:controller TestController admin');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/TestController.php')));

        $contents = file_get_contents( base_path('packages/admin/src/Http/Controllers/TestController.php') );
        $this->assertNotFalse(strpos($contents, 'App\Http\Controllers\Controller'));
    }

    /** @test */
    public function it_should_also_not_remove_base_controller_import_in_the_api_controller()
    {
        File::deleteDirectory(base_path('packages/admin'));

        $this->artisan('span:controller Api/TestController admin');
        $this->assertTrue(File::exists(base_path('packages/admin/src/Http/Controllers/Api/TestController.php')));

        $contents = file_get_contents( base_path('packages/admin/src/Http/Controllers/Api/TestController.php') );
        $this->assertNotFalse(strpos($contents, 'App\Http\Controllers\Controller'));
    }
}
