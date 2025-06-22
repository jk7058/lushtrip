<div class="form-group-item">
    <br>
     <div class="text-left" id="delte_btn" style="display:none;">
            <span class="btn btn-danger btn-sm btn-remove-item" id="delete_selected"><i class="fa fa-trash"></i></span>
    </div>
    <br>
    <div class="table-responsive">
        
    <!-- <div id"withPnr"> -->
    <div id="withPnr" class="tab-pane fade show active">
    <!-- <div class="g-items-header"> -->
    <table class="table g-items-header">
            <thead>
                <tr>
                    <th style="min-width: 50px;">
                        <input type="checkbox" class="check-item select_row" id="select_all" value="{{$row->id}}">
                    </th>
                    <th style="min-width: 150px;">{{__("PNR")}}</th>
                    <th style="min-width: 150px;">{{__("Date")}}</th>
                    <th style="min-width: 150px;">{{__("Seat Type")}}</th>
                    <th style="min-width: 150px;">{{__("Price")}}</th>
                    <th style="min-width: 150px;">{{__("Max Passengers")}}</th>
                    <th style="min-width: 150px;">{{__("Person Type")}}</th>
                    <th style="min-width: 150px;">{{__("Baggage Check in")}}</th>
                    <th style="min-width: 150px;">{{__("Baggage Cabin")}}</th>
                </tr>
            </thead>
</table>
    <!-- </div> -->
    <table class="table">
    <!-- <div class="g-items g-items-with-pnr"> -->
    <tbody class="g-items g-items-with-pnr">
                @if(!empty($rowFlight))
                    @foreach($rowFlight as $key => $seats)
                        <tr class="item" data-number="{{$key}}">
                            <td style="min-width: 50px;">
                                <input type="checkbox" class="check-item select_row" value="{{$row->id}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][pnr_number]" value="{{$seats->pnr_number ?? ""}}" class="form-control" placeholder="{{__('PNR Number')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][date]" class="form-control" value="{{$seats->date ?? ""}}" placeholder="{{__('Date')}}" readonly>
                            </td>
                            <td style="min-width: 150px;">
                                <select class="form-control" id="seatType" name="seats[{{$key}}][seat_type]">
                                    @foreach($seatType as $id => $seatValue)
                                        <option value="{{ $seatValue->code }}" @if(isset($seats->seat_type) && $seats->seat_type == $seatValue->code) selected @endif>{{ $seatValue->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][price]" class="form-control" value="{{$seats->price ?? ""}}" placeholder="{{__('Enter Price')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][max_passengers]" value="{{$seats->max_passengers ?? ""}}" class="form-control" placeholder="{{__('Max Passengers')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <select name="seats[{{$key}}][person]" class="form-control" id="">
                                    <option @if($seats->person=='adult') selected @endif value="adult">{{__('Adult')}}</option>
                                    <option @if($seats->person=='child') selected @endif value="child">{{__('Child')}}</option>
                                </select>
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][baggage_in]" class="form-control" value="{{$seats->baggage_check_in ?? ""}}" placeholder="{{__('Enter Baggae In')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][baggage_cabin]" class="form-control" value="{{$seats->baggage_cabin ?? ""}}" placeholder="{{__('Enter Bagage Cabin')}}">
                            </td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tbody class="g-more hide">
                <tr class="item" data-number="__number__">
                    <td style="min-width: 50px;">
                        <input type="checkbox" class="check-item select_row" value="{{$row->id}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][pnr_number]" class="form-control" placeholder="{{__('PNR Number')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][date]" id="date___number__" class="form-control" placeholder="{{__('Date')}}" readonly>
                    </td>
                    <td style="min-width: 150px;">
                        <select class="form-control" __name__="seats[__number__][seat_type]">
                            @foreach($seatType as $id => $seatValue)
                                <option value="{{ $seatValue->code }}">{{ $seatValue->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][price]" class="form-control" placeholder="{{__('Price')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][max_passengers]" class="form-control" placeholder="{{__('Max Passengers')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <select __name__="seats[__number__][person]" class="form-control" id="">
                            <option value="adult">{{__('Adult')}}</option>
                            <option value="child">{{__('Child')}}</option>
                        </select>
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][baggage_in]" class="form-control" placeholder="{{__('Baggage Check in')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][baggage_cabin]" class="form-control" placeholder="{{__('Bagage Cabin')}}">
                    </td>
            
                </tr>
            </tbody>
</table>
    <!-- </div> -->
   
</div>




<div id="withoutPnr" class="tab-pane fade">
    <!-- <div class="g-items-header"> -->
    <table class="table g-items-header">
            <thead>
                <tr>
                    <th style="min-width: 50px;">
                        <input type="checkbox" class="check-item select_row" id="select_all" value="{{$row->id}}">
                    </th>
                    <th style="min-width: 150px;">{{__("Date")}}</th>
                    <th style="min-width: 150px;">{{__("Seat Type")}}</th>
                    <th style="min-width: 150px;">{{__("Price")}}</th>
                    <th style="min-width: 150px;">{{__("Max Passengers")}}</th>
                    <th style="min-width: 150px;">{{__("Person Type")}}</th>
                    <th style="min-width: 150px;">{{__("Baggage Check in")}}</th>
                    <th style="min-width: 150px;">{{__("Baggage Cabin")}}</th>
                </tr>
            </thead>
</table>
    <!-- </div> -->
    <table class="table">
    <!-- <div class="g-items g-items-with-pnr"> -->
    <tbody class="g-items g-items-without-pnr">
                @if(!empty($rowFlight))
                    @foreach($rowFlight as $key => $seats)
                        <tr class="item" data-number="{{$key}}">
                            <td style="min-width: 50px;">
                                <input type="checkbox" class="check-item select_row" value="{{$row->id}}">
                            </td>
                         
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][date]" class="form-control" value="{{$seats->date ?? ""}}" placeholder="{{__('Date')}}" readonly>
                            </td>
                            <td style="min-width: 150px;">
                                <select class="form-control" id="seatType" name="seats[{{$key}}][seat_type]">
                                    @foreach($seatType as $id => $seatValue)
                                        <option value="{{ $seatValue->code }}" @if(isset($seats->seat_type) && $seats->seat_type == $seatValue->code) selected @endif>{{ $seatValue->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][price]" class="form-control" value="{{$seats->price ?? ""}}" placeholder="{{__('Enter Price')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][max_passengers]" value="{{$seats->max_passengers ?? ""}}" class="form-control" placeholder="{{__('Max Passengers')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <select name="seats[{{$key}}][person]" class="form-control" id="">
                                    <option @if($seats->person=='adult') selected @endif value="adult">{{__('Adult')}}</option>
                                    <option @if($seats->person=='child') selected @endif value="child">{{__('Child')}}</option>
                                </select>
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][baggage_in]" class="form-control" value="{{$seats->baggage_check_in ?? ""}}" placeholder="{{__('Enter Baggae In')}}">
                            </td>
                            <td style="min-width: 150px;">
                                <input type="text" name="seats[{{$key}}][baggage_cabin]" class="form-control" value="{{$seats->baggage_cabin ?? ""}}" placeholder="{{__('Enter Bagage Cabin')}}">
                            </td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tbody class="g-more hide">
                <tr class="item" data-number="__number__">
                    <td style="min-width: 50px;">
                        <input type="checkbox" class="check-item select_row" value="{{$row->id}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][date]" id="date___number__" class="form-control" placeholder="{{__('Date')}}" readonly>
                    </td>
                    <td style="min-width: 150px;">
                        <select class="form-control" __name__="seats[__number__][seat_type]">
                            @foreach($seatType as $id => $seatValue)
                                <option value="{{ $seatValue->code }}">{{ $seatValue->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][price]" class="form-control" placeholder="{{__('Price')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][max_passangers]" class="form-control" placeholder="{{__('Max Passengers')}}">
                    </td>
                    <td style="min-width: 150px;">
                        <select __name__="seats[__number__][person]" class="form-control" id="">
                            <option value="adult">{{__('Adult')}}</option>
                            <option value="child">{{__('Child')}}</option>
                        </select>
                    </td>
                    <td style="min-width: 150px;">
                        <input type="text" __name__="seats[__number__][baggage_in]" class="form-control" placeholder="{{__('Baggage Check in')}}">
                    </td>
            
                </tr>
            </tbody>
</table>
    <!-- </div> -->
   
</div>

</div>
</div>