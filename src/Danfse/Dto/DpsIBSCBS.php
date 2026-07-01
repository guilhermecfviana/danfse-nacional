<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

/** IBSCBS do bloco DPS/infDPS (dados de tributação da operação) */
readonly class DpsIBSCBS
{
    public function __construct(
        public string $cIndOp = '',
        public string $finNFSe = '',
        public ?Destinatario $dest = null,
        public ?DpsIBSCBSValores $valores = null,
    ) {}
}
