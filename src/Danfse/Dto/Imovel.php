<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Imovel
{
    public function __construct(
        public string $inscImobFisc = '',
    ) {}
}
