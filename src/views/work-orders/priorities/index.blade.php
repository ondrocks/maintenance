@extends('maintenance::layouts.pages.main.panel')

@section('panel.head.content')
    <div class="btn-toolbar">
        <a href="{{ route('maintenance.work-orders.priorities.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            New Priority
        </a>
    </div>
@stop

@section('panel.body.content')

    @if($priorities->count() > 0)

        {{
            $priorities->columns([
                'name' => 'Name',
                'color' => 'Color',
                'label' => 'Displayed As',
                'created_by' => 'Created By',
                'created_at' => 'Created At',
                'action' => 'Action',
            ])
            ->means('created_by', 'user.full_name')
            ->modify('action', function($priority) {
                return $priority->viewer()->btnActions();
            })
            ->hidden(['color', 'created_by', 'created_at', 'name'])
            ->render()
        }}

    @else
        <h5>There are no priorities to display.</h5>
    @endif

@stop
