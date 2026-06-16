<?php

declare(strict_types=1);

namespace GuilhermeViana\Nfsenacional\Danfse;

use CuyZ\Valinor\MapperBuilder;
use GuilhermeViana\Nfsenacional\Danfse\Exception\NfseException;
use GuilhermeViana\Nfsenacional\Danfse\Config\DanfseConfig;
use GuilhermeViana\Nfsenacional\Danfse\Dto\NFSe;
use GuilhermeViana\Nfsenacional\Danfse\Template\DanfseTemplate;
use Dompdf\Dompdf;
use Dompdf\Options;

class DanfseGenerator
{
    private const OUTPUT_INLINE_STRING = 'string';
    private const OUTPUT_FILE = 'file';

    /**
     * @param array<string, string> $options
     */
    public function generateFromXmlFile(string $xmlPath, array $options = []): string
    {
        if (!is_file($xmlPath) || !is_readable($xmlPath)) {
            throw new NfseException('Arquivo XML nao encontrado ou sem permissao de leitura.');
        }

        $xmlContent = file_get_contents($xmlPath);
        if ($xmlContent === false) {
            throw new NfseException('Nao foi possivel ler o arquivo XML informado.');
        }

        return $this->generateWithOfficialModel($xmlContent, $options);
    }

    /**
     * @param array<string, string> $options
     */
    public function generateFromXmlString(string $xmlContent, array $options = []): string
    {
        return $this->generateWithOfficialModel($xmlContent, $options);
    }

    /**
     * @param array<string, string> $options
     */
    public function generate(string $xmlInput, array $options = []): string
    {
        if (is_file($xmlInput)) {
            return $this->generateFromXmlFile($xmlInput, $options);
        }

        if (str_contains(ltrim($xmlInput), '<')) {
            return $this->generateFromXmlString($xmlInput, $options);
        }

        throw new NfseException('Entrada XML invalida. Informe caminho de arquivo ou XML em string.');
    }

    /**
     * @param array<string, string> $options
     */
    private function generateWithOfficialModel(string $xmlContent, array $options): string
    {
        $logoPath = realpath(__DIR__ . '/../../assets/logos/nfse.png');
        $footerText = trim($options['footerText'] ?? '');
        $config = $logoPath !== false
            ? new DanfseConfig(logoPath: $logoPath, footerText: $footerText !== '' ? $footerText : null)
            : new DanfseConfig(footerText: $footerText !== '' ? $footerText : null);

        $rawPdf = $this->generateFromXml($xmlContent, $config);

        $outputType = $options['output'] ?? self::OUTPUT_INLINE_STRING;
        if ($outputType === self::OUTPUT_FILE) {
            $outputPath = $options['outputPath'] ?? '';
            if ($outputPath === '') {
                throw new NfseException('Para output=file, informe outputPath.');
            }

            $directory = dirname($outputPath);
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            file_put_contents($outputPath, $rawPdf);

            return $outputPath;
        }

        return $rawPdf;
    }

    public function generateFromXml(string $xml, ?DanfseConfig $config = null): string
    {
        $nfse = $this->parseXml($xml);

        return $this->generatePdf($nfse, $config ?? new DanfseConfig());
    }

    public function parseXml(string $xml): NFSe
    {
        $converter = new XmlToArray();
        $array = $converter->convert($xml);

        $mapper = (new MapperBuilder())
            ->allowSuperfluousKeys()
            ->allowPermissiveTypes()
            ->mapper();

        return $mapper->map(NFSe::class, $array);
    }

    public function generateHtml(NFSe $nfse, ?DanfseConfig $config = null): string
    {
        $template = new DanfseTemplate();

        return $template->render($nfse, $config ?? new DanfseConfig());
    }

    public function generatePdf(NFSe $nfse, ?DanfseConfig $config = null): string
    {
        $resolvedConfig = $config ?? new DanfseConfig();
        $html = $this->generateHtml($nfse, $resolvedConfig);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Arial');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('isUnicodeEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $footerRaw = trim((string) ($resolvedConfig->footerText ?? ''));
        if ($footerRaw !== '') {
            $linkUrl = null;
            $linkLabel = null;

            $anchorPattern = '~<a\s[^>]*href\s*=\s*(["\'])(.*?)\1[^>]*>(.*?)</a>~is';
            if (preg_match($anchorPattern, $footerRaw, $anchorMatch) === 1) {
                $candidateUrl = html_entity_decode(trim((string) ($anchorMatch[2] ?? '')), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                if (filter_var($candidateUrl, FILTER_VALIDATE_URL) !== false) {
                    $linkUrl = $candidateUrl;
                    $linkLabel = trim(html_entity_decode(strip_tags((string) ($anchorMatch[3] ?? '')), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
                    if ($linkLabel === '') {
                        $linkLabel = $linkUrl;
                    }
                }

                $footerRaw = (string) preg_replace_callback(
                    $anchorPattern,
                    static fn(array $m): string => (string) ($m[3] ?? $m[2] ?? ''),
                    $footerRaw,
                    1,
                );
            }

            $footerText = trim((string) preg_replace(
                '/\s+/u',
                ' ',
                html_entity_decode(strip_tags($footerRaw), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            ));

            if ($footerText === '') {
                return $dompdf->output();
            }

            if ($linkUrl === null && preg_match('~https?://[^\s]+~i', $footerText, $urlMatch) === 1) {
                $candidateUrl = trim((string) ($urlMatch[0] ?? ''));
                if (filter_var($candidateUrl, FILTER_VALIDATE_URL) !== false) {
                    $linkUrl = $candidateUrl;
                    $linkLabel = $candidateUrl;
                }
            }

            $canvas = $dompdf->getCanvas();
            $fontMetrics = $dompdf->getFontMetrics();
            $font = $fontMetrics->getFont('Helvetica', 'normal');
            $fontSize = 7;
            $fontHeight = $fontMetrics->getFontHeight($font, $fontSize);
            $textWidth = $fontMetrics->getTextWidth($footerText, $font, $fontSize);
            $pageWidth = $canvas->get_width();
            $pageHeight = $canvas->get_height();

            $x = max(14.0, $pageWidth - 14.0 - $textWidth);
            $y = $pageHeight - 10.0;

            $canvas->text($x, $y, $footerText, $font, $fontSize, [0, 0, 0]);

            if ($linkUrl !== null && $linkLabel !== null) {
                $linkPos = strpos($footerText, $linkLabel);
                if ($linkPos === false) {
                    $linkPos = strpos($footerText, $linkUrl);
                    if ($linkPos !== false) {
                        $linkLabel = $linkUrl;
                    }
                }
                if ($linkPos !== false) {
                    $prefix = substr($footerText, 0, $linkPos);
                    $linkX = $x + $fontMetrics->getTextWidth($prefix, $font, $fontSize);
                    $linkWidth = $fontMetrics->getTextWidth($linkLabel, $font, $fontSize);
                    $canvas->add_link($linkUrl, $linkX, $y, $linkWidth, $fontHeight);
                    $canvas->text($linkX, $y, $linkLabel, $font, $fontSize, [0, 0, 1]);
                }
            }
        }

        return $dompdf->output();
    }
}
