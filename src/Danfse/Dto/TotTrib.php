<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class TotTrib
{
    public function __construct(
        public string $vTotTrib = '',
        public ?TotTribPercent $pTotTrib = null,
        public string $indTotTrib = '',
        public string $pTotTribSN = '',
    ) {}
}
