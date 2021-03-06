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
                @if($status)
                    <div class="col-md-2">
                        <a href="{{route('trips.book', $trip->id)}}" class="btn btn-success">Book Seat</a>
                    </div>
                @endif
            </div>
            @include('admin.layouts.messages')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">{{$trip->source->name}} -- {{$trip->destination->name}}</h4>
                            <p class="card-category">
                                @php
                                    $cities = $trip->cities;
                                @endphp
                                @foreach($cities as  $key => $city)
                                    {{$city->name}} {{$key < count($cities) -1 ? '->' : ''}}
                                @endforeach
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if(count($bookings))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="">
                                        <th>ID</th>
                                        <th>Source</th>
                                        <th>Destination</th>
                                        <th>Seat #</th>
                                        <th>User</th>
                                        </thead>
                                        <tbody>
                                        @foreach($bookings as $key => $booking)
                                            <tr data-href="{{route('trips.show', $trip->id)}}">
                                                <td>{{$key+1}}</td>
                                                <td>{{$booking->source->name}}</td>
                                                <td>{{$booking->destination->name}}</td>
                                                <td>{{$booking->seat_no}}</td>
                                                <td>{{$booking->user->name}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="card">
                                    <div class="card-header card-header-primary">
                                        <h4 class="card-title ">No Bookings</h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
