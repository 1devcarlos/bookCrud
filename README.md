# Book Favorite API - README

## Descrição

Este projeto é uma API que permite aos usuários marcar livros como favoritos e gerenciar a lista de favoritos. A API foi construída usando o framework **Laravel**, com a arquitetura **Repository-Service-Controller**. 

A funcionalidade principal inclui:
- Visualização de todos os livros cadastrados.
- Marcação de livros como favoritos para um usuário autenticado.
- Remoção de livros da lista de favoritos.
- Exibição de uma coluna `is_favorite` indicando se o livro está favoritado pelo usuário autenticado.

### Tecnologias Utilizadas:
- **Laravel** (framework PHP)
- **PostgreSQL** (banco de dados relacional)
- **Composer** (gerenciador de dependências PHP)

---

## Pré-requisitos

Antes de começar, você vai precisar ter as seguintes ferramentas instaladas no seu computador:

- [Git](https://git-scm.com) - Para clonar o repositório.
- [PHP](https://www.php.net/downloads.php) (>= 8.2) - Laravel requer pelo menos a versão 8.2 do PHP.
- [Composer](https://getcomposer.org/download/) - Gerenciador de dependências do PHP.
- [PostgreSQL](https://www.enterprisedb.com/downloads/postgres-postgresql-downloads) ou qualquer outro banco de dados relacional.
- [Postman](https://www.postman.com/) ou [Insomnia](https://insomnia.rest/) - Para testar os endpoints da API.

---

## Instruções de Instalação

### 1. Clonar o Repositório

Abra seu terminal e execute o seguinte comando para clonar o projeto do GitHub:

```bash
git clone https://github.com/1devcarlos/bookCrud.git
```

Depois, navegue até o diretório do projeto:

```bash
cd book-favorite-api
```

### 2. Instalar Dependências

O Laravel utiliza o Composer para gerenciar suas dependências. Para instalar as dependências do projeto, rode o seguinte comando no diretório raiz do projeto:

```bash
composer install
```

É possível que você necessite descomentar algumas extensões no seu php.ini como o sodium e o pgsql. 

Além disso alguns pacotes externos podem necessitar um:

```bash
composer update
```

### 3. Configurar Variáveis de Ambiente

O Laravel usa um arquivo `.env` para gerenciar as configurações de ambiente, como as credenciais do banco de dados e a chave de aplicação. Para configurar, faça o seguinte:

1. Faça uma cópia do arquivo `.env.example` e renomeie-o para `.env`:

   ```bash
   cp .env.example .env
   ```

2. Abra o arquivo `.env` no seu editor de texto e modifique as seguintes linhas para configurar o banco de dados e outras variáveis:

   ```ini
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=nome_do_banco_de_dados
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

   Altere `nome_do_banco_de_dados`, `seu_usuario` e `sua_senha` de acordo com a configuração do seu ambiente de MySQL.

### 4. Gerar Chave da Aplicação

O Laravel requer uma chave de criptografia única. Para gerar essa chave, execute o seguinte comando:

```bash
php artisan key:generate
```

Isso vai modificar o arquivo `.env` e adicionar a chave gerada na variável `APP_KEY`.

### 5. Gerar a chave JWT do projeto 

O projeto utiliza um pacote externo para gerar tokens de autenticação JWT. para utilizar o recurso corretamente, é necessário fazer a configuração do secret JWT. da aplicação, para isto  use o seguinte: 

```bash
php artisan jwt:secret
```

### 6. Configurar o Banco de Dados

Certifique-se de que o PostgreSQL está rodando e que as credenciais configuradas no arquivo `.env` estão corretas. Em seguida, execute as migrações para criar as tabelas no banco de dados:

```bash
php artisan migrate
```

Se houver algum problema com permissões ou acessos ao banco de dados, verifique as configurações no arquivo `.env`.

## Rodar o Servidor de Desenvolvimento

Com todas as dependências instaladas e o banco de dados configurado, agora você pode iniciar o servidor de desenvolvimento do Laravel:

```bash
php artisan serve
```

Por padrão, o servidor vai rodar em `http://localhost:8000`.

---

## Testando a API

Agora que a aplicação está rodando, você pode usar ferramentas como Postman ou Insomnia para fazer requisições HTTP para a API.

## Autenticação
Essa API utiliza JWT (JSON Web Tokens) para autenticação. Certifique-se de que as requisições aos endpoints protegidos enviem o token de autenticação no cabeçalho Authorization.

Exemplo de cabeçalho:

``` 
    Authorization: Bearer {seu-token-aqui}
```

### Endpoints Disponíveis

#### 1. Criação de Usuário

**URL**: `POST /api/register`

Para testar o Registro, envie o seguinte payload com os campos "name", "email" e "password". O campo name requer um mínimo de 3 caracteres, o campo email precisa ter um formato válido e o campo password necessita de pelo menos 6 caracteres.

Exemplo de payload:

```json
{
    "name": "Teste",
    "email": "teste@test.com",
    "password": "123456"
}
```

#### 2. Login de Usuário

**URL**: `POST /api/login`

O campo de login segue os mesmos critérios de validação do registro. Para autenticar, basta informar o email e senha. Caso as credenciais estejam corretas, um token JWT será retornado.

Exemplo de payload:

```json
{
    "email": "teste@test.com",
    "password": "123456"
}
```

### Endpoints protegidos

#### 3. Logout do Usuário

**URL**: `POST /api/logout`

O logout não requer um payload, apenas o token JWT no cabeçalho da requisição. Ele invalidará o token.

#### 5. Listar Todos os Livros

**URL**: `GET /api/books`

Retorna todos os livros cadastrados. Para cada livro, incluirá a propriedade is_favorite indicando se o livro é favoritado pelo usuário autenticado.

Exemplo de resposta:

```json
[
    {
        "id": 1,
        "title": "O Livro das Sombras",
        "description": "Um guia místico sobre as artes secretas",
        "is_favorite": true
    },
    {
        "id": 2,
        "title": "O Grande Gatsby",
        "description": "Um clássico da literatura americana",
        "is_favorite": false
    }
]
```

#### 6. Obter Livro por ID

**URL**: `GET /api/books/{id}`

Busca um livro específico pelo seu ID.

Exemplo de resposta:

```json
    {
        "id": 1,
        "title": "O Livro das Sombras",
        "description": "Um guia místico sobre as artes secretas",
        "is_favorite": true
    },
```

#### 7. Criar um Livro

**URL**: `POST /api/books/create`

Cria um livro para o usuário logado. Requer os campos title e description.

Exemplo de payload:

```json
{
    "title": "Test Book",
    "description": "tiny description"
}
```

#### 8. Atualizar um Livro

**URL**: `PUT /api/books/update/{id}`

Atualiza os campos de um livro pelo ID. Requer um ou ambos os campos title e description.

Exemplo de payload:

```json
{
    "title": "Book for Dummies",
    "description": "A really tiny description."
}
```

#### 9. Deletar um Livro

**URL**: `DELETE /api/books/delete/{id}`

Deleta um livro pelo seu ID.


#### 10. Obter Favoritos do Usuário

**URL**: `GET /api/favorites`

Retorna todos os livros favoritos do usuário autenticado.

#### 11. Adicionar um Livro aos Favoritos

**URL**: `POST /api/favorites/add/{id}`

Adiciona o livro identificado pelo ID à lista de favoritos do usuário.

#### 11. Remover um Livro dos Favoritos

**URL**: `DELETE /api/favorites/remove/{id}`

Remove o livro identificado pelo ID da lista de favoritos do usuário.
---

## Autenticação

Essa API utiliza **JWT (JSON Web Tokens)** para autenticação. Certifique-se de que as requisições aos endpoints protegidos enviem o token de autenticação no cabeçalho `Authorization`.

Exemplo de cabeçalho:

```http
Authorization: Bearer {seu-token-aqui}
```

### Obtenção de Token de Autenticação

Se o projeto já estiver configurado com um sistema de autenticação JWT (como `laravel/passport` ou `tymon/jwt-auth`), você pode usar as rotas de login para gerar um token de autenticação. 

---

## Considerações Finais

Este projeto foi desenvolvido para fins educacionais e de teste, pode ser expandido com novas funcionalidades conforme necessário. Se você encontrar algum problema ou tiver sugestões de melhorias, sinta-se à vontade para abrir uma issue ou contribuir com código.

---

## Licença

Este projeto está sob a licença MIT.
