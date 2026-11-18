# Working
## Descrição
Working é um sistema de gerenciamento de registros de entrada e saída, criado para facilitar o controle de horas trabalhadas em diferentes projetos. Cada projeto possui um valor atribuído por hora, e quando um registro de saída é feito, o sistema calcula automaticamente o período trabalhado e o valor total a ser recebido.

## Tecnologias utilizadas
- [Laravel](https://laravel.com/)
- [Livewire 3](https://livewire.laravel.com/)
- [TailwindCSS](https://tailwindcss.com/)
- [DaisyUI](https://daisyui.com/)
- [MySQL](https://www.mysql.com/)

## Requisitos
- [Docker](https://docs.docker.com/get-docker/) com o plugin [Docker Compose](https://docs.docker.com/compose/) (v2)

Não é necessário ter PHP, Composer ou Node instalados na máquina: tudo roda dentro dos containers.

## Ambiente Docker

| Serviço | Função | Acesso |
|---|---|---|
| `app` | PHP 8.2 com Apache, Composer e Xdebug | http://localhost:8000 |
| `mysql` | MySQL 8.4, com os bancos `working` e `testing` | localhost:3306 |
| `vite` | Servidor de assets com hot reload | http://localhost:5173 |
| `mailpit` | Caixa de e-mails local | http://localhost:8025 |

O Docker usa Node para compilar os assets durante o build da imagem e executar o Vite com hot reload durante o desenvolvimento. O registro de ponto, o cálculo do período, a atualização do saldo e a recuperação de senha são executados durante a requisição.

## Instalação local

### 1. Confira o Docker

Com o Docker em execução, confirme que o plugin Compose está disponível.

~~~bash
docker compose version
~~~

### 2. Clone o projeto e prepare o ambiente

~~~bash
git clone https://github.com/CWillwohl/project-working.git
cd project-working
cp .env.dev .env
~~~

O `.env.dev` configura o Laravel para acessar `mysql:3306` e enviar e-mails locais para `mailpit:1025`. No Linux, confira seu UID e GID com `id -u` e `id -g`. Se os valores forem diferentes de `1000`, ajuste `HOST_UID` e `HOST_GID` no `.env` para que os containers possam gravar na pasta do projeto.

Se alguma porta já estiver ocupada, ajuste `APP_PORT`, `VITE_PORT`, `FORWARD_DB_PORT` ou `FORWARD_MAILPIT_DASHBOARD_PORT` no `.env`. Se mudar `APP_PORT`, atualize também `APP_URL`. As portas internas do MySQL e do SMTP continuam sendo `3306` e `1025`.

### 3. Suba os quatro serviços

~~~bash
docker compose up -d --build
docker compose ps
docker compose logs -f app vite
~~~

Na primeira inicialização, o serviço `app` instala as dependências PHP, gera a chave da aplicação e aplica as migrations. O serviço `vite` instala as dependências JavaScript e inicia o hot reload. Aguarde os dois concluírem a inicialização e use `Ctrl+C` para sair dos logs.

### 4. Abra a aplicação

- Acesse http://localhost:8000 para criar uma conta ou entrar.
- Para testar o hot reload, mantenha a aplicação aberta e edite um arquivo em `resources/css`, `resources/js` ou uma view em `resources/views`. O Vite recarrega os assets ou atualiza a página conforme a alteração.
- Para testar a recuperação de senha, solicite o e-mail na aplicação e abra http://localhost:8025 para ver a mensagem no Mailpit.

Para um deploy que envia e-mails reais, configure as variáveis `MAIL_*` para o seu servidor SMTP.

### Atualizar um ambiente que usava Redis

Antes do `git pull`, suspenda as operações que geram jobs e deixe o worker antigo processar os jobs pendentes, reservados e agendados no Redis. Trate os jobs com falha que precisarão ser reenviados na stack antiga. Depois, rode `docker compose down` para desligar os serviços antigos e preservar o volume MySQL.

Depois do `git pull`, edite o `.env` existente para preservar a chave da aplicação e as credenciais do MySQL. Remova `QUEUE_CONNECTION=redis`, as variáveis `REDIS_*` e `FORWARD_REDIS_PORT` do `.env`. Caso tenha configurado outros drivers Redis, use `CACHE_DRIVER=file`, `SESSION_DRIVER=file` e `BROADCAST_DRIVER=log`. Para usar o Mailpit local, configure `MAIL_MAILER=smtp`, `MAIL_HOST=mailpit` e `MAIL_PORT=1025`.

~~~bash
docker compose up -d --build --remove-orphans
~~~

### Comandos úteis

~~~bash
docker compose exec --user app app php artisan migrate:status
docker compose exec --user app app php artisan db:seed
docker compose exec --user app app php artisan test tests/Feature
docker compose down
~~~

Para depurar com Xdebug, defina `XDEBUG_MODE=debug` no `.env` e recrie o container com `docker compose up -d`. O Xdebug se conecta em `host.docker.internal:9003` quando a requisição tem um gatilho de debug.

### Considerações Finais
Sinta-se à vontade para adaptar o arquivo .env conforme suas necessidades, garantindo que todas as variáveis de ambiente estejam corretamente configuradas para o funcionamento do projeto.

---

Feito com ❤️ por Caio Willwohl
