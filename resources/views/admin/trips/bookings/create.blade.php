@extends('admin.layouts.app')
@section('title','Trips')
@section('content')
    <div class="content">
        <div class="container-fluid justify-content-center align-items-center">
            @include('admin.layouts.messages')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Book A Seat</h4>
                            <p class="card-category">
                                @foreach($cities as  $key => $city)
                                    {{$city->name}} {{$key < count($cities) -1 ? '->' : ''}}
                                @endforeach
                            </p>
                        </div>
                        <div class="card-body">
                            <form id="form" method="POST" action="{{route('trips.book.store', $trip->id)}}">
                                {{csrf_field()}}
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group" id="user">
                                            <label class="bmd-label-floating">User</label>
                                            <select type="select" name="user" class="form-control">
                                                <option></option>
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->email}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="source">
                                            <label class="bmd-label-floating">Source</label>
                                            <select type="select" name="source" class="form-control">
                                                @foreach($cities as $key => $city)
                                                   @if($key != count($cities)-1) <option value="{{$city->id}}">{{$city->name}}</option> @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="destination">
                                            <label class="bmd-label-floating">Destination</label>
                                            <select type="select" name="destination" class="form-control">
                                                @foreach($cities as $key => $city)
                                                   @if($key != 0) <option value="{{$city->id}}">{{$city->name}}</option> @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="error">

                                </div>
                                <button type="button" id="submit" class="btn btn-primary pull-right">Book</button>
                                <button type="submit" id="done" class="btn btn-primary pull-right d-none">Book</button>
                                <div class="clearfix"></div>
                            </form>
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
            let source, destination, user;
            $('#submit').on('click', function (e) {
                $('#error').html('');
                source = +$('[name="source"]').val();
                destination = +$('[name="destination"]').val();
                user = +$('[name="user"]').val();
                if (validate()) {
                    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
                    $.ajax({
                        url: '{{route('trips.check',$trip->id)}}',
                        type: 'POST',
                        data: {
                            source: source,
                            destination: destination,
                            user: user
                        }
                    }).done(function (response) {
                        if (response.available) {
                            $('#done').trigger('click')
                        } else {
                            e.preventDefault();
                            $('#error').append('<div class="row justify-content-center">\n' +
                                '                                    <div class="col-md-4">\n' +
                                '                                        <div class="alert alert-danger text-center">\n' +
                                '                                            <h4>No Available Seats</h4>\n' +
                                '                                        </div>\n' +
                                '                                    </div>\n' +
                                '                                </div>');
                            return false
                        }
                    }).fail(function (xhr, status, errorThrown) {
                        e.preventDefault();
                        alert('alert their is an error in the request');
                        return false;
                    });
                } else{
                    e.preventDefault();
                    return false;
                }
            });

            function validate() {
                $('#user').removeClass('alert-danger');
                $('#source').removeClass('alert-danger');
                $('#destination').removeClass('alert-danger');
                if (!user) {
                    $('#user').addClass('alert-danger');
                    alert("You must choose a user to book for");
                    return false;
                }
                if (source > destination) {
                    $('#source').addClass('alert-danger');
                    $('#destination').addClass('alert-danger');
                    alert("You cannot choose a destination behind the source");
                    return false;
                }
                else if (source === destination) {
                    $('#source').addClass('alert-danger');
                    $('#destination').addClass('alert-danger');
                    alert("You cannot choose the same source and destination");
                    return false;
                }
                return true;
            }
        })
    </script>
@endpush