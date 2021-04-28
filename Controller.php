<?php
require_once 'autoload.php';
require_once 'utils/Utils.php';

class Controller
{
    private $action;
    private $db;
    private $twig;

    public function __construct()
    {
        // setup connection

        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);

        // connect to database

        // get action
        $this->action = Utils::getAction();
    }

    public function invoke()
    {
        match ($this->action) {
            'Home' => $this->showHomePage(),
            default => $this->showHomePage()
        };
    }

    private function showHomePage()
    {
        echo $this->twig->load('home.twig')->render();
    }
}
