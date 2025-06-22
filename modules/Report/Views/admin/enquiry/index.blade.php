@extends ('admin.layouts.app')
@section ('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__('All Enquiries')}}</h1>
        </div>
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
        <div class="filter-div d-flex justify-content-between">
            <div class="col-left">
                @if(!empty($enquiry_update))
                    <form method="post" action="{{route('report.admin.enquiry.bulkEdit')}}" class="filter-form filter-form-left d-flex justify-content-start">
                        @csrf
                        <select name="action" class="form-control">
                            <option value="">{{__("-- Bulk Actions --")}}</option>
                            @if(!empty($statues))
                                @foreach($statues as $status)
                                    <option value="{{$status}}">{{__('Mark as: :name',['name'=>booking_status_to_text($status)])}}</option>
                                @endforeach
                            @endif
                            <option value="delete">{{__("DELETE Enquiry")}}</option>
                            <option value="completed">{{__("Mark as: Completed")}}</option>
                            <option value="cancel">{{__("Mark as: Cancel")}}</option>
                            <option value="pending">{{__("Mark as: Pending")}}</option>
                            
                        </select>
                        <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{__('Apply')}}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="" class="filter-form filter-form-right d-flex justify-content-end">
                    <input type="text" name="s" value="{{ Request()->s }}" placeholder="{{__('Search by email')}}" class="form-control">
                    <button class="btn-info btn btn-icon" type="submit">{{__('Filter')}}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="panel booking-history-manager">
            <div class="panel-title">{{__('Enquiries')}}</div>
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <table class="table table-hover bravo-list-item">
                        <thead>
                        <tr>
                            <th width="80px"><input type="checkbox" class="check-all"></th>
                            <th>{{__('Service')}}</th>
                            <th>{{__('Customer')}}</th>
                            <th width="80px">{{__('Status')}}</th>
                            <th width="80px">{{__('Replies')}}</th>
                            <th width="180px">{{__('Created At')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($rows->total() > 0)
                                @foreach($rows as $row)
                                    <tr>
                                        <td><input type="checkbox" class="check-item" name="ids[]" value="{{$row->id}}">
                                            #{{$row->id}}</td>
                                        <td>
                                            @if($service = $row->service)
                                                <a href="{{$service->getDetailUrl()}}" target="_blank">{{$service->title ?? ''}}</a>
                                                @if($service->author->id)
                                                    <br>
                                                    <span>{{__('by')}}</span>
                                                    <a href="{{route('user.admin.detail',['id'=>$service->author->id])}}"
                                                       target="_blank">{{ $service->author->getDisplayName() .' (#'.$service->author->id.')' }}</a>
                                                @endif
                                            @else
                                                {{__("[Deleted]")}}
                                            @endif
                                        </td>
                                        <td>
                                            <ul>
                                                <li>{{__("Name:")}} {{$row->name}}</li>
                                                <li>{{__("Email:")}} {{$row->email}}</li>
                                                <li>{{__("Phone:")}} {{$row->phone}}</li>
                                                <li>{{__("Notes:")}} {{$row->note}}</li>
                                            </ul>
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
                                        <td>{{$row->replies_count}}</td>
                                        <td>{{display_datetime($row->updated_at)}}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                    {{__("Actions")}}
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{route('report.admin.enquiry.reply',['enquiry'=>$row])}}">{{__("Reply")}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">{{__("No data")}}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            {{$rows->links()}}
        </div>
    </div>
@endsection
