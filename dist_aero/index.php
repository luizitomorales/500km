<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apresentação Dinâmica de Dados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
    $cidade = '';
    $uf = '';

    if (isset($_GET['cidade'])) {
        $cidade = htmlspecialchars($_GET['cidade']);
    }

    if (isset($_GET['uf'])) {
        $uf = htmlspecialchars($_GET['uf']);
    }
?>
<div id="filter-container">
    <form method="GET" action="">
        <label for="Cidade">Cidade:</label>
        <input type="text" id="Cidade" name="cidade" value="<?php echo $cidade; ?>">

        <label for="UF">UF:</label>
        <input type="text" id="UF" name="uf" value="<?php echo $uf; ?>">
        
        <button type="submit">Filtrar</button>
    </form>
</div>

<div id="table-container">
    <table id="data-table">
        <thead>
            <tr>
                <th>Cidade</th>
                <th>UF da cidade</th>
                <th>Aeroporto</th>
                <th>UF do Aeroporto</th>
                <th>Situado na Amazônia Legal</th>
                <th>Distância (em KM)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "500km";

            // Criar conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Paginação
            $results_per_page = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $results_per_page;

            // Construir consulta SQL com base nos filtros
            $sql = "SELECT `cidade`, `UF da cidade`, `Aeroporto`, `UF do Aeroporto`, `SITUADO NA AMAZÔNIA LEGAL`, `Distância (em KM)` FROM dist_cidades_aero WHERE 1=1";

            if (!empty($cidade)) {
                $cidade_esc = $conn->real_escape_string($cidade);
                $sql .= " AND `cidade` LIKE '%$cidade_esc%'";
            }

            if (!empty($uf)) {
                $uf_esc = $conn->real_escape_string($uf);
                $sql .= " AND `UF da cidade` LIKE '%$uf_esc%'";
            }

            // Adicionar LIMIT e OFFSET para a página
            $sql .= " LIMIT $results_per_page OFFSET $offset";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["cidade"]) . "</td>
                            <td>" . htmlspecialchars($row["UF da cidade"]) . "</td>
                            <td>" . htmlspecialchars($row["Aeroporto"]) . "</td>
                            <td>" . htmlspecialchars($row["UF do Aeroporto"]) . "</td>
                            <td>" . htmlspecialchars($row["SITUADO NA AMAZÔNIA LEGAL"]) . "</td>
                            <td>" . htmlspecialchars($row["Distância (em KM)"]) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Nenhum resultado encontrado</td></tr>";
            }
            
            // Calcular o número total de registros
            $total_sql = "SELECT COUNT(*) AS total FROM dist_cidades_aero WHERE 1=1";

            if (!empty($cidade)) {
                $total_sql .= " AND `cidade` LIKE '%$cidade_esc%'";
            }

            if (!empty($uf)) {
                $total_sql .= " AND `UF da cidade` LIKE '%$uf_esc%'";
            }

            $total_results = $conn->query($total_sql);
            $total_row = $total_results->fetch_assoc();
            $total_pages = ceil($total_row['total'] / $results_per_page);

            $conn->close();
            ?>
        </tbody>
    </table>
    <div id="pagination">
        <?php
        $range = 2; // Número de links de página a serem exibidos ao redor da página atual

        // Link para a página anterior
        if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . (isset($_GET['cidade']) ? '&cidade=' . urlencode($_GET['cidade']) : '') . (isset($_GET['uf']) ? '&uf=' . urlencode($_GET['uf']) : '') . '">Anterior</a>';
        }

        // Links para as páginas
        for ($i = max(1, $page - $range); $i <= min($total_pages, $page + $range); $i++) {
            echo '<a href="?page=' . $i . (isset($_GET['cidade']) ? '&cidade=' . urlencode($_GET['cidade']) : '') . (isset($_GET['uf']) ? '&uf=' . urlencode($_GET['uf']) : '') . '"' . ($i == $page ? ' style="font-weight: bold;"' : '') . '>' . $i . '</a>';
        }

        // Link para a próxima página
        if ($page < $total_pages) {
            echo '<a href="?page=' . ($page + 1) . (isset($_GET['cidade']) ? '&cidade=' . urlencode($_GET['cidade']) : '') . (isset($_GET['uf']) ? '&uf=' . urlencode($_GET['uf']) : '') . '">Próximo</a>';
        }
        ?>
    </div>
</div>
</body>
</html>
