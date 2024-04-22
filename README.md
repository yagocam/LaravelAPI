

## Projeto Laravel API para posts

Projeto de API simples com CRUD completo para posts. Aplicando vários conceitos de programação e otimização de código.

- Você pode consumir a api através da url: https://laravelapi-production-2db4.up.railway.app/
- Swagger: https://laravelapi-production-2db4.up.railway.app/api/documentation
Nesse readme terá várias informações como: 

- Como executar o projeto
- Conceitos abordados
- Documentação da API
- Autenticação.
- Explicação do ci/cd do github actions.
- Site utilizado para fazer o deploy da aplicação.
- Testes




## Como executar o projeto

Versão testada e utilizada no PHP 8.2.
Para instalação de todas as dependências do projeto:
- Compose update
- composer install --prefer-dist --no-interaction --no-progress

- ls -la 
Algumas vezes o .env.example fica invisivel, esse comando força sua visualização para evitar erros.
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan serve

## Utilizando postgresql via docker 
![App Screenshot](https://i.imgur.com/FTYabf4.png)
Foi criado a base de dados nomeada de pgsql e um pgadmin nomeado de backend-pgadmin-1

## Conceitos Abordados

- Classe para request
Com o intuito de estabelecer regras para validar todas as informações que serão enviadas o backend, o request é utilizado para garantir que todas os dados estão dentro dos padrões estabelecidos. 

![App Screenshot](https://i.imgur.com/U0dh5KF.png)
Exemplo do CreatePostRequest.php utilizado no projeto

- Traits
Traits são frequentemente usados para compartilhar funcionalidades entre controladores, modelos ou outros componentes.
ResponseTrait sendo criado dentro do PostController.php utilizado no projeto
![App Screenshot](https://i.imgur.com/fA6iW5d.png)
Trait sendo utilizado em uma função 
![App Screenshot](https://i.imgur.com/UEWTbR5.png)
Foi utilizado essa estrategia pois na maioria das requisições são retornadas um padrão de dados, que é o dado retornado e o status, economizando bastante tempo e esse trait pode ser utilizado em qualquer controle que atenda os requisitos dele. 
- Repository Pattern
É usado para separar a lógica de acesso a dados do restante do código da aplicação, o que torna seu código mais modular, mais fácil de entender e mais testável. Além disso, se você precisar mudar de um tipo de armazenamento de dados, só precisará modificar a implementação do repositório, mantendo o restante do código inalterado.

PostRepository criado para separar as requisições do banco de dados do resto do código.
![App Screenshot](https://i.imgur.com/KDHa5KN.png)

Repositório sendo utilizado, também sendo uma injeção de dependência já que o código está confiando no êxito do método do repositório.
![App Screenshot](https://i.imgur.com/yZ1Mjgz.png)

- Contracts
Um contrato é uma especificação de métodos que uma classe deve implementar. Ele define um conjunto de operações que outras classes podem confiar que estarão disponíveis quando interagirem com objetos que implementam essa interface.

A interface PostRepository servindo também como contrato 
![App Screenshot](https://i.imgur.com/7FDRhRq.png)

- Interface 
Uma interface em programação é uma estrutura que define um conjunto de métodos que uma classe deve implementar. Ela estabelece um contrato que especifica quais comportamentos uma classe deve fornecer, sem definir como esses comportamentos são implementados.

Mesma imagem anterior pois ele acaba se tornando interface e contrato
![App Screenshot](https://i.imgur.com/7FDRhRq.png)

- Dependency Injection
A injeção de dependência é uma técnica poderosa para desacoplar classes e promover a flexibilidade, modularidade e testabilidade do código. É especialmente útil ao lidar com classes que têm dependências externas, como acesso a banco de dados ou serviços externos.

No construtor do PostController, você recebe uma instância de PostRepositoryInterface como um argumento. Isso significa que sempre que um PostController é criado, ele precisa de uma implementação de PostRepositoryInterface para funcionar corretamente.

![App Screenshot](https://i.imgur.com/2wCkBxv.png)

## Pipeline de CI/CD 
Foi utilizado o github actions para validar os testes criados. 
Ele é acessado pela pasta github/workflows e nesse caso nomeei o arquivo para runtests.yml
É possível acessar o arquivo utilizado para rodar os testes logo abaixo:
https://github.com/yagocam/LaravelAPI/blob/main/.github/workflows/runtests.yml

Teste concluido de um commit.
![App Screenshot](https://i.imgur.com/7FzaoEp.png)


Passo a passo executado pela pipeline e exibição do sucesso dos testes
![App Screenshot](https://i.imgur.com/MVr6n0m.png)


### Deploy da aplicação

Foi utilizado o railway para o deploy da aplicação.

Banco de Dados e API hospedados pelo railway.
![App Screenshot](https://i.imgur.com/L336D1J.png)


Visão da tela inicial da API hospedada
![App Screenshot](https://i.imgur.com/1vSJD05.png)

Swagger do projeto hospedado
![App Screenshot](https://i.imgur.com/VTmcYSs.png)






## Documentação da API

#### Cadastra seu usuario
```http
  POST /api/register
```
| Body   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `name`      | `string` | **Obrigatório**. O seu nome |
| `email`      | `string` | **ÚNICO** O email que você usará |
| `password`      | `string` | **Obrigatório** A senha que você utilizará |


#### Login do seu usuario
```http
  POST /api/login
```
| Body   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `email`      | `string` | **Obrigatório** O email que você cadastrou |
| `password`      | `string` | **Obrigatório** A senha que você cadastrou |

#### Retorna todos os posts
```http
  GET /api/posts
```
| Header   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authorization`      | `string` | **Obrigatório** Bearer + token gerado ao logar/registrar |

#### Retorna todos os posts com as tags solicitadas
```http
  GET /api/posts?tag=node
```
| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `tag`      | `string` |     A tag na qual você deseja buscar|

| Header   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authorization`      | `string` | **Obrigatório** Bearer + token gerado ao logar/registrar |

#### Retorna um post específico

```http
  GET /api/posts/${id}
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | **Obrigatório**. O ID do post que você quer |

| Header   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authorization`      | `string` |**Obrigatório** Bearer + token gerado ao logar/registrar | |

#### Edita um post específico
```http
  PUT /api/posts/${id}
```


| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | **Obrigatório**. O ID do post que você quer |

| Body   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `title`      | `string` |  O title do item que você quer |
| `author`      | `string` |  O author do item que você quer |
| `content`      | `string` | O content do item que você quer |
| `tags`      | `string` |  O tags do item que você quer |

| Header   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authorization`      | `string` | **Obrigatório** Bearer + token gerado ao logar/registrar | |



#### Exclui um post específico
```http
  DELETE /api/posts/${id}
```

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `id`      | `string` | **Obrigatório**. O ID do post que você quer |

| Header   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authorization`      | `string` |**Obrigatório** Bearer + token gerado ao logar/registrar 
