@extends('layout')
@section('content')

<a href="{{ route('jobs.index') }}">
  <h4 class="title">Jobs</h4>
  <a href="{{ route('jobs.create') }}" class="btn btn-info btn-sm">Create Job</a>
</a>
<hr>
<div class="card">
  <div class="card-header pb-0">
    <h6><i class="fas fa-search"></i> Search</h6>
    <form action="{{ route('jobs.redirectSearch') }}" method="POST" id="style-search-form">
      @csrf
      <div class="row">
        <div class="col-xl-4 form-group">
          <label for="input1">Title</label>
          <input 
            type="text" 
            name="input_title"
            class="form-control js-input-title"
            value=""
            placeholder="Title, description">
        </div>
        <div class="col-xl-4 form-group">
          <label for="input2">Place</label>
          <input 
            type="text" 
            name="input_place" 
            class="form-control js-input-place"
            value=""
            id="autocomplete"
            value="{{ old('input_place') }}"
            placeholder="Country, State, City">
        </div>
        <div class="col-xl-4 form-group">
          <label for="button">*</label>
          <br>
          {{-- <button type="submit" class="btn btn-success js-input-search">search</button> --}}
          <button type="submit" class="btn btn-success">search</button>
        </div>
      </div>
    </form>
  </div>
</div>
<br>

<h6>Work list</h6>
<table class="table table-bordered">
  <thead class="bg-dark text-white">
    <tr>
      <th>Title</th>
      <th>Description</th>
      <th>Country</th>
      <th>State</th>
      <th>City</th>
    </tr>
  </thead>
  <tbody>
    @foreach($jobs as $job)
      <tr>
        <td>{{ $job->title }}</td>
        <td>{{ $job->description }}</td>
        <td>{{ $job->country->name }}</td>
        <td>{{ $job->state->name }}</td>
        <td>{{ $job->city->name }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
<div>
  {{ $jobs->links("pagination::bootstrap-4") }}
</div>

@endSection