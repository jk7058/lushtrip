@extends('admin.layouts.app')
@section('content')
    <div class="container">
    <button type="button" data-toggle="modal" data-target="#bulkUpload" data-uid="bulk" class="update btn btn-primary btn-md">{{__("Upload Timings")}}</button>
    <div class="row">
		<table class="table table-hover table-responsive">
		    <thead>
		        <tr>
		            <th>Departure At</th>
		            <th>Arrival At</th>
		            <th>Edit</th>
		        </tr>
		    </thead>
		    <tbody>
                @foreach($timings as $row)
		        <tr id="d1">
		            <td id="f1">{{\Carbon\Carbon::parse($row->departure_time)->format("D M d Y H:i A")}}</td>                   
		            <td id="l1">{{\Carbon\Carbon::parse($row->arrival_time)->format("D M d Y H:i A")}}</td>
		            <td><button type="button" data-toggle="modal" data-target="#edit{{$row->id}}" data-uid="1" class="update btn btn-warning btn-sm"><span class="fa fa-pencil"></span></button></td>
		        </tr>

<div id="edit{{$row->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Flight Timings</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        <form method='post' action="{{route('flight.admin.flight.timings.update')}}">
            {{ csrf_field() }}
            <input type="hidden" class="form-control" name="timingsId" value="{{$row->id}}">
            <input type="hidden" class="form-control" name="flightId" value="{{$row->flight_id}}">
            <div class="form-group">
                <label>Depart:</label>
                <input type="text" class="form-control" name="departTime" value="{{$row->departure_time}}">
            </div>
            <div class="form-group">
                <label>Arrival:</label>
                <input type="text" class="form-control" name="arrrivalTime" value="{{$row->arrival_time}}">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update" />
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
                @endforeach
		    </tbody>
		</table>
	</div>
</div>
<div id="bulkUpload" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Bulk Upload(CSV File)</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body">
        <form method='post' action="{{route('flight.admin.flight.timings.bulkUpload')}}" enctype='multipart/form-data' >
        {{ csrf_field() }}
        <input type='file' name='file'>
        <input type="hidden" name='flight_id' value="{{$row->id}}"/>
        <input type='submit' name='submit' value='Import'/>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="delete" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">Delete Data</h4>
      </div>
      <div class="modal-body">
        <strong>Are you sure you want to delete this data?</strong>
      </div>
      <div class="modal-footer">
        <button type="button" id="del" class="btn btn-danger" data-dismiss="modal">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('.has-datetimepicker').daterangepicker({
                singleDatePicker: true,
                timePicker: true,
                showCalendar: false,
                autoUpdateInput: false, //disable default date
                sameDate: true,
                autoApply           : true,
                disabledPast        : true,
                enableLoading       : true,
                showEventTooltip    : true,
                classNotAvailable   : ['disabled', 'off'],
                disableHightLight: true,
                timePicker24Hour: true,

                locale:{
                    format:'YYYY/MM/DD HH:mm:ss'
                }
            }).on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY/MM/DD HH:mm:ss'));
            });
        })
    </script>
@endpush
