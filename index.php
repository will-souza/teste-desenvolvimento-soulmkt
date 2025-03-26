<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Desenvolvimento PHP Soulmkt</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Teste de Desenvolvimento PHP Soulmkt</h1>
        <form id="csvForm" action="upload.php" enctype="multipart/form-data" method="POST">
            <div class="row">
                <div class="col-md-4">
                    <label for="csv_file" class="form-label">Selecione o arquivo CSV:</label>
                    <input class="form-control" type="file" id="csv_file" name="csv_file" accept=".csv" required>
                </div>
                <div class="col-md-4">
                    <label for="delimiter" class="form-label">Selecione o delimitador:</label>
                    <select class="form-select" id="delimiter" name="delimiter" required>
                        <option value="" selected disabled>Selecione uma opção</option>
                        <option value=",">Vírgula (',')</option>
                        <option value=";">Ponto e vírgula (';')</option>
                    </select>
                </div>
                <div class="clear mb-3"></div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Enviar Arquivo</button>
                    <button type="reset" class="btn btn-danger d-none" id="clear-products">Limpar tabela</button>
                </div>
            </div>
        </form>

        <div class="alert alert-danger mt-3 mb-3 d-none" id="product-alert" role="alert"></div>

        <table class="table table-striped table-hover mt-5 d-none" id="products-table">
            <thead id="products-head"></thead>
            <tbody id="products-body"></tbody>
        </table>
    </div>

    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/script.js"></script>
</body>
</html>
