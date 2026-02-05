{{-- ================= EMPLOYEE MODAL ================= --}}
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 id="modalTitle">➕ Add Employee</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <form id="employeeForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id">

                    <!-- TABS NAV -->
                    <ul class="nav nav-tabs mb-3" id="employeeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" 
                                    data-bs-toggle="tab" data-bs-target="#general"
                                    type="button" role="tab">
                                    <b>
                                     <i class="fa fa-user"></i>
                                     General Information
                                     </b>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" 
                                    data-bs-toggle="tab" data-bs-target="#documents"
                                    type="button" role="tab">
                                    <b>
                                        <i class="fa fa-file"></i> 
                                        Employee Documents
                                    </b>
                            </button>
                        </li>
                    </ul>

                    <!-- TABS CONTENT -->
                    <div class="tab-content" id="employeeTabContent">

                        <!-- GENERAL INFORMATION TAB -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control">
                                    <small class="error-text" data-error="name"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email_id" id="email_id" class="form-control">
                                    <small class="error-text" data-error="email_id"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control">
                                    <small class="error-text" data-error="mobile_no"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Gender <span class="text-danger">*</span></label><br>
                                    <label class="me-3">
                                        <input type="radio" name="gender" value="Male"> Male
                                    </label>
                                    <label>
                                        <input type="radio" name="gender" value="Female"> Female
                                    </label>
                                    <small class="error-text d-block" data-error="gender"></small>
                                </div>
                                <div class="col-md-6 mb-3 position-relative">
                                    <label>Designation <span class="text-danger">*</span></label>
                                    <input type="text" id="designation" name="designation" class="form-control" autocomplete="off">
                                    <div id="designationList" class="list-group position-absolute w-100 d-none" style="z-index:1000;"></div>
                                    <small class="error-text" data-error="designation"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <select name="department[]" id="department" multiple class="form-select">
                                        <option value="Marketing">Marketing</option>
                                        <option value="Finance">Finance</option>
                                        <option value="HR">HR</option>
                                        <option value="Sales">Sales</option>
                                        <option value="IT">IT</option>
                                    </select>
                                    <small class="error-text" data-error="department"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select name="state_id" id="state" class="form-select">
                                        <option value="">Select</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="error-text" data-error="state_id"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>City <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city" class="form-select">
                                        <option value="">Select</option>
                                    </select>
                                    <small class="error-text" data-error="city_id"></small>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control"></textarea>
                                    <small class="error-text" data-error="address"></small>
                                </div>
                            </div>
                        </div>

                        <!-- DOCUMENTS TAB -->
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <div class="mb-3" id="existingDocumentsPreview">
                                <h6>Existing Documents:</h6>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="docTableBody"></tbody>
                                </table>
                            </div>

                            <!-- Upload wrapper (hidden in view-only mode) -->
                            <div id="documentWrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addDocumentRow">
                                ➕ Add Document
                            </button>
                            <small class="error-text d-block mt-2" data-error="documents"></small>

                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-success">
                                    <b><i class="fa fa-check"></i> Submit</b>
                                </button>
                            </div>
                        </div>

                    </div> <!-- END TAB CONTENT -->
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ================= VIEW DOCUMENTS MODAL ================= --}}
<div class="modal fade" id="viewDocumentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">📄 Employee Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Document Type</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody id="viewDocTableBody">
                        <!-- Documents will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
