<?php

class Email
{
    private const WEBMASTER_EMAIL = 'mail@admin.com';
    private const SITE_TITLE = 'Projet Lemon-Tonic';
    private const HEADERS = 'MIME-Version: 1.0' . PHP_EOL . 'Content-type: text/html;charset=utf-8' . PHP_EOL;

    static function HTML_to_webmaster($message): void
    {
        mail(self::WEBMASTER_EMAIL, 'Message du site' . self::SITE_TITLE
            , $message
            , self::HEADERS);
    }

    static function table_to_webmaster($array): void
    {
        $message = '<pre>' . json_encode([$array], JSON_PRETTY_PRINT) . '</pre>';
        mail(self::WEBMASTER_EMAIL, 'Message du site' . self::SITE_TITLE
            , wordwrap($message, 70, PHP_EOL)
            , self::HEADERS);
    }

    static function HTML_to_user($address_mail, $subject, $message): void
    {
        mail($address_mail, $subject
            , $message
            , self::HEADERS);
    }

}
