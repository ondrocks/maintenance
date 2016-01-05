<?php

namespace App\Http\Presenters\WorkOrder;

use App\Http\Presenters\Presenter;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Priority;
use App\Models\Status;
use App\Models\WorkOrder;
use Orchestra\Contracts\Html\Form\Fieldset;
use Orchestra\Contracts\Html\Form\Grid as FormGrid;
use Orchestra\Contracts\Html\Table\Column;
use Orchestra\Contracts\Html\Table\Grid as TableGrid;

class WorkOrderPresenter extends Presenter
{
    /**
     * Returns a new table of all work orders.
     *
     * @param WorkOrder $workOrder
     *
     * @return \Orchestra\Contracts\Html\Builder
     */
    public function table(WorkOrder $workOrder)
    {
        return $this->table->of('work-orders', function (TableGrid $table) use ($workOrder) {
            $table->with($workOrder)->paginate($this->perPage);

            $table->attributes([
                'class' => 'table table-hover table-striped',
            ]);

            $table->column('ID', 'id');

            $table->column('subject', function (Column $column) {
                $column->value = function (WorkOrder $workOrder) {
                    return link_to_route('maintenance.work-orders.show', $workOrder->subject, [$workOrder->getKey()]);
                };
            });

            $table->column('Created At', 'created_at');

            $table->column('created_by', function (Column $column) {
                $column->value = function (WorkOrder $workOrder) {
                    return $workOrder->user->fullname;
                };
            });

            $table->column('priority');

            $table->column('status');
        });
    }

    /**
     * Returns a new form for work orders.
     *
     * @param WorkOrder $workOrder
     *
     * @return \Orchestra\Contracts\Html\Builder
     */
    public function form(WorkOrder $workOrder)
    {
        return $this->form->of('work-orders', function (FormGrid $form) use ($workOrder) {
            if ($workOrder->exists) {
                $method = 'PATCH';
                $url = route('maintenance.work-orders.update', [$workOrder->getKey()]);
                $form->submit = 'Save';
            } else {
                $method = 'POST';
                $url = route('maintenance.work-orders.store');
                $form->submit = 'Create';
            }

            $form->with($workOrder);

            $form->attributes(compact('method', 'url'));

            $form->fieldset(function (Fieldset $fieldset) {
                $fieldset
                    ->control('select', 'category')
                    ->value(function (WorkOrder $workOrder) {
                        return $workOrder->category_id;
                    })
                    ->options(function () {
                        return Category::getSelectHierarchy('work-orders');
                    });

                $fieldset
                    ->control('select', 'location')
                    ->value(function (WorkOrder $workOrder) {
                        return $workOrder->location_id;
                    })
                    ->options(function () {
                        return Location::getSelectHierarchy();
                    });

                $fieldset
                    ->control('select', 'status')
                    ->options(function () {
                        $statuses = Status::all()->pluck('name', 'id');
                        $statuses[0] = 'None';

                        return $statuses;
                    });

                $fieldset
                    ->control('select', 'priority')
                    ->value(function (WorkOrder $workOrder) {
                        return $workOrder->priority_id;
                    })
                    ->options(function () {
                        $priorities = Priority::all()->pluck('name', 'id');
                        $priorities[0] = 'None';

                        return $priorities;
                    });

                $fieldset
                    ->control('select', 'assets[]')
                    ->label('Assets')
                    ->options(function () {
                        return Asset::all()->pluck('name', 'id');
                    })
                    ->attributes([
                        'class'    => 'select2',
                        'multiple' => true,
                    ]);

                $fieldset
                    ->control('input:text', 'subject')
                    ->attributes([
                        'placeholder' => 'ex. Worked on HVAC',
                    ]);

                $fieldset->control('input:textarea', 'description');
            });
        });
    }

    /**
     * Returns a navbar for the work order index.
     *
     * @return \Illuminate\Support\Fluent
     */
    public function navbar()
    {
        return $this->fluent([
            'id'         => 'work-orders',
            'title'      => 'Work Orders',
            'menu'       => view('work-orders._nav'),
            'attributes' => [
                'class' => 'navbar-default',
            ],
        ]);
    }
}