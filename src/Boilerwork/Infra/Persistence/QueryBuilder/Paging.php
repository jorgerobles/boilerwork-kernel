#!/usr/bin/env php
<?php

declare(strict_types=1);

namespace Boilerwork\Infra\Persistence\QueryBuilder;

final class Paging
{
    private int $totalCount = 0;

    public function __construct(
        private readonly int $perPage,
        private readonly int $page,
    ) {
        container()->instance('Paging', $this);
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function totalCount(): int
    {
        return $this->totalCount;
    }

    public function totalPages(): int
    {
        return max(1, (int) ceil($this->totalCount() / $this->perPage()));
    }

    public function serialize(): array
    {
        return [
            'perPage' => $this->perPage(),
            'page' => $this->page(),
            'totalCount' => $this->totalCount(),
            'totalPages' => $this->totalPages(),
        ];
    }
}
