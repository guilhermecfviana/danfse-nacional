<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class TotTrib
{
    public function __construct(
        /** @var string|array{vTotTribFed?: string, vTotTribEst?: string, vTotTribMun?: string} */
        public string|array $vTotTrib = '',
        public ?TotTribPercent $pTotTrib = null,
        public string $indTotTrib = '',
        public string $pTotTribSN = '',
    ) {}
}
