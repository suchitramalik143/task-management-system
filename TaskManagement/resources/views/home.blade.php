<?php
$dates = [['label' => 'Recent', 'value' => 'recent'], ['label' => 'Low to high price', 'value' => 'low_to_high_price'], ['label' => 'High to low price', 'value' => 'high_to_low_price']];

$quickFilters = [
    [
        'name' => 'status',
        'label' => 'Status',
        'selected_label' => 'Status: ',
        'type' => 'dropdown',
        'isMulti' => false,
        'values' => [
            [
                'label' => 'Pending',
                'value' => 'pending'
            ],
            [
                'label' => 'In progress',
                'value' => 'in_progress'
            ],
            [
                'label' => 'Completed',
                'value' => 'completed'
            ],

        ]
    ],
    [
        'name' => 'priority',
        'label' => 'Priority',
        'selected_label' => 'Priority: ',
        'type' => 'dropdown',
        'isMulti' => false,
        'values' => [
            [
                'label' => 'High',
                'value' => 'high'
            ],
            [
                'label' => 'Medium',
                'value' => 'medium'
            ],
            [
                'label' => 'Low',
                'value' => 'low'
            ],
        ]
    ],

    [
        'name' => 'due_date',
        'label' => 'Due date',
        'selected_label' => 'Due date: ',
        'type' => 'dropdown',
        'isMulti' => false,
        'values' => [
            [
                'label' => 'Recent',
                'value' => 'recent'
            ],
            [
                'label' => 'Last three month',
                'value' => 'last_three_month'
            ],
            [
                'label' => 'Last six month',
                'value' => 'last_six_month'
            ],
        ]
    ],
]
?>
@extends('layouts.app')

@section('template_title')
Task management
@endsection

@section('head')


@endsection
@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

<style>
    .page-wrapper {
        background-color: white;
    }

    .br-14 {
        bTask-radius: 14px;
    }

    .table thead th,
    .markdown>table thead th {
        color: black;
        font-weight: 500px;
        font-size: small;
    }

    .quick-filter-item {
        display: block;
        background: #eceff4;
        padding: 4px 14px;
        border-radius: 40px;
        color: #666060;
        text-decoration: none !important;
        border: 0px;
    }

    .quick-filter-item.active {
        background: var(--tblr-primary);
        color: #fff;
        padding-right: 30px;
        position: relative;
    }

    .quick-filter-item .filter-clear-btn {
        color: #fff;
        font-size: 16px;
        line-height: 20px;
        position: absolute;
        right: 5px;
        top: 5px;
        padding: 0 1px;
        border-radius: 50%;
        height: 20px;
        width: 20px;
        background: #116cce;
        border: 0;
    }

    .filter-dropdown .dropdown-menu {
        padding: 5px;
    }

    .filter-dropdown .dropdown-item {
        padding: 8px;
    }

    .filter-dropdown .dropdown-item:hover {
        background-color: #f1f1f1;
        border-radius: 4px;
    }

    .filter-dropdown .dropdown-item.active {
        background: transparent;
        color: var(--tblr-body-color);
    }

    .filter-dropdown .dropdown-item.active i {
        color: var(--tblr-dropdown-link-active-color);
    }

    .filter-dropdown .dropdown-item.active:hover {
        background-color: #f1f1f1;
        border-radius: 4px;
    }

    .table th,
    .table td {
        max-width: 200px;
        /* Adjust as needed */
        overflow: hidden;
        /* Hide overflow content */
        text-overflow: ellipsis;
        /* Show ellipsis for overflow text */
        white-space: nowrap;
        /* Prevent text from wrapping */
    }

    .table td {
        word-wrap: break-word;
        /* Allow long words to break */
    }

    .editable span {
        display: inline-block;
        /* Ensure span behaves like a block */
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 300px
    }

    .clickable {
        cursor: pointer;
    }
</style>
@endsection

@section('breadcrumb')
<div class="col">
    <a href="#" class="text-decoration-none fs-2 fw5 text-dark">Task management
    </a>
</div>

@endsection
@section('breadcrumb-action')


@endsection

@section('content')
<div class="container-xl">


    <div class="card">
        <div class="card-header bTask-bottom-0 pb-3">
            <h3 class="card-title"> Task ({{ $lists->total()}})</h3>

        </div>

        <div class="card-body ">
            <div class="d-flex justify-content-between  mb-2 flex-column flex-sm-row">
                <div class="d-flex align-items-center mb-3">
                    <div id="app">
                        <quick-filters
                            :filters="{{ json_encode($quickFilters) }}"
                            :request-data="{{ json_encode(request()->all()) }}"
                            :base-url="'{{ route('home') }}'">
                        </quick-filters>
                    </div>



                </div>
                <div class="d-flex align-items-center mb-3">

                    <form method="get" autocomplete="off">
                        @foreach(request()->except('page','keyword') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <div class="input-icon">
                            <input type="text" value="{{request()->get('keyword')}}"
                                name="keyword" autocomplete="off"
                                class="form-control mb-0" placeholder="Search by Title ">
                            <button type="submit" class="input-icon-addon btn btn-transparent d-action">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>
                    </form>

                </div>


            </div>
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                    @if ($lists && count($lists))
                    <thead>
                        <tr>
                            <th class="checkbox-cell">
                                <input type="checkbox" data-act="select-input" data-select-name="id[]"
                                    class="select-all-checkbox check-input form-check-input">
                            </th>
                            <th>Title</th>
                            <th>Assign to</th>
                            <th>Due Date</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created On</th>
                            <th class="w50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lists as $list)
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="id[]" class="td-checkbox check-input form-check-input" value="{{ $list->id }}">
                            </td>
                            <td>{{ ucfirst($list->title) }}</td>

                            <td class="w80">
                                {{ $list->assignee->name ?? '<i class="ti ti-users"></i>' }}
                            </td>

                            <td>
                                <div class="{{ $list->assigned_to == $loggedInUserId ? 'editable' : '' }}">
                                    <span class="selected-due-date clickable">
                                        {{ $list->due_date ?? '<i class="ti ti-calendar"></i>' }}
                                    </span>
                                    <input type="date" class="due-date-input form-control" data-id="{{ $list->id }}" style="display: none;" value="{{ $list->due_date }}">
                                </div>
                            </td>

                            <td>
                                <div class="{{ $list->assigned_to == $loggedInUserId ? 'editable' : '' }}">
                                    <span class="selected-priority clickable">{!! getPriorityBadge($list->priority, "badge-pill", false) !!}</span>
                                    <select class="priority-select form-select" data-id="{{ $list->id }}" style="display: none;">
                                        <option value="low" {{ $list->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $list->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $list->priority == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div class="{{ $list->assigned_to == $loggedInUserId ? 'editable' : '' }}">
                                    <span class="selected-status clickable">{!! getStatusBadge($list->status, "badge-pill", false) !!}</span>
                                    <select class="status-select form-select" data-id="{{ $list->id }}" style="display: none;">
                                        <option value="pending" {{ $list->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $list->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $list->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </td>

                            <td>{{ convertDate($list->updated_at) }}</td>
                            @if ($list->assigned_to === auth()->id())
                            <td class="text-center">

                                <button class=" text-danger delete-btn btn btn-link" type="button"
                                    data-bs-toggle="modal" data-bs-target="#confirmDelete"
                                    data-id="{{ $list->id }}"
                                    data-url="{{ route('task.delete', ['id' => $list->id]) }}">
                                    <i class="ti ti-trash text-danger fs-1"></i>
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>



                    @endif
                </table>
                @if ($lists === null || count($lists) === 0)
                <no-result title="Tasks are not available"
                    subtitle="Please try changing the filters."></no-result>
                @endif
                <nav aria-label="Page navigation" class="center-pagination my-5">
                    {{ $lists->appends(request()->except('page'))->links() }}
                </nav>
            </div>

        </div>
    </div>
</div>
@endsection

@section('popup')




<!-- Delete Modal -->
<div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item?</p>
                </div>
                <div class="p-3 text-end pt-0">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('additional_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {

        $('.editable .selected-assignee, .editable .selected-due-date, .editable .selected-priority, .editable .selected-status').on('click', function() {
            $(this).hide();
            $(this).siblings('select, input[type="date"]').show().focus();
        });

        $('.assignee-select, .priority-select, .status-select, .due-date-input').on('change blur', function() {
            $(this).hide();
            $(this).siblings('span').show();
        });

        $('.due-date-input').on('change', function() {
            const taskId = $(this).data('id');
            const dueDate = $(this).val();

            $.ajax({
                url: '/admin/task/update/' + taskId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    due_date: dueDate
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error updating due date.');
                }
            });
        });

        $('.assignee-select').on('change', function() {
            const taskId = $(this).data('id');
            const assigneeId = $(this).val();

            $.ajax({
                url: '/admin/task/update/' + taskId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    assignee_id: assigneeId
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error updating assignee.');
                }
            });
        });

        $('.priority-select').on('change', function() {
            const taskId = $(this).data('id');
            const priority = $(this).val();

            $.ajax({
                url: '/admin/task/update/' + taskId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    priority: priority
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error updating priority.');
                }
            });
        });

        $('.status-select').on('change', function() {
            const taskId = $(this).data('id');
            const status = $(this).val();

            $.ajax({
                url: '/admin/task/update/' + taskId,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error('Error updating status.');
                }
            });
        });

        $('.delete-btn').on('click', function() {
            const url = $(this).data('url');
            $('#deleteForm').attr('action', url);
        });

        $('body').on('click', '[data-bs-toggle="modal"]', function() {
            var url = $(this).data('url');
            var title = $(this).data('title');

            $('#editModal .modal-title').text(title);
            $('#editModal .modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');

            $.ajax({
                type: 'GET',
                url: url,
                success: function(response) {
                    $('#editModal .modal-body').html(response.success ? response.value : '<div class="alert alert-danger">' + response.msg + '</div>');
                },
                error: function(xhr) {
                    $('#editModal .modal-body').html('<div class="alert alert-danger">An error occurred while fetching data.</div>');
                }
            });
        });
    });
</script>



@endsection