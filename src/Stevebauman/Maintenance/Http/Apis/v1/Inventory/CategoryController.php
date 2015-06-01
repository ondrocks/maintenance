<?php

namespace Stevebauman\Maintenance\Http\Apis\v1\Inventory;

use Stevebauman\Maintenance\Repositories\Inventory\CategoryRepository;
use Stevebauman\Maintenance\Http\Apis\v1\CategoryController as BaseController;

class CategoryController extends BaseController
{
    /**
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
}