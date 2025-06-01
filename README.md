# Desafio Ecommerce
<img src="https://go-skill-icons.vercel.app/api/icons?i=git,docker,php,sqlite,laravel,html,css,vue,vite,typescript,pinia,composer,npm,nuxt,tailwind" />

Nesse projeto, segui a abordagem de criar um carrinho para cada usuário, sendo assim, sempre que um carrinho sofre alteração, o backend recalcula os valores do mesmo e mantém sempre tudo atualizado.

Com relação à arquitetura escolhida para o backend, utilizei um padrão no qual já escreví anteriormente no dev.to, você pode dar uma conferida [aqui](https://dev.to/jhonhenkel/minha-arquitetura-no-laravel-26nj).

### Sumário
- [Como Instalar](#como-instalar)
  - [Processo automatizado](#processo-automatizado)
  - [Processo manual](#processo-manual)
- [Comandos Make](#comandos-make)
- [Desafio solicitado](#desafio-solicitado)
  - [🎯 Objetivo](#-objetivo)
  - [🧩 Descrição do Desafio](#-descrição-do-desafio)
  - [📦 Especificações Técnicas](#-especificações-técnicas)

## Como Instalar
### Processo automatizado: 
- Para instalar o projeto, basta executar o comando `make install`. 
- Após finalizar a instalação, pode demorar alguns minutos até que seja instalado os pacotes npm e seja dado o start no nuxt. 
- Após finalizar a instalação, caso dê erros ao fazer login, rode os comandos abaixo:,
  ```bash
  sudo chown www-data:www-data -R backend/storage/logs/ && sudo chown www-data:www-data -R backend/storage/framework && sudo chown www-data:www-data backend/database/ && sudo chown www-data:www-data backend/database/database.sqlite
  ```

### Processo manual:
- Basta rodar os comandos abaixo:
  ```bash
  cp config/develop/docker/docker-compose.yml docker-compose.yml
  docker compose up -d --build
  docker exec ec_backend /bin/bash -c "composer install"
  cp backend/.env.example backend/.env
  cp frontend/.env.example frontend/.env
  docker exec ec_backend /bin/bash -c "php artisan key:generate"
  docker exec ec_backend /bin/bash -c "php artisan migrate --seed --force"
  chown www-data:www-data -R backend/storage/logs/
  chown www-data:www-data -R backend/storage/framework
  chown www-data:www-data backend/database/
  chown www-data:www-data backend/database/database.sqlite
  ```
- Após finalizar a instalação, pode demorar alguns minutos até que seja instalado os pacotes npm e seja dado o start no nuxt.

Para acessar o frontend, basta acessar a URL `http://localhost` e para acessar o backend, basta acessar a URL `http://localhost:8000`.
## Comandos Make
- Acessar container backend: `make be-bash`
- Acessar container frontend: `make be-sh`
- Logs backend: `make be-logs`
- Instalar o projeto: `make install`

---
## Desafio solicitado
### 🎯 Objetivo
Desenvolver um módulo de carrinho de compras, simulando a jornada de compra de um usuário. O sistema deverá permitir a seleção de produtos, escolha da forma de pagamento e exibir o valor final da compra, aplicando descontos ou juros, conforme regras de negócio.

### 🧩 Descrição do Desafio
Você deve construir:

- Uma API em PHP responsável por processar os dados do carrinho, aplicar regras de cálculo e retornar o valor final da compra.
- Frontend em Vue.js onde o usuário possa:
  - Adicionar produtos ao carrinho
  - Escolher a forma de pagamento (Pix, Cartão de Crédito à vista ou parcelado)
  - Visualizar o valor final da compra, já com os descontos ou acréscimos aplicados
- Teste unitário (backend): Criar testes automatizados que verifiquem se o valor final da compra está sendo corretamente calculado de acordo com as regras de desconto para pagamentos à vista e aplicação de juros compostos para parcelamentos.

### 📦 Especificações Técnicas
- **Frontend**: Vue.js (com ou sem biblioteca de componentes)
- **Backend**: PHP (com ou sem framework)
- **Banco de dados**: Não é necessário — os produtos podem estar fixos no código
- **Produtos disponíveis**: 5 produtos fixos com nome e preço, definidos no código
- Regras de Pagamento:
  - **Pix**: Pagamento à vista com 10% de desconto
  - **Cartão de Crédito à Vista (1x)**: 10% de desconto
  - **Cartão de Crédito Parcelado (2x até 12x)**: Juros compostos de 1% ao mês sobre o valor total

Para o cálculo do valor total com juros compostos, utilize a seguinte fórmula:

**M = P ⋅ ( 1 + i ) n**

Onde:

- **M** é o montante final, ou seja, o valor total da compra com juros aplicados.
- **P** é o principal, ou seja, o valor inicial (total da compra sem juros).
- **i** é a taxa de juros por período, que deve ser expressa em decimal. No caso de 1% ao mês, seria 0,01.
- **n** é o número de períodos (número de meses, no caso de uma taxa mensal).

O cálculo do valor final da compra deve ser feito considerando a forma de pagamento selecionada, aplicando descontos e acréscimos conforme descrito nas regras de negócio.

Exemplo de payload da requisição:

```json
{
    "produtos": [
        { "nome": "Fone Bluetooth", "valor": 100.00, "quantidade": 2 },
        { "nome": "Mouse Gamer", "valor": 150.00, "quantidade": 1 }
    ],
    "metodo_pagamento": "CARTAO_CREDITO",
    "parcelas": 3
}
```
💡 Exemplo de resposta da API:

```json
{
    "valor_total": 365.00
}
```
