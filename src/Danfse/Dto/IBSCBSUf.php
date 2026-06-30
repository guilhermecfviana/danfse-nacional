<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class IBSCBSUf
{
    public function __construct(
        public string $pIBSUF = '',
        public string $pAliqEfetUF = '',
        public string $pRedAliqUF = '',
    ) {}
}
