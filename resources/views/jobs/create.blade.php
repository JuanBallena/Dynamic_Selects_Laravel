@extends('layout')
@section('content')

<h1>Create Job</h1>

<form action="{{ route('jobs.store') }}" method="POST">
  @csrf
  <div class="row">
    <div class="col-xl-7 form-group">
      <label for="">Title</label>
      <input type="text" name="title" class="form-control" value="{{ old('title') }}">
      {!! $errors->first('title', '<span class="text-danger">:message</span>') !!}
    </div>
    
    <div class="col-xl-7 form-group">
      <label for="">Description</label>
      <input type="text" name="description" class="form-control" value="{{ old('description')}}">
      {!! $errors->first('description', '<span class="text-danger">:message</span>') !!}
    </div>

    <div class="col-xl-7 form-group">
      <label for="">Country</label>
      <select class="form-control" name="id_country" id="js-select-countries">
        <option value="" selected disabled>Seleccione</option>
        @foreach($countries as $country)
          <option value="{{ $country->id_country }}">{{ $country->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-xl-7 form-group">
      <label for="">State</label>
      <select class="form-control" name="id_state" id="js-select-states">
        <option selected disabled>Seleccione</option>
      </select>
    </div>

    <div class="col-xl-7 form-group">
      <label for="">City</label>
      <select class="form-control" name="id_city" id="js-select-cities">
        <option selected disabled>Seleccione</option>
      </select>
    </div>

    <div class="col-xl-7 form-group">
      <button type="submit" class="btn btn-success">Send data</button>
    </div>

    {{-- <div class="col-xl-7 form-group">
      <label for="">TEST</label>
      <select class="form-control" name="id_country">
        <option value="" selected disabled>Seleccione</option>
        @foreach($countries as $country)
          <option value="{{ $country->id_country }}"
            {{ old('id_country') == $country->id_country
              ? 'selected'
              : '' }}
          >
            {{ $country->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="col-xl-7 form-group">
      <label for="">State</label>
      <select class="form-control" name="id_state">
        <option value="" selected disabled>Seleccione</option>
        @if($states)
          @foreach($states as $state)
            <option value="{{ $state->id_state }}"
              {{ old('id_state') == $state->id_state 
                ? 'selected'
                : '' }}
            >
              {{ $state->name }}
            </option>
          @endforeach
        @endif
      </select>
    </div> --}}
  </div>
</form>

@endSection