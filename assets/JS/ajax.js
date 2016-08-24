/*
Samuel Pacheco
10/08/2016

Este arquivo manipula requisições ajax.

*/

//Dispara a funçao sendForm a partir do evento submit do formulario
$(document.body).on('submit', '[data-action]', function(event) { 

	//Coloca o botao de submit como loading
	$(this).find('button[type=submit]').button('loading');
	//Chama a função de envio de dados, passando o form como parametro

	processRequest(sendForm(this));

	//Previne o comportamento padrao do navegador (recarregamento de pagina)
	event.preventDefault();
	return false;
});

//Dispara a função sendSimple a partir de um evento clique
$(document.body).on('click', '[data-param]', function(event) {

	//faz a pergunta de confirmação
	if(confirm($(this).data('msg'))) {
		processRequest(sendSimple(this));
	}
	
	//Previne o comportamento padrao do navegador (recarregamento de pagina)
	event.preventDefault();
	return false;
});

//Envia uma requisição simples, enviando 1 parametro para o back-end
function sendSimple(send) {

	var url = $(send).data('url');
	var dados = $(send).data('param');
	var reload = $(send).data('reload');

	//Faz a chamada POST com o ajax
	var request = $.ajax({
		method: 'POST',
		url: url,
		data: {'param' : dados},
	});

	return {'request' : request, 'form' : null, 'reload' : reload};
}


//Envia os dados do formulario
function sendForm(form) {
	//Remove todas as classes de erro em formularios (se houver)
	$('.form-group').removeClass('has-error');
	
	//Pega a url e verifica se é pra fazer o upload
	var url = $(form).data('action');
	var upload = $(form).data('upload');
	var reload = $(form).data('reload');

	//Configura para processamento do upload
	if(upload == true) {
		var formSe = $(form)[0];
		var dados = new FormData(formSe);
		var contentType = false;
		var cache = false;
		var processData = false;
	} else {
		//Serializa os dados do formulário
		var dados = $(form).serializeArray();
	}

	//Faz a chamada POST com o ajax
	var request = $.ajax({
		method: 'POST',
		url: url,
		data: dados,
		contentType: contentType,  
		cache: cache,
		processData: processData
	});

	return {'request' : request, 'form' : form, 'reload' : reload};

}

//Processa o resultado de uma requisição
function processRequest(response) {

	//Em caso da requisição ser feita com sucesso
	response.request.done(function(result) {
		//Tenta processar o resultado
		try {
			//Parseia o resultado no formato JSON
			result = JSON.parse(result); 

			//Faz o reload na div de resultados;
			if(result.reload) {
				var view = $(result.reload).data('view');
				var target = $(result.reload).data('target');
				var tab = result.tab_active;
				
				loadView(view, target, tab);
				
			}

			//Se retornar instrução para redirecionar, faz o redirecionamento
			if(result.redirect) {
				window.location.href = result.redirect, 500;
			}
			

			//retorna as mensagens de erro/sucesso
			if(result.message) {
				var message = result.message;
				//Percorre as mensagens
				for(var notice in message) {
					//Verifica o tipo de mensagem e abre o alerta
					switch(message[notice][0]) {
						case 'warning':
							$.growl.warning({title: 'ATENÇÃO!',  message: message[notice][1]}); break;
						case 'error':
							$.growl.error({title: 'ERRO!',  message: message[notice][1]});
							//Coloca classe de erro no input (3 parametro do array[nome co campo])
							$('input[name='+ message[notice][2] +']').closest('.form-group').addClass('has-error'); break;
						case 'success':
							//Fecha todos os modals
							$('.modal').modal('hide');
							$.growl.notice({title: 'SUCESSO!',  message: message[notice][1]}); break;
					}
				}
			}

			//Para debug do que ocorre no servidor
			if(result.debug) {
				var debug = result.debug;
				//Percorre os erros
				for(var error in debug) {
					console.log(debug[error]);
				}
			}
		//Se nada der certo, mostra mensagem de erro
		} catch(e) {
			$.growl.error({title: 'ERRO NO SISTEMA:',  message: e});
		}
	});

	//Se a requisição der erro, mostra um alerta informando
	response.request.error(function() {
		$.growl.error({title: 'FALHA!',  message: 'Não foi possível processar a requisição.'});
	});

	//Em qualqur caso...
	response.request.always(function() {
		//Retira o estado de loading do botao
		$(response.form).find('button[type=submit]').button('reset');
	});
}

//Carrega uma view(view) dentro de uma div qualquer(target), podendo ou nao colocar uma aba(tab) como active
function loadView(view, target, tab = null) {
	//Gera a requisĩção
	$.ajax({
		method: 'POST',
		url: view,
		//Injeta o resultado na tab alvo
		success:function(data) {
        	$(target).html(data);
        	$('a[href='+ tab +']').tab('show');
    	},

    	//Em caso de erro, mostra mensagem
    	error: function() {
    		$(target).html('Não foi possível retornar os dados pra esta solicitação.');
    	}
    });
}
loadView($('[data-view]').data('view'), $('[data-view]').data('target'));


//Faz um chamado ajax para retorna uma view ao abrir um modal
$(document.body).on('click', '[data-modal]', function (e) {
    loadFormOnModal(this);
});

function loadFormOnModal(event) {

	var view = $(event).data('modal');
	var target = $(event).data('target');
	var action = $(event).data('action');

	$(target).find('.modal-body').html("<div class='loading'></div>");

	//Gera a requisĩção
	$.ajax({
		method: 'POST',
		url: view,
		//Injeta o resultado na tab alvo
		success:function(data) {
        	$(target).find('.modal-body').html(data);
        	$(target).find('form').attr('data-action', action);
    	},

    	//Em caso de erro, mostra mensagem
    	error: function() {
    		$(target).html('Não foi possível retornar os dados pra esta solicitação.');
    	}
    });
}