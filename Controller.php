<?php
require_once 'autoload.php';
require_once 'utils/Utils.php';
require_once 'utils/Database.php';
require_once 'utils/Validator.php';
require_once 'model/Field.php';

require_once 'model/ClientTable.php';
require_once 'model/DadTable.php';

class Controller
{
    private $action;
    private $db;
    private $twig;

    private $client_table;
    private $dad_table;

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
        $this->client_table = new ClientTable($this->db->getDB());
        $this->dad_table = new DadTable($this->db->getDB());

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
            'Returning Home' => $this->showHomePage('returning'),
            'Welcome Home' => $this->showHomePage('welcome'),
            'Logout Home' => $this->showHomePage('logout'),
            'Show Registration' => $this->showRegistrationPage(),
            'Register' => $this->registerClient(),
            'Show Login' => $this->showLoginPage(),
            'Login' => $this->loginClient(),
            'Logout' => $this->logoutUser(),
            'Show Dad Selection' => $this->showDadSelection(),
            'Rent This Dad' => $this->rentDad(),
            default => $this->showHomePage()
        };
    }


    private function showHomePage($version = '')
    {
        $first_name = null;

        if (isset($_SESSION['username'])) {
            $first_name = $this->client_table->getFirstNameViaUsername($_SESSION['username']);
        }

        echo $this->twig->load('home.twig')->render(['version' => $version, 'first_name' => $first_name]);
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
        if ($this->client_table->clientExists($username->value) && !Field::hasError($username))
            $username->error = 'Username already exists.';

        $password = new Field('password', 'password');
        $password->value = filter_input(INPUT_POST, 'password');
        Validator::required($password);
        Validator::properLength($password, 8, 20);
        Validator::matchPattern($password, '/^(?=.*[[:upper:]])(?=.*[[:lower:]]).*$/', 'Must contain an uppercase and lowercase letter.');
        Validator::matchPattern($password, '/^(?=.*[[:digit:]]).*$/', 'Must contain a number.');
        Validator::matchPattern($password, '/^(?=.*[!@#$%^&*]).*$/', 'Must contain a symbol (!@#$%^&*).');

        $confirm_password = new Field('confirm_password', 'password');
        $confirm_password->value = filter_input(INPUT_POST, 'confirm_password');
        Validator::checkConfirmPassword($confirm_password, $password);

        $first_name = new Field('first_name');
        $first_name->value = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        Validator::required($first_name);
        Validator::properLength($first_name);

        $last_name = new Field('last_name');
        $last_name->value = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        Validator::required($last_name);
        Validator::properLength($last_name);

        $email = new Field('email');
        $email->value = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        Validator::required($email);
        Validator::properLength($email, 1, 50);
        Validator::checkEmail($email);

        if (Validator::allValid([$username, $password, $confirm_password, $first_name, $last_name, $email])) {

            $this->client_table->addClient($username->value, $password->value, $first_name->value, $last_name->value, $email->value);

            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username->value;

            header("Location: .?action=Welcome Home");
            return;
        }

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
    }

    private function showLoginPage($username = null, $password = null, $login_error = '')
    {
        echo $this->twig->load('login.twig')->render(['fields' => [
            $username ?? new Field('username'),
            $password ?? new Field('password', 'password')
        ], 'login_error' => $login_error]);
    }

    private function loginClient()
    {
        $username = new Field('username');
        $username->value = filter_input(INPUT_POST, 'username');
        Validator::required($username);

        $password = new Field('password', 'password');
        $password->value = filter_input(INPUT_POST, 'password');
        Validator::required($password);

        $login_error = '';
        if (!$this->client_table->isValidLogin($username->value, $password->value))
            $login_error = 'Username or password was not recognized';

        if (Validator::allValid([$username, $password]) && $login_error === '') {

            $_SESSION['is_valid_user'] = true;
            $_SESSION['username'] = $username->value;

            header("Location: .?action=Returning Home");
            return;
        }

        $this->showLoginPage($username, $password, $login_error);
    }

    private function logoutUser()
    {
        $_SESSION = [];
        $this->twig->addGlobal('session', $_SESSION);

        session_destroy();

        header("Location: .?action=Logout Home");
    }

    private function showDadSelection()
    {
        $dads = $this->dad_table->getAllDads();

        echo $this->twig->load('dad_selection.twig')->render(['dads' => $dads]);
    }

    private function rentDad()
    {
    }
}
