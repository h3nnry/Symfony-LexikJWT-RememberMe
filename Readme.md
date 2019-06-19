## Documentation for adding remember me functionality in symfony when using package "lexik/jwt-authentication-bundle" 
- For enable remember me functionality in json object parameters add "_remember_me" set to true.
- Add JWTCreatedListener to your project.
- config/services.yml:
```
services:
  RememberMe\Event\JWTCreatedListener:
    arguments: [ '@request_stack' ]
    tags:
      - { name: kernel.event_listener, event: lexik_jwt_authentication.on_jwt_created, method: onJWTCreated }
```
