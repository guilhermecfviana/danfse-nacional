<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class InfoCompl
{
    public function __construct(
        public string $idDocTec = '',
        public string $docRef = '',
        public string $xPed = '',
        public ?GItemPed $gItemPed = null,
        public string $xInfComp = '',
    ) {}
}
