<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class GIBSTot
{
    public function __construct(
        public string $vIBSTot = '',
        public ?GIBSUFTot $gIBSUFTot = null,
        public ?GIBSMunTot $gIBSMunTot = null,
    ) {}
}
