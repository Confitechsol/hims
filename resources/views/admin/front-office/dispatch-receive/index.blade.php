@extends('layouts.adminLayout')
@section('content')

<div class="row px-5 py-4">
    <div class="col-12 d-flex">
        <div class="card shadow-sm flex-fill w-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="ti ti-mail me-2"></i>{{ $type }} List</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <a href="{{ route('visitors') }}" class="btn btn-secondary text-white fs-13 btn-md"><i class="ti ti-arrow-left me-1"></i>Back to Visitors</a>
                        <button class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#createModal">Add {{ $type }}</button>
                    </div>
                    <div>
                        <!-- export/copy buttons could go here -->
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>To Title</th>
                                <th>Reference No</th>
                                <th>From Title</th>
                              
                                <th>Date</th>
                                
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->to_title }}</td>
                                    <td>{{ $item->reference_no }}</td>
                                    <td>{{ $item->from_title}}</td>
                                    <td>{{ optional($item->date)->format('d-m-Y') ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <button
                                                class="fs-18 p-1 btn btn-icon btn-sm btn-soft-success me-2 edit-btn"
                                                data-id="{{ $item->id }}"
                                                data-reference_no="{{ $item->reference_no }}"
                                                data-from_title="{{ $item->from_title }}"
                                                data-to_title="{{ $item->to_title }}"
                                                data-address="{{ $item->address }}"
                                                data-note="{{ $item->note }}"
                                                data-date="{{ optional($item->date)->format('Y-m-d') ?? '' }}"
                                                data-type="{{ $item->type }}"
                                                title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </button>
                                            <form method="POST" action="{{ route('dispatch.destroy', $item->id) }}" class="ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="submit" class="fs-18 p-1 btn btn-icon btn-sm btn-soft-danger rounded-pill" title="Delete"><i class="ti ti-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->links() }}
                </div>

                <!-- Create Modal -->
                <x-modals.form-modal type="add" id="createModal" title="Add {{ $type }}" action="{{ route('dispatch.store') }}" :fields="[
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
                ]" :columns="2" />

                <!-- Edit Modal -->
                <x-modals.form-modal method="put" type="edit" id="edit_modal" title="Edit {{ $type }}" action="{{ url('/dispatch-receive/update') }}" :fields="[
                    ['name' => 'id', 'type' => 'hidden', 'required' => true],
                    ['name' => 'type', 'label' => 'Type', 'type' => 'select', 'options' => ['receive' => 'Receive', 'dispatch' => 'Dispatch'], 'required' => true, 'size' => '6'],
                    ['name' => 'reference_no', 'label' => 'Reference No', 'type' => 'text', 'required' => true, 'size' => '6'],
                    ['name' => 'from_title', 'label' => 'From Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'to_title', 'label' => 'To Title', 'type' => 'text', 'required' => false, 'size' => '6'],
                    ['name' => 'address', 'label' => 'Address', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'note', 'label' => 'Note', 'type' => 'textarea', 'required' => false, 'size' => '12'],
                    ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => false, 'size' => '6'],
                    ['name' => 'image', 'label' => 'Attach Image', 'type' => 'file', 'required' => false, 'size' => '6'],
                ]" :columns="2" />

                @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.querySelectorAll('.edit-btn').forEach(function (btn) {
                            btn.addEventListener('click', function () {
                                var id = this.dataset.id;
                                var setValue = function (name, value) {
                                    var el = document.querySelector('#edit_modal [name="' + name + '"]');
                                    if (!el) return;
                                    if (el.tagName === 'SELECT') el.value = value;
                                    else if (el.type === 'file') return;
                                    else el.value = value;
                                };

                                setValue('id', id);
                                setValue('reference_no', this.dataset.reference_no || '');
                                setValue('from_title', this.dataset.from_title || '');
                                setValue('to_title', this.dataset.to_title || '');
                                setValue('address', this.dataset.address || '');
                                setValue('note', this.dataset.note || '');
                                setValue('date', this.dataset.date || '');
                                setValue('type', this.dataset.type || 'receive');

                                var actionUrl = '{{ url('/dispatch-receive/update') }}';
                                var modalForm = document.querySelector('#edit_modal form');
                                if (modalForm) {
                                    modalForm.action = actionUrl + '/' + id;
                                }

                                var modal = new bootstrap.Modal(document.getElementById('edit_modal'));
                                modal.show();
                            });
                        });
                    });
                </script>
                @endpush

            </div>
        </div>
    </div>
</div>

@endsection
