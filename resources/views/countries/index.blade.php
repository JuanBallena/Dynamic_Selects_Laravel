@extends('layout')
@section('content')

<h3>Countries</h3>

<div class="row">
  <div class="col-xl-5 col-lg-6 col-sm-6">
    <h4>Country form</h4>

    <form method="POST" action="{{ route('countries.store') }}" id="country-form">
      @csrf
      <div class="row">
        <div class="col-xl-12 form-group">
          <input class="form-control" type="text" name="name">
        </div>
        <div class="col-xl-12 form-group">
          <button type="submit" class="btn btn-success btn-block">
            ADD
          </button>
        </div>
      </div>
    </form>

  </div>
  <div class="col-xl-3 col-lg-6 col-sm-6 scroll">
    <h4>Countries List</h4>
    <ul id="country-list" data-route="{{ route('countries.update.status')}}">
      @foreach($countries as $country)
        <li class="py-1">{{ $country->name }} 
          <i 
            data-id="{{ $country->id_country }}"
            class="float-right fas
            {{ $country->status_visible == 1
              ? 'fa-home text-success' : 'fa-user text-primary' }}">
          </i>
        </li>
      @endforeach
    </ul>
  </div>
</div>


@endSection