# Teste de Desenvolvimento PHP Soulmkt

## 📝 Descrição do Projeto

Esta aplicação web permite o upload de arquivos CSV contendo informações de produtos (nome, código e preço), processa os dados no servidor e exibe os resultados em uma tabela HTML interativa, com funcionalidades especiais conforme requisitos do teste.

## ✨ Funcionalidades

Upload de arquivos CSV via AJAX
- Configuração do delimitador (vírgula ou ponto-e-vírgula)
- Ordenação alfabética dos produtos por nome
- Exibição em tabela HTML com tratamento especial para:
    - Linhas vermelhas para preços negativos
    - Botão de copiar JSON apenas para códigos com números pares
- Validação de arquivos e tratamento de erros

## 🛠️ Pré-requisitos
- PHP 7.0+
- Composer
- NPM (para desenvolvimento opcional)
- Servidor web (Apache/Nginx) ou PHP built-in server

## 🚀 Instalação

### Clone o repositório:

```bash
git clone git@github.com:will-souza/teste-desenvolvimento-soulmkt.git
cd teste-desenvolvimento-soulmkt
```

### Instale as dependências do PHP:

```bash
composer install
composer dump-autoload
```

### Instale as dependências do JavaScript:

```bash
npm install
```

### Configure o servidor:

Para Apache/Nginx: configure o document root para a pasta raiz do projeto

Para PHP built-in server:

```bash
php -S localhost:8000 -t .
```
## 🧑‍💻 Como Utilizar
- Acesse a aplicação no navegador (ex: http://localhost:8000)

- Selecione o arquivo CSV contendo os produtos

- Escolha o delimitador correto (vírgula ou ponto-e-vírgula)

- Clique em "Enviar Arquivo" para processar o arquivo

- Visualize os resultados na tabela exibida

## 📋 Formatos de Arquivo Aceitos
A aplicação aceita arquivos CSV com as colunas:

- __nome__ (obrigatório)
- __codigo__ (obrigatório)
- __preco__ (obrigatório)

### Exemplo de formato:

```csv
nome,codigo,preco
Produto A,ABC123,R$ 10,99
Produto B,XYZ456,R$ -5,50
```

## 📄 Licença
Este projeto é para fins de avaliação técnica.