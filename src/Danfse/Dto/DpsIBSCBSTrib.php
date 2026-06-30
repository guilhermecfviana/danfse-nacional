<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class DpsIBSCBSTrib
{
    public function __construct(
        public ?GibsCbs $gIBSCBS = null,
    ) {}
}
