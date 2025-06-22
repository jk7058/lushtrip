@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{__('Credit Purchase Report')}}</h1>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between">
            <div class="col-left">
                @if(!empty($rows))              
                    <!-- Modal -->
                    <div class="modal fade" id="modalForm" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                </div>
                                <!-- Modal Body -->
                                <div class="modal-body">                               
                                <form method="post" action="{{route('user.admin.wallet.reportBulkEdit')}}" class="filter-form filter-form-left d-flex justify-content-start">
                                    {{csrf_field()}}
                                    <input type="hidden" name="action" id="action_type"/>
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea onkeyup="message(this)" name="comment_message" placeholder="Enter Comment Message*" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button data-confirm="{{__("Do you want to delete?")}}" class="btn-info btn btn-icon dungdt-apply-form-btn apply-button" type="button" style="float:
                                                right" id="my-button" disabled>{{__('Submit')}}</button>
                                            </div>
                                        </div>   
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button positions Credit Purchase Report -->
                    <div class="row col-md-12">
                        <div class="col-md-8">
                        <div class="form-group">
                            <select id="options" class="form-control" style="max-width: 100%;
    height: 35px;">
                                <option value="">{{__(" Bulk Actions ")}}</option>
                                <option value="completed">{{__("Mark as completed")}}</option>
                                <option value="rejected">{{__("Mark as rejected")}}</option>
                            </select>
                        </div> 
                        </div>
                        <div class="col-md-4" style="margin-left: -10px;">                         
                        <div class="form-group">
                            <button data-toggle="modal" data-target="#modalForm"class="btn-info btn btn-icon dungdt-apply-form-btn apply-button" onclick="GetSelectedTextValue()" type="button" style="float:
                            right">{{__('Apply')}}</button>
                        </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="" class="filter-form filter-form-right d-flex justify-content-end">
                    <select name="status" class="form-control">
                        <option value="">{{__("-- Status --")}}</option>
                        <option @if(request()->query('status') == 'fail') selected @endif value="fail">{{__("Failed")}}</option>
                        <option @if(request()->query('status') == 'processing') selected @endif value="processing">{{__("Processing")}}</option>
                        <option @if(request()->query('status') == 'completed') selected @endif value="completed">{{__("Completed")}}</option>
                    </select>
                    @csrf
                        <?php
                        $user = !empty(Request()->user_id) ? App\User::find(Request()->user_id) : false;
                        \App\Helpers\AdminForm::select2('user_id', [
                            'configs' => [
                                'ajax'        => [
                                    'url'      => route('user.admin.getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'allowClear'  => true,
                                'placeholder' => __('-- User --')
                            ]
                        ], !empty($user->id) ? [
                            $user->id,
                            $user->name_or_email . ' (#' . $user->id . ')'
                        ] : false)
                        ?>
                    <button class="btn-info btn btn-icon" type="submit">{{__('Filter')}}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{__('Found :total items',['total'=>$rows->total()])}}</i></p>
        </div>
        <div class="panel booking-history-manager">
            <div class="panel-title">{{__('Purchase logs')}}</div>
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <table class="table table-hover bravo-list-item">
                        <thead>
                        <tr>
                            <th width="80px"><input type="checkbox" class="check-all"></th>
                            <th>{{__('Customer')}}</th>

                            <th width="80px">{{__('Amount')}}</th>
                            <th width="80px">{{__('Credit')}}</th>
                            <th width="80px">{{__('Status')}}</th>
                            <th width="150px">{{__('Payment Method')}}</th>
                            <th width="120px">{{__('Created At')}}</th>
                            <th width="80px">{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $row)
                            <tr>
                                <td><input type="checkbox" class="check-item" name="ids[]" value="{{$row->id}}">
                                    #{{$row->id}}</td>
                                <td>
                                    @if($row->user)
                                        <a href="">{{$row->user->display_name}}</a>
                                    @endif
                                </td>
                                <td>{{format_money_main($row->amount)}}</td>
                                <td>{{$row->getMeta('credit')}}</td>
                                <td>
                                    <span class="label label-{{$row->status}}">{{$row->statusName}}</span>
                                </td>
                                <td>
                                    {{$row->gatewayObj ? $row->gatewayObj->getDisplayName() : ''}}
                                </td>
                                <td>{{display_datetime($row->updated_at)}}</td>
                                <td>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            {{$rows->links()}}
        </div>
    </div>

<script >
function message(txt){
    if (txt.value.length > 0) {
        document.getElementById("my-button").disabled = false;
    } else {
        document.getElementById("my-button").disabled = true;
    }    
}

function GetSelectedTextValue() {
    document.getElementById("action_type").value = '';
    var ddlFruits = document.getElementById("options");
    var selectedText = ddlFruits.options[ddlFruits.selectedIndex].innerHTML;
    var selectedValue = ddlFruits.value;
    // alert("Selected Text: " + selectedText + " Value: " + selectedValue);
    document.getElementById("action_type").value = selectedValue;
}
</script>   
@endsection
