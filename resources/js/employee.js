let departmentChoices;

$(function () {

    const csrf = $('meta[name="csrf-token"]').attr('content');

    // Set CSRF token globally for all AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrf }
    });

    const employeeModalEl = document.getElementById('employeeModal');
    const employeeModal = new bootstrap.Modal(employeeModalEl, {backdrop: 'static', keyboard: false});

    /* ================= DEPARTMENT CHOICES INIT ================= */
    departmentChoices = new Choices('#department', {
        removeItemButton: true,
        searchEnabled: true,
        placeholder: true,
        placeholderValue: 'Select Departments'
    });

    /* ================= TOASTER FUNCTION ================= */
    function showToast(message, type='success') {
        const toastId = 'toast' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : (type==='error' ? 'bg-danger' : 'bg-info');
        const html = `
        <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
        $('#toastContainer').append(html);
        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl, {delay: 3000});
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', ()=>{ $(toastEl).remove(); });
    }

    /* ================= DATATABLE ================= */
    let table = $('#employeeTable').DataTable({
        ajax: '/employees',
        processing: true,
        serverSide: false,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,"All"]],
        responsive: true,
        autoWidth: false,
        columns: [
            { data:null, render:(d,t,r,m)=>m.row+1 },
            { data:'name' },
            { data:'email_id' },
            { data:'mobile_no' },
            { data:'gender' },
            { data:'designation' },
            // multiple departments ('It' , 'Hr' , 'Sales' , 'Marketing') handling in if else, if more departments added in future then it will automatically handled in else part
            
            { data:'department', render: d => {
                if(!d) return '';
                if(typeof d === "string"){
                    try{
                        d = JSON.parse(d);
                    } catch(e){
                        return d; // return as is if not a valid JSON
                    }
                }
                if(Array.isArray(d)){
                    return d.join(', ');
                }
                return d.toString();
            }},
            { data:'state.name' },
            { data:'city.name' },
            { data:'documents', render: docs=>`
                <span class="badge bg-info">${docs.length}</span>
                <button class="btn btn-sm btn-outline-primary ms-1 viewDocs" data-docs='${JSON.stringify(docs)}'>View</button>
            `},
            { data:'employee_id', render:id=>`
                <button class="btn btn-sm btn-warning btnEdit" data-id="${id}">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete" data-id="${id}">Delete</button>
            `}
        ]
    });

    /* ================= DOCUMENT ROWS (ADD / REMOVE / PREVIEW / VALIDATION) ================= */
    function addDocumentRow(type = '', file = '', docId = '') {

        const existingInput = docId
            ? `<input type="hidden" name="existing_document_file[]" value="${docId}">`
            : `<input type="hidden" name="existing_document_file[]" value="">`;

        const filePreview = file
            ? `<div class="file-preview mt-1">
                    <a href="/storage/${file}" target="_blank">View File</a>
            </div>`
            : '';

        const html = `
        <div class="row document-row mb-2">
            <div class="col-md-4">
                <select name="document_type[]" class="form-select">
                    <option value="">Select Document</option>
                    <option value="pan" ${type === 'pan' ? 'selected' : ''}>PAN</option>
                    <option value="aadhaar" ${type === 'aadhaar' ? 'selected' : ''}>Aadhaar</option>
                </select>
                <small class="error-text" data-error="document_type"></small>
            </div>

            <div class="col-md-5">
                <input type="file" name="document_file[]" class="form-control">
                ${filePreview}
                ${existingInput}
                <small class="error-text" data-error="document_file"></small>
            </div>

            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-sm removeRow">Remove</button>
            </div>
        </div>`;

        $('#documentWrapper').append(html);
    }

    /* ================= ADD BUTTON ================= */
    $('#addDocumentRow').on('click', function () {
        addDocumentRow();
    });

    /* ================= REMOVE ROW ================= */
    $(document).on('click', '.removeRow', function () {
        $(this).closest('.document-row').remove();
    });

    /* ================= FILE INPUT PREVIEW & VALIDATION ================= */
    $(document).on('change', 'input[name="document_file[]"]', function () {

        const row = $(this).closest('.document-row');

        row.find('.file-preview').remove();
        row.find('[data-error="document_file"]').text('');
        $(this).removeClass('input-error');

        if (this.files && this.files[0]) {

            const file = this.files[0];
            const allowedExtensions = ['xls','xlsx','pdf','doc','docx'];
            const maxSizeMB = 5;
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExt)) {
                row.find('[data-error="document_file"]').text('Invalid file type. Only Excel, PDF and Word allowed.');
                $(this).addClass('input-error');
                $(this).val('');
                return;
            }

            if (file.size > maxSizeMB * 1024 * 1024) {
                row.find('[data-error="document_file"]').text('File size must not exceed 5MB.');
                $(this).addClass('input-error');
                $(this).val('');
                return;
            }

            $(this).after(`<div class="file-preview mt-1">${file.name}</div>`);
        }
    });

    /* ================= FILE INPUT PREVIEW & VALIDATION ================= */
    $(document).on('change', 'input[type="file"]', function () {

        const row = $(this).closest('.document-row');
        row.find('.file-preview').remove();
        $(row).find('.error-text[data-error="document_file"]').text('');
        $(this).removeClass('input-error');

        if (this.files && this.files[0]) {

            const file = this.files[0];
            const allowedExtensions = ['xls', 'xlsx', 'pdf', 'doc', 'docx'];
            const maxSizeMB = 5;
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExt)) {
                $(row).find('.error-text[data-error="document_file"]').text('Invalid file type. Only Excel, PDF and Word allowed.');
                $(this).addClass('input-error');
                $(this).val('');
                return;
            }

            if (file.size > maxSizeMB * 1024 * 1024) {
                $(row).find('.error-text[data-error="document_file"]').text('File size must not exceed 5MB.');
                $(this).addClass('input-error');
                $(this).val('');
                return;
            }

            $(this).after(`<div class="file-preview mt-1">${file.name}</div>`);
        }
    });

    /* ================= VALIDATION ================= */
    function clearErrors(){
        $('.error-text').text('');
        $('.input-error').removeClass('input-error');
    }

    function validateForm(){

        let valid = true;

        const fields = [
            {name:'name', message:'Name is required'},
            {name:'email_id', message:'Email is required', format:'Invalid email format'},
            {name:'mobile_no', message:'Mobile number is required', format:'Mobile must be 10 digits'},
            {name:'gender', message:'Select gender'},
            {name:'designation', message:'Designation is required'},
            {name:'state_id', message:'Select state'},
            {name:'city_id', message:'Select city'},
            {name:'address', message:'Address is required'}
        ];

        /* ================= NORMAL FIELD VALIDATION ================= */
        fields.forEach(f=>{

            const el = $('[name="'+f.name+'"]');
            let val;

            if(el.is(':radio')){
                val = $('input[name="'+f.name+'"]:checked').val();
            }else{
                val = el.val();
            }

            if(!val){
                el.addClass('input-error');
                $('[data-error="'+f.name+'"]').text(f.message);
                valid = false;
            }else{
                el.removeClass('input-error');
                $('[data-error="'+f.name+'"]').text('');

                if(f.name==='email_id' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)){
                    el.addClass('input-error');
                    $('[data-error="'+f.name+'"]').text(f.format);
                    valid = false;
                }

                if(f.name==='mobile_no' && !/^\d{10}$/.test(val)){
                    el.addClass('input-error');
                    $('[data-error="'+f.name+'"]').text(f.format);
                    valid = false;
                }
            }
        });

        /* ================= DEPARTMENT VALIDATION (FIXED) ================= */
        const depValues = departmentChoices.getValue(true);

        if(!depValues.length){
            $('[data-error="department"]').text('Select at least one department');
            valid = false;
        }else{
            $('[data-error="department"]').text('');
        }

        /* ================= DOCUMENT VALIDATION ================= */
        $('#documentWrapper .document-row').each(function(i,row){

            const typeSelect = $(row).find('select[name^="document_type"]');
            const type       = typeSelect.val();

            const fileInput  = $(row).find('input[type="file"]');
            const file       = fileInput[0].files[0];

            const existing   = $(row).find('input[name^="existing_document_file"]').val();

            if(!type){
                typeSelect.addClass('input-error');
                $(row).find('[data-error="document_type"]').text('Please select document type');
                valid = false;
            }

            if(!existing && !file){
                fileInput.addClass('input-error');
                $(row).find('[data-error="document_file"]').text('Please upload document file');
                valid = false;
            }
        });

        return valid;
    }

    /* ================= FORM SUBMIT ================= */
    $('#employeeForm').on('submit', function(e){

        // console.log('FORM SUBMIT TRIGGERED');
        e.preventDefault();
        clearErrors();

        // Choices sync
        $('#department').val(departmentChoices.getValue(true));

        console.log('VALID RESULT = ', validateForm());

        if(!validateForm()) return;

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save this employee data and submit documents for verification?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if(!result.isConfirmed) return;

            const id = $('#employee_id').val();
            const url = id ? `/employees/${id}` : '/employees';

            const formData = new FormData(this);
            if(id) formData.append('_method','PUT');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    employeeModal.hide();
                    table.ajax.reload(null,false);
                    showToast(res.message,'success');
                },
                error: function(xhr){

                    if(xhr.status === 422 && xhr.responseJSON?.errors){

                        $.each(xhr.responseJSON.errors, function(k,v){

                            let field = k.replace(/\.(\d+)/g, '[$1]');
                            $('[name="'+field+'"]').addClass('input-error');

                            let parts = k.split('.');
                            let baseKey = parts[0];
                            let index   = parts[1] ?? null;

                            if(index !== null){
                                const row = $('#documentWrapper .document-row').eq(index);
                                row.find('[data-error="'+baseKey+'"]').text(v[0]);
                            }else{
                                $('[data-error="'+baseKey+'"]').first().text(v[0]);
                            }
                        });

                        showToast('Validation error, please check form','error');

                    } 
                    else if(xhr.status >= 500){
                        showToast('Server error, please try again later','error');
                    } 
                    else{
                        showToast('Something went wrong','error');
                    }
                }
            });

        });
    });

    /* ================= RESET FORM ================= */
    function resetForm(){
        $('#employeeForm')[0].reset();
        clearErrors();
        $('#documentWrapper').html('');
        addDocumentRow();
        $('#employee_id').val('');
        $('#docTableBody').html('');

        if(departmentChoices){
            departmentChoices.removeActiveItems();
        }
    }

    /* ================= ADD ================= */
    $('#btnAddEmployee').on('click',function(){
        resetForm();
        $('#modalTitle').text('➕ Add Employee');
        employeeModal.show();
    });

    /* ================= EDIT ================= */
    $('#employeeTable').on('click', '.btnEdit', function () {

        let id = $(this).data('id');
        resetForm();

        $.get(`/employees/${id}/edit`, function (res) {

            $('#modalTitle').text('✏️ Edit Employee');

            $('#employee_id').val(res.employee_id);
            $('#name').val(res.name);
            $('#email_id').val(res.email_id);
            $('#mobile_no').val(res.mobile_no);
            $('#designation').val(res.designation);
            $('#address').val(res.address);

            $('input[name="gender"]').prop('checked', false);
            $('input[name="gender"][value="' + res.gender + '"]').prop('checked', true);

            if(departmentChoices){
                departmentChoices.removeActiveItems();

                let departments = res.department;
                console.log('DEPARTMENTS FROM BACKEND = ', departments);

                if(typeof departments === "string"){
                    try{
                        departments = JSON.parse(departments);
                    } catch(e){
                        departments = [];
                    }
                }

                if(Array.isArray(departments)){
                    departments.forEach(dep => {
                        let cleanDep = dep.toString().trim();
                        cleanDep = cleanDep.charAt(0).toUpperCase() + cleanDep.slice(1).toLowerCase();
                        departmentChoices.setChoiceByValue(cleanDep);

                        // chek value exists in choices, if not add it dynamically in console
                        console.log('Checking department choice for value:', cleanDep);
                    });
                } else {
                    let cleanDep = departments.toString().trim();
                    cleanDep = cleanDep.charAt(0).toUpperCase() + cleanDep.slice(1).toLowerCase();
                    departmentChoices.setChoiceByValue(cleanDep);
                }
            }

            $('#documentWrapper').html('');
            if(res.documents && res.documents.length){
                res.documents.forEach(doc => {
                    addDocumentRow(doc.document_type, doc.document_file, doc.id);
                });
            } else {
                addDocumentRow();
            }

            $('#state').val(res.state_id).trigger('change');

            $(document).one('citiesLoaded', function(){
                $('#city').val(res.city_id).trigger('change');
            });

            employeeModal.show();
        });

    });

    /* ================= DELETE ================= */
    $('#employeeTable').on('click', '.btnDelete', function(){
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this employee permanently?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result)=>{
            if(!result.isConfirmed) return;
            $.post(`/employees/${id}`, {_method:'DELETE', _token:csrf}, res=>{
                table.ajax.reload(null,false);
                showToast(res.message,'success');
            });
        });
    });

    /* ================= DESIGNATION AUTOCOMPLETE ================= */
    const designations = ['Manager', 'Developer', 'Designer', 'Analyst', 'Consultant', 'Intern', 'Administrator', 'Coordinator', 'Engineer', 'Specialist'];

    $('#designation').on('input', function () {
        let query = $(this).val().toLowerCase();
        const list = $('#designationList');
        if(query.length < 1){
            list.addClass('d-none').html('');
            return;
        }
        let matches = designations.filter(d => d.toLowerCase().includes(query));
        if(matches.length > 0){
            const items = matches.map(d => `<button type="button" class="list-group-item list-group-item-action">${d}</button>`).join('');
            list.html(items).removeClass('d-none');
        } else list.addClass('d-none').html('');
    });

    $(document).on('click', '#designationList .list-group-item', function () {
        $('#designation').val($(this).text());
        $('#designationList').addClass('d-none').html('');
    });

    $(document).on('click', function(e){
        if(!$(e.target).closest('#designation, #designationList').length){
            $('#designationList').addClass('d-none').html('');
        }
    });

    /* ================= VIEW DOCUMENTS MODAL ================= */
    $(document).on('click', '.viewDocs', function(){
        const docs = JSON.parse($(this).attr('data-docs'));
        let html = '';
        if(docs.length){
            docs.forEach(doc => {
                html += `<tr><td>${doc.document_type.toUpperCase()}</td><td><a href="/storage/${doc.document_file}" target="_blank" class="btn btn-sm btn-success">View</a></td></tr>`;
            });
        } else html = `<tr><td colspan="2" class="text-center">No documents uploaded</td></tr>`;
        $('#viewDocTableBody').html(html);
        const viewDocModal = new bootstrap.Modal(document.getElementById('viewDocumentsModal'));
        viewDocModal.show();
    });

    /* ================= STATE → CITY ================= */
    $('#state').on('change', function(){
        const state_id = $(this).val();
        $('#city').html('<option value="">Select</option>');
        if(!state_id) return;
        $.get(`/get-cities/${state_id}`, function(cities){
            $.each(cities,(i,city)=>{
                $('#city').append(`<option value="${city.id}">${city.name}</option>`);
            });
            $(document).trigger('citiesLoaded');
        });
    });

});