<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Template;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use GuilhermeViana\Nfsenacional\Danfse\Config\DanfseConfig;
use GuilhermeViana\Nfsenacional\Danfse\Dto\NFSe;
use GuilhermeViana\Nfsenacional\Danfse\Enums\OpSimpNac;
use GuilhermeViana\Nfsenacional\Danfse\Enums\RegApTribSN;
use GuilhermeViana\Nfsenacional\Danfse\Enums\RegEspTrib;
use GuilhermeViana\Nfsenacional\Danfse\Enums\TpRetISSQN;
use GuilhermeViana\Nfsenacional\Danfse\Enums\TribISSQN;
use GuilhermeViana\Nfsenacional\Danfse\Data\Municipios;
use GuilhermeViana\Nfsenacional\Danfse\Formatter;

/**
 * Constrói o array de dados para o template e gera o QR Code.
 */
class DanfseTemplate
{
    private Formatter $fmt;

    public function __construct()
    {
        $this->fmt = new Formatter();
    }

    /**
     * Renderiza o template e retorna o HTML completo
     */
    public function render(NFSe $nfse, DanfseConfig $config): string
    {
        $data = $this->buildData($nfse);
        $hasSubstTag = (string) ($data['nfse_subst_chave'] ?? '') !== '';
        $resolvedWatermarkStatus = $config->watermarkStatus ?? ($hasSubstTag ? 'substituida' : null);
        $data['watermark_status'] = $resolvedWatermarkStatus;
        $data['watermark_text'] = $this->resolveWatermarkText((string) ($resolvedWatermarkStatus ?? ''));
        $logo = $config->logoDataUri;
        $municipality = $config->municipality;
        $qrCode = $this->generateQrCode($data['chave_acesso']);
        $fontFacesCss = $this->buildFontFacesCss();
        array_walk_recursive($data, fn(&$v) => $v = is_string($v) ? htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : $v);

        $templatePath = __DIR__ . '/danfse.php';

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    private function resolveWatermarkText(string $status): ?string
    {
        return match ($status) {
            'cancelada' => 'CANCELADA',
            'substituida' => 'SUBSTITUÍDA',
            default => null,
        };
    }

    private function buildFontFacesCss(): string
    {
        $rootPath = dirname(__DIR__, 4);
        $fontDir = $rootPath . '/assets/fonts/ttf';

        $rules = [];
        $rules[] = $this->fontFaceRule('Microsoft Sans Serif', $fontDir . '/ms-sans-serif.ttf', 'normal', 'normal');
        $rules[] = $this->fontFaceRule('Arial', $fontDir . '/arial.ttf', 'normal', 'normal');
        $rules[] = $this->fontFaceRule('Arial', $fontDir . '/arial-bold.ttf', 'normal', 'bold');

        return implode("\n", array_filter($rules));
    }

    private function fontFaceRule(string $family, string $filePath, string $style, string $weight): string
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return '';
        }

        $fontData = file_get_contents($filePath);
        if ($fontData === false || $fontData === '') {
            return '';
        }

        $base64 = base64_encode($fontData);

        return sprintf(
            "@font-face {\n"
            . "  font-family: '%s';\n"
            . "  font-style: %s;\n"
            . "  font-weight: %s;\n"
            . "  src: url('data:font/ttf;base64,%s') format('truetype');\n"
            . "}\n",
            $family,
            $style,
            $weight,
            $base64,
        );
    }

    /**
     * Constrói o array de dados para o template a partir dos DTOs
     */
    public function buildData(NFSe $nfse): array
    {
        $inf = $nfse->infNFSe;
        $dps = $inf?->DPS;
        $infDps = $dps?->infDPS;
        $prest = $infDps?->prest;
        $regTrib = $prest?->regTrib;
        $emit = $inf?->emit;
        $enderEmit = $emit?->enderNac;
        $toma = $infDps?->toma;
        $endToma = $toma?->end;
        $interm = $infDps?->interm;
        $endInterm = $interm?->end;
        $serv = $infDps?->serv;
        $locPrest = $serv?->locPrest;
        $cServ = $serv?->cServ;
        $valores = $infDps?->valores;
        $vServPrest = $valores?->vServPrest;
        $trib = $valores?->trib;
        $tribMun = $trib?->tribMun;
        $tribFed = $trib?->tribFed;
        $totTrib = $trib?->totTrib;
        $totTribPercent = $totTrib?->pTotTrib;
        $totTribValues = is_array($totTrib?->vTotTrib ?? null) ? $totTrib->vTotTrib : null;
        $valoresNfse = $inf?->valores;
        $chaveSubst = trim((string) ($infDps?->subst?->chSubstda ?? ''));

        // Chave de acesso (remove prefixo "NFS")
        $id = $inf?->Id ?? '';
        $chaveAcesso = str_starts_with($id, 'NFS') ? substr($id, 3) : $id;

        // Endereço emitente
        $enderecoEmit = implode(', ', array_filter([
            $enderEmit?->xLgr ?? '',
            $enderEmit?->nro ?? '',
            $enderEmit?->xBairro ?? '',
        ], fn($v) => $v !== ''));

        $municipioEmit = '';
        if (($inf?->xLocEmi ?? '') !== '' && ($enderEmit?->UF ?? '') !== '') {
            $municipioEmit = ($inf->xLocEmi) . ' - ' . $enderEmit->UF;
        }

        // Endereço tomador
        $enderecoToma = implode(', ', array_filter([
            $endToma?->xLgr ?? '',
            $endToma?->nro ?? '',
            $endToma?->xBairro ?? '',
        ], fn($v) => $v !== ''));

        $cepToma = $endToma?->endNac?->CEP ?? '';

        // Endereço intermediário
        $enderecoInterm = implode(', ', array_filter([
            $endInterm?->xLgr ?? '',
            $endInterm?->nro ?? '',
            $endInterm?->xBairro ?? '',
        ], fn($v) => $v !== ''));

        $cepInterm = $endInterm?->endNac?->CEP ?? '';

        return [
            'chave_acesso' => $chaveAcesso,
            'numero_nfse' => $inf?->nNFSe ?? '-',
            'competencia' => $this->fmt->date($infDps?->dCompet ?? ''),
            'emissao_nfse' => $this->fmt->dateTime($inf?->dhProc ?? ''),
            'numero_dps' => $infDps?->nDPS ?? '-',
            'serie_dps' => $infDps?->serie ?? '-',
            'emissao_dps' => $this->fmt->dateTime($infDps?->dhEmi ?? ''),
            'ambiente' => (int) ($infDps?->tpAmb ?? 1),

            'emitente' => [
                'nome' => $emit?->xNome ?? '-',
                'cnpj_cpf' => $this->fmt->cnpjCpf($emit?->documento() ?? ''),
                'im' => '-',
                'telefone' => $this->fmt->phone($emit?->fone ?? ''),
                'email' => strtolower($emit?->email ?? ''),
                'endereco' => $enderecoEmit ?: '-',
                'municipio' => $municipioEmit ?: '-',
                'cep' => $this->fmt->cep($enderEmit?->CEP ?? ''),
                'simples_nacional' => OpSimpNac::labelFor($regTrib?->opSimpNac ?? ''),
                'regime_sn' => RegApTribSN::labelFor($regTrib?->regApTribSN ?? ''),
            ],

            'tomador' => $toma !== null ? [
                'nome' => $toma->xNome ?: '-',
                'cnpj_cpf' => $this->fmt->cnpjCpf($toma->documento()),
                'im' => $toma->IM ?: '-',
                'telefone' => $this->fmt->phone($toma->fone),
                'email' => strtolower($toma->email),
                'endereco' => $enderecoToma ?: '-',
                'municipio' => $endToma?->endNac?->cMun ? Municipios::lookup($endToma->endNac->cMun) : '-',
                'cep' => $this->fmt->cep($cepToma),
            ] : null,

            'intermediario' => $interm !== null ? [
                'nome' => $interm->xNome ?: '-',
                'cnpj_cpf' => $this->fmt->cnpjCpf($interm->documento()),
                'im' => $interm->IMPrestMun ?: '-',
                'telefone' => $this->fmt->phone($interm->fone),
                'email' => strtolower($interm->email),
                'endereco' => $enderecoInterm ?: '-',
                'municipio' => $endInterm?->endNac?->cMun ? Municipios::lookup($endInterm->endNac->cMun) : '-',
                'cep' => $this->fmt->cep($cepInterm),
            ] : null,

            'servico' => [
                'codigo_trib_nacional' => $this->fmt->codTribNacional($cServ?->cTribNac ?? ''),
                'desc_trib_nacional' => $this->fmt->limit(trim($inf?->xTribNac ?? ''), 40),
                'codigo_trib_municipal' => $cServ?->cTribMun ?? '-',
                'desc_trib_municipal' => $this->fmt->limit(trim($inf?->xTribMun ?? ''), 60),
                'local_prestacao' => $locPrest?->cLocPrestacao ? Municipios::lookup($locPrest->cLocPrestacao) : ($inf?->xLocPrestacao ?? '-'),
                'pais_prestacao' => $locPrest?->cPaisPrestacao ?? '-',
                'descricao' => $cServ?->xDescServ ?? '-',
            ],

            'tributacao_municipal' => [
                'tributacao_issqn' => TribISSQN::labelFor($tribMun?->tribISSQN ?? ''),
                'municipio_incidencia' => $inf?->cLocIncid ? Municipios::lookup($inf->cLocIncid) : ($inf?->xLocIncid ?? '-'),
                'regime_especial' => RegEspTrib::labelFor($regTrib?->regEspTrib ?? ''),
                'valor_servico' => $this->fmt->currency($vServPrest?->vServ ?? ''),
                'bc_issqn' => ($tribMun?->vBC ?? '') !== ''
                    ? $this->fmt->currency($tribMun->vBC)
                    : ((($valoresNfse?->vBC ?? '') !== '') ? $this->fmt->currency($valoresNfse->vBC) : '-'),
                'aliquota' => ($tribMun?->pAliq ?? '') !== ''
                    ? $tribMun->pAliq . '%'
                    : ((($valoresNfse?->pAliqAplic ?? '') !== '') ? $valoresNfse->pAliqAplic . '%' : '-'),
                'retencao_issqn' => TpRetISSQN::labelFor($tribMun?->tpRetISSQN ?? ''),
                'issqn_apurado' => ($tribMun?->vISSQN ?? '') !== ''
                    ? $this->fmt->currency($tribMun->vISSQN)
                    : ((($valoresNfse?->vISSQN ?? '') !== '') ? $this->fmt->currency($valoresNfse->vISSQN) : '-'),
            ],

            'tributacao_federal' => [
                'irrf' => $tribFed?->vRetIRRF ? $this->fmt->currency($tribFed->vRetIRRF) : '-',
                'cp' => $tribFed?->vRetCP ? $this->fmt->currency($tribFed->vRetCP) : '-',
                'csll' => $tribFed?->vRetCSLL ? $this->fmt->currency($tribFed->vRetCSLL) : '-',
                'contrib_sociais' => $tribFed?->vRetCSLL ? $this->fmt->currency($tribFed->vRetCSLL) : '-',
                'desc_contrib_sociais' => $this->labelForTpRetPisCofins($tribFed?->piscofins?->tpRetPisCofins ?? ''),
                'pis' => $tribFed?->piscofins?->vPis ? $this->fmt->currency($tribFed->piscofins->vPis) : '-',
                'cofins' => $tribFed?->piscofins?->vCofins ? $this->fmt->currency($tribFed->piscofins->vCofins) : '-',
            ],

            'totais' => [
                'valor_servico' => $this->fmt->currency($vServPrest?->vServ ?? ''),
                'desconto_condicionado' => $tribMun?->vDescCond ? $this->fmt->currency($tribMun->vDescCond) : '-',
                'desconto_incondicionado' => $tribMun?->vDescIncond ? $this->fmt->currency($tribMun->vDescIncond) : '-',
                'issqn_retido' => (($tribMun?->vISSQN ?? '') !== '' || (($valoresNfse?->vISSQN ?? '') !== '')) && ($tribMun?->tpRetISSQN ?? '1') !== '1'
                    ? $this->fmt->currency(($tribMun?->vISSQN ?? '') !== '' ? $tribMun->vISSQN : $valoresNfse->vISSQN)
                    : '-',
                'retencoes_federais' => $this->sumCurrency(
                    $tribFed?->vRetIRRF ?? '',
                    $tribFed?->vRetCP ?? '',
                    $tribFed?->vRetCSLL ?? '',
                ),
                'pis_cofins' => $this->sumCurrency(
                    $tribFed?->piscofins?->vPis ?? '',
                    $tribFed?->piscofins?->vCofins ?? '',
                ),
                'valor_liquido' => $this->fmt->currency($valoresNfse?->vLiq ?? ''),
            ],

            'totais_tributos' => [
                'federais' => $totTribPercent?->pTotTribFed
                    ? $totTribPercent->pTotTribFed . '%'
                    : (($totTribValues['vTotTribFed'] ?? '') !== '' ? $this->fmt->currency((string) $totTribValues['vTotTribFed']) : '-'),
                'estaduais' => $totTribPercent?->pTotTribEst
                    ? $totTribPercent->pTotTribEst . '%'
                    : (($totTribValues['vTotTribEst'] ?? '') !== '' ? $this->fmt->currency((string) $totTribValues['vTotTribEst']) : '-'),
                'municipais' => $totTribPercent?->pTotTribMun
                    ? $totTribPercent->pTotTribMun . '%'
                    : (($totTribValues['vTotTribMun'] ?? '') !== '' ? $this->fmt->currency((string) $totTribValues['vTotTribMun']) : '-'),
            ],

            'nbs' => trim((string) ($cServ?->cNBS ?? '')),
            'nfse_subst_chave' => $chaveSubst,
            'informacoes_complementares' => $serv?->infoCompl?->xInfComp ?? '',
        ];
    }

    /**
     * Soma valores monetários e retorna formatado, ou '-' se todos forem vazios.
     */
    private function sumCurrency(string ...$values): string
    {
        $sum = 0.0;
        $hasValue = false;
        foreach ($values as $v) {
            if ($v !== '') {
                $sum += (float) $v;
                $hasValue = true;
            }
        }
        return $hasValue ? $this->fmt->currency((string) $sum) : '-';
    }

    private function labelForTpRetPisCofins(string $tpRetPisCofins): string
    {
        return match (trim($tpRetPisCofins)) {
            '0' => '0 - PIS/COFINS/CSLL Não Retidos',
            '1' => '1 - PIS/COFINS Retido',
            '2' => '2 - PIS/COFINS Não Retido',
            '3' => '3 - PIS/COFINS/CSLL Retidos',
            '4' => '4 - PIS/COFINS Retidos, CSLL Não Retido',
            '5' => '5 - PIS Retido, COFINS/CSLL Não Retido',
            '6' => '6 - COFINS Retido, PIS/CSLL Não Retido',
            '7' => '7 - PIS Não Retido, COFINS/CSLL Retidos',
            '8' => '8 - PIS/COFINS Não Retidos, CSLL Retido',
            '9' => '9 - COFINS Não Retido, PIS/CSLL Retidos',
            default => '-',
        };
    }

    /**
     * Gera QR Code como data URI PNG
     */
    private function generateQrCode(string $chaveAcesso): string
    {
        $url = "https://www.nfse.gov.br/ConsultaPublica/?tpc=1&chave={$chaveAcesso}";

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd(),
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($url);

        // Retorna como SVG embutido em data URI
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
