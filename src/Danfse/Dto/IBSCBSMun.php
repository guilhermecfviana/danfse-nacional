<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class IBSCBSMun
{
    public function __construct(
        public string $pIBSMun = '',
        public string $pAliqEfetMun = '',
        public string $pRedAliqMun = '',
    ) {}
}
