<?php

namespace GuilhermeViana\Nfsenacional\Danfse\Dto;

readonly class Destinatario
{
    public function __construct(
        public string $CNPJ = '',
        public string $CPF = '',
        public string $NIF = '',
        public string $xNome = '',
        public ?Endereco $end = null,
        public string $fone = '',
        public string $email = '',
    ) {}

    public function documento(): string
    {
        return $this->CNPJ ?: ($this->CPF ?: $this->NIF);
    }
}