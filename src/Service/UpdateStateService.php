<?php

namespace App\Service;

use App\Repository\SortieRepository;

class UpdateStateService
{
    public function __construct(private SortieRepository $sortieRepository)
    {
    }

    public function updateOngoingSorties(): int
    {
        return $this->sortieRepository->updateOngoingSorties();
    }
}        


