<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class EnderecoExterior
{
    public function __construct(
        public string $xCidade = '',
        public string $cEndPost = '',
    ) {}
}