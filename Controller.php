<?php
require_once 'autoload.php';
require_once 'utils/Utils.php';
require_once 'utils/Database.php';

class Controller
{
    private $action;
    private $db;
    private $twig;

    public function __construct()
    {
        // setup connection
        Utils::setupConnection();
        
        // load template engine
        $loader = new Twig\Loader\FilesystemLoader('./view');
        $this->twig = new Twig\Environment($loader);
        $this->twig->addGlobal('session', $_SESSION);
        
        // connect to database
        $this->connectToDatabase();

        // get action
        $this->action = Utils::getAction();
    }

    public function invoke()
    {
        match ($this->action) {
            'Home' => $this->showHomePage(),
            'Show Registration' => $this->showRegistrationPage(),
            default => $this->showHomePage()
        };
    }

    private function connectToDatabase()
    {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            echo $this->twig->load('database_error.twig')->render(['error_message' => $error_message]);
            exit();
        }
    }

    private function showHomePage()
    {
        echo $this->twig->load('home.twig')->render();
    }

    private function showRegistrationPage()
    {
        echo $this->twig->load('registration.twig')->render();
    }
}
