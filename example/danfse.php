<?php

declare(strict_types=1);

use GuilhermeViana\Nfsenacional\Danfse\DanfseGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

// Le todos os arquivos da pasta assets/xml e gera um DANFSe para cada um
$xmlFiles = glob(dirname(__DIR__) . '/assets/xml/*.xml');
if ($xmlFiles === false) {
    throw new RuntimeException('Nao foi possivel ler os arquivos XML de entrada.');
}

foreach ($xmlFiles as $xmlFile) {
    $xmlContent = file_get_contents($xmlFile);
    if ($xmlContent === false) {
        echo "Nao foi possivel ler o arquivo XML: {$xmlFile}" . PHP_EOL;
        continue;
    }

     // Gera o nome do arquivo de saída com base no nome do arquivo XML
    $outputPath = __DIR__ . '/output/' . pathinfo($xmlFile, PATHINFO_FILENAME) . '.pdf';

    $generator = new DanfseGenerator();

    $generator->generate(
        $xmlContent,
        [
            //'watermark' => 'cancelada', // opções: 'cancelada', 'substituida'
            'output' => 'file', // 'string' é o valor padrão, retorna o PDF como string. 'file' salva o PDF em um arquivo.
            'outputPath' => $outputPath, // necessário se 'output' for 'file'
            'footerText' => 'Gerado por GuilhermeViana\DanfseNacional - <a href="https://github.com/guilhermecfviana/danfse-nacional">https://github.com/guilhermecfviana/danfse-nacional</a>',
        ]
    );

    echo "DANFSe (XML string) gerada em: {$outputPath}" . PHP_EOL;
}