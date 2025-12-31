@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-mail me-2"></i>Dispatch / Receive Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table">
                            @foreach($columns as $col)
                                <tr>
                                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                    <td>
                                        @if($col === 'image')
                                            @if(!empty($item->image))
                                                <img src="{{ asset(ltrim($item->image, '/')) }}" class="img-fluid img-thumbnail" style="max-width:200px;" alt="Attachment">
                                            @else
                                                <span class="text-muted">No image attached</span>
                                            @endif
                                        @elseif(in_array($col, ['date', 'created_at', 'updated_at']))
                                            {{ optional($item->$col)->format(in_array($col, ['created_at','updated_at']) ? 'd-m-Y H:i' : 'd-m-Y') ?? '-' }}
                                        @else
                                            {{ $item->$col ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h6>Attachment</h6>
                        @if($item->image)
                            <img src="{{ asset(ltrim($item->image, '/')) }}" class="img-fluid img-thumbnail" alt="Attachment">
                        @else
                            <p class="text-muted">No image attached</p>
                        @endif
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
