@extends('web.supplier.layout')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-lg-12">

                {!! Form::open(['method'=>'POST','route'=>'supplier.inventory.store']) !!}

                    <div class="row col-lg-12">
                        @include('web.partials.show_errors')
                    </div>
                    <div class="col-lg-12">

                        <h1>Inventory</h1>
                        @foreach($equipmentTypes as $equipmentType)
                            @if($equipmentType->equipments->count()>0)
                                <div class="row">
                                    <div class="col-lg-12">

                                        <h4>{{$equipmentType->name}}</h4>
                                        <table class="table">
                                            <thead>
                                            <tr class="d-flex">
                                                <th class="col-1">#</th>
                                                <th class="col-9">Name</th>
                                                <th class="col-2">Details</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($equipmentType->equipments as $equipment)
                                                <tr class="d-flex">
                                                    <td class="col-sm-1">
                                                        @if(in_array($equipment->id,$equipmentIds))
                                                            <input checked  type="checkbox" class="form-control" name="equipment[]" value="{{$equipment->id}}">
                                                        @else
                                                            <input  type="checkbox" class="form-control" name="equipment[]" value="{{$equipment->id}}">
                                                        @endif
                                                    </td>
                                                    <td class="col-sm-9">{{$equipment->name}}</td>
                                                    <td class="col-sm-2"><a href="{{route('supplier.equipment.show',$equipment->id)}}" class="btn btn-primary btn-sm">View</a></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>

                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary">{{__("Submit")}}</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@endsection
