<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class GItemPed
{
    public function __construct(
        /** @var string|array<int, string> */
        public string|array $xItemPed = '',
    ) {}
}
