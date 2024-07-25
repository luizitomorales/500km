import mysql.connector
import csv

# Configurando a conexão ao banco de dados
config = {
    'user': 'root',
    'password': '',
    'host': 'localhost',
    'database': '500km'
}

try:
    conexao = mysql.connector.connect(**config)
    cursor = conexao.cursor()

    # Contar o número de linhas antes da deleção
    cursor.execute('SELECT COUNT(*) FROM dist_cidades_aero')
    linhas_antes = cursor.fetchone()[0]
    print(f"Número de linhas antes da deleção: {linhas_antes}")

    # Deletar todos os dados existentes na tabela
    cursor.execute('DELETE FROM dist_cidades_aero')

    # Contar o número de linhas após a deleção
    cursor.execute('SELECT COUNT(*) FROM dist_cidades_aero')
    linhas_depois_delecao = cursor.fetchone()[0]
    print(f"Número de linhas após a deleção: {linhas_depois_delecao}")

    csv_caminho = 'resultado_dist_cidades_aero.csv'

    linhas_lidas = 0  # Variável para contar o número de linhas lidas do CSV

    with open(csv_caminho, 'r', encoding='utf-8') as csvfile:
        reader = csv.reader(csvfile)
        next(reader)  # Pula a primeira linha (cabeçalho)

        for row in reader:
            cursor.execute('''
                INSERT INTO dist_cidades_aero (
                    cidade, `UF da cidade`, `Latitude da cidade`, `Longitude da cidade`,
                    Aeroporto, `UF do Aeroporto`, `Latitude do Aeroporto`,
                    `Longitude do Aeroporto`, `SITUADO NA AMAZÔNIA LEGAL`,
                    `Distância (em KM)`
                ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            ''', row)
            linhas_lidas += 1

    conexao.commit()

    # Contar o número de linhas após a inserção
    cursor.execute('SELECT COUNT(*) FROM dist_cidades_aero')
    linhas_apos_insercao = cursor.fetchone()[0]
    print(f"Número de linhas após a inserção: {linhas_apos_insercao}")

    cursor.close()
    conexao.close()

    print(f"Dados inseridos com sucesso! Linhas lidas do CSV: {linhas_lidas}")

except mysql.connector.Error as err:
    print(f"Erro: {err}")
