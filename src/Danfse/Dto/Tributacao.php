<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Tributacao
{
    public function __construct(
        public ?TribMunicipal $tribMun = null,
        public ?TribFederal $tribFed = null,
        public ?TotTrib $totTrib = null,
    ) {}
}
