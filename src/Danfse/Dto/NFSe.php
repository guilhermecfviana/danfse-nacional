<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class NFSe
{
    public function __construct(
        public ?InfNFSe $infNFSe = null,
        public string $versao = '',
    ) {}
}
