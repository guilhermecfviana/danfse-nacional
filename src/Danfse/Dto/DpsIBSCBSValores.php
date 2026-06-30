<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class DpsIBSCBSValores
{
    public function __construct(
        public ?DpsIBSCBSTrib $trib = null,
    ) {}
}
