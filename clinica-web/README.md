# Sistema de Clínica/Consultório

**Trabalho 2 — Desenvolvimento Web Servidor — UTFPR**

## Integrantes

- Breno Gonzalez Vieira
- Matheus de Lima Santos

## Sobre o projeto

Aplicação web para a gestão de uma clínica/consultório: cadastro de **pacientes**, cadastro de **profissionais** e **agendamento de consultas**, com autenticação de usuários por sessão. O projeto evolui o Trabalho 1 (PHP procedural) para uma arquitetura **orientada a objetos**, com **MVC**, **rotas com URLs transparentes**, **Composer (autoload + pacote Carbon)** e **banco de dados MySQL/MariaDB via PDO**.

### Funcionalidades

- Login e logout com sessão; a senha é verificada com `password_verify` (hash bcrypt) e o id da sessão é renovado no login
- Todas as páginas, exceto o login, exigem autenticação
- Proteção CSRF: todo formulário POST envia um token validado no servidor
- Painel inicial com totais e próximas consultas
- CRUD completo de Pacientes, Profissionais e Consultas (listar, cadastrar, editar e excluir)
- Validações feitas no servidor (PHP) em todos os formulários, com mensagens de erro e de sucesso
- CPF validado pelos dígitos verificadores (e salvo somente com números), e-mail, datas e tamanho máximo dos campos
- Regra de negócio: um profissional não pode ter duas consultas (não canceladas) no mesmo horário
- Proteção da integridade dos dados: não é possível excluir paciente/profissional que possui consultas
- Filtro de consultas por status
- Interface com Bootstrap 5 (responsiva)

## Tecnologias

- PHP 8+ (orientação a objetos)
- MySQL / MariaDB com **PDO**
- **Composer** (autoload + pacote [`nesbot/carbon`](https://carbon.nesbot.com/) para manipulação e formatação de datas)
- Bootstrap 5 (via CDN)
- Apache com `mod_rewrite` (URLs transparentes)

## Requisitos para instalação

| Requisito | Versão |
|---|---|
| PHP | 8.0 ou superior (extensão `pdo_mysql` habilitada) |
| MySQL ou MariaDB | 5.7+ / 10.3+ |
| Apache com `mod_rewrite` | já vem habilitado no XAMPP |
| Composer | 2.x (https://getcomposer.org) |
| Navegador com acesso à internet | para carregar o Bootstrap via CDN |

> O XAMPP (https://www.apachefriends.org) já traz Apache, PHP e MySQL/MariaDB.

## Passo a passo de instalação (XAMPP / Windows)

1. **Instale o XAMPP** e o **Composer** (no instalador do Composer, aponte para o `php.exe` do XAMPP, em `C:\xampp\php\php.exe`).
2. **Baixe o projeto** para dentro da pasta `htdocs` do XAMPP:
   ```bash
   cd C:\xampp\htdocs
   git clone <URL-DO-REPOSITORIO> clinica-web
   cd clinica-web
   ```
   (a pasta deve se chamar `clinica-web`; se usar outro nome, as URLs se ajustam automaticamente)
3. **Instale as dependências do Composer** (gera a pasta `vendor/` com o autoload e o pacote Carbon):
   ```bash
   composer install
   ```
4. **Inicie o Apache e o MySQL** no XAMPP Control Panel.
5. **Crie o banco de dados:** acesse `http://localhost/phpmyadmin`, vá na aba **Importar** e selecione o arquivo `database/schema.sql` (ele cria o banco `clinica_db`, as tabelas e o usuário inicial).
   - *Opcional:* importe também `database/dados_exemplo.sql` para ter pacientes, profissionais e consultas de exemplo.
6. **Confira o arquivo de configuração** `config/config.php`. O padrão do XAMPP já funciona (usuário `root`, senha vazia). Se o seu banco usa outros dados, altere ali:
   ```php
   'bd' => [
       'host'    => 'localhost',
       'nome'    => 'clinica_db',
       'usuario' => 'root',
       'senha'   => '',
   ],
   ```
7. **Acesse o sistema:** `http://localhost/clinica-web/`

### Login

| E-mail | Senha |
|---|---|
| `admin@gmail.com` | `123456` |

### Solução de problemas

- **Página diz "Dependências não instaladas"** → rode `composer install` na pasta do projeto.
- **"Erro na conexão"** → confira se o MySQL está iniciado e os dados em `config/config.php`.
- **Todas as páginas (exceto a inicial) dão "Not Found"** → o `mod_rewrite` ou o `.htaccess` não está ativo. No XAMPP, em `apache/conf/httpd.conf`, confira se existe a linha `LoadModule rewrite_module modules/mod_rewrite.so` (sem `#`) e se o diretório `htdocs` está com `AllowOverride All`. Reinicie o Apache.
- **Criou uma nova classe em `app/`** → rode `composer dump-autoload` para atualizar o autoload.

## Rotas (URLs transparentes)

As rotas ficam em `config/rotas.php` (array `URL => [Controller, método]`) e são processadas pela classe `Roteador`.

| URL | Descrição |
|---|---|
| `/` | Painel inicial |
| `/login`, `/logout` | Autenticação |
| `/pacientes` | Lista de pacientes |
| `/pacientes/novo` | Formulário de novo paciente |
| `/pacientes/editar?id=1` | Formulário de edição |
| `/pacientes/salvar` | Grava o paciente (POST) |
| `/pacientes/excluir` | Exclui o paciente (POST) |
| `/profissionais`, `/profissionais/novo`, `/profissionais/editar`, `/profissionais/salvar`, `/profissionais/excluir` | Mesmo padrão, para profissionais |
| `/consultas`, `/consultas/novo`, `/consultas/editar`, `/consultas/salvar`, `/consultas/excluir` | Mesmo padrão, para consultas (lista aceita `?status=agendada`) |

## Arquitetura (MVC + Orientação a Objetos)

```
clinica-web/
├── index.php                 → ponto de entrada único (front controller)
├── .htaccess                 → envia todas as URLs para o index.php
├── composer.json             → autoload (classmap) + pacote nesbot/carbon
├── config/
│   ├── config.php            → dados de conexão com o banco
│   └── rotas.php             → tabela de rotas
├── database/
│   ├── schema.sql            → cria o banco, as tabelas e o usuário inicial
│   └── dados_exemplo.sql     → dados de exemplo (opcional)
└── app/
    ├── Core/                 → BD (PDO), Roteador, Url, Csrf, Controller (abstrato), ControllerProtegido
    ├── Controllers/          → Auth, Home, Paciente, Profissional e Consulta
    ├── Models/               → entidades: Usuario, Paciente, Profissional, Consulta
    ├── Repositories/         → acesso ao banco com PDO (interface Crud + classe abstrata)
    ├── Traits/               → MensagemFlash, ExigeLogin, Validacao
    └── Views/                → apenas HTML de apresentação (layout, login, home, cadastros)
```

- **Model**: classes de entidade (`Paciente`, `Consulta`...) e **repositórios** que executam as consultas no banco com PDO (`prepare`, `bindParam`, `execute`, `fetchAll` com `PDO::FETCH_CLASS`).
- **View**: somente apresentação; recebem os dados do Controller pela variável `$dados`.
- **Controller**: recebe a requisição, valida os dados no servidor, chama o repositório e escolhe a View.
- **Orientação a objetos utilizada**: classes e objetos, encapsulamento (`private`/`protected`), herança (`ControllerProtegido extends Controller`, repositórios `extends RepositorioBase`), classe abstrata, interface (`Crud`), métodos estáticos (`BD::conectar()`, `Url::para()`) e **traits**.
- **Composer**: autoload por *classmap* da pasta `app/` e uso do pacote **Carbon** para formatar e validar datas.

## Requisitos do trabalho × onde foram atendidos

| Requisito | Atendido em |
|---|---|
| PHP 8+ e Orientação a Objetos | todo o diretório `app/` |
| Composer com autoload e packages | `composer.json` (classmap + `nesbot/carbon`), `index.php` |
| Banco MySQL/MariaDB via PDO | `app/Core/BD.php` e `app/Repositories/` |
| Rotas (URL transparente) + MVC | `.htaccess`, `config/rotas.php`, `app/Core/Roteador.php` |
| Documentação de instalação | este README |
| Interface adequada com feedback e erros | `app/Views/` + mensagens flash (`MensagemFlash`) |
| Extra: framework de interface | Bootstrap 5 |

## Evolução em relação ao Trabalho 1

| Trabalho 1 (PHP procedural) | Trabalho 2 (este projeto) |
|---|---|
| `index.php?acao=pacientes-lista` e *whitelist* de ações | URLs transparentes: `/pacientes` (`.htaccess` + `config/rotas.php` + classe `Roteador`) |
| Funções soltas em `models/*.model.php` | Entidades (`Paciente`...) + repositórios com interface `Crud` e classe abstrata |
| `require` manual de cada arquivo | Composer com autoload (`vendor/autoload.php`) |
| Controllers como scripts (`*.controller.php`) | Classes `Controller` com herança e traits |
| Datas mostradas como vêm do banco | Datas formatadas e validadas com o pacote Carbon |
| Cadastro e listagem | Cadastro, edição, **exclusão**, filtro por status e painel inicial |
| Erros de validação na tela | Erros de validação **e** mensagens de sucesso (flash) |
| Banco com PDO (`FETCH_ASSOC`) | PDO com `bind`/`FETCH_CLASS` mapeando para objetos |

Foram mantidos do Trabalho 1: o esquema do banco, o login com hash de senha, a proteção CSRF, a validação do CPF e o tratamento de CPF duplicado.

## Particularidades

- O CPF é salvo apenas com números; os pontos e o traço são removidos (a tela exibe formatado).
- O sistema usa o Bootstrap pela internet (CDN), então é preciso estar conectado para a interface ficar com o visual correto.
- Um profissional não pode ter duas consultas (não canceladas) no mesmo horário.
- Paciente ou profissional com consultas registradas não pode ser excluído.
- Ao criar uma nova classe dentro de `app/`, rode `composer dump-autoload`.

## Problemas conhecidos

- Não há tela de cadastro de usuários do sistema; o usuário inicial é criado pelo `schema.sql`.
- A validação do e-mail e do CPF é apenas de formato (não confirma se existem de verdade).

## Distribuição de atividades

- **Breno Gonzalez Vieira:** (preencher)
- **Matheus de Lima Santos:** (preencher)
