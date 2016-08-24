#SAMPP v.1.0

##O que é?
Sampp é uma estrutura básica de sistema de gestão em PHP construído usando o framework Codeigniter com HMVC e diversos plugins front-end instalados.

##O que está incluído?

**Conteúdo back-end:**
- Dashboard
- Páginas protegidas por login e senha;
- Cadastro de usuários;
- Módulos protegidos por permissão de usuários;
- Library MY_Form_validation;
- Library MY_Upload;
- Library MY_Crud;
- Tradução em PT-BR;
- fPDF para emissão de relatórios;

**Conteúdo front-end:**
- Bootstrap 3.0.6;
- Tema AdminLTE;
- JQuery 2.1.4;
- JQuery-UI 1.11.4;
- JQuery Growl 1.3.2;
- iCheck 1.0.1;
- Select2 4.0.3;
- ChartJS 2.1.4;
- Helper para tratamento ajax;

**Conteúdo banco de dados:**
- Usuário administrador pré-cadastrado;
- Módulos cadastro e relatórios;
- Tabela de permissões;
- Tabela com todas as cidades do Brasil;

##Como instalar?
Para instalar basta clonar este repositório:
	
```
git clone https://github.com/samuelpacheco/sampp.git
```

E por fim, importar no MySQL o arquivo sampp.sql que está na raiz do sistema e configurar o acesso ao banco de dados:
  
```
'hostname' => 'localhost',
'username' => 'seuusuario',
'password' => 'suasenha',
'database' => 'sampp',
```

##Como começar a usar?
Para fazer login no sistema, o usuário:senha padrão é admin:123. Ao fazer login, vai direto pra dashboard, que contem um resumo sobre os outros módulos. Não apague o usuário administrador, pois ele será usado para criar novos módulos dentro do sistema.

##COMO UTILIZAR AS FUNÇÕES AJAX INCLUSAS?	

###Envio de formulários
Voce pode criar uma requisição simplesmente omitindo a action do formulario e trocando por data-action='url'. Esta requisição deverá retornar até 4 parâmetros, conforme descrito no item PROCESSANDO REQUISIÇÕES.

###Carregamento dinâmico de conteúdo
É possível carregar ou atualizar conteúdo dinamicamente dentro do html, sem a necessidade de atualizar a página. Para isso, um elemento na pagina devera conter os atributos data-view(url da requisição onde está o conteúdo) e data-target(elemento que conterá o conteúdo carregado);

###Carregamento de formulário em modal
Para carregar conteúdo dinâmico em um modal, é necessário colocar o atributo data-modal(url da requisição onde está o conteúdo) e data-action(action do formulário) em um elemento que dispara o modal. Se a requisição retornar sucesso, o modal é fechado.
	
###Processando requisições
Toda requisição de formulário retorna até 4 parâmetros. São eles:
- *Array* message(tipo, mensagem, input) : Mensagens de erro/sucesso/atenção, que será mostrada com o plugin Growl. A mensagem de erro gerada pela library Form_validation retornará tambem o nome do input inválido para tratar a explanação do erro;
- *String* redirect : Uma url para o usuário ser redirecionado em caso de sucesso;
- *String* reload : Um elemento para ser recarregado em caso de sucesso. Este elemento deverá conter os atributos data-view e data-target, descritos na sessão CARREGAMENTO DINÂMICO DE CONTEUDO;
- *String* tab : Uma determinada aba que deverá estar ativa assim que o reload for executado.

