document.querySelector('#imagem').addEventListener('change', function() { 
    document.querySelector('.span1').textContent = this.files[0].name;
});

$(document).ready(function() {
    // Função para carregar a lista de produtos
    function carregarListaProdutos() {
        $.ajax({
            url: 'listar_produtos.php', // Arquivo PHP que retorna a lista de produtos
            method: 'GET',
            success: function(response) {
                $('#produtos').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Erro ao buscar produtos:', status, error);
            }
        });
    }

    // Carregar a lista de produtos quando a página é carregada
    carregarListaProdutos();

    // Submissão do formulário via AJAX
    $('.formu').on('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        
        $.ajax({
            url: '', // O script que processa o formulário
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Resposta do servidor:', response);
                
                // Limpar o formulário após o envio bem-sucedido
                $('.formu')[0].reset();
                
                // Limpar o nome do arquivo exibido
                document.querySelector('.span1').textContent = 'Selecione a Imagem';
                
                // Exibir mensagem de sucesso
                $('#res').text('Produto cadastrado com sucesso!');

                // Atualizar a lista de produtos
                carregarListaProdutos();
            },
            error: function(xhr, status, error) {
                console.error('Erro:', status, error);
                
                // Exibir mensagem de erro
                $('#res').text('Ocorreu um erro ao cadastrar o produto.');
            }
        });
    });
});