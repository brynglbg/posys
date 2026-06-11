// initialization
const url0 = $('.url0').val();
const tst0 = $('.tst0');
const spnnr0 = $('.spnnr0');
const mdl0 = $('.mdl0'), mdl1 = $('.mdl1');
const dtopt0 = {
    destroy: true,
    stateSave: true,
    processing: true,
    dom: (`
        <'d-flex align-items-start flex-column flex-md-row'
            <'mb-2 d-flex align-items-start flex-column flex-md-row gap-2'lB>
            <'mb-2 ms-md-auto'f>
        >
        <'mb-2 table-responsive'tr>
        <'mb-2 d-flex justify-content-center'i>
        <'mb-2 d-flex justify-content-center'p>
    `),
    language: {
        search: '',
        searchPlaceholder: 'Search...',
        lengthMenu: '_MENU_',
    },
    buttons: [
        {
            extend: 'colvis',
            className: 'btn btn-default',
            init: function(api, node, config){
                $(node).html('<i class="bi bi-layout-three-columns me-2"></i><small>Columns</small>');
                $(node).addClass('btn-sm btn-outline-primary');
                $(node).removeClass('btn-secondary dropdown-toggle');
            },
        },
        {
            extend: 'excel',
            className: 'btn btn-default',
            exportOptions: {
                columns: ':visible',
            },
            init: function(api, node, config){
                $(node).html('<i class="bi bi-file-earmark-spreadsheet me-2"></i><small>Excel</small>');
                $(node).addClass('btn-sm btn-outline-primary');
                $(node).removeClass('btn-secondary');
            },
        },
        {
            extend: 'print',
            className: 'btn btn-default',
            exportOptions: {
                columns: ':visible',
                modifier: {
                    page: 'current',
                }
            },
            customize: function(win){
                $(win.document.body).find('h1').text('');
            },
            init: function(api, node, config){
                $(node).html('<i class="bi bi-printer me-2"></i><small>Print</small>');
                $(node).addClass('btn-sm btn-success');
                $(node).removeClass('btn-secondary');
            },
        },
    ]
};
const dtopt1 = {
    destroy: true,
    stateSave: true,
    processing: true,
    dom: `<'mb-2 table-responsive'tr>`,
    ordering: false,
    searching: false,
    paging: false
};
const dtopt2 = {
    destroy: true,
    stateSave: true,
    processing: true,
    dom: (`
        <'d-flex align-items-start flex-column flex-md-row'
            <'mb-2 d-flex align-items-start flex-column flex-md-row gap-2'l>
            <'mb-2 ms-md-auto'f>
        >
        <'mb-2 table-responsive'tr>
        <'mb-2 d-flex justify-content-center'i>
    `),
    ordering: true,
    searching: false,
    paging: false
};
const observer = new MutationObserver(mutations => {
    let datepicker0Bool = false;
    let datepickerRange0Bool = false;
    let compute_tblBool = false;
    let form_floating0Bool = false;
    select_mdl0();
    select_mdl1();
    mutations.forEach(mutation => {
        if(mutation.type === 'attributes'){
            const el = mutation.target;
            if(el.nodeType !== 1) return;
            if($(el).is('.req') || $(el).data('was-req')){ req0(el); }
            return;
        }
        $(mutation.addedNodes).each(function(){
            if(this.nodeType !== 1) return;
            const $node = $(this);
            $node.find('.req').addBack('.req').each(function(){ req0(this); });
            tab_exclude(this);
            if($node.is('.datepicker0') || $node.find('.datepicker0').length){ datepicker0Bool = true; }
            if($node.is('.datepicker-range0') || $node.find('.datepicker_range0').length){ datepickerRange0Bool = true; }
            if($node.is('table') || $node.find('table').length){ compute_tblBool = true; }
            if($node.is('.form-floating') || $node.find('.form-floating').length){ form_floating0Bool = true; }
        });
    });
    if(datepicker0Bool){ datepicker0(); }
    if(datepickerRange0Bool){ datepicker_range0(); }
    if(compute_tblBool){ compute_tbl(); }
    if(form_floating0Bool){ form_floating0(); }
});
observer.observe(document.body, {
    childList: true,
    subtree: true,
    attributes: true,
    attributeFilter: ['class']
});
// main
$('.sidebar-toggler').on('click', function(){
    $('#sidebar').toggleClass('width');
});
$('#sidebar .page-menu').on('shown.bs.collapse', function(){
    $(this).prev('.page').find('i.bi-chevron-down').css('transform', 'rotate(180deg)');
});
$('#sidebar .page-menu').on('hidden.bs.collapse', function(){
    $(this).prev('.page').find('i.bi-chevron-down').css('transform', 'rotate(0deg)');
});
function toast0(type = 'error', message = ''){
    let icon = 'exclamation-circle';
    if(type == 'success'){ icon = 'check-circle'; }
    if(type == 'error'){ icon = 'x-circle'; type = 'danger'; message = 'Operation failed, please try again later or contact system admin.'; }
    const toastHTML = (`<div class="toast fade m-2">
        <div class="toast-body">
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div><i class="bi bi-${ icon } text-${ type } fs-3"></i></div>
                    <div>${ message }</div>
                </div>
                <div class="ms-auto"><button class="btn-close" type="button" data-bs-dismiss="toast"></button></div>
            </div>
        </div>
    </div>`);
    const container = document.querySelector('.tst-cont0');
    container.insertAdjacentHTML('afterbegin', toastHTML);
    const toastEl = container.firstElementChild;
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();
    toastEl.addEventListener('hidden.bs.toast', () => { toastEl.remove(); });
}
function spinner0(action = 'show'){
    if(action === 'show'){ spnnr0.removeClass('d-none'); }
    else if(action === 'hide'){ spnnr0.addClass('d-none'); }
}
function modal0(form, head, body, foot = '', size = ''){
    mdl0.html(`<div class="modal-dialog modal-dialog-scrollable modal-${ size }">
        <form id="${ form }-form" class="modal-content" novalidate>
            <div class="modal-header">
                ${ head }
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">${ body }</div>
            ${ foot ? `<div class="modal-footer">${ foot }</div>` : `` }
        </form>
    </div>`);
    mdl0.modal('show');
}
function modal1(form, head, body, foot = '', size = ''){
    mdl1.html(`<div class="modal-dialog modal-dialog-scrollable modal-${ size }">
        <form id="${ form }-form" class="modal-content" novalidate>
            <div class="modal-header">
                ${ head }
                <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">${ body }</div>
            ${ foot ? `<div class="modal-footer">${ foot }</div>` : `` }
        </form>
    </div>`);
    mdl1.modal('show');
}
function popover0(el, message = ''){
    if(message){ $(el).popover({html: true, content: message, trigger: 'manual'}).popover('show'); }
    else{ $(el).popover('dispose'); }
}
function dropdown0(items){
    return (`<div class="dropstart">
        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
        <ul class="dropdown-menu">${ items }</ul>
    </div>`)
}
function salert0(icon, message){
    return Swal.fire({
        icon: icon,
        html: message + '<br>Please confirm to proceed.',
        showConfirmButton: true,
        showCancelButton: true,
    });
}
function datepicker0(){
    $('.datepicker0')
        .attr('autocomplete', 'off')
        .attr('readonly', true)
        .datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
}
function datepicker_range0(){
    $('.datepicker-range0 input')
        .attr('autocomplete', 'off')
        .attr('readonly', true);
    $('.datepicker-range0').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        inputs: $('.datepicker-range0 input')
    });
}
function form_floating0(){
    $('.form-floating input, .form-floating textarea').each(function(){
        $(this).attr('placeholder', '');
    });
}
$(() => { datepicker0(); datepicker_range0(); });
// form
$('.req').each(function(){
    req0(this);
});
$(document).on('change', '.show-pass', function(){
    show_pass(this);
});
$(document).on('change', '.req', function(){
    check_field(this);
});
$(document).on('focus', '.to-view input, .to-view select, .to-view textarea', function(){
    $(this).blur();
});
function show_pass(el){
    if($(el).is(':checked')){ $('.pass-field').attr('type', 'text'); }
    else{ $('.pass-field').attr('type', 'password'); }
}
function tab_exclude(el){
    $(el)
    .find('input.input-view, select.input-view, textarea.input-view')
    .attr('tabindex', -1);
}
function check_form(form){
    let isValid = true;
    $(`#${ form }-form .req`).each(function(){ isValid = check_field(this) && isValid; });
    return isValid;
}
function check_field(el, message = ''){
    // RESET
    let isValid = true;
    $(el).removeClass('is-invalid');
    popover0(el);
    if(message){ message += '<br>'; }
    // INITIALIZATION
    let val = $(el).val().trim();
    let valType = $(el).attr('type');
    let min = $(el).attr('min');
    let max = $(el).attr('max');
    let minl = $(el).attr('minlength');
    let maxl = $(el).attr('maxlength');
    // CONDITION
    if(!val){ message += `This field is required<br>`; }
    if(valType === 'email' && !/^\S+@\S+\.\S+$/.test(val)){ message += `Invalid email format<br>`; }
    if(min && parseFloat(val) < parseFloat(min)){ message += `Enter a minimum of ${ min }.<br>`; }
    if(max && parseFloat(val) > parseFloat(max)){ message += `Enter a maximum of ${ max }.<br>`; }
    if(minl && val.length < parseInt(minl)){ message += `Enter a minimum of ${ minl }, currently entered ${ val.length }<br>`; }
    if(maxl && val.length > parseInt(maxl)){ message += `Enter a maximum of ${ maxl }, currently entered ${ val.length }<br>`; }
    // RESULT
    if(message){
        isValid = false;
        $(el).addClass('is-invalid');
        popover0(el, message);
    }
    return isValid;
}
function req0(input){
    if($(input).closest('.to-view').length) return;
    const input_id = $(input).attr('id');
    const label = $(`label[for="${ input_id }"]`);
    if(label.length && label.find('span.req-star').length === 0){
        label.append('<span class="req-star ms-2" style="color: var(--bs-danger)">*</span>');
    }
}
function build_data(tbl, fields){
    if(!fields) return;
    let data = {};
    fields.forEach(function(f){
        const val = $(`#${ tbl }-${ f }`).val();
        data[f] = val;
    });
    return data;
}
// other
$('.select0').select2({
    theme: 'bootstrap-5',
    width: '100%'
});
$(document).on('click', '.tr-remove', function(){
    $(this).closest('tr').remove();
    compute_tbl();
});
$(document).on('change', '.td-name', function(){
    compute_tbl();
});
$(document).on('input', '.td-cost, .td-qty, .td-discount', function(){
    compute_tbl();
});
$(document).on('input', '.no-space', function(){
    this.value = this.value.replace(/\s/g, '');
});
function select_mdl0(){
    $('.select-mdl0').each(function(){
        if(!$(this).hasClass('select2-hidden-accessible')){
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(this).closest('.modal')
            });
        }
    });
}
function select_mdl1(){
    $('.select-mdl1').each(function(){
        if(!$(this).hasClass('select2-hidden-accessible')){
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownParent: $(this).closest('.modal'),
                tags: true
            });
        }
    });
}
function number_format(number, decimal = null){
    number = parseFloat(number || 0);
    if(decimal === null || decimal === undefined){
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    return number.toFixed(decimal).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function payment_status(amount, paid){
    paid = parseFloat(paid || 0);
    amount = parseFloat(amount || 0);
    let status = '<span class="badge bg-danger">Unpaid</span>';
    if(paid >= amount){
        status = '<span class="badge bg-success">Paid</span>';
    }else if(paid > 0 && paid < amount){
        status = '<span class="badge bg-warning">Partially Paid</span>';
    }
    return status;
}
function compute_balance(amount, paid){
    return parseFloat(amount || 0) - parseFloat(paid || 0);
}
function sprintf(char, id){
    return ('00000000000' + id).slice(-char);
}
function short_name(fullName){
    const parts = fullName.trim().split(" ");
    if(parts.length < 2){
        return fullName;
    }
    const first = parts[0];
    const lastInitial = parts[parts.length - 1][0].toUpperCase();
    return first + " " + lastInitial + ".";
}
function date_dmY(dateStr){ // format date from Y-m-d to d-m-Y
    if(!dateStr){ return ""; }
    let parts = dateStr.split("-");
    if(parts.length !== 3){ return dateStr; }
    let year = parts[0];
    let month = parts[1];
    let day = parts[2];
    return day + "-" + month + "-" + year;
}
function date_MdY(dateStr){
    if(!dateStr){ return ""; }
    let parts = dateStr.split("-");
    if(parts.length !== 3){ return dateStr; }
    let year = parts[0];
    let month = parseInt(parts[1],10);
    let day = parseInt(parts[2],10);
    let months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    if(month < 1 || month > 12){ return dateStr; }
    return months[month - 1] + " " + day + ", " + year;
}
function date_MdY_hmA(dateStr){
    if(!dateStr){ return ""; }
    let parts = dateStr.split(" ");
    if(parts.length !== 2){ return dateStr; }
    let datePart = parts[0].split("-");
    let timePart = parts[1].split(":");
    if(datePart.length !== 3 || timePart.length < 2) return dateStr;
    let year = datePart[0];
    let month = parseInt(datePart[1], 10);
    let day = parseInt(datePart[2], 10);
    let hour24 = parseInt(timePart[0], 10);
    let minute = parseInt(timePart[1], 10);
    if(isNaN(month) || isNaN(day) || isNaN(hour24) || isNaN(minute) || month < 1 || month > 12 || hour24 < 0 || hour24 > 23 || minute < 0 || minute > 59) return dateStr;
    let months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
    let period = hour24 >= 12 ? "PM" : "AM";
    let hour12 = hour24 % 12;
    if(hour12 === 0){ hour12 = 12; }
    let minuteStr = minute < 10 ? "0" + minute : minute;
    return months[month - 1] + " " + day + ", " + year + " " + hour12 + ":" + minuteStr + " " + period;
}
function time_hmA(timeStr){
    if(!timeStr){ return ""; }
    let timePart = timeStr.split(":");
    if(timePart.length < 2) return timeStr;
    let hour24 = parseInt(timePart[0], 10);
    let minute = parseInt(timePart[1], 10);
    if(isNaN(hour24) || isNaN(minute) || hour24 < 0 || hour24 > 23 || minute < 0 || minute > 59) return timeStr;
    let period = hour24 >= 12 ? "PM" : "AM";
    let hour12 = hour24 % 12;
    if(hour12 === 0){ hour12 = 12; }
    let minuteStr = minute < 10 ? "0" + minute : minute;
    return hour12 + ":" + minuteStr + " " + period;
}
function clear_tbody(tbl){
    let tbody = $(`.tbl-sub-${ tbl } tbody`).html('');
}
function compute_tbl(){
    $('table').each(function(){
        let table = $(this);
        let total_qty = 0;
        let total_amount = 0;
        let hasTdName = table.find('tbody .td-name').length > 0;
        table.find('tbody tr').each(function(){
            let row = $(this);
            let cost = parseFloat(row.find('.td-cost').val()) || 0;
            let qty = parseFloat(row.find('.td-qty').val()) || 0;
            let amount = cost * qty;
            row.find('.td-amount').text(number_format(amount, 2));
            let includeInTotal = true;
            if(hasTdName){
                includeInTotal = row.find('.td-name:checked').length > 0;
            }
            if(includeInTotal){
                total_qty += qty;
                total_amount += amount;
            }
        });
        table.find('.td-total_qty').text(number_format(total_qty));
        let discount = table.find('input.td-discount').val();
        total_amount -= parseFloat(discount || 0);
        table.find('.td-total_amount').text(number_format(total_amount, 2));
    });
}
function check_dates(d1, d2){
    const date1 = new Date(d1);
    const date2 = new Date(d2);
    if(date1 > date2){
        return false;
    }
    return true;
}
// ajax
function c_tbl(tbl, data){
    return Swal.fire({
        icon: 'info',
        html: `Adding a record, please confirm to proceed.`,
        showConfirmButton: true,
        showCancelButton: false,
    }).then((btn) => {
        if(btn.isConfirmed){
            return $.ajax({
                url: url0 + 'ajax/c_' + tbl,
                type: 'post',
                dataType: 'json',
                data: { data: data }
            });
        }
    });
}
function r_tbl(tbl, all = true, toFilter = {}){
    return $.ajax({
        url: url0 + 'ajax/r_' + tbl,
        type: 'post',
        dataType: 'json',
        data: { all: all, toFilter: toFilter }
    });
}
function u_tbl(tbl, data, toFilter){
    return Swal.fire({
        icon: 'info',
        html: `Updating a record, please confirm to proceed.`,
        showConfirmButton: true,
        showCancelButton: false,
    }).then((btn) => {
        if(btn.isConfirmed){
            return $.ajax({
                url: url0 + 'ajax/u_' + tbl,
                type: 'post',
                dataType: 'json',
                data: { data: data, toFilter: toFilter }
            });
        }
    });
}
function d_tbl(tbl, toFilter){
    return Swal.fire({
        icon: 'info',
        html: `Deleting a record, please confirm to proceed.`,
        showConfirmButton: true,
        showCancelButton: false,
    }).then((btn) => {
        if(btn.isConfirmed){
            return $.ajax({
                url: url0 + 'ajax/d_' + tbl,
                type: 'post',
                dataType: 'json',
                data: { toFilter: toFilter }
            });
        }
    });
}
