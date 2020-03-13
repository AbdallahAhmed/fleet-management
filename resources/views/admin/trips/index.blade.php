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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('tr').on('click', function () {
                window.location = $(this).data('href');
            })
        })
    </script>
@endpush