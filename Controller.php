<?php
require_once 'autoload.php';
require_once 'utils/Utils.php';
require_once 'utils/Database.php';
require_once 'utils/Validator.php';
require_once 'model/Field.php';

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

    private function connectToDatabase()
    {
        $this->db = new Database();
        if (!$this->db->isConnected()) {
            $error_message = $this->db->getErrorMessage();
            echo $this->twig->load('database_error.twig')->render(['error_message' => $error_message]);
            exit();
        }
    }

    public function invoke()
    {
        match ($this->action) {
            'Home' => $this->showHomePage(),
            'Show Registration' => $this->showRegistrationPage(),
            'Register' => $this->registerClient(),
            'Show Login' => $this->showLoginPage(),
            'Login' => $this->loginClient(),
            default => $this->showHomePage()
        };
    }


    private function showHomePage()
    {
        echo $this->twig->load('home.twig')->render();
    }

    private function showRegistrationPage(
        $username = null,
        $password = null,
        $confirm_password = null,
        $first_name = null,
        $last_name = null,
        $email = null
    ) {
        echo $this->twig->load('registration.twig')->render(['fields' => [
            $username ?? new Field('username'),
            $password ?? new Field('password', 'password'),
            $confirm_password ?? new Field('confirm_password', 'password'),
            $first_name ?? new Field('first_name'),
            $last_name ?? new Field('last_name'),
            $email ?? new Field('email')
        ]]);
    }

    private function registerClient()
    {
        $username = new Field('username');
        $username->value = filter_input(INPUT_POST, 'username');
        Validator::required($username);
        Validator::properLength($username);

        $password = new Field('password', 'password');
        $password->value = filter_input(INPUT_POST, 'password');
        Validator::required($password);
        Validator::properLength($password, 8, 20);

        $confirm_password = new Field('confirm_password', 'password');
        $confirm_password->value = filter_input(INPUT_POST, 'confirm_password');
        Validator::checkConfirmPassword($confirm_password, $password);

        $first_name = new Field('first_name');
        $first_name->value = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        Validator::required($first_name);

        $last_name = new Field('last_name');
        $last_name->value = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        Validator::required($last_name);

        $email = new Field('email', 'email');
        $email->value = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        Validator::required($email);

        if (!Validator::allValid([$username, $password, $confirm_password])) {
            $password->value = '';
            $confirm_password->value = '';

            $this->showRegistrationPage(
                $username,
                $password,
                $confirm_password,
                $first_name,
                $last_name,
                $email
            );
            return;
        }

        $this->showHomePage();
    }

    private function showLoginPage()
    {
        $fields = [
            new Field('username'),
            new Field('password')
        ];
        echo $this->twig->load('login.twig')->render(['fields' => $fields]);
    }

    private function loginClient()
    {
        $this->showHomePage();
    }
}
