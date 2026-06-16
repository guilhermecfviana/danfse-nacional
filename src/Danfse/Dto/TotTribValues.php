<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class TotTribValues
{
    public function __construct(
        public string $vTotTribFed = '',
        public string $vTotTribEst = '',
        public string $vTotTribMun = '',
    ) {}
}
