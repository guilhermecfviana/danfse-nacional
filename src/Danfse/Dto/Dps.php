<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Dps
{
    public function __construct(
        public ?InfDPS $infDPS = null,
        public string $versao = '',
    ) {}
}
