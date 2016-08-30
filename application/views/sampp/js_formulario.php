<script>
$('#carregaForm').on('click', function() {

	var url = $(this).data('url');
	var tabela = $('#tabela').val();
	var submodulo = $('#submodulo').val();
 
	 if(!url || !tabela || !submodulo) {

	 	$.growl.error({title: 'ERRO!',  message: 'Selecione a tabela e o submódulo.'});

	 } else {
	
		$('#form').html("<div class='loading'></div>");

		loadView(url+'?tabela='+tabela+'&submodulo='+submodulo, '#form');	
	}

});



</script>