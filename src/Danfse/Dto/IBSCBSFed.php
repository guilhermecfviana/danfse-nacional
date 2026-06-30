<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class IBSCBSFed
{
    public function __construct(
        public string $pCBS = '',
        public string $pAliqEfetCBS = '',
        public string $pRedAliqCBS = '',
    ) {}
}
