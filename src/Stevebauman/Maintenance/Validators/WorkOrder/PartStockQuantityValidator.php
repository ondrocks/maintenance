<?php

namespace Stevebauman\Maintenance\Validators\WorkOrder;

use Illuminate\Support\Facades\Route;
use Stevebauman\Maintenance\Repositories\Inventory\StockRepository;
use Stevebauman\Maintenance\Services\Inventory\StockService;

class PartStockQuantityValidator
{
    /**
     * @var StockService
     */
    protected $inventoryStock;

    /**
     * Constructor.
     *
     * @param StockRepository $inventoryStock
     */
    public function __construct(StockRepository $inventoryStock)
    {
        $this->inventoryStock = $inventoryStock;
    }

    /**
     * Validates that the inserted stock is smaller than the available stock.
     *
     * @param $attribute
     * @param $quantity
     * @param $parameters
     *
     * @return bool
     */
    public function validateEnoughQuantity($attribute, $quantity, $parameters)
    {
        if (is_numeric($quantity)) {
            $stockId = Route::getCurrentRoute()->getParameter('stocks');

            $stock = $this->inventoryStock->find($stockId);

            if ($quantity > $stock->quantity) {
                return false;
            }

            return true;
        }

        return false;
    }
}
