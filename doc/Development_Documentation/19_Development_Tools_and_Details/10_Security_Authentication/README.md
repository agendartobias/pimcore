# Security and Authentication

You can make full use of the [Symfony Security Component](https://symfony.com/doc/5.3/security.html) to handle complex
authentication/authorization scenarios. 
Please be aware that also the Pimcore admin UI uses the Security component, so be careful 
when changing/modifying the configuration. 

## Login example

The [Demo CMS profile](https://github.com/pimcore/demo) provides a simple login
example using a `User` Pimcore object and a `form_login` authenticator which allows a site-wide login with public and
secured areas:
 
* [security.yaml](https://github.com/pimcore/demo/blob/10.x/config/packages/security.yaml)
* [AccountController](https://github.com/pimcore/demo/blob/10.x/src/Controller/AccountController.php)

A simplified guide to this setup is illustrated in [Authenticate against Pimcore Objects](./01_Authenticate_Pimcore_Objects.md).

For more complex examples, custom user providers and a full configuration reference please read the
[Symfony Security Component documentation](https://symfony.com/doc/5.3/security.html).
