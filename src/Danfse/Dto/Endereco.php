<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Endereco
{
    public function __construct(
        public ?EnderecoNacional $endNac = null,
        public string $xLgr = '',
        public string $nro = '',
        public string $xBairro = '',
    ) {}
}
