# OAuth Configuration

All configuration for OAuth is done in the `config/oauth.php` file. The configuration file is organized by the provider name.

## Example Configuration File

    return array(
        /**
         * Twitter application registration: https://twitter.com/apps
         */
        'twitter' => array(
            'key' => 'your consumer key',
            'secret' => 'your consumer secret'
        ),
        /**
         * Google application registration: https://www.google.com/accounts/ManageDomains
         */
        'google' => array(
            'key' => 'your domain name',
            'secret' => 'your consumer secret'
        ),
    );

[!!] The consumer key and secret **must** be defined for all providers.
