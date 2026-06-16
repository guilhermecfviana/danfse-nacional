<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Valores
{
    public function __construct(
        public ?VServPrest $vServPrest = null,
        public ?Tributacao $trib = null,
        public string $vDescCondIncond = '',
    ) {}
}
