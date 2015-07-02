<?php

namespace Stevebauman\Maintenance\Http\Apis\v1\Admin\Archive;

use Stevebauman\Maintenance\Repositories\WorkOrder\Repository as WorkOrderRepository;
use Stevebauman\Maintenance\Http\Apis\v1\Controller as BaseController;

class WorkOrderController extends BaseController
{
    /**
     * @var WorkOrderRepository
     */
    protected $workOrder;

    /**
     * Constructor.
     *
     * @param WorkOrderRepository $workOrder
     */
    public function __construct(WorkOrderRepository $workOrder)
    {
        $this->workOrder = $workOrder;
    }

    /**
     * Returns a new grid instance of all archived work orders.
     *
     * @return \Cartalyst\DataGrid\DataGrid
     */
    public function grid()
    {
        $columns = [
            'id',
            'created_at',
            'user_id',
            'subject',
        ];

        $settings = [
            'sort'      => 'created_at',
            'direction' => 'desc',
        ];

        $transformer = function(WorkOrder $workOrder)
        {
            return [
                'id' => $workOrder->id,
                'created_at' => $workOrder->created_at,
                'subject' => $workOrder->subject,
                'view_url' => route('maintenance.admin.archive.work-orders.show', [$workOrder->id]),
                'created_by' => $workOrder->user->full_name,
                'status' => $workOrder->viewer()->lblStatus(),
                'priority' =>  $workOrder->viewer()->lblPriority(),
            ];
        };

        return $this->workOrder->gridArchived($columns, $settings, $transformer);
    }
}
