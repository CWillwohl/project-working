# Working
## Descrição
Working é um sistema de gerenciamento de registros de entrada e saída, criado para facilitar o controle de horas trabalhadas em diferentes projetos. Cada projeto possui um valor atribuído por hora, e quando um registro de saída é feito, o sistema calcula automaticamente o período trabalhado e o valor total a ser recebido.

## Tecnologias utilizadas
- [Laravel](https://laravel.com/)
- [Livewire 3](https://livewire.laravel.com/)
- [TailwindCSS](https://tailwindcss.com/)
- [DaisyUI](https://daisyui.com/)
- [MySQL](https://www.mysql.com/)
- [Redis](https://redis.io/)

## Requisitos
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Docker](https://docs.docker.com/get-docker/)

### Instalação
Siga os passos abaixo para configurar o ambiente de desenvolvimento:

### 1) Clonar o Repositório
- git clone https://github.com/CWillwohl/project-working
- cd working

### 2) Instalar Dependências
Execute o comando abaixo para instalar as dependências do projeto via Composer:

- composer install

Configuração do Arquivo .env:
- Copie o arquivo de exemplo .env.dev ou configure o .env conforme suas preferências
- Adicione a funcionalidade de SMTP para o envio de e-mails de recuperação de senha. Para testes, recomendo o uso do [Mailtrap.io](https://mailtrap.io/).
- Atualize as variáveis de ambiente conforme necessário, com especial atenção para as configurações de banco de dados e do sistema de filas. Certifique-se de ajustar as definições relacionadas ao Redis, que é utilizado como gerenciador de filas no projeto.

### 3) Inicializar o Docker (Sail)
Este projeto utiliza o Laravel Sail como ambiente de desenvolvimento. Para subir os containers do Docker, execute o comando:

- ./vendor/bin/sail up -d

### 4) Executar Migrações
Agora, aplique as migrações de banco de dados para configurar a estrutura inicial:

- ./vendor/bin/sail php artisan migrate

### 5) Iniciar os Workers
Agora, inicie os workers para operar os Jobs

- ./vendor/bin/sail php artisan queue:work

Acesse o Projeto
Com o Docker em execução, você pode acessar o projeto no seu navegador:

http://localhost

### Comandos Úteis
Para parar os containers do Docker:
- ./vendor/bin/sail down

Para executar comandos Artisan:
- ./vendor/bin/sail artisan <comando>

### Considerações Finais
Sinta-se à vontade para adaptar o arquivo .env conforme suas necessidades, garantindo que todas as variáveis de ambiente estejam corretamente configuradas para o funcionamento do projeto.

---

Feito com ❤️ por Caio Willwohl
