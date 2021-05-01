<?php
class Utils
{
    public const PROJECT_PATH = '/cs6252/projects/final_spencerDent';

    public static function getAction()
    {
        return filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
            ?? filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS)
            ?? '';
    }

    /**
     * Ensures a secure connection and starts the session
     */
    public static function setupConnection()
    {
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        if (!$https) {
            $host = filter_input(INPUT_SERVER, 'HTTP_HOST');
            $uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
            $url = 'https://' . $host . $uri;
            header("Location: " . $url);
            exit();
        }

        session_start();
    }
}
