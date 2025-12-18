<div class="d-flex justify-content-between w-100">
    <div class="text-end d-flex mb-3sta">
        <a href="javascript:void(0);" class="btn btn-primary text-white fs-13 btn-md" data-bs-toggle="modal"
            data-bs-target="#createModal"><i class="ti ti-plus me-1"></i>Add {{$name}}</a>
            @if($name === 'Birth Record' || $name === 'Death Record')
        <a href="{{ route('importbirth') }}" class="btn btn-primary text-white fs-13 btn-md ms-2" data-bs-toggle="modal"
            data-bs-target="">Import {{$name}}</a>
        @endif
          
        @if($name === 'Visitor')
        <div class="btn-group ms-2">
            <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-mail me-1"></i>Postal
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dispatch.receive') }}">Receive</a></li>
                <li><a class="dropdown-item" href="{{ route('dispatch.dispatch') }}">Dispatch</a></li>
            </ul>
        </div>
        <a href="{{ route('phone-call-log') }}" class="btn btn-secondary text-white fs-13 btn-md ms-2"><i class="ti ti-phone me-1"></i>Phone Call Log</a>
        @endif
    </div>
    <div class="mb-3">
        <button class="btn btn-primary copy-btn" data-clipboard-target="#{{$id}}">Copy</button>
        <button class="btn btn-success" onclick="exportToExcel('{{$id}}')">Export to Excel</button>
        <button class="btn btn-info" onclick="exportToCSV('{{$id}}')">Export to CSV</button>
        <button class="btn btn-danger" onclick="exportToPDF('{{$id}}')">Export to PDF</button>
        <button class="btn btn-warning" onclick="printTable('{{$id}}')">Print</button>
    </div>
</div>
</div>
<div class="input-icon-start position-relative mb-3">
    <span class="input-icon-addon">
        <i class="ti ti-search"></i>
    </span>
    <input type="text" class="form-control shadow-sm" placeholder="Search" id="search-input"
        {{-- onkeyup="searchTable(this, '{{$id}}')" --}}
        >

</div>