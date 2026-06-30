<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class IBSCBS
{
    public function __construct(
        public string $cLocalidadeIncid = '',
        public string $xLocalidadeIncid = '',
        public ?IBSCBSValores $valores = null,
        public ?TotCIBS $totCIBS = null,
    ) {}
}
