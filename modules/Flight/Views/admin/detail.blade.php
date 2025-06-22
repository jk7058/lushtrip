@extends('admin.layouts.app')
@section('content')
<form action="{{route('flight.admin.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])}}"
    method="post">
    @csrf
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <div class="">
                <h1 class="title-bar">{{$row->id ? __('Edit: ').$row->title : __('Add new flight')}}</h1>

            </div>
            <div class="">
                @if($row->id)
                <a class="btn btn-primary btn-sm"
                    href="{{route('flight.admin.flight.seat.index',['flight_id'=>$row->id])}}" target="_blank"><i
                        class="fa fa-ticket" aria-hidden="true"></i> {{__(" Flight Ticket type")}}</a>
                @endif
            </div>
        </div>
        @include('admin.message')
        <div class="row">
            <div class="col-md-9">
                @include('Flight::admin.flight.form')
                @include('Core::admin/seo-meta/seo-meta')
            </div>
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-title"><strong>{{__('Publish')}}</strong></div>
                    <div class="panel-body">
                        @if(is_default_lang())
                        <div>
                            <label><input @if($row->status=='publish') checked @endif type="radio" name="status"
                                value="publish"> {{__("Publish")}}
                            </label>
                        </div>
                        <div>
                            <label><input @if($row->status=='draft') checked @endif type="radio" name="status"
                                value="draft"> {{__("Draft")}}
                            </label>
                        </div>
                        @endif
                        <div class="text-right">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                {{__('Save Changes')}}</button>
                        </div>
                    </div>
                </div>
                @if(is_default_lang())
                <div class="panel">
                    <div class="panel-title"><strong>{{__("Author Setting")}}</strong></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <?php
                                    $user = !empty($row->create_user) ? App\User::find($row->create_user) : false;
                                    \App\Helpers\AdminForm::select2('create_user', [
                                        'configs' => [
                                            'ajax'        => [
                                                'url' => route('user.admin.getForSelect2'),
                                                'dataType' => 'json'
                                            ],
                                            'allowClear'  => true,
                                            'placeholder' => __('-- Select User --')
                                        ]
                                    ], !empty($user->id) ? [
                                        $user->id,
                                        $user->getDisplayName() . ' (#' . $user->id . ')'
                                    ] : false)
                                    ?>
                        </div>
                    </div>
                </div>
                @include('Tour::admin.tour.attributes')
                @endif
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
<!-- <script src="{{asset('libs/daterange/daterangepicker.min.js?_ver='.config('app.asset_version'))}}"></script> -->
<script src="{{asset('themes/mytravel/js/home.js?_ver=1.3.1.5')}}"></script>
<!-- <script src="{{url('libs/daterange/daterangepicker.min.js?_ver='.config('app.asset_version'))}}"></script>
<link rel="stylesheet" href="{{url('libs/daterange/daterangepicker.css')}}"/> -->
<script>
//     $(document).ready(function () {
//         $('.has-datetimepicker').daterangepicker({
//             singleDatePicker: true,
//             timePicker: true,
//             showCalendar: false,
//             autoUpdateInput: false, //disable default date
//             sameDate: true,
//             autoApply           : true,
//             disabledPast        : true,
//             enableLoading       : true,
//             showEventTooltip    : true,
//             classNotAvailable   : ['disabled', 'off'],
//             disableHightLight: true,
//             timePicker24Hour: true,

//             locale:{
//                 format:'YYYY/MM/DD HH:mm:ss'
//             }
//         }).on('apply.daterangepicker', function (ev, picker) {
//             $(this).val(picker.startDate.format('YYYY/MM/DD HH:mm:ss'));
//         });
//         $(function() {
//         $('#date_range').daterangepicker({
//             locale: {
//                 format: 'YYYY-MM-DD'
//             }
//         });


//         $('#date_range').on('apply.daterangepicker', function(ev, picker) {
//             const startDate = picker.startDate;
//             const endDate = picker.endDate;

//             // Calculate the number of days
//             const daysCount = endDate.diff(startDate, 'days') + 1;

//             // Update the count display
//             $('#count').text(daysCount);

//             console.log(daysCount)

//             // Add new rows
//             for (let date = startDate.clone(); date.isSameOrBefore(endDate); date.add(1, 'days')) {
//                 $('#datesTable tbody').append('<tr><td>' + date.format('YYYY-MM-DD') + '</td></tr>');
//             }


//         });

//         var p = $(this).closest(".form-group-item").find(".g-items");
// var number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
// if (number === undefined) number = 0;else number++;
// var extra_html = $(this).closest(".form-group-item").find(".g-more").html();
// extra_html = extra_html.replace(/__name__=/gi, "name=");
// extra_html = extra_html.replace(/__number__/gi, number);
// p.append(extra_html);

// if (extra_html.indexOf('dungdt-select2-field-lazy') > 0) {
//   p.find('.dungdt-select2-field-lazy').each(function () {
//     var configs = $(this).data('options');
//     $(this).select2(configs);
//   });
// }

//         // Initial setup on page load
//         const startDate = moment().startOf('day');
//         const endDate = moment().endOf('day');
//         $('#count').text(endDate.diff(startDate, 'days') + 1);
//     });
//     })




$(function() {
    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
    const startDate = picker.startDate;
    const endDate = picker.endDate;

    // Calculate the number of days
    const daysCount = endDate.diff(startDate, 'days') + 1;

    // Update the count display
    $('#count').text(daysCount);

    // Clear existing rows
    $('.form-group-item .g-items').empty();

    // Add new rows
    let p = $('.form-group-item .g-items');
    let number = p.find('.item:last-child').data('number');
    if (number === undefined) number = 0;
    else number++;

    for (let date = startDate.clone(); date.isSameOrBefore(endDate); date.add(1, 'days')) {
        // Format date as needed (e.g., 'YYYY-MM-DD')
        const formattedDate = date.format('YYYY-MM-DD');

        // Check visibility of divs and add dynamic row accordingly
    if ($('#withPnr').is(':visible')) {
        let p = $('.form-group-item .g-items-with-pnr');
        let extra_html = $('#withPnr .g-more').html();
        extra_html = extra_html.replace(/__name__=/gi, "name=");
        extra_html = extra_html.replace(/__number__/gi, number);

        p.append(extra_html);

        $('#date_' + number).val(formattedDate).prop('readonly', true);
        $("#delte_btn").css('display', 'block');
    } else if ($('#withoutPnr').is(':visible')) {
        let p = $('.form-group-item .g-items-without-pnr');
        let extra_html = $('#withoutPnr .g-more').html();
        extra_html = extra_html.replace(/__name__=/gi, "name=");
        extra_html = extra_html.replace(/__number__/gi, number);
        p.append(extra_html);
        $('#date_' + number).val(formattedDate).prop('readonly', true);
        $("#delte_btn").css('display', 'block');
    }

        number++;
    }

    });
     // Select all functionality
     $('#select_all').on('change', function() {
        $('.select_row').prop('checked', $(this).prop('checked'));
    });
    function areAnyCheckboxesChecked() {
        return $('.select_row:checked').length > 0;
    }
    $('.select_row').on('change', function() {
    if (areAnyCheckboxesChecked()) {
        $("#delte_btn").css('display','block');
    } else {
        $("#delte_btn").css('display','none');
    }
});
let deletedIds = [];

$('#delete_selected').on('click', function () {
    $('.g-items input.select_row:checked').each(function () {
        const row = $(this).closest('tr');
        const rowIndex = row.data('number');

        // Track deleted PNR (optional)
        const pnrInput = row.find('input[name*="[pnr_number]"]');
        if (pnrInput.length) {
            deletedIds.push(pnrInput.val());
        }

        // Remove name attributes in BOTH views
        $(`tr.item[data-number="${rowIndex}"]`).each(function () {
            $(this).find('input, select, textarea').removeAttr('name');
        });

        // Remove the rows in BOTH views
        $(`tr.item[data-number="${rowIndex}"]`).remove();
    });

    // Optional: pass deleted list to controller
    $('<input>').attr({
        type: 'hidden',
        name: 'deleted_pnrs',
        value: JSON.stringify(deletedIds)
    }).appendTo('form');

    $('#select_all').prop('checked', false);
    $('#delte_btn').hide();
});



    function togglePnrInput() {
            if ($('#showPNR').hasClass('active')) {
                $("#withPnr").css('display', 'block');
                $("#withoutPnr").css('display', 'none');
                // $('.form-group-item .g-items').empty();
                // $("#delte_btn").css('display','none');
            } else {
                $("#withPnr").css('display', 'none');
                $("#withoutPnr").css('display', 'block');
                // $('.form-group-item .g-items').empty();
                // $("#delte_btn").css('display','none');
            }
        }

        // Initial call to set the correct visibility
        togglePnrInput();

        // Event listener for tab change
        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
            togglePnrInput();
        });

});
</script>
@endpush