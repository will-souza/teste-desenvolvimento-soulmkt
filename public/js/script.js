jQuery(function ($) {
    $('#csvForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: 'upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                showProducts(response.data);
            },
            error: function(xhr) {
                let error = JSON.parse(xhr.responseText);
                clearProducts();
                $('#product-alert').removeClass('d-none');
                $('#product-alert').text(error.message);
            }
        });

        function showProducts(data) {
            if (data.length === 0) return;
            clearProducts();
            
            const allowedHeaders = ['codigo', 'nome', 'preco'];
            const headers = Object.keys(data[0]).filter(header => {
                return allowedHeaders.includes(header);
            });

            const previewData = data.slice(0, 20);

            let headRow = '<tr>';

            headers.forEach(function(h) {
                if (allowedHeaders.includes(h)) {
                    headRow += `<th>${h}</th>`;
                }
            });


            headRow += '</tr>';


            $('#products-head').html(headRow);
            $('#products-head > tr').append('<th>opções</th>');
            

            previewData.forEach(row => {
                const filteredRow = filterProductObject(row)

                let tr = $('<tr>', {
                    class: row.isRedLine ? 'table-danger' : ''
                });

                let copyButton = $('<button>', {
                    text: 'Copiar JSON',
                    class: 'btn btn-primary',
                }).attr('data-json', JSON.stringify(filteredRow));

                headers.forEach(h => {
                    tr.append($('<td>', {
                        text: filteredRow[h] || '',
                    }))
                });

                let tdOptions = $('<td>').append(copyButton);
                row.copyAllowed ? tr.append(tdOptions) : tr.append($('<td>'));

                $('#products-body').append(tr);

                copyButton.on('click', function() {
                    const dataJson = $(this).attr('data-json');

                    try {
                        navigator.clipboard.writeText(dataJson);

                        $(this).removeClass('btn-primary');
                        $(this).addClass('btn-success');
                        $(this).text('Copiado!');
                    } catch (err) {
                        $(this).removeClass('btn-primary');
                        $(this).addClass('btn-danger');
                        $(this).text('Erro!');
                    }
                })

            });
            
            $('#products-table').removeClass('d-none');
            $('#clear-products').removeClass('d-none');
        }

        $('#clear-products').on('click', function() {
            clearProducts()
        });

        function clearProducts() {
            $('#product-alert').addClass('d-none');
            $('#products-head').empty();
            $('#products-body').empty();
            
            $('#clear-products').addClass('d-none');
        }

        function filterProductObject(product) {
            return {
                codigo: product.codigo,
                nome: product.nome,
                preco: product.preco
            };
        }
    })
});
