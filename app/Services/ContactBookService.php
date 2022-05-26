<?php

namespace App\Services;

use App\Repositories\ContactBookRepository;


class ContactBookService
{
    public function __construct(
        protected ContactBookRepository $repository
    ) {}
}
