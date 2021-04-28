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

    public static function startSession()
    {
        session_set_cookie_params(
            0,                  // lifetime - ends when the user closes the browser
            self::PROJECT_PATH  // path
        );

        session_start();
    }
}
