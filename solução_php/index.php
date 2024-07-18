<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apresentação Dinâmica de Dados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="filter-container">
        <form method="GET" action="">
            <label for="Cidade">Cidade:</label>
            <input type="text" id="Cidade" name="cidade" value="<?php echo isset($_GET['cidade']) ? $_GET['cidade'] : ''; ?>">
            
            <button type="submit">Filtrar</button>
        </form>
    </div>
    <div id="table-container">
        <table id="data-table">
            <thead>
                <tr>
                    <th>ID</th>
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

                // Construir consulta SQL com base nos filtros
                $sql = "SELECT `cidade`, `UF da cidade`, `Aeroporto`, `UF do Aeroporto`, `SITUADO NA AMAZÔNIA LEGAL`, `Distância (em KM) ` FROM dist_cidades_aero WHERE 1=1";

                if (isset($_GET['cidade']) && $_GET['cidade'] != '') {
                    $cidade = $conn->real_escape_string($_GET['cidade']);
                    $sql .= " AND cidade LIKE '%$cidade%'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Saída dos dados de cada linha
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["cidade"]. "</td>
                                <td>" . $row["UF da cidade"]. "</td>
                                <td>" . $row["Aeroporto"]. "</td>
                                <td>" . $row["UF do Aeroporto"]. "</td>
                                <td>" . $row["SITUADO NA AMAZÔNIA LEGAL"]. "</td>
                                <td>" . $row["Distância (em KM)"]. "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum resultado encontrado</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
