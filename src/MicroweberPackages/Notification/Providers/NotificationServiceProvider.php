<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Notification\Providers;

use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Notification\Mail\SimpleHtmlEmail;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\Utils\ThirdPartyLibs\Mail\Swift_MailTransport;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->_configMailSender();
    }


    private function _configMailSender(){
        
        // SMTP SETTINGS
        $smtpHost = Option::getValue('smtp_host', 'email');
        $smtpPort = Option::getValue('smtp_port', 'email');
        $smtpUsername = Option::getValue('smtp_username', 'email');
        $smtpPassword = Option::getValue('smtp_password', 'email');
        $smtpAuth = Option::getValue('smtp_auth', 'email');
        $smtpSecure = Option::getValue('smtp_secure', 'email');

        // Type transport
        $emailTransport = Option::getValue('email_transport', 'email');

        // From Name
        $emailFromName = Option::getValue('email_from_name', 'email');
        if (!$emailFromName) {
            $emailFromName = getenv('USERNAME');
        }

        // Email From
        $emailFrom = Option::getValue('email_from', 'email');
        if (!$emailFrom) {
            $hostname = mw()->url_manager->hostname();
            if ($emailFromName != '') {
                $emailFrom = ($emailFromName) . '@' .$hostname;
            } else {
                $emailFrom = 'noreply@' . $hostname;
            }
            $emailFrom = str_replace(' ', '-', $emailFrom);
        }

        //Set config mails
        Config::set('mail.from.name', $emailFromName);
        Config::set('mail.from.address', $emailFrom);

        // Set mai credentinals
        Config::set('mail.username', $smtpUsername);
        Config::set('mail.password', $smtpPassword);


       // Set mail hots
        Config::set('mail.host', $smtpHost);
        Config::set('mail.port', $smtpPort);
        Config::set('mail.encryption', $smtpAuth);

        if ($emailTransport == 'gmail') {
            Config::set('mail.host', 'smtp.gmail.com');
            Config::set('mail.port', 587);
            Config::set('mail.encryption', 'tls');
        }
        if ($emailTransport == 'cpanel') {
            Config::set('mail.port', 587);
            Config::set('mail.encryption', 'tls');
        }
        if ($emailTransport == 'plesk') {
            Config::set('mail.port', 25);
            Config::set('mail.encryption', 'tls');
        }
    }
}
