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
                        <div class="card-body">
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
                                    @foreach($trip->bookings as $booking)
                                        <tr data-href="{{route('trips.show', $trip->id)}}">
                                            <td>{{$booking->id}}</td>
                                            <td>{{$booking->source->name}}</td>
                                            <td>{{$booking->destination->name}}</td>
                                            <td>{{$booking->seat_no}}</td>
                                            <td>{{$booking->user->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
