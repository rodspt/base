<?php

namespace App\DTO;

class PaginationDTO
{
    public function __construct(
          public ?int $totalPerPage = 15,
          public ?int $page = 1,
          public ?string $filter = '',
     ){
        $this->setDefault();
    }

    public function setDefault(){
        $this->totalPerPage = $this->totalPerPage ?? 15;
        $this->page = $this->page ?? 1;
        $this->filter = $this->filter ?? '';
    }
}
