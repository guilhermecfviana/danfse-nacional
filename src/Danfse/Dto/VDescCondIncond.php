<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class VDescCondIncond
{
    public function __construct(
        public string $vDescCond = '',
        public string $vDescIncond = '',
    ) {}
}
