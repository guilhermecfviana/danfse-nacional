<?php

declare(strict_types=1);

use GuilhermeViana\Nfsenacional\Danfse\DanfseGenerator;
require dirname(__DIR__) . '/vendor/autoload.php';

$xmlPath = dirname(__DIR__) . '/assets/xml/31062002240569411000117011111110109126040147233790.xml';
$outputPath = __DIR__ . '/output/danfse-file.pdf';

$generator = new DanfseGenerator();

$generator->generate(
    $xmlPath,
    [
        //'watermark' => 'cancelada', // opções: 'cancelada', 'substituida'
        'output' => 'file',
        'outputPath' => $outputPath,
        'footerText' => 'Gerado por GuilhermeViana\DanfseNacional - <a href="https://github.com/guilhermecfviana/danfse-nacional">https://github.com/guilhermecfviana/danfse-nacional</a>',
    ]
);

echo "DANFSe gerada em: {$outputPath}" . PHP_EOL;
