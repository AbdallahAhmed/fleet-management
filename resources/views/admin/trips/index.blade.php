@extends('admin.layouts.app')
@section('title','Trips')
@section('content')
    @push('styles')
        <style>
            tbody tr {
                cursor: pointer;
            }
        </style>
    @endpush
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <a href="{{route('trips.create')}}" class="btn btn-primary">Add New Trip</a>
                </div>
            </div>
            @if(count($trips))
                <form action="{{route('trips.index')}}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group" id="source">
                                <label class="bmd-label-floating">Source</label>
                                <select type="select" name="source" class="form-control">
                                    <option @if(!$source ) selected @endif></option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}"
                                                @if($source == $city->id) selected @endif >{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="destination">
                                <label class="bmd-label-floating">Destination</label>
                                <select type="select" name="destination" class="form-control">
                                    <option @if(!$destination ) selected @endif></option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}"
                                                @if($destination == $city->id) selected @endif >{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--<div class="col-md-3">
                            <div class="form-group">
                                <label class="bmd-label-floating">Date</label>
                                <div class="date-picker" data-date-format="dd-mm-yyyy"
                                     data-date-start-date="+0d">
                                    <input class="form-control" name="date" value="2000-2-2">
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-md-3">
                            <button class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
                @include('admin.layouts.messages')
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Trips</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="">
                                        <th>ID</th>
                                        <th>Source</th>
                                        <th>Destination</th>
                                        <th>Date</th>
                                        <th>Bookings</th>
                                        </thead>
                                        <tbody>
                                        @foreach($trips as $trip)
                                            <tr data-href="{{route('trips.show', $trip->id)}}">
                                                <td>{{$trip->id}}</td>
                                                <td>{{$trip->source->name}}</td>
                                                <td>{{$trip->destination->name}}</td>
                                                <td>{{$trip->date_to_book}}</td>
                                                <td>{{$trip->bookings->count()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">No Trips</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
            });
            $('tr').on('click', function () {
                if ($(this).data('href'))
                    window.location = $(this).data('href');
            })
        });
    </script>
@endpush