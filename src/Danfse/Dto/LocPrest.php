<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class LocPrest
{
    public function __construct(
        public ?string $cLocPrestacao = null,
        public ?string $cPaisPrestacao = null,
    ) {}
}
