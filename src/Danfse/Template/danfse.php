<?php
/** @var array $data */
/** @var string $logo */
/** @var string $qrCode */
/** @var \DanfseNacional\Config\MunicipalityBranding $municipality */
/** @var string $fontFacesCss */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>DANFSe - <?php echo $data['numero_nfse']; ?></title>
    <style>
        <?php echo $fontFacesCss; ?>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
        }

        body {
            font-family: "Microsoft Sans Serif", Arial, Helvetica, sans-serif;
            font-size: 7pt;
            color: #000;
            /* Margem mínima para a moldura não ser cortada na borda do papel. */
            margin: 2pt;
            /* Ocupa a altura da página para que a borda inferior fique no fim
               da folha, abaixo do texto do rodapé. */
            min-height: 838pt;
            /* Espaçamento interno em branco: evita que as linhas divisórias
               encostem na borda da margem (conforme NT). */
            padding: 4pt 6pt;
            border: 1pt #000 solid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        td {
            padding: 1pt 2pt;
            border: none;
            vertical-align: top;
        }

        table > tbody > tr > td {
            padding-bottom: 3pt;
        }

        /* Gray 5% (K5) shading for header, block titles and highlighted fields */
        .shaded {
            background-color: #f2f2f2;
        }

        .bordered-section {
            margin-bottom: 0;
            border-bottom: 0.5pt solid #000;
        }

        .bordered-section:last-of-type {
            border-bottom: none;
        }

        .first-section table td {
            padding-bottom: 0 !important;
        }

        .label {
            font-family: Arial, "Microsoft Sans Serif", Helvetica, sans-serif;
            font-size: 6pt;
            font-weight: bold;
            color: #000;
            display: block;
            margin-bottom: 0;
        }

        /* Identification block (item 2.1.2) labels: 7pt bold uppercase */
        .label-id {
            font-size: 7pt;
            text-transform: uppercase;
        }

        .value {
            font-family: "Microsoft Sans Serif", Arial, Helvetica, sans-serif;
            font-size: 7pt;
            font-weight: normal;
            color: #000;
        }

        .value.multiline-compact {
            display: block;
            font-size: 7pt;
            line-height: 1.1;
        }

        .single-line-ellipsis {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .compact-value {
            font-size: 7pt;
        }

        .compact-nowrap-label {
            white-space: nowrap;
            font-size: 6.5pt;
        }

        .section-header {
            font-family: Arial, "Microsoft Sans Serif", Helvetica, sans-serif;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
            text-align: left;
            padding: 3pt;
        }

        .section-title {
            font-family: Arial, "Microsoft Sans Serif", Helvetica, sans-serif;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
        }

        .header-table {
            margin-bottom: 0;
            border-bottom: 0.5pt solid #000;
        }

        .title-cell {
            font-family: Arial, "Microsoft Sans Serif", Helvetica, sans-serif;
        }

        .header-table td {
            border: none;
            padding-bottom: 1pt !important;
        }

        .logo-cell {
            width: 130pt;
            text-align: left;
        }

        .title-cell {
            text-align: center;
            vertical-align: middle;
        }

        .municipality-cell {
            width: 150pt;
            text-align: left;
            font-size: 5.5pt;
            vertical-align: top;
        }

        .municipality-info {
            font-family: Arial, "Microsoft Sans Serif", Helvetica, sans-serif;
            font-size: 6pt;
            color: #000;
            line-height: 1.4;
        }

        .municipality-info .muni-label {
            font-weight: bold;
        }

        .municipality-info .muni-municipio {
            font-size: 7.5pt;
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
            /*padding: 3pt;*/
            position: absolute;
            right: 0;
            top: 8pt;
        }

        /* Watermarks */
        .watermark_homologacao,
        .watermark_substituida,
        .watermark_cancelada {
            position: fixed;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 48pt;
            font-weight: bold;
            color: rgba(200, 200, 200, 0.3);
            z-index: -1;
            white-space: nowrap;
        }

        .watermark_homologacao {
            top: 58%;
        }

        .watermark_substituida,
        .watermark_cancelada {
            top: 44%;
        }

    </style>
</head>
<body>
    <?php if (($data['watermark_status'] ?? '') === 'substituida' && !empty($data['watermark_text'])): ?>
    <div class="watermark_substituida"><?php echo $data['watermark_text']; ?></div>
    <?php endif; ?>
    <?php if (($data['watermark_status'] ?? '') === 'cancelada' && !empty($data['watermark_text'])): ?>
    <div class="watermark_cancelada"><?php echo $data['watermark_text']; ?></div>
    <?php endif; ?>
    <?php if ($data['ambiente'] == 2): ?>
    <div class="watermark_homologacao">HOMOLOGAÇÃO</div>
    <?php endif; ?>

    <!-- Header -->
    <table class="header-table shaded">
        <tr>
            <td class="logo-cell">
                <?php if ($logo): ?>
                <img src="<?php echo htmlspecialchars($logo); ?>" alt="Logo" style="max-width: 130pt; max-height: 40pt;">
                <?php endif; ?>
            </td>
            <td class="title-cell">
                <div style="font-family: Arial, 'Microsoft Sans Serif', sans-serif; font-size: 9pt; font-weight: bold;">DANFSe v2.0</div>
                <div style="font-family: Arial, 'Microsoft Sans Serif', sans-serif; font-size: 9pt; font-weight: bold;">Documento Auxiliar da NFS-e</div>
                <?php if ($data['ambiente'] == 2): ?>
                    <div style="font-family: Arial, 'Microsoft Sans Serif', sans-serif; font-size: 9pt; font-weight: bold; color: #ff0000;">NFS-e SEM VALIDADE JURÍDICA</div>
                <?php endif; ?>
            </td>
            <td class="municipality-cell">
                <?php if ($municipality): ?>
                <table style="margin-bottom: 2pt;">
                    <tr>
                        <?php if ($municipality->logoDataUri): ?>
                        <td><img style="height: 30pt; width: auto" src="<?php echo htmlspecialchars($municipality->logoDataUri); ?>" alt="Prefeitura" /></td>
                        <?php endif; ?>
                        <td style="font-size: 7pt;">
                            <?php echo htmlspecialchars($municipality->name); ?><br>
                            <?php if ($municipality->department): ?>
                            <?php echo htmlspecialchars($municipality->department); ?><br>
                            <?php endif; ?>
                            <?php if ($municipality->email): ?>
                            <?php echo htmlspecialchars($municipality->email); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <?php endif; ?>
                <div class="municipality-info">
                    <?php if ($data['municipio_emissao'] !== ''): ?>
                    <div class="muni-municipio"><?php echo $data['municipio_emissao']; ?></div>
                    <?php endif; ?>
                    <div><span class="muni-label">Ambiente Gerador:</span> <?php echo $data['ambiente_gerador']; ?></div>
                    <div><span class="muni-label">Tipo de Ambiente:</span> <?php echo $data['tipo_ambiente']; ?></div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Grade de Identificação (DADOS DA NFS-e) -->
    <div class="bordered-section first-section">
        <table style="min-height: 110px;">
            <tr>
                <td colspan="3">
                    <span class="label label-id">Chave de Acesso da NFS-e</span>
                    <span class="value"><?php echo $data['chave_acesso']; ?></span>
                </td>
                <td style="width: 25%; position: relative;" rowspan="3">
                    <div class="qr-container">
                        <img src="<?php echo htmlspecialchars($qrCode); ?>" alt="QR Code" style="width: 70px; height: 70px; display: block; margin: 0 auto;" />
                        <div style="font-size: 6pt; padding-top: 2pt; text-align: left; line-height: 1.05;">
                            A autenticidade desta NFS-e pode ser verificada pela leitura deste código QR ou pela consulta da chave de acesso no portal nacional da NFS-e
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 25%; padding-top: 8pt;">
                    <span class="label label-id">Número da NFS-e</span>
                    <span class="value"><?php echo $data['numero_nfse']; ?></span>
                </td>
                <td style="width: 25%; padding-top: 8pt;">
                    <span class="label label-id">Competência da NFS-e</span>
                    <span class="value"><?php echo $data['competencia']; ?></span>
                </td>
                <td style="width: 25%; padding-top: 8pt;">
                    <span class="label label-id">Data e Hora da emissão da NFS-e</span>
                    <span class="value"><?php echo $data['emissao_nfse']; ?></span>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 4pt;">
                    <span class="label label-id">Número do DPS</span>
                    <span class="value"><?php echo $data['numero_dps']; ?></span>
                </td>
                <td style="padding-top: 4pt;">
                    <span class="label label-id">Série do DPS</span>
                    <span class="value"><?php echo $data['serie_dps']; ?></span>
                </td>
                <td style="padding-top: 4pt;">
                    <span class="label label-id">Data e Hora da emissão da DPS</span>
                    <span class="value"><?php echo $data['emissao_dps']; ?></span>
                </td>
            </tr>
            <tr>
                <td class="shaded">
                    <span class="label label-id">Emitente da NFS-e</span>
                    <span class="value"><?php echo $data['emitente_nfse']; ?></span>
                </td>
                <td>
                    <span class="label label-id">Situação da NFS-e</span>
                    <span class="value"><?php echo $data['situacao']; ?></span>
                </td>
                <td>
                    <span class="label label-id">Finalidade</span>
                    <span class="value"><?php echo $data['finalidade']; ?></span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Prestador / Fornecedor -->
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                    <span class="section-title">PRESTADOR / FORNECEDOR</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">CNPJ / CPF / NIF</span>
                    <span class="value"><?php echo $data['emitente']['cnpj_cpf']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Indicador Municipal (Inscrição)</span>
                    <span class="value"><?php echo $data['emitente']['im']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Telefone</span>
                    <span class="value"><?php echo $data['emitente']['telefone']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Nome / Nome Empresarial</span>
                    <span class="value"><?php echo $data['emitente']['nome']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Município / Sigla UF</span>
                    <span class="value"><?php echo $data['emitente']['municipio']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código IBGE / CEP</span>
                    <span class="value"><?php echo $data['emitente']['cep']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">E-mail</span>
                    <span class="value"><?php echo $data['emitente']['email']; ?></span>
                </td>
                <td colspan="2">
                    <span class="label">Endereço</span>
                    <span class="value"><?php echo $data['emitente']['endereco']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Simples Nacional na Data de Competência</span>
                    <span class="value"><?php echo $data['emitente']['simples_nacional']; ?></span>
                </td>
                <td colspan="2">
                    <span class="label">Regime de Apuração Tributária pelo SN</span>
                    <span class="value"><?php echo $data['emitente']['regime_sn']; ?></span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tomador -->
    <?php if ($data['tomador'] !== null): ?>
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                    <span class="section-title">TOMADOR / ADQUIRENTE</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">CNPJ / CPF / NIF</span>
                    <span class="value"><?php echo $data['tomador']['cnpj_cpf']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Indicador Municipal (Inscrição)</span>
                    <span class="value"><?php echo $data['tomador']['im']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Telefone</span>
                    <span class="value"><?php echo $data['tomador']['telefone']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Nome / Nome Empresarial</span>
                    <span class="value"><?php echo $data['tomador']['nome']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Município / Sigla UF</span>
                    <span class="value"><?php echo $data['tomador']['municipio']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código IBGE / CEP</span>
                    <span class="value"><?php echo $data['tomador']['cep']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">E-mail</span>
                    <span class="value"><?php echo $data['tomador']['email']; ?></span>
                </td>
                <td colspan="2">
                    <span class="label">Endereço</span>
                    <span class="value"><?php echo $data['tomador']['endereco']; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php else: ?>
    <div class="bordered-section" style="min-height: 0.32cm; text-align: center; font-weight: normal; font-size: 7pt; padding: 2pt;">
        TOMADOR/ADQUIRENTE DA OPERAÇÃO NÃO IDENTIFICADO NA NFS-e
    </div>
    <?php endif; ?>

    <!-- Destinatário da Operação -->
    <?php
    $destinatario = $data['destinatario'] ?? null;
    $tomador = is_array($data['tomador'] ?? null) ? $data['tomador'] : null;

    $normalizeDocumento = static function ($value): string {
        $normalized = strtoupper(trim((string) $value));
        return preg_replace('/[^A-Z0-9]+/', '', $normalized) ?? '';
    };

    $truncateWithEllipsis = static function ($value, int $maxLength = 77): string {
        $text = trim((string) $value);
        if ($text === '') {
            return '';
        }

        if ($maxLength <= 3) {
            return str_repeat('.', max(0, $maxLength));
        }

        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($text, 'UTF-8') <= $maxLength) {
                return $text;
            }

            return rtrim(mb_substr($text, 0, $maxLength - 3, 'UTF-8')) . '...';
        }

        if (strlen($text) <= $maxLength) {
            return $text;
        }

        return rtrim(substr($text, 0, $maxLength - 3)) . '...';
    };

    $isDestinatarioProprio = false;
    if (is_array($destinatario) && $tomador !== null) {
        $docDestinatario = $normalizeDocumento($destinatario['cnpj_cpf'] ?? '');
        $docTomador = $normalizeDocumento($tomador['cnpj_cpf'] ?? '');

        if ($docDestinatario !== '' && $docTomador !== '' && $docDestinatario === $docTomador) {
            $isDestinatarioProprio = true;
        }
    }
    ?>
    <?php if (is_array($destinatario) && !$isDestinatarioProprio): ?>
    <div class="bordered-section">
        <table>
            <tr>
                <td colspan="4" class="section-header shaded">
                    <span class="section-title">DESTINATÁRIO DA OPERAÇÃO</span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">CNPJ / CPF / NIF</span>
                    <span class="value"><?php echo $destinatario['cnpj_cpf']; ?></span>
                </td>
                <td style="width: 50%;">
                    <span class="label">Telefone</span>
                    <span class="value"><?php echo $destinatario['telefone']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Nome / Nome Empresarial</span>
                    <span class="value"><?php echo $truncateWithEllipsis($destinatario['nome'] ?? ''); ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Município / Sigla UF</span>
                    <span class="value"><?php echo $destinatario['municipio']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código IBGE / CEP</span>
                    <span class="value"><?php echo $destinatario['cep']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Endereço</span>
                    <span class="value"><?php echo $truncateWithEllipsis($destinatario['endereco'] ?? ''); ?></span>
                </td>
                <td colspan="2" style="width: 50%;">
                    <span class="label">E-mail</span>
                    <span class="value"><?php echo $destinatario['email']; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php elseif ($isDestinatarioProprio): ?>
    <div class="bordered-section" style="min-height: 0.32cm; text-align: center; font-weight: normal; font-size: 7pt; padding: 2pt;">
        O DESTINATÁRIO É O PRÓPRIO TOMADOR/ADQUIRENTE DA OPERAÇÃO
    </div>
    <?php else: ?>
    <div class="bordered-section" style="min-height: 0.32cm; text-align: center; font-weight: normal; font-size: 7pt; padding: 2pt;">
        DESTINATÁRIO DA OPERAÇÃO NÃO IDENTIFICADO NA NFS-e
    </div>
    <?php endif; ?>

    <!-- Intermediário -->
    <?php if ($data['intermediario'] !== null): ?>
    <div class="bordered-section">
        <table>
            <tr>
                <td colspan="4" class="section-header shaded">
                    <span class="section-title">INTERMEDIÁRIO DA OPERAÇÃO</span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">CNPJ / CPF / NIF</span>
                    <span class="value"><?php echo $data['intermediario']['cnpj_cpf']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Indicador Municipal (Inscrição)</span>
                    <span class="value"><?php echo $data['intermediario']['im']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Telefone</span>
                    <span class="value"><?php echo $data['intermediario']['telefone']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Nome / Nome Empresarial</span>
                    <span class="value"><?php echo $data['intermediario']['nome']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Município / Sigla UF</span>
                    <span class="value"><?php echo $data['intermediario']['municipio']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código IBGE / CEP</span>
                    <span class="value"><?php echo $data['intermediario']['cep']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Endereço</span>
                    <span class="value"><?php echo $data['intermediario']['endereco']; ?></span>
                </td>
                <td colspan="2" style="width: 50%;">
                    <span class="label">E-mail</span>
                    <span class="value"><?php echo $data['intermediario']['email']; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php else: ?>
    <div class="bordered-section" style="min-height: 0.32cm; text-align: center; font-weight: normal; font-size: 7pt; padding: 2pt;">
        INTERMEDIÁRIO DA OPERAÇÃO NÃO IDENTIFICADO NA NFS-e
    </div>
    <?php endif; ?>

    <!-- Serviço Prestado -->
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                  <span class="section-title">SERVIÇO PRESTADO</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código de Tributação Nacional / Municipal</span>
                    <span class="value single-line-ellipsis compact-value"><?php echo $data['servico']['codigo_trib_nac_mun']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Código da NBS</span>
                    <span class="value single-line-ellipsis compact-value"><?php echo $data['servico']['codigo_nbs']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label compact-nowrap-label">Local da Prestação / Sigla UF / País</span>
                    <span class="value single-line-ellipsis"><?php echo $data['servico']['local_prestacao_pais']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="value multiline-compact"><?php echo $data['servico']['desc_trib_nac_mun']; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="label">Descrição do Serviço</span>
                    <span class="value multiline-compact"><?php echo nl2br(str_replace('\\n', "\n", (string) ($data['servico']['descricao'] ?? ''))); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tributação Municipal -->
    <?php if (is_array($data['tributacao_municipal'] ?? null)): ?>
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                  <span class="section-title">TRIBUTAÇÃO MUNICIPAL (ISSQN)</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Tipo de Tributação do ISSQN</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['tributacao_issqn'] ?? '-'; ?></span>
                </td>
                <td colspan="2" style="width: 50%;">
                    <span class="label">Município / Sigla UF / País de Incidência do ISSQN</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['municipio_incidencia'] ?? '-'; ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">Regime Especial de Tributação</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['regime_especial'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Tipo de Imunidade</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['tipo_imunidade'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Suspensão da Exigibilidade do ISSQN</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['suspensao_exigibilidade'] ?? 'Não'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Número Processo Suspensão</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['num_processo_suspensao'] ?? '-'; ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">Benefício Municipal</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['beneficio_municipal'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Cálculo do BM</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['calculo_bm'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Total Deduções/Reduções</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['total_deducoes'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Desconto Incondicionado</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['desconto_incondicionado'] ?? '-'; ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">BC ISSQN</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['bc_issqn'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Alíquota Aplicada</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['aliquota'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Retenção do ISSQN</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['retencao_issqn'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">ISSQN Apurado</span>
                    <span class="value"><?php echo $data['tributacao_municipal']['issqn_apurado'] ?? '-'; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php else: ?>
    <div class="bordered-section" style="min-height: 0.32cm; text-align: center; font-weight: normal; font-size: 7pt; padding: 2pt;">
        TRIBUTAÇÃO MUNICIPAL (ISSQN) - OPERAÇÃO NÃO SUJEITA AO ISSQN
    </div>
    <?php endif; ?>

    <!-- Tributação Federal -->
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                  <span class="section-title">TRIBUTAÇÃO FEDERAL (EXCETO CBS)</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">IRRF</span>
                    <span class="value"><?php echo $data['tributacao_federal']['irrf'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Contribuição Previdenciária - Retida</span>
                    <span class="value"><?php echo $data['tributacao_federal']['cp'] ?? '-'; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Contribuições Sociais - Retidas</span>
                    <span class="value"><?php echo $data['tributacao_federal']['contrib_sociais'] ?? '-'; ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">PIS - Débito Apuração Própria</span>
                    <span class="value"><?php echo $data['tributacao_federal']['pis'] ?? '-'; ?></span>
                </td>
                <td>
                    <span class="label">COFINS - Débito Apuração Própria</span>
                    <span class="value"><?php echo $data['tributacao_federal']['cofins'] ?? '-'; ?></span>
                </td>
                <td colspan="2">
                    <span class="label">Descrição Contrib. Sociais - Retidas</span>
                    <span class="value"><?php echo $data['tributacao_federal']['desc_contrib_sociais'] ?? '-'; ?></span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tributação IBS / CBS -->
    <?php if ($data['tributacao_ibscbs'] !== null): ?>
    <?php $ibs = $data['tributacao_ibscbs']; ?>
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" colspan="4">
                    <span class="section-title">TRIBUTAÇÃO IBS / CBS</span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">CST / cClassTrib</span>
                    <span class="value"><?php echo ($ibs['cst'] !== '' || $ibs['class_trib'] !== '') ? $ibs['cst'] . ' / ' . $ibs['class_trib'] : '-'; ?></span>
                </td>
                <td colspan="3" style="width: 75%;">
                    <span class="label">Indicador de Operação / Código IBGE Incidência / Município Incidência / Sigla UF</span>
                    <span class="value"><?php
                        $parts = array_filter([$ibs['ind_op'], $ibs['cod_ibge_incidencia'], $ibs['municipio_incidencia_uf']], fn($v) => $v !== '');
                        echo $parts !== [] ? implode(' / ', $parts) : '-';
                    ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">Exclusões e Reduções da Base de Cálculo</span>
                    <span class="value"><?php echo $ibs['exclusoes_reducoes']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Base de Cálculo Após Exclusões e Reduções</span>
                    <span class="value"><?php echo $ibs['vbc']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Red. Alíquota IBS / Red. Alíquota CBS</span>
                    <span class="value"><?php
                        $ra = array_map(fn($v) => $v !== '' ? $v . '%' : '-', [$ibs['p_red_aliq_uf'], $ibs['p_red_aliq_mun'], $ibs['p_red_aliq_cbs']]);
                        echo implode(' / ', $ra);
                    ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Alíquota – IBS UF / IBS Mun</span>
                    <span class="value"><?php
                        $ai = array_map(fn($v) => $v !== '' ? $v . '%' : '-', [$ibs['p_ibs_uf'], $ibs['p_ibs_mun']]);
                        echo implode(' / ', $ai);
                    ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">Aliq. Efetiva Municipal – IBS</span>
                    <span class="value"><?php echo $ibs['p_aliq_efet_mun']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Valor Apurado Municipal – IBS</span>
                    <span class="value"><?php echo $ibs['v_ibs_mun']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Aliq. Efetiva Estadual – IBS</span>
                    <span class="value"><?php echo $ibs['p_aliq_efet_uf']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Valor Apurado Estadual – IBS</span>
                    <span class="value"><?php echo $ibs['v_ibs_uf']; ?></span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <span class="label">Valor Total Apurado – IBS</span>
                    <span class="value"><?php echo $ibs['v_ibs_tot']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Alíquota – CBS</span>
                    <span class="value"><?php echo $ibs['p_cbs']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Alíquota Efetiva – CBS</span>
                    <span class="value"><?php echo $ibs['p_aliq_efet_cbs']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Valor Total Apurado – CBS</span>
                    <span class="value"><?php echo $ibs['v_cbs']; ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <!-- Valor Total -->
    <div class="bordered-section">
        <table>
            <tr>
                <td class="section-header shaded" style="width: 25%; vertical-align: top;">
                  <span class="section-title">VALOR TOTAL DA NFS-e</span>
                </td>
                <td style="width: 25%;">
                    <span class="label">VALOR DA OPERAÇÃO / SERVIÇO</span>
                    <span class="value"><?php echo $data['totais']['valor_servico']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Desconto Incondicionado</span>
                    <span class="value"><?php echo $data['totais']['desconto_incondicionado']; ?></span>
                </td>
                <td style="width: 25%;">
                    <span class="label">Desconto Condicionado</span>
                    <span class="value"><?php echo $data['totais']['desconto_condicionado']; ?></span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Total das retenções (ISSQN / Federais)</span>
                    <span class="value"><?php echo $data['totais']['total_retencoes']; ?></span>
                </td>
                <td>
                    <span class="label">VALOR LÍQUIDO DA NFS-e</span>
                    <span class="value" style="font-weight: bold;"><?php echo $data['totais']['valor_liquido']; ?></span>
                </td>
                <td>
                    <span class="label">Total do IBS/CBS</span>
                    <span class="value"><?php echo $data['totais']['total_ibscbs']; ?></span>
                </td>
                <td class="shaded">
                    <span class="label">VALOR LÍQUIDO DA NFS-e + IBS/CBS</span>
                    <span class="value" style="font-weight: bold;"><?php echo $data['totais']['valor_liquido_ibscbs']; ?></span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Informações Complementares -->
        <div class="bordered-section">
            <table>
                <tr>
                    <td class="section-header">
                      <span class="section-title">INFORMAÇÕES COMPLEMENTARES</span>
                    </td>
                </tr>
                <tr>
                    <td style="min-height: 18pt; padding: 3pt 5pt;">
                    <?php
                    $substChave = trim((string) ($data['nfse_subst_chave'] ?? ''));
                    $metaParts = [];
                    if ($substChave !== '') {
                        $metaParts[] = '<strong>NFSe Subst:</strong> ' . $substChave;
                    }
                    ?>
                    <?php if ($metaParts !== []): ?>
                    <div class="value multiline-compact" style="margin-bottom: 3pt;"><?php echo implode(' | ', $metaParts); ?></div>
                    <?php endif; ?>
                    <span class="value"><?php echo $data['informacoes_complementares'] !== '' ? $data['informacoes_complementares'] : ''; ?></span>
                    <?php if (trim((string) ($data['totais_aprox_tributos'] ?? '')) !== ''): ?>
                    <div class="value multiline-compact" style="margin-top: 3pt;"><?php echo $data['totais_aprox_tributos']; ?></div>
                    <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

</body>
</html>
