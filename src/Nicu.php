<?php


namespace Nicu;

use Closure;
use InvalidArgumentException;
use Noodlehaus\Config;
use DI\Bridge\Slim\App;
use Exception;

class Nicu
{
    protected $app;
    protected $routes;
    protected $middlewares;

    public function __construct(Config $config)
    {
        $this->app = new App();
        $this->configContainer($config);
        $this->routes = $config->get('routes');
        $this->middlewares = $config->get('app.middlewares');
    }

    public static function getInstance($configFolder): Nicu
    {
        try {
            $config = new Config($configFolder);
        } catch (Exception $e) {
            throw new InvalidArgumentException("Wrong config folder $configFolder");
        }
        return new static($config);
    }

    public function run()
    {
        $this->loadMiddlewares();
        $this->registerRoutes();
        $this->runApp();
    }

    public function registerRoutes()
    {
        foreach ($this->routes as $route) {
            $this->app->{$route['method']}($route['route'], $route['action']);
        }
    }

    private function runApp()
    {
        $this->app->run();
    }

    public function getApp(): App
    {
        return $this->app;
    }

    private function configContainer(Config $config)
    {
        foreach ($config->get('app.boot') as $key => $value) {
            $this->app->getContainer()->set($key, $value);
        }

        $this->app->getContainer()->set(Config::class, function () use ($config) {
            return $config;
        });

        foreach ($config->get('providers') as $provider) {
            (new $provider($this->app))->boot();
        }
    }

    private function loadMiddlewares()
    {
        foreach ($this->middlewares as $middleware => $config) {
            if ($config instanceof Closure) {
                $this->app->add($config);
                continue;
            }

            $this->app->add(new $middleware($config));
        }
    }
}
