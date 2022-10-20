<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'baza';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    const SECRET_KEY = 'SUygtdaFzoePCtQjpTO12yMn3YhSde7t';

    const mailHost = "smtp.gmail.com";

    const mailPort = 587;

    const mailUsername = "budgetmailer123@gmail.com";

    const mailPassword = "eghiszfvfqmajqha";

}
