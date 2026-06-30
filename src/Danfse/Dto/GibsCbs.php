<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

/** gIBSCBS do bloco DPS/infDPS/IBSCBS/valores/trib */
readonly class GibsCbs
{
    public function __construct(
        public string $CST = '',
        public string $cClassTrib = '',
    ) {}
}
