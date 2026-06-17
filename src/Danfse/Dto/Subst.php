<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Subst
{
    public function __construct(
        public string $chSubstda = '',
        public string $cMotivo = '',
        public string $xMotivo = '',
    ) {}
}
