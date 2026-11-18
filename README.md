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

## Instalação

~~~bash
git clone https://github.com/CWillwohl/project-working
cd project-working
cp .env.dev .env
docker compose up -d --build
~~~

Acesse http://localhost:8000 quando o container `app` concluir a instalação das dependências PHP, a geração da chave e as migrations. Use `docker compose logs -f app vite` para acompanhar. Ajuste `HOST_UID` e `HOST_GID` no `.env` se seu usuário usar valores diferentes de `1000`.

### E-mail e desenvolvimento front-end

O Compose sobe Vite e Mailpit junto com a aplicação. O `.env.dev` envia os e-mails locais para o Mailpit via SMTP, e a caixa fica em http://localhost:8025. Em um deploy, configure `MAIL_*` para o seu servidor de e-mail.

### Atualizar um ambiente que usava Redis

Antes do `git pull`, suspenda as operações que geram jobs e deixe o worker antigo processar os jobs pendentes, reservados e agendados no Redis. Trate os jobs com falha que precisarão ser reenviados na stack antiga. Depois, rode `docker compose down` para desligar os serviços antigos e preservar o volume MySQL.

Depois do `git pull`, remova `QUEUE_CONNECTION=redis`, as variáveis `REDIS_*` e `FORWARD_REDIS_PORT` do `.env`. Caso tenha configurado outros drivers Redis, use `CACHE_DRIVER=file`, `SESSION_DRIVER=file` e `BROADCAST_DRIVER=log`. Para usar o Mailpit local, configure `MAIL_MAILER=smtp`, `MAIL_HOST=mailpit` e `MAIL_PORT=1025`.

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
