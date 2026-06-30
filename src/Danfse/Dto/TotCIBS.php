<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class TotCIBS
{
    public function __construct(
        public ?GIBSTot $gIBS = null,
        public ?GCBSTot $gCBS = null,
        public string $vTotNF = '',
    ) {}
}
