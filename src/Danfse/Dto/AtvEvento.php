<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class AtvEvento
{
    public function __construct(
        public string $idAtvEvt = '',
        public string $xNome = '',
        public string $dtIni = '',
        public string $dtFim = '',
        public ?Endereco $end = null,
    ) {}
}
