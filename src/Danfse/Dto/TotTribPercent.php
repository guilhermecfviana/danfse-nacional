<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class TotTribPercent
{
    public function __construct(
        public string $pTotTribFed = '',
        public string $pTotTribEst = '',
        public string $pTotTribMun = '',
    ) {}
}
