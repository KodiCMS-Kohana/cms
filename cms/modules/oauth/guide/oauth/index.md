# About OAuth

[OAuth](http://oauth.net/) is an open protocol to allow secure API authorization in a simple and standard method from desktop and web applications. This module provides a pure PHP implementation of the OAuth v1.0 protocol, with support for PLAINTEXT and HMAC-SHA1 signatures.

## Supported Providers

The following providers are available by default:

* [Twitter](http://twitter.com/) using [OAuth_Provider_Twitter]
* [Google](http://www.google.com/) using [OAuth_Provider_Google]

Additional providers can be created by creating an extension of [OAuth_Provider].


