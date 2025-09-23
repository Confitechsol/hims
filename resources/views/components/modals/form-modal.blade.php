@props([
    'id',              // Modal ID
    'title',           // Modal title
    'method' => 'POST',// HTTP Method
    'action',          // Form action URL
    'fields' => [],    // Array of fields with structure
    'columns' => 1     // Number of columns (default: 1)
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ $action }}" method="POST" id="{{ $id }}-form">
                @csrf
                @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
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
                        @if($field['type'] == 'hidden')
                        <input type="hidden" name="id" value="" data-field="{{ $field['name'] }}"/>
                        @php continue; @endphp
                        @endif
                            <div class="col-md-{{ isset($field['size']) ? $field['size'] : 12 / $columns }}">
                            <div class="mb-3">                                    
                                    <label for="{{ $field['name'] }}" class="form-label">
                                        {{ $field['label'] ?? ucfirst($field['name']) }}
                                        @if (!empty($field['required']))<span class="text-danger">*</span>@endif
                                    </label>
                                    @if ($field['type'] === 'select')
                                        <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select" data-field="{{ $field['name'] }}">
                                            @foreach ($field['options'] ?? [] as $key => $val)
                                                <option value="{{ $key }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-control" data-field="{{ $field['name'] }}"
                                            rows="3">{{ $field['value'] ?? old($field['name']) }}</textarea>
                                    @else
                                        <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" data-field="{{ $field['name'] }}"
                                            id="{{ $field['name'] }}" value="{{ $field['value'] ?? old($field['name']) }}" class="form-control"
                                            @if (!empty($field['required'])) required @endif>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');
        const modal = new bootstrap.Modal(document.getElementById('edit_modal'));
        const form = document.getElementById('edit_modal-form');
        
        // Loop through all edit buttons
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the ID from the button
                // const id = this.getAttribute('data-id');
                
                // Dynamically update the form's action URL to match the ID for the update route
                // form.action = `{{url('/')}}/medicine-group/${id}`;

                // Find all input fields in the form
                const inputFields = form.querySelectorAll('[data-field]');

                // Loop through each input field
                inputFields.forEach(input => {
                    const fieldName = input.getAttribute('data-field');  // Get the field name from data-field attribute
                    const fieldValue = this.getAttribute(`data-${fieldName}`);  // Get corresponding data-* attribute from button
                    
                    // Set the input field's value dynamically
                    if (fieldValue !== null) {
                        input.value = fieldValue;
                    }
                });

                // Show the modal
                modal.show();
            });
        });
    });
</script>
<script>
    // Listen for when *any* modal is hidden
    document.addEventListener('hidden.bs.modal', function (event) {
        // Force remove lingering backdrop if it exists
        const modalBackdrop = document.querySelector('.modal-backdrop');
        if (modalBackdrop) {
            modalBackdrop.remove();
            document.body.classList.remove('modal-open'); // Ensure scrolling is re-enabled
            document.body.style.paddingRight = '';        // Reset padding if set
        }
    });
</script>

    