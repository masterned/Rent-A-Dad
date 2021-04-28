<?php
require_once 'autoload.php';
require_once 'utils/Utils.php';
require_once 'utils/Database.php';
require_once 'utils/Validator.php';

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
            'Register Client' => $this->registerClient(),
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

    private function showRegistrationPage($username_error = '', $password_error = '')
    {
        echo $this->twig->load('registration.twig')->render([
            'username_error' => $username_error,
            'password_error' => $password_error
        ]);
    }

    private function registerClient()
    {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $username_error = Validator::checkText($username);
        $password_error = Validator::checkText($password);

        if (!empty($username_error) || !empty($password_error)) {
            $this->showRegistrationPage($username_error, $password_error);
            return;
        }

        $this->showHomePage();
    }
}
