# nfsenacional
NFSe Nacional do Brasil

Biblioteca PHP para gerar DANFSe (Documento Auxiliar da NFS-e Nacional) a partir do XML da NFS-e.

# Versão
DANFSe Nacional na versão 2.0.

## Modelo de DANFSe

- O gerador utiliza por padrao o modelo oficial internalizado no proprio projeto.

## Organizacao do codigo

- `src/Danfse`: modulo principal de DANFSe com gerador, DTOs, template, configuração e renderer PDF.
- `assets/logos`: logo oficial local usada no DANFSe (`nfse.png`).
- `assets/fonts/ttf`: fontes TTF usadas no template para embutir no PDF (Arial, Microsoft Sans Serif).
- `assets/xml`: XMLs de exemplo da NFS-e Nacional.

## Recursos

- Geracao de DANFSe no modelo oficial (padrao).
- Leitura de XML por caminho de arquivo ou por string.
- Saida do PDF como binario em memoria ou gravacao direta em arquivo.
- QR Code gerado no template oficial com URL de consulta publica:
	- `https://www.nfse.gov.br/ConsultaPublica/?tpc=1&chave={chave_de_acesso}`
- Logo oficial local padrao:
	- `assets/logos/nfse.png`

## Instalacao

```bash
composer require guilhermecfviana/nfsenacional
```

## Uso rapido

```php
<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use GuilhermeViana\Nfsenacional\Danfse\DanfseGenerator;

$generator = new DanfseGenerator();

// 1) Gerar DANFSe em memoria
$pdfBinary = $generator->generate(__DIR__ . '/assets/xml/nfse.xml');

// 2) Gerar DANFSe em arquivo
$generator->generate(
		__DIR__ . '/assets/xml/nfse.xml',
		[
				'output' => 'file',
				'outputPath' => __DIR__ . '/saida/danfse.pdf',
		]
);

// 3) Gerar a partir de XML em string
$xml = file_get_contents(__DIR__ . '/assets/xml/nfse.xml');
$pdfBinaryFromString = $generator->generate((string) $xml);
```

## Opcoes de geracao

- `output`
	- `string` (padrao, retorna binario do PDF)
	- `file` (grava em disco; requer `outputPath`)
- `outputPath`
	- caminho de saida quando `output = file`
- `footerText`
	- texto opcional no rodape, alinhado a direita. Ex.: `Gerado pelo sistema XXXXXX - https://meusistema.com.br`
- `watermark`
	- define watermark de status no DANFSe:
		- `cancelada` exibe `CANCELADA`
		- `substituida` exibe `SUBSTITUÍDA`
	- em ambiente de homologacao (`tpAmb = 2`), a marca `HOMOLOGAÇÃO` continua sendo exibida junto.
	- quando o XML possuir a tag `subst/chSubstda`, a marca `SUBSTITUÍDA` e aplicada automaticamente (caso `watermark` nao seja informado).

Informacoes Complementares:
	- quando existir `subst/chSubstda`, o DANFSe exibe no topo do bloco:
		- `NFSe Subst: {chave_de_acesso}`
	- se houver NBS, o formato fica:
		- `NFSe Subst: {chave_de_acesso} | NBS: {codigo_nbs}`
- `logo`
	- URL/caminho da logo (opcional). Por padrao usa `assets/logos/nfse.png`.

Exemplo de uso com watermark:

```php
$generator->generate(
		$xmlContent,
		[
				'output' => 'file',
				'outputPath' => __DIR__ . '/saida/danfse.pdf',
				'watermark' => 'substituida', // ou 'cancelada'
		]
);
```

## API principal

- `DanfseGenerator::generateFromXmlFile(string $xmlPath, array $options = []): string`
- `DanfseGenerator::generateFromXmlString(string $xmlContent, array $options = []): string`
- `DanfseGenerator::generate(string $xmlInput, array $options = []): string`
