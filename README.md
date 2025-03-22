Shopping Digital

Descrição do Projeto
O Shopping Digital é uma plataforma que integra diversas lojas de uma cidade, permitindo que clientes comparem preços, realizem compras e acionem entregadores autônomos para a entrega dos pedidos. O sistema conta com quatro tipos de usuários: cliente, lojista, entregador e administrador.
Tecnologias Utilizadas
    • Back-end: PHP puro (usando XAMPP para ambiente local) 
    • Banco de Dados: MySQL 
    • Front-end: Flutter (para versão web e mobile) 
    • Back-end Adicional: Node.js (para futuras funcionalidades em tempo real) 
    • Estilização: Bootstrap (para painel do lojista e cliente) 
Como Rodar o Projeto
1. Configuração do Ambiente
    1. Instale o XAMPP para rodar o servidor local (Apache + MySQL). 
    2. Instale o Flutter para rodar o front-end. 
    3. Instale o Node.js caso precise executar serviços adicionais no back-end. 
2. Configurar o Banco de Dados
    1. Abra o phpMyAdmin via XAMPP e crie um banco de dados chamado shopping_digital. 
    2. Importe o arquivo shopping_digital.sql para criar as tabelas. 
3. Rodar o Projeto
    • Back-end PHP: 
        ◦ Coloque os arquivos PHP na pasta htdocs do XAMPP. 
        ◦ Inicie o Apache e o MySQL no painel do XAMPP. 
    • Front-end Flutter: 
        ◦ Acesse a pasta do projeto Flutter e rode flutter run no terminal. 
    • Node.js (se aplicável): 
        ◦ Na pasta do back-end Node.js, execute npm install e npm start. 
Estrutura do Banco de Dados
    • usuarios (id, nome, email, senha, tipo_usuario) 
    • lojas (id, nome, descricao, id_usuario) 
    • produtos (id, nome, preco, id_loja) 
    • pedidos (id, id_cliente, status, data_criacao) 
    • itens_pedido (id, id_pedido, id_produto, quantidade, preco) 
    • entregadores (id, nome, status_disponivel) 
Funcionalidades Principais
    • Clientes: Buscar lojas, adicionar produtos ao carrinho e finalizar compras via Pix ou cartão. 
    • Lojistas: Cadastrar produtos, definir categorias e acompanhar pedidos. 
    • Entregadores: Aceitar pedidos e realizar entregas. 
    • Administradores: Gerenciar usuários, lojas e pedidos. 
Melhorias Futuras
    • Implementação de notificações em tempo real com Node.js. 
    • Otimização da interface com design aprimorado. 
    • Ampliação para outras cidades no futuro. 

Se precisar de suporte para instalação ou dúvidas sobre o projeto, entre em contato! 🚀
