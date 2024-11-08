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

1) ./vendor/bin/sail up -d

2) php artisan key:generate

3) php artisan jwt:secret

4) Caso queria usar o gerador de crud:   php artisan my:generator
   
5) Caso seja criado uma nova rota:  php artisan l5-swagger:generate  

6) Como é usado o octane para otimização é necessário realizar um refresh a cada alteração:  php artisan octane:reload 


Obs: ERROR  Octane server is not running  (Significa que o Octane está desligado)
Para habilitar o octane comente a linha 15 do /docker/8.2/supervisord.conf e descomente a linha 12

Sem octane: command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=8000

Com octane: command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan octane:start --workers=4 --task-workers=6 --server=swoole --host=0.0.0.0 --port=8000