<?php

if (isset($_FILES["arquivos"]["tmp_name"])) {

    foreach ($_FILES["arquivos"]["tmp_name"] as $chave => $caminhoTemporario) {

        if ($_FILES["arquivos"]["error"][$chave] === UPLOAD_ERR_OK) {
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://api.ocr.space/parse/image',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'apikey' => 'K83939763988957',
                    'language' => 'por',
                    'isOverlayRequired' => 'false',
                    'file' => new CURLFile($caminhoTemporario, $_FILES["arquivos"]["type"][$chave], $_FILES["arquivos"]["name"][$chave])
                ]
            ]);

            $resposta = curl_exec($ch);

            curl_close($ch);

            $resultadoOCR = json_decode($resposta, true);

            $texto = $resultadoOCR["ParsedResults"][0]["ParsedText"] ?? '';

            $linhas = preg_split('/\r\n|\r|\n/', trim($texto));

            $linhas = array_values(
                array_filter(
                    array_map(function ($linha) {
                        $linha = trim($linha);
                        $linha = ltrim($linha, ':');
                        return trim($linha);
                    }, $linhas),
                    fn($linha) => $linha !== ''
                )
            );

            if ($linhas[1] === 'CÓD.') {
                // ITEM, CÓD, VAL, QTD, FORN, depois os valores
                $produto = [
                    'item'       => $linhas[5] ?? '',
                    'codigo'     => $linhas[6] ?? '',
                    'validade'   => $linhas[7] ?? '',
                    'quantidade' => $linhas[8] ?? '',
                    'fornecedor' => $linhas[9] ?? ''
                ];
            } else {
                // ITEM, valor, CÓD, valor, VAL, valor...
                $produto = [
                    'item'       => $linhas[1] ?? '',
                    'codigo'     => $linhas[3] ?? '',
                    'validade'   => $linhas[5] ?? '',
                    'quantidade' => $linhas[7] ?? '',
                    'fornecedor' => $linhas[9] ?? ''
                ];
            }

            $listaRespostaFinal[] = [
                'arquivo' => $_FILES["arquivos"]["name"][$chave],
                'produto' => $produto
            ];
        }
    }

    $nomeCsv = 'produtos.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $nomeCsv . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fp = fopen('php://output', 'w');

    fwrite($fp, "\xEF\xBB\xBF");

    fputcsv($fp, [
        'item',
        'codigo',
        'validade',
        'quantidade',
        'fornecedor'
    ], ';');

    if (isset($listaRespostaFinal)) {
        foreach ($listaRespostaFinal as $resultado) {
            $produto = $resultado['produto'];

            fputcsv($fp, [
                $produto['item'],
                $produto['codigo'],
                $produto['validade'],
                $produto['quantidade'],
                $produto['fornecedor']
            ], ';');
        }
    }

    fclose($fp);

    exit;
}
