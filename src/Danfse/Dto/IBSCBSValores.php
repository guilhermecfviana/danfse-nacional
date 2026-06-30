<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class IBSCBSValores
{
    public function __construct(
        public string $vBC = '',
        public string $vCalcReeRepRes = '',
        public ?IBSCBSUf $uf = null,
        public ?IBSCBSMun $mun = null,
        public ?IBSCBSFed $fed = null,
    ) {}
}
