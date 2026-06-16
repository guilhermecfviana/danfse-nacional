<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class VServPrest
{
    public function __construct(
        public string $vServ = '',
        public string $vReceb = '',
    ) {}
}
