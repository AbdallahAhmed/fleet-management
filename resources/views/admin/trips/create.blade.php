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
                            <h4 class="card-title">Add New Trip</h4>
                            <p class="card-category">Complete your profile</p>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{route('trips.create')}}">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="source">
                                            <label class="bmd-label-floating">Source</label>
                                            <select type="select" name="source" class="form-control">
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="destination">
                                            <label class="bmd-label-floating">Destination</label>
                                            <select type="select" name="destination" class="form-control">
                                                @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Date</label>
                                            <div class="date-picker" data-date-format="dd-mm-yyyy"
                                                 data-date-start-date="+0d">
                                                <input class="form-control" name="date">
                                                <span class="input-group-btn"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary pull-right">Add Trip</button>
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
            let source, destination;
            $('.date-picker').datepicker({
                format: 'dd-mm-yyyy',
            });
            $('form').on('submit', function () {
                source = $('[name="source"]').val();
                destination = $('[name="destination"]').val();
                return validate();
            })
            function validate() {
                console.log(source,destination)
                if (source > destination) {
                    $('#source').addClass('alert-danger');
                    $('#destination').addClass('alert-danger');
                    alert("You cannot choose a source behind the destination");
                    return false;
                }
                else if (source === destination) {
                    $('#source').addClass('alert-danger');
                    $('#destination').addClass('alert-danger');
                    alert("You cannot choose the same source and destination");
                    return false;
                }
                $('#source').removeClass('alert-danger');
                $('#destination').removeClass('alert-danger');
                return true;
            }
        })
    </script>
@endpush