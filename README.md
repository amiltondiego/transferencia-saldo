Transferência de saldo
==============================================

### Sequencia para rodar o projeto
-----
* Rodar o comando dentro da pasta para iniciar o docker-compose
* Rodar o comando que instala as dependências do composer
* Rodar o comando que faz os presets do laravel

<details>
    <summary>Se tiver o Make instalado use esses comandos para iniciar</summary>
Start para subir o docker-compose

    make start
Stop derrubar o docker-compose

    make stop
test para rodar os test de feature

    make test
composer-i para instalar as dependências do composer

    make composer-i
preset para fazer o preset do laravel

    make preset
</details>
<details>
    <summary>Caso não tenha o Make use esses comandos</summary>
Subir o docker-compose

    docker-compose up -d --build
Derrubar o docker-compose

    docker-compose down
Rodar os test de feature

    docker exec -it php-web php -d xdebug.mode=coverage artisan test --debug -vvv
Instalar as dependências do composer

    docker exec -it php-web composer install
Faz os presets do Laravel

    docker exec -it php-web cp .env.example .env && chmod -R 777 storage && chmod -R 777 bootstrap && php artisan migrate:refresh --seed
</details>

### API
-----
o endpoint da feature é `api/transaction` e é necessário usar autenticação por [token](https://laravel.com/docs/9.x/sanctum), você pode criar um token de usuário na rota `api/create-token`.

* Transfere saldo [POST] `api/transaction`
    ```
    {
        "value" : 100.00,
        "payee" : "email@example.com"
    }
    ```
### DADOS
-----
O seed do banco vai adicionar 3 usuários:
* `user-login@gmail.com` sem saldo
* `user-comum@gmail.com` com 10k de saldo
* `lojista@gmail.com` com 1k de saldo

### REGRAS
----
* Lojista só recebe transferência.
* Só transfere se tiver saldo.