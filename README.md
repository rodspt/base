## Projeto Base - Laravel 10

# Componentes:

- **[Octane Swoole](https://laravel.com/docs/10.x/octane)**
- **[Sail](https://laravel.com/docs/10.x/sail)**
- **[Telescope](https://laravel.com/docs/10.x/telescope)**
- **[Breeze](https://laravel.com/docs/10.x/starter-kits#breeze-and-next)**
- **[Debugbar](https://github.com/barryvdh/laravel-debugbar)**
- **[Swagger](https://github.com/darkaonline/l5-swagger)**
- **[Traducao](https://github.com/lucascudo/laravel-pt-BR-localization)**
- **[Fakerphp](https://fakerphp.github.io/)**


# Instruções

1) cp .env.example .env

2) ./vendor/bin/sail up -d

3) php artisan key:generate

4) php artisan jwt:secret

5) Caso queria usar o gerador de crud:   php artisan my:generator
   
6) Caso seja criado uma nova rota:  php artisan l5-swagger:generate  

7) Como é usado o octane para otimização é necessário realizar um refresh a cada alteração:  php artisan octane:reload 
