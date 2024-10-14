# IP4Y Teste de Desenvolvimento para Programador Senior em PHP
![enter image description here](https://raw.githubusercontent.com/Lesnock/ip4y/main/images/projects-page.JPG)

Este repostório é a implementação do Teste prático para Senior PHP na IP4Y.
- [Instalação e configuração](#instalação-e-configuração)
- [Documentação do Software](#documentação-do-software)
- [Quem sou eu?](#quem-sou-eu)
- [Respostas das questões teóricas](#respostas-das-questões-teóricas)
- [Arquitetura](#arquitetura)
- - [Clean Architecture](#clean-architecture)
- - [SOLID](#solid)
- - [CQS](#cqs)
- - [Repository](#repository)

## Instalação e configuração
Este sistema foi desenvolvido utilizando o Laravel Sail (Docker) em um sistema operacional Linux Ubuntu. Por esse motivo, os comandos abaixo serão específicos para configurar este ambiente, mas o leitor fica livre para escolher o ambiente que melhor lhe parecer. 

Clone o repositório:
```
git clone https://github.com/Lesnock/ip4y
cd ip4y
```
Instale as dependências do PHP e do Javascript:
```
composer install --ignore-platform-reqs
npm install
```
Gere uma app key do Laravel:
```
php artisan key:generate
```
Crie a pasta `docker/volumes/sqlserver` e dê permissão de escrita e leitura:
```
mkdir -p docker/volumes/sqlserver
sudo chmod -R 777 docker/volumes/sqlserver
```
Crie o arquivo `.env`  à partir do arquivo `.env.example`. Se a porta `3000` já estiver em uso na sua máquina, altere as variáveis `APP_URL` e `APP_PORT` para a porta que desejar.
```
cp .env.example .env
```
Suba os containers configurados no docker-compose.yml:
```
./vendor/bin/sail up
```
Entre no container do SQL Server para criar as databases de desenvolvimento e de testes:
```
docker exec -it ip4y_sql_server bash
```
Dentro do container do SQL Server execute os seguintes comandos:
```
/opt/mssql-tools18/bin/sqlcmd -S 127.0.0.1 -U sa -P $MSSQL_SA_PASSWORD -d master -C -Q "CREATE DATABASE $MSSQL_DATABASE";
/opt/mssql-tools18/bin/sqlcmd -S 127.0.0.1 -U sa -P $MSSQL_SA_PASSWORD -d master -C -Q "CREATE DATABASE $MSSQL_TESTING_DATABASE";
exit
```

Rode os migrations e seeders:
```
sail artisan migrate:refresh --seed
```
Execute o vite que irá servir os arquivos do frontend:
```
npm run vite
```
Abra sua aplicação no navegador, na porta escolhida e faça o registro do seu usuário.

Para executar os testes rode os seguintes comandos:
```
./vendor/bin/sail artisan test
npm run test
```

# Documentação do Software

- [Create Project HTTP Request](docs/create-project-http-request.jpg)
- [Update Project HTTP Request](docs/update-project-http-request.jpg)
- [Delete Project HTTP Request](docs/delete-project-http-request.jpg)
- [List Project HTTP Request](docs/list-project-http-request.jpg)
- [Export Project PDF HTTP Request](docs/export-project-pdf-http-request.jpg)
- [Export Project XLSX HTTP Request](docs/export-project-xlsx-http-request.jpg)
- [Create Task HTTP Request](docs/create-task-http-request.jpg)
- [Update Task HTTP Request](docs/update-task-http-request.jpg)
- [Delete Task HTTP Request](docs/delete-task-http-request.jpg)
- [List Task HTTP Request](docs/list-task-http-request.jpg)

**Eu escrevi diversos comentários sobre minhas decisões no código. Todos iniciam com o termo "CANDIDATO:", se caso o leitor quiser encontrá-los rapidamente com a ajuda de uma IDE.**

# Quem sou eu?

Meu nome é Caio Lesnock, e atualmente eu sou o lider da equipe de desenvolvimento da empresa Metadil. 
Já trabalho como programador full stack há 7 anos, e tenho utilizado **PHP** como minha principal linguagem de desenvolvimento no backend e eu possuo profunda experiência nos frameworks **Laravel** e **CodeIgniter**.
Sou **pós-graduado em banco de dados**, e tenho utilizado **SQL Server**, **MySQL** e **PostgreSQL** no meu dia-a-dia.
No frontend, eu possuo vasta experiência em **React/Vue** e **TailwindCSS/Bulma** para estilização.


# Respostas das questões teóricas

## 1. Explique a diferença entre Eloquent ORM e Query Builder no Laravel. Quais são os prós e contras de cada abordagem?

O Eloquent ORM, como o próprio nome diz, é um Object Relational Mapping, ou seja, é uma forma de "mapear" registros do banco de dados em forma de objetos na programação, isto é, cada tupla no banco de dados será transformada em uma instância de um Model no PHP. O Eloquent é um ORM que além de fazer esse mapeamento, ele também é muito útil para adicionarmos "lógica" sob a camada de banco de dados, criando métodos que interagem com as propriedades carregadas do banco de dados.
No entanto, na minha opinião, o Eloquent realmente "brilha" quando falamos sobre relações entre tabelas do banco de dados. O Eloquent possui diversos métodos que automatizam, tanto consultas, quanto alterações de *relationships* no banco de dados. É simplesmente **incrível**!
O Query Builder, por sua vez, como o nome também sugere, é um mecanismo que nos permite criar e executar queries no banco de dados sem a necessidade de escrevermos SQL diretamente. É muito útil para criar queries mais simples que não exigem muito SQL específico de um SGBD. Não sei se é natural comparar o Eloquent com o Query Builder, visto que são coisas diferentes, e podem ser utilizados juntamente sem problemas. Além disso, o Eloquent utiliza o Query Builder por baixo dos panos.

**Pontos positivos do Eloquent ORM:**
- É MUITO útil para consultar/salvar tabelas relacionadas.
- É útil para adicionar uma camada de lógica sobre o banco de dados.

**Pontos negativos do Eloquent ORM:**
- Pode induzir o desenvolvedor a colocar muita regra de negócio nos Models que deveriam estar em uma camada de domínio.
- Difícil de mockar, o que torna praticamente impossível criar testes unitários para componentes que utilizem o Eloquent, forçando o programador a escrever testes de integração.

**Pontos positivos do Query Builder:**
- Deixa o código mais elegante e fácil de ler (mas nem sempre).
- É uma camada de abstração sobre a camada de banco de dados, o que torna uma possível troca de SGDB em algo trivial.
- Possui a opção de escrever partes "raw" na query.

**Pontos negativos do Query Builder**
- Pode se tornar extremamente irritante ao tentar escrever queries mais complexas.
- Adiciona uma camada de complexidade sobre o código.
- Difícil de mockar.

## 2. Como você garantiria a segurança de uma aplicação Laravel ao lidar com entradas de usuários e dados sensíveis? Liste pelo menos três práticas recomendadas e explique cada uma delas.

O Laravel possui alguns recursos para lidar com a segurança da aplicação. Entre eles eu citaria:
- Utilizar o **CSRF Token**, que vai garantir que usuário externos não consigam enviar formulários para a nossa aplicação sem estar dentro da nossa página.
- Utilizar os recursos do Query Builder para evitar **SQL Injection**. Nunca concatenar valores diretamente dentro de uma query.
- Utilizar **Form Requests** que validem a entrada de dados na aplicação.

## 3. Qual é o papel dos Middlewares no Laravel e como eles se integram ao pipeline de requisição? Dê um exemplo prático de como você criaria e aplicaria um Middleware personalizado para verificar se o usuário está ativo antes de permitir o acesso a uma rota específica.

Eu considero middlewares como uma "cadeia de portões" de uma requisição. Cada middleware é executado e "chama" o próximo middleware. Os middlewares tem o poder de interromper uma requisição e retornar algo para o solicitante da requisição. 
Uma requisição pode possuir quantos middlewares forem necessários. 
Se eu fosse criar um middleware para verificar se um usuário está ativo, eu apenas carregaria a sessão do usuário, e caso o usuário estivesse ativo, eu deixaria a requisição continuar, caso contrário, retornaria um erro de Forbidden. Segue o exemplo abaixo:
```php
class CheckUserIsActiveMiddleware
{
	public function handle(Request $request, Closure $next)
	{
		if (!Auth::user()->isActive()) {
			Auth::logout();
			return redirect()->route('login')->withErrors([
				'message' => 'Usuário desativado'
			]);
		}

		return $next($request);
	}
}
```

## 4. Descreva como o Laravel gerencia migrations e como isso é útil para o desenvolvimento de aplicações. Quais são as melhores práticas ao criar e aplicar migrations?

As migrations são "utilitários" que gerenciam o versionamento do banco de dados. O Laravel salva essas versões em uma tabela no banco de dados chamada "migrations". Cada migration corresponde à uma linha nessa tabela. O nome das migrations é um aspecto importante deste recurso, pois incluem o timestamp da criação da migration e o nome que representa o que ela modifica no banco de dados. Entre as boas práticas relacionadas às migrations eu citaria:
- Criar sempre bons nomes que descrevem bem o que a migration faz.
- Sempre executar a migration e dar um rollback ainda no ambiente de desenvolvimento para ver se ambos realmente funcionam como esperado.
- Se o banco permitir, realizar um "squash" das migrations para reduzir o número de arquivos.

## 5. Qual é a diferença entre transações e savepoints no SQL Server? Como você usaria transações em um ambiente Laravel?

Eu sempre penso em transações como um grupo de comandos que devem ser executados no banco de dados e que **devem** ser executados por completo ou cancelados. Em outras palavras: **TUDO** ou **NADA**. O SQL Server, no entando, possui o recurso de "savepoints" que "particionam" uma transação, e possibilita que o rollback não seja feito por inteiro, mas somente até um determinado ponto. No Laravel, ou em qualquer outra aplicação, eu usaria uma transaction para salvar componentes que são considerados como um único "agregado", por exemplo, um orçamento deve sempre ser salvo com seus itens. Se um erro ocorrer durante a inserção de um dos itens, então toda a transação deve ser desfeita. Se tudo ocorresse bem, um commit na transação salvaria todo o processo efetuado em definitivo.


# Arquitetura
## Clean Architecture
Este projeto foi desenvolvido com base na arquitetura de software chamada **Clean Architecture**. É claro que utilizar Clean Architecture para uma aplicação tão simples é um baita de um "Overengineering", mas eu o fiz para efeitos de demonstração de conhecimento.
![enter image description here](https://blog.cleancoder.com/uncle-bob/images/2012-08-13-the-clean-architecture/CleanArchitecture.jpg)

Nesta aplicação eu fiz a separação entre algumas camadas:
- Camada de Domínio (inclui as entidades Project e Task) e 1 objeto de domínio (TaskStatus). Esta camada é responsável somente pela regra de negócio da aplicação. **Esta camada não deve possuir códigos de frameworks ou de recursos externos.**
- Camada de Aplicação (Use Cases) que são responsáveis por fazer a orquestração da camada de domínio. Nesta camada você irá encontrar as classes responsáveis por fazer o CRUD dos projetos e tarefas.
- Camada de Controllers, que são responsáveis apenas por receber a requisição e respondê-la com um HTTP Status Code e dados de retorno. O controller **não deve possuir regras de negócio!**
- Camada de Request que é onde estão os Form Request do Laravel. Estes são responsáveis por validar os dados de entrada da requisição e verificar se o usuário pode acessar o recurso em questão.
- Camada do Frontend que é responsável por exibir a interface para o usuário e fazer as requisições para o backend. Como o PDF de requisitos do projeto não especificou nada sobre o uso de frameworks JavaScript, eu tomei a liberdade de utilizar **Vue 3** juntamente com **Typescript** e **Tailwind CSS**.

## SOLID

Eu procurei aplicar os princípios do SOLID nesta aplicação. São eles:
- **Single Responsability Principle**: Determina que cada "módulo" ou classe só deve possuir 1 motivo para mudar. Apliquei este princípio em praticamente todas as classes do sistema, mesmo no frontend. Exemplos dessa utilização são as classes de Use Case que recebem dados de entrada, processam esses dados e retornam um resultado. Nada mais.
- **Open-Closed Principle**: Determina que as classes devem ser "abertas" para extensão, mas "fechadas" para modificação. 
- **Liskov Substitution Principle**: Determina que uma classe "pai" pode ser substituída por qualquer classe "filha" sem quebrar a aplicação. O leitor encontrará o uso desse princípio nas classes de entidade e nas classes de exceção. 
- **Interface Segreggation Principle**: Determina que uma classe não deve ser obrigada a implementar métodos que não irá utilizar. Embora esta aplicação tenha poucos intefaces, o leitor poderá observar que todos os métodos definidos por interfaces são utilizados por suas implementações.
- **Dependency Injection**: Determina que as dependências devem ser "injetadas" e não instanciadas dentro de uma classe ou módulo. O leitor poderá observar o uso desse princípio em todo o software, desde as camadas mais baixas da aplicação (use cases) até as camadas superiores no frontend. O Service Container do Laravel torna a injeção de dependências algo muito simples de ser aplicado.

## CQS
O CQS (Command Query Segreggation) determina que cada método de uma aplicação deve fazer apenas uma de duas coisas: command ou query. Os "commands" são responsáveis por alterar o estado do sistema, e as queries são responsáveis por fazer uma consulta no sistema.
Esta separação faz com que muitos bugs sejam evitados, e o software se torne algo mais "previsível". O leitor encontrará o uso desse princípio em diversos pontos desta aplicação, principalmente na escrita dos Use Cases. Observe como cada Use Case faz uma consulta ou um comando.

### Repository
Ainda falando sobre CQS, é importante notar que, os *comandos* devem ser tratados com muito mais "cuidado" pelo sistema, isto é, os *comandos* devem passar pela camada de domínio, pois é lá que as alterações de estado da aplicação são de fato validadas. As *queries* no entanto, não precisam passar pela camada de domínio, pois são responsáveis apenas por retornar dados que já foram outrora validados pelo sistema.
É nesse ponto que se torna interessante nós falarmos do padrão **repository**. 
Este padrão é muito mal interpretado pelos desenvolvedores, que muitas das vezes acreditam que este padrão serve apenas para criar uma abstração sobre a camada de banco de dados. Isso não poderia estar mais errado... O repositório deve ser considerado como uma *coleção* de entidades ou agregados que são gravados e consultados pelo sistema, ou seja, o repositório existe para interagir com a **camada de domínio**. Sendo assim, *Use Cases* de consulta, não precisam utilizar um repositório para consultar os dados. Isso apenas deixaria o sistema mais lento sem benefício algum. Por esse motivo, o leitor poderá perceber que nos *Use Cases* de consulta (como *GetProject*, por exemplo) eu não utilizei o ProjectRepository, mas sim o model Project do próprio Laravel. 
