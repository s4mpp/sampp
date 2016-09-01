 <script>

    $('.select2_ajax.select2-hidden-accessible').select2("destroy");
    $('.select2').select2({placeholder: "Selecione", allowClear: true, language: "pt-BR"}); 

    $('.select2_ajax[class!=select2-hidden-accessible]').select2({
        placeholder: "Selecione",
        allowClear: true,
        language: "pt-BR",
        ajax: {
            url: function() {
                return $(this).data('target')
            },
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 10) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (res) {
            return res;
        },
        minimumInputLength: 3,
        templateResult: function(data) {
            if (data.loading) return data.text;

            var res = data.nome;
            return res;
        },
        templateSelection: function(data) {
            return data.nome || data.text;
        }
    });


    $('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue',
        }
    );


 $(function() {
    $( "#sortable" ).sortable({
      revert: true
    });
    $( "#draggable" ).draggable({
      connectToSortable: "#sortable",
      helper: "clone",
      revert: "invalid"
    });
    $( "ul, li" ).disableSelection();
  });
 
    $(".datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });



    $('.datepicker').mask('99/99/9999', {placeholder: '__/__/____'});
    $('.time').mask('99:99', {placeholder: '__:__'});
    $('.cep').mask('99.999-999', {placeholder: '__.___-___'});
    $('.cpf').mask('999.999.999-99', {placeholder: '___.___.___'});
    $('.cnpj').mask('99.999.999/9999-99', {placeholder: '__.____.____/____-__'});   
    $('.placa').mask('aaa-9999', {
        translation: {
            'a': {
                pattern: /[A-Za-z]/, optional: true
            }
        },
        placeholder: '___-____', 
    });
    $('.telefone').mask('(99) 9999-9999?9', {placeholder: '(__) ____-____  '});


    $(function(){
        var formObject = $('form');

        if($(formObject).length > 0) {
            formObject.data('original_serialized_form', formObject.serialize());

            $(':submit').click(function() {
                window.onbeforeunload = null;
            });

            window.onbeforeunload = function() {
                if (formObject.data('original_serialized_form') !== formObject.serialize()) {
                    return "Os dados contidos no formulário não foram salvos. Ao sair desta página sem salvar, todas as mudanças serão perdidas.";
                }
            };
        }
    });

    //Redireciona ao clicar em uma linha de uma tabela com o atributo data-click
    $('tr[data-click]').on('click', function() {
        window.location.href = $(this).data('click');
    });

    //Paginação de resultados
    $('.pag a.btn').on('click', function() {
        loadView($(this).data('url'), '#lista');
    });

    //Fecha modal com ESC
    $(document).keydown(function(e) {
        if (e.keyCode == 27) {
            $('.modal').modal('hide');
        }
    });

</script>