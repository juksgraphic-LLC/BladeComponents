<?php

namespace Docs\controllers;

use flight\Engine;

class HomeController
{

    /**
     * Engine Flight
     * @var Engine
     */
    protected Engine $app;


    /**
     * Summary of __construct
     * @param Engine $app
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

    /**
     * Summary of index
     * @return void
     */
    public function index()
    {
        $this->app->render("home");
    }


    public function components(string $component){
        $this->app->render("components.{$component}");
    }
}
