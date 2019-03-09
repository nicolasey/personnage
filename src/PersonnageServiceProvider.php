<?php
namespace Nicolasey\Personnage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class PersonnageServiceProvider extends ServiceProvider
{
    protected $namespace = "Nicolasey\Personnages\Http\Controllers";

    public function boot()
    {
        $this->setConfig(__DIR__."/../config.php", "personnages");
        $this->mapApiRoutes();
        $this->loadMigrationsFrom(__DIR__."/../database/migrations");
    }

    public function register()
    {
        $this->registerEloquentFactoriesFrom(__DIR__."/../database/factories");
    }

    /**
     * Set config file
     *
     * @param string $path
     * @param string $key
     * @return void
     */
    private function setConfig($path, $key)
    {
        $this->mergeConfigFrom($path, $key);

        $this->publishes([
            $path => config_path($key.".php"),
        ], "config");

        $this->mapApiRoutes();
    }

    /**
     * Add api routes in provider using standard 'api' middleware
     *
     * @return void
     */
    private function mapApiRoutes()
    {
        Route::namespace($this->namespace)
            ->middleware("api")
            ->prefix("api")
            ->group(function () {
                require __DIR__.'/Http/routes/api.php';
            });
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path)
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}