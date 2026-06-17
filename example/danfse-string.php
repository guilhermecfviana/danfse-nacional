<?php

declare(strict_types=1);

use GuilhermeViana\Nfsenacional\Danfse\DanfseGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$xmlPath = dirname(__DIR__) . '/assets/xml/31062002240569411000117011111110109126040147233790.xml';
$xmlContent = file_get_contents($xmlPath);
if ($xmlContent === false) {
    throw new RuntimeException('Nao foi possivel ler o XML de entrada.');
}

$outputPath = __DIR__ . '/output/danfse-string.pdf';

$generator = new DanfseGenerator();

$generator->generate(
    $xmlContent,
    [
        //'watermark' => 'cancelada', // opções: 'cancelada', 'substituida'
        'output' => 'file',
        'outputPath' => $outputPath,
        'footerText' => 'Gerado por GuilhermeViana\DanfseNacional - <a href="https://github.com/guilhermecfviana/danfse-nacional">https://github.com/guilhermecfviana/danfse-nacional</a>',
    ]
);

echo "DANFSe (XML string) gerada em: {$outputPath}" . PHP_EOL;
