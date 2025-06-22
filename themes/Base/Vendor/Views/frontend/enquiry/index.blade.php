@extends('layouts.user')
@section('content')
    <h2 class="title-bar no-border-bottom">
        {{ $page_title }}
    </h2>
    @include('admin.message')
    <style>
        .status_bg {
            padding: 5px 10px;
            font-size: 12px;
            color: #fff;
            border-radius: 5px;
            min-width: 50px;
            margin: 5px 2px 0px;
            cursor:text !important;
        }
    </style>
    <div class="booking-history-manager">
        <div class="tabbable">
            @if (!empty($rows) and $rows->total() > 0)
                <div class="tab-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-booking-history">
                            <thead>
                                <tr>
                                    <th width="2%">{{ __('Type') }}</th>
                                    <th>{{ __('Service Info') }}</th>
                                    <th>{{ __('Customer Info') }}</th>
                                    <th width="80px">{{ __('Status') }}</th>
                                    <th width="80px">{{ __('Replies') }}</th>
                                    <th width="180px">{{ __('Created At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rows->total() > 0)
                                    @foreach ($rows as $row)
                                        <tr>
                                            <td class="booking-history-type">
                                                @if ($service = $row->service)
                                                    <i class="{{ $service->getServiceIconFeatured() }}"></i>
                                                @endif
                                                <small>{{ $row->object_model }}</small>
                                            </td>
                                            <td>
                                                @if ($service = $row->service)
                                                    <a href="{{ $service->getDetailUrl() }}"
                                                        target="_blank">{{ $service->title ?? '' }}</a>
                                                @else
                                                    {{ __('[Deleted]') }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-nowrap">{{ __('Name:') }} {{ $row->name }}</div>
                                                <div>{{ __('Notes:') }} {{ $row->note }}</div>
                                            </td>
                                            <td>
                                                @if ($row->status == 'pending')
                                                    <span class="label label-{{ $row->status }} btn btn-warning status_bg text-white">{{ $row->statusName }}</span>
                                                @endif
                                                @if ($row->status == 'completed')
                                                    <span class="label label-{{ $row->status }} btn btn-success status_bg">{{ $row->statusName }}</span>
                                                @endif
                                                @if ($row->status == 'cancel')
                                                    <span class="label label-{{ $row->status }} btn btn-danger status_bg">{{ $row->statusName }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->replies_count }}</td>
                                            <td>{{ display_datetime($row->updated_at) }}</td>
                                            <td width="2%">
                                                @if (!empty($has_permission_enquiry_update))
                                                    <a class="btn btn-xs btn-info btn-make-as" data-toggle="dropdown">
                                                        <i class="icofont-ui-settings"></i>
                                                        {{ __('Action') }}
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a
                                                            href="{{ route('vendor.enquiry_report.reply', ['enquiry' => $row]) }}"><i
                                                                class="icofont-long-arrow-right"></i>
                                                            {{ __('Add Reply') }}</a>
                                                        <div class="dropdown-divider"></div>
                                                        @if (!empty($statues))
                                                            @foreach ($statues as $status)
                                                                <a
                                                                    href="{{ \Illuminate\Support\Facades\URL::signedRoute('vendor.enquiry_report.bulk_edit', ['id' => $row->id, 'status' => $status]) }}">
                                                                    <i class="icofont-long-arrow-right"></i>
                                                                    {{ __('Mark as: :name', ['name' => booking_status_to_text($status)]) }}
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">{{ __('No data') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="bravo-pagination">
                        {{ $rows->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                {{ __('No data') }}
            @endif
        </div>
    </div>
@endsection
