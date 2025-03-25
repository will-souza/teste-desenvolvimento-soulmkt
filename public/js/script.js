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
                console.log(error.message);
            }
        });

        function showProducts(data) {
            if (data.length === 0) return;

            clearProducts();
            
            const headers = Object.keys(data[0]);
            let headRow = '<tr>';
            headers.forEach(h => headRow += `<th>${h}</th>`);
            headRow += '</tr>';
            $('#products-head').html(headRow);
            
            const previewData = data.slice(0, 20);
            previewData.forEach(row => {
                let rowHtml = '<tr>';
                headers.forEach(h => rowHtml += `<td>${row[h] || ''}</td>`);
                rowHtml += '</tr>';
                $('#products-body').append(rowHtml);
            });
            
            $('#products-table').removeClass('d-none');
            $('#clear-products').removeClass('d-none');
        }

        $('#clear-products').on('click', function() {
            clearProducts();
        });

        function clearProducts() {
            $('#products-head').empty();
            $('#products-body').empty();
            
            $('#clear-products').addClass('d-none');
        }
    })
});
