//Redireciona ao clicar em uma linha de uma tabela com o atributo data-click
$('tr[data-click]').click(function() {
	window.location.href = $(this).data('click');
});


