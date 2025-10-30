@props([
    'id', // Modal ID
    'title', // Modal title
    'method' => 'POST', // HTTP Method
    'action', // Form action URL
    'fields' => [], // Array of fields with structure
    'columns' => 1, // Number of columns (default: 1)
    'repeatable_group' => [],
    'type',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ $action }}" method="POST" id="{{ $id }}-form" enctype="multipart/form-data">
                @csrf
                @if (in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                    @method($method)
                @endif

                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="row">
                        @foreach ($fields as $field)
                            @if ($field['type'] == 'hidden')
                                <input type="hidden" name="id" value="" data-field="{{ $field['name'] }}" />
                                @php continue; @endphp
                            @endif
                            <div class="col-md-{{ isset($field['size']) ? $field['size'] : 12 / $columns }}">
                                <div class="mb-3">
                                    <label for="{{ $field['name'] }}" class="form-label">
                                        {{ $field['label'] ?? ucfirst($field['name']) }}
                                        @if (!empty($field['required']))
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>
                                    @if ($field['type'] === 'select')
                                        <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select" data-field="{{ $field['name'] }}"
                                        @if (!empty($field['required'])) required @endif
                                        >
                                            <option value="">Select {{ $field['label'] }}</option>
                                            @foreach ($field['options'] ?? [] as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-control"
                                            data-field="{{ $field['name'] }}" rows="3">{{ $field['value'] ?? old($field['name']) }}</textarea>
                                    @else
                                        <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}"
                                            data-field="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                            value="{{ $field['value'] ?? old($field['name']) }}" class="form-control"
                                            @if (!empty($field['required'])) required @endif>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        {{-- Repeatable Group --}}
                        @if (!empty($repeatable_group))
                            <div id="repeatable-group-wrapper">
                                <div class="repeatable-group row mb-3">
                                    @foreach ($repeatable_group as $subField)
                                        <div class="col-md-{{ $subField['size'] ?? 6 }}">
                                            <label class="form-label">
                                                {{ $subField['label'] ?? ucfirst($subField['name']) }}
                                                @if (!empty($subField['required']))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            @if ($subField['type'] === 'select')
                                                <select name="{{ $subField['name'] }}[0]" class="form-select"
                                                    data-name="{{ $subField['name'] }}"
                                                    @if (!empty($subField['required'])) required @endif>
                                                    @foreach ($subField['options'] ?? [] as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="{{ $subField['type'] ?? 'text' }}"
                                                    name="{{ $subField['name'] }}[0]" class="form-control"
                                                    data-name="{{ $subField['name'] }}"
                                                    @if (!empty($subField['required'])) required @endif>
                                            @endif
                                        </div>
                                    @endforeach
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-group">
                                            <i class="bi bi-x-lg"></i> {{-- Optional icon (Bootstrap Icons) --}}
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-success"
                                        id="add-repeatable-group">Add</button>
                                </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-primary">{{ strtoupper($method) === 'PUT' ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@if (isset($type) && $type == 'edit')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            const modal = new bootstrap.Modal(document.getElementById('edit_modal'));
            const form = document.getElementById('edit_modal-form');

            // Loop through all edit buttons
            // editButtons.forEach(button => {
            //     button.addEventListener('click', function() {
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.edit-btn');
            if (!button) return;
                    // Get the ID from the button
                    // const id = this.getAttribute('data-id');

                    // Dynamically update the form's action URL to match the ID for the update route
                    // form.action = `{{ url('/') }}/medicine-group/${id}`;

                    // Find all input fields in the form
                    const inputFields = form.querySelectorAll('[data-field]');

                    // Loop through each input field
                    inputFields.forEach(input => {
                        const fieldName = input.getAttribute('data-field'); // Get the field name from data-field attribute
                        // const fieldValue = this.getAttribute(`data-${fieldName}`); // Get corresponding data-* attribute from button
                        const fieldValue = button.getAttribute(`data-${fieldName}`);

                        // Set the input field's value dynamically
                        if(input.tagName === 'SELECT') {
                            $(input).select2(); 
                            $(input).val(fieldValue).trigger('change.select2');
                        } else{
                            if (fieldValue !== null) {
                            input.value = fieldValue;
                        }
                        }
                    });

                    // Show the modal
                    modal.show();
                });
            // });
        });
    </script>
@endif
<script>
    // Listen for when *any* modal is hidden
    document.addEventListener('hidden.bs.modal', function(event) {
        // Force remove lingering backdrop if it exists
        const modalBackdrop = document.querySelector('.modal-backdrop');
        if (modalBackdrop) {
            modalBackdrop.remove();
            document.body.classList.remove('modal-open'); // Ensure scrolling is re-enabled
            document.body.style.paddingRight = ''; // Reset padding if set
        }
    });
</script>
@if (!empty($repeatable_group))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('add-repeatable-group');
            const wrapper = document.getElementById('repeatable-group-wrapper');

            if (addBtn && wrapper) {
                let index = 1;

                addBtn.addEventListener('click', () => {
                    const group = wrapper.querySelector('.repeatable-group');
                    const clone = group.cloneNode(true);

                    // Clear inputs and update names with new index
                    clone.querySelectorAll('input, select').forEach(el => {
                        // input.value = '';
                        // const baseName = input.getAttribute('data-name');
                        // input.name = `${baseName}[${index}]`;
                        const baseName = el.getAttribute('data-name');
                        if (!baseName) return;

                        el.name = `${baseName}[${index}]`;

                        if (el.tagName === 'SELECT') {
                            el.selectedIndex = 0; // Reset to first option
                        } else {
                            el.value = '';
                        }
                    });

                    wrapper.appendChild(clone);
                    index++;
                });
                wrapper.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-group')) {
                        const group = e.target.closest('.repeatable-group');
                        if (wrapper.querySelectorAll('.repeatable-group').length > 1) {
                            group.remove();
                        }
                    }
                });
            }
        });
    </script>
@endif
