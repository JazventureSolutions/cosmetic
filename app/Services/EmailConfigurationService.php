<?php

namespace App\Services;

use App\Models\EmailConfiguration;

class EmailConfigurationService
{
    public static function getSmtpConfig()
    {
        return EmailConfiguration::first(); // Assuming you only have one email configuration in the database
    }
}
