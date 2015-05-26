@extends('maintenance::layouts.pages.main.panel')

@section('panel.extra.top')

    @include('maintenance::work-orders.modals.search', array(
        'url'=>route('maintenance.work-orders.index')
    ))

@stop

@section('panel.head.content')
    <div class="btn-toolbar">
        <a href="{{ route('maintenance.work-orders.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            New Work Order
        </a>
        <a href="#" class="btn btn-primary" data-target="#search-modal" data-toggle="modal" title="Filter results">
            <i class="fa fa-search"></i>
            Search
        </a>

        <div class="col-md-2 pull-right">
            @include('maintenance::select.records-per-page')
        </div>
    </div>
@stop

@section('panel.body.content')

    @if($workOrders->count() > 0)

        {{
            $workOrders->columns([
                'id' => 'ID',
                'completed' => 'Completed',
                'status' => 'Status',
                'priority' => 'Priority',
                'subject' => 'Subject',
                'description' => 'Description',
                'category' => 'Category',
                'created_by' => 'Created By',
                'created_at' => 'Created At',
                'action' => 'Action',
            ])
            ->means('status', 'status.label')
            ->means('priority', 'priority.label')
            ->means('category', 'category.trail')
            ->means('created_by', 'user.full_name')
            ->means('description', 'limited_description')
            ->modify('action', function($workOrder) {
                return $workOrder->viewer()->btnActions();
            })
            ->modify('completed', function($workOrder) {
                return $workOrder->viewer()->lblCheckCompleted();
            })
            ->sortable([
                'id',
                'status'=>'status_id',
                'priority' => 'priority_id',
                'category' => 'category_id',
                'created_by' => 'user_id',
                'subject',
                'created_at'
            ])
            ->hidden(['id', 'description', 'category', 'created_by', 'created_at'])
            ->showPages()
            ->render()
        }}

    @else

        <h5>There are no work orders to display.</h5>

    @endif

@stop
