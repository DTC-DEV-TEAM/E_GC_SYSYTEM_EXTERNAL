<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')
  <!-- Your html goes here -->
  <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Redeem Code Home</a></p>
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Form</div>
    <div class='panel-body'>      
        <div class='form-group'>
          <table id='table-detail' class='table table-striped'>
          <tr>
            <td class="label-bold" style="width: 25%;">Name</td>
            <td>{{ $row->name }}</td>
          </tr>
          <tr>
            <td class="label-bold">Phone</td>
            <td>{{ $row->phone }}</td>
          </tr>
          <tr>
            <td class="label-bold">Email</td>
            <td><a href='mailto:hills.velda@schiller.com' target="_blank">{{ $row->email }}</a></td>
          </tr>
          <tr>
            <td class="label-bold">Campaign ID</td>
            <td>{{ $row->campaign_id }}</td>
          </tr>
          <tr>
            <td class="label-bold">Campaign Description</td>
            <td>{{ $row->gc_description }}</td>
          </tr>
          <tr>
            <td class="label-bold">GC value</td>
            <td>{{ $row->gc_value }}</td>
          </tr>
          <tr>
            <td class="label-bold">Batch Group</td>
            <td>{{ $row->batch_group }}</td>
          </tr>
          <tr>
            <td class="label-bold">Status</td>
            @if ($row->status)
            <td>CLAIMED</td>
            @else
            <td>UNCLAIMED</td>
            @endif
          </tr>
          </table>
        </div>
                
      </form>
    </div>
  </div>
@endsection