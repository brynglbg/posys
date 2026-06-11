<!-- PURCHASE LIST -->
<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Purchase List</span>
                <?php if(in_array(user()->type, [1])): ?>
                <button class="btn btn-primary" type="button" onclick="mdl_purchase()"><i class="bi bi-plus me-2"></i>Add Purchase</button>
                <?php endif; ?>
            </div>
            <table class="table table-sm table-hover tbl-purchase">
                <thead>
                    <tr class="trth0">
                        <th class="w50px">PO No.</th>
                        <th class="">Vendor</th>
                        <th class="w150px">Amount</th>
                        <th class="w200px">Date Delivered</th>
                        <th class="w50px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    const tbl_1 = 'purchase';
    const tbl_2 = 'purchase_payment';
    // purchase
    window['mod_' + tbl_1] = async function(id = 0, toDel = false){
        if(!toDel && !check_form(tbl_1)) return;
        let fields = ['vendor_id', 'discount'];
        let data = build_data(tbl_1, fields);
        if(!toDel){
            let hasError = false;
            let hasRow = false;
            $(`.tbl-sub-${ tbl_1 } tr`).each(function(){
                let nameInput = $(this).find(`input[name="${ tbl_1 }-td-name"]`);
                let costInput = $(this).find(`input[name="${ tbl_1 }-td-cost"]`);
                if(nameInput.length && costInput.length){
                    let name = nameInput.val().trim();
                    let cost = costInput.val().trim();
                    hasRow = true;
                    if(name === '' || cost === ''){
                        hasError = true;
                        return false;
                    }
                }
            });
            if(!hasRow){
                toast0('warning', 'Add at least 1 product.');
                return;
            }
            if(hasError){
                toast0('warning', 'Name and cost are required.');
                return;
            }
            data['purchase_product'] = $(`.tbl-sub-${ tbl_1 } input[name="${ tbl_1 }-td-name"]`).map(function(){
                return {
                    purchase_id: id,
                    name: $(this).closest('tr').find(`input[name="${ tbl_1 }-td-name"]`).val(),
                    cost: $(this).closest('tr').find(`input[name="${ tbl_1 }-td-cost"]`).val(),
                    qty: $(this).closest('tr').find(`input[name="${ tbl_1 }-td-qty"]`).val()
                };
            }).get();
        }
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_1, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_1, data) : await u_tbl(tbl_1, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            <?= user()->type != 1 ? 'po_count(1);' : 'po_count(2);' ?>
            window['tbl_' + tbl_1]();
            if(!toDel) window['mdl_' + tbl_1](id);
        }
    }
    window['mdl_' + tbl_1] = async function(id = 0, toView = false, vendor_id = 0){
        const row = await r_tbl(tbl_1, false, {id: id});
        const vendor = await r_tbl('vendor');
        let vendorOption = '';
        vendor.forEach((v) => {
            vendorOption += (`<option value="${ v.id }" ${ (row && v.id == row.vendor_id) || (v.id == vendor_id) ? 'selected' : '' }>
                ${ v.name }
            </option>`);
        });
        let purchase_product = [];
        let purchase_productTr = '';
        let total_qty = 0;
        let total_amount = 0;
        if(row){
            purchase_product = await r_tbl('purchase_product', true, {purchase_id: row.id});
            purchase_product.forEach((pp) => {
                const cost = parseFloat(pp.cost), qty = parseFloat(pp.qty), amount = cost * qty;
                purchase_productTr += (`<tr>
                    <td><input name="${ tbl_1 }-td-name" class="form-control input-view" type="text" value="${ pp.name }"></td>
                    <td><input name="${ tbl_1 }-td-barcode" class="form-control input-view" type="text" value="${ pp.barcode }"></td>
                    <td><input name="${ tbl_1 }-td-cost" class="form-control td-cost" type="text" value="${ cost }"></td>
                    <td><input name="${ tbl_1 }-td-qty" class="form-control td-qty" type="number" value="${ qty }"></td>
                    <td class="text-end td-amount">${ number_format(amount, 2) }</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger tr-remove ${ toView ? 'd-none' : '' }" type="button">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                </tr>`);
                total_qty += qty; total_amount += amount;
            });
        }
        modal0(
            tbl_1,
            (`<div class="">
                <h5>${ !toView ? (row ? 'Edit ' : 'Add ') : '' }Purchase ${ row ? `<b>#${ sprintf(6, row.id) }</b>` : '' }</h5>
                ${ row ? payment_status(row.total_amount, row.total_paid) : '' }
            </div>`),
            (`<div class="row g-3 ${ toView ? 'to-view' : '' }">
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="${ tbl_1 }-vendor_id">Vendor</label>
                            <select id="${ tbl_1 }-vendor_id" class="form-select  ${ row ? 'input-view' : ' select-mdl0' }">
                                <option value="" selected disabled>-- SELECT --</option>
                                ${ vendorOption }
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="${ tbl_1 }-vendor_agent_name">Agent Name</label>
                            <input id="${ tbl_1 }-vendor_agent_name" class="form-control input-view" type="text" value="${ row && row.vendor_agent_name ? row.vendor_agent_name : '--' }">
                        </div>
                        <div class="col-md-4">
                            <label for="${ tbl_1 }-vendor_contact_no">Contact No.</label>
                            <input id="${ tbl_1 }-vendor_contact_no" class="form-control input-view" type="text" value="${ row && row.vendor_contact_no ? row.vendor_contact_no : '--' }">
                        </div>
                        <div class="col-md-4 ${ !toView ? 'd-none' : '' }">
                            <label for="${ tbl_1 }-delivered_at">Delivery Date</label>
                            <input id="${ tbl_1 }-delivered_at" class="form-control input-view" type="text" value="${ row && row.delivered_at ? row.delivered_at : '--' }">
                        </div>
                        <div class="col-md-4 ${ !toView ? 'd-none' : '' }">
                            <label for="${ tbl_1 }-encoded_at">Encode Date</label>
                            <input id="${ tbl_1 }-encoded_at" class="form-control input-view" type="text" value="${ row && row.encoded_at ? row.encoded_at : '--' }">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover tbl-sub-${ tbl_1 }">
                        <thead>
                            <tr class="trth0">
                                <th class="">Product Name</th>
                                <th class="w200px">Barcode</th>
                                <th class="w100px">Cost</th>
                                <th class="w100px">Qty.</th>
                                <th class="w150px">Amount</th>
                                <th class="w50px"></th>
                            </tr>
                            <tr class="${ toView ? 'd-none' : '' }">
                                <th class="" colspan="4">
                                    <select id="${ tbl_1 }-vp_name" class="form-select select-mdl0">
                                        <option value="">-- SELECT A VENDOR FIRST --</option>
                                    </select>
                                </th>
                                <th></th>
                                <th class="text-center">
                                    <button class="btn btn-sm btn-info" type="button" onclick="vp_add()">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ purchase_productTr }
                        </tbody>
                        <tfoot>
                            <tr class="text-end">
                                <th class="" colspan="4">Total Qty.:</th>
                                <th class="td-total_qty">${ number_format(total_qty) }</th>
                                <th></th>
                            </tr>
                            <tr class="text-end">
                                <th class="" colspan="4">Discount:</th>
                                <th><input id="${ tbl_1 }-discount" class="form-control text-end td-discount" type="number" value="${ row && row.discount ? row.discount : 0 }"></th>
                                <th></th>
                            </tr>
                            <tr class="text-end">
                                <th class="" colspan="4">Total Amount:</th>
                                <th class="td-total_amount">${ number_format(total_amount, 2) }</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea id="${ tbl_1 }-encoding_note" class="form-control min-h100px input-view">${ row && row.encoding_note ? row.encoding_note : '--' }</textarea>
                        <label for="${ tbl_1 }-encoding_note">Encoding Note</label>
                    </div>
                </div>
            </div>`),
            `${ !toView ?
                '<button class="btn btn-primary" type="submit">Submit</button>' :
                `${ row ?
                    `<button class="btn btn-success" type="button" onclick="purchase_repeat(${ row.vendor_id }, ${ id })"><i class="bi bi-arrow-counterclockwise me-2"></i>Repeat PO</button>` :
                    ''
                }`
            }`,
            `xl`
        );
        vp_options();
        $(`#${ tbl_1 }-vendor_id`).on('change', function(){
            clear_tbody(tbl_1);
            vp_options();
        });
        $(`#${ tbl_1 }-form`).on('submit', function(e){
            e.preventDefault();
            window['mod_' + tbl_1](id);
        });
    }
    window['tbl_' + tbl_1] = async function(){
        purchase_filtered().done((res) => {
            $(`.tbl-${ tbl_1 }`).DataTable({
                ...dtopt0,
                order: [[0, 'desc']],
                data: res,
                columns: [
                    {
                        data: 'id',
                        render: (d, t, row) => (`
                            ${ !row.delivered_at ?
                                `<a href="#" onclick="mdl_deliveredAt(${ row.id })">${ sprintf(6, row.id) }</a>` :
                                `${ !row.encoded_at ?
                                    `<a href="#" onclick="mdl_encodedAt(${ row.id })">${ sprintf(6, row.id) }</a>` :
                                    `<a href="#" onclick="mdl_${ tbl_1 }(${ row.id }, true)">${ sprintf(6, row.id) }</a>`
                                }`
                            }
                        `),
                        className: 'text-center'
                    },
                    { data: 'vendor_name' },
                    {
                        data: 'total_amount',
                        render: (d, t, row) => number_format((parseFloat(d) - parseFloat(row.discount)), 2),
                    },
                    {
                        data: 'delivered_at',
                        render: (d) => date_MdY(d),
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: (d, t, row) => dropdown0(`
                            <span class="d-none">${ row.pp_names }</span>
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_1 }(${ row.id }, true)">View</a></li>
                            <li class="<?php if(!in_array(user()->type, [1])): ?>${ row.delivered_at ? 'd-none' : '' }<?php endif; ?>">
                                <a class="dropdown-item" href="#" onclick="mdl_${ tbl_1 }(${ row.id })">Edit</a>
                            </li>
                            <li class="${ row.delivered_at ? 'd-none' : '' }"><a class="dropdown-item" href="<?= base_url() ?>pdf_print/purchases/print_pending?id=${ row.id }" target="_blank">Print PO</a></li>
                            <li class="${ !row.delivered_at ? 'd-none' : '' }"><a class="dropdown-item" href="#" onclick="mdl_${ tbl_2 }(${ row.id })">Payment Details</a></li>
                            <?php if(in_array(user()->type, [1])): ?>
                            <li><hr></li>
                            <li><a class="dropdown-item" href="#" onclick="mod_${ tbl_1 }(${ row.id }, true)">Delete</a></li>
                            <?php endif; ?>
                        `),
                        className: 'text-center',
                        orderable: false
                    }
                ],
                createdRow: function(row, data, dataIndex){
                    <?php if(user()->type == 1): ?>
                    if(data.delivered_at != '' && data.encoded_at == ''){
                        $(row).addClass('table-active');
                    }
                    <?php endif; ?>
                }
            });
        });
    }
    $(() => { window['tbl_' + tbl_1](); });
    function purchase_filtered(){
        return $.ajax({
            url: '<?= base_url() ?>ajax/purchase_filtered',
            type: 'post',
            dataType: 'json',
        });
    }
    async function purchase_repeat(vendor_id, purchase_id){
        if(window['mdl_' + tbl_1](0, false, vendor_id)){
            const row = await r_tbl(tbl_1, false, {id: purchase_id});
            if($(`#${ tbl_1 }-vendor_id`).val(vendor_id)){
                let purchase_product = [];
                let purchase_productTr = '';
                let total_qty = 0;
                let total_amount = 0;
                purchase_product = await r_tbl('purchase_product', true, {purchase_id: purchase_id});
                purchase_product.forEach((pp) => {
                    const cost = parseFloat(pp.cost), qty = parseFloat(pp.qty), amount = cost * qty;
                    purchase_productTr += (`<tr>
                        <td><input name="${ tbl_1 }-td-name" class="form-control input-view" type="text" value="${ pp.name }"></td>
                        <td><input name="${ tbl_1 }-td-barcode" class="form-control input-view" type="text" value="${ pp.barcode }"></td>
                        <td><input name="${ tbl_1 }-td-cost" class="form-control td-cost" type="text" value="${ cost }"></td>
                        <td><input name="${ tbl_1 }-td-qty" class="form-control td-qty" type="number" value="${ qty }"></td>
                        <td class="text-end td-amount">${ number_format(amount, 2) }</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger tr-remove" type="button">
                                <i class="bi bi-x"></i>
                            </button>
                        </td>
                    </tr>`);
                    total_qty += qty; total_amount += amount;
                });
                $(`.tbl-sub-${ tbl_1 } tbody`).html(purchase_productTr);
                $('.td-total_qty').text(number_format(total_qty));
                $('.td-total_amount').text(number_format(total_amount, 2));
            }
        }
    }
    async function vp_options(){
        const vendor_id = $(`#${ tbl_1 }-vendor_id`).val();
        if(vendor_id){
            const vendor = await r_tbl('vendor', false, {id: vendor_id});
            const vendor_product = await r_tbl('vendor_product', true, {vendor_id: vendor_id});
            let list = '';
            vendor_product.forEach((vp) => {
                list += `<option value="${ vp.name }" ${ vp.id == vendor_id ? 'selected' : '' }>${ vp.name }</option>`;
            });
            $(`#${ tbl_1 }-vp_name`).html(list);
            $(`#${ tbl_1 }-vendor_agent_name`).val(vendor.agent_name ? vendor.agent_name : '--');
            $(`#${ tbl_1 }-vendor_contact_no`).val(vendor.contact_no ? vendor.contact_no : '--');
        }
    }
    async function vp_add(){
        const vendor_id = $(`#${ tbl_1 }-vendor_id`).val();
        const vp_name = $(`#${ tbl_1 }-vp_name`).val();
        if(vp_name){
            let exists = false;
            $(`input[name="${ tbl_1 }-td-name"]`).each(function(){
                if($(this).val() == vp_name){
                    exists = true;
                    return false;
                }
            });
            if(exists){
                toast0('info', 'Product is already on the list.');
                return;
            }
            const vp = await r_tbl('vendor_product', false, {vendor_id: vendor_id, name: vp_name});
            $(`.tbl-sub-${ tbl_1 } tbody`).append(`<tr>
                <td><input name="${ tbl_1 }-td-name" class="form-control input-view" type="text" value="${ vp.name }"></td>
                <td></td>
                <td><input name="${ tbl_1 }-td-cost" class="form-control td-cost" type="text" value="${ vp.cost }"></td>
                <td><input name="${ tbl_1 }-td-qty" class="form-control td-qty" type="number" value="0"></td>
                <td class="text-end td-amount">0</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger tr-remove" type="button">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            </tr>`);
        }
    }
    function vp_cost(purchase_id, vendor_id, el){
        let cost = $(el).val();
        let name = $(el).closest('tr').find('input[name="encode_details-td-name"]').val();
        $.ajax({
            url: '<?= base_url() ?>ajax/vp_cost',
            type: 'post',
            dataType: 'json',
            data: {
                purchase_id: purchase_id,
                vendor_id: vendor_id,
                name: name,
                cost: cost
            }
        });
    }
    function getpoproducts_purchaseid(purchase_id){
        return $.ajax({
            url: '<?= base_url() ?>ajax/getpoproducts_purchaseid',
            type: 'post',
            dataType: 'json',
            data: { purchase_id: purchase_id }
        });
    }
    async function mdl_deliveredAt(purchase_id){
        const row = await r_tbl(tbl_1, false, {id: purchase_id});
        let purchase_product = await getpoproducts_purchaseid(purchase_id);
        let purchase_productTr = '';
        let ppNum = 0;
        purchase_product.forEach((pp) => {
            const cost = parseFloat(pp.cost), qty = parseFloat(pp.qty);
            purchase_productTr += (`<tr>
                <td>
                    <div class="form-check">
                        <input id="ppNum${ ppNum }" name="delivery_details-td-name" class="form-check-input td-name" type="checkbox" value="${ pp.name }">
                        <label for="ppNum${ ppNum }" class="form-check-label">${ pp.name }</label>
                    </div>
                </td>
                <td><input name="delivery_details-td-barcode" class="form-control" type="number" value="${ pp.barcode }" onchange="barcode_input(${ purchase_id }, this)"></td>
                <td class="d-none"><input name="delivery_details-td-cost" class="form-control input-view td-cost" type="text" value="${ cost }"></td>
                <td><input name="delivery_details-td-qty" class="form-control td-qty" type="number" value="${ qty }"></td>
                <td class="td-amount d-none">0</td>
            </tr>`);
            ppNum++;
        });
        modal0(
            'delivery_details',
            `<h5>Delivery Details ${ row ? `<b>#${ sprintf(6, row.id) }</b>` : '' }</h5>`,
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_name" class="form-control input-view" type="text" value="${ row.vendor_name }">
                        <label for="${ tbl_1 }-vendor_name">Vendor Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_agent_name" class="form-control input-view" type="text" value="${ row.vendor_agent_name ? row.vendor_agent_name : '--' }">
                        <label for="${ tbl_1 }-vendor_agent_name">Agent Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_contact_no" class="form-control input-view" type="text" value="${ row.vendor_contact_no ? row.vendor_contact_no : '--' }">
                        <label for="${ tbl_1 }-vendor_contact_no">Contact No.</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-delivered_at" class="form-control input-view" type="text" value="<?= date('Y-m-d') ?>">
                        <label for="${ tbl_1 }-delivered_at">Delivery Date</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover tbl-sub-delivery_details">
                        <thead>
                            <tr class="trth0">
                                <th class="">Product Name</th>
                                <th class="w200px">Barcode</th>
                                <th class="d-none">Cost</th>
                                <th class="w100px">Qty.</th>
                                <th class="d-none w150px">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ purchase_productTr }
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><select id="delivery_details-vp_name" class="form-select select-mdl1"></select></th>
                                <th></th>
                                <th class="text-center">
                                    <button id="delivery_details-btn" class="btn btn-sm btn-info" type="button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </th>
                            </tr>
                            <tr class="text-end">
                                <th class="" colspan="2">Total Qty.:</th>
                                <th class="td-total_qty">0</th>
                            </tr>
                            <tr class="d-none">
                                <th class="" colspan="4">Total Amount:</th>
                                <th class="td-total_amount">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>`),
            `<button class="btn btn-primary" type="submit">Submit</button>`,
            `lg`
        );
        $('#delivery_details-btn').on('click', async () => {
            let vp_name = $('#delivery_details-vp_name').val();
            const add_po_pp_result = await add_po_product(purchase_id, vp_name);
            if(add_po_pp_result == 200){
                $('.tbl-sub-delivery_details tbody').append(`<tr>
                    <td>
                        <div class="form-check">
                            <input id="ppNum${ ppNum }" name="delivery_details-td-name" class="form-check-input td-name" type="checkbox" value="${ vp_name }">
                            <label for="ppNum${ ppNum }" class="form-check-label">${ vp_name }</label>
                        </div>
                    </td>
                    <td><input name="delivery_details-td-barcode" class="form-control" type="number" onchange="barcode_input(${ purchase_id }, this)"></td>
                    <td class="d-none"><input name="delivery_details-td-cost" class="form-control input-view td-cost" type="text"></td>
                    <td><input name="delivery_details-td-qty" class="form-control td-qty" type="number"></td>
                    <td class="td-amount d-none">0</td>
                </tr>`);
                ppNum++;
            }
        });
        $('#delivery_details-form').on('submit', async (e) => {
            e.preventDefault();
            if(!check_form('delivery_details')) return;
            let fields = ['delivered_at'];
            let data = build_data(tbl_1, fields);
            // 
            let hasError = false;
            let hasRow = false;
            $(`.tbl-sub-delivery_details tr`).each(function(){
                let nameInput = $(this).find(`input[name="delivery_details-td-name"]:checked`);
                if(nameInput.length){
                    hasRow = true;
                }
            });
            if(!hasRow){
                toast0('warning', 'Add at least 1 product.');
                return;
            }
            if(hasError){
                toast0('warning', 'Name and cost are required.');
                return;
            }
            data['purchase_product'] = $(`.tbl-sub-delivery_details input[name="delivery_details-td-name"]:checked`).map(function(){
                return {
                    purchase_id: purchase_id,
                    name: $(this).closest('tr').find(`input[name="delivery_details-td-name"]`).val(),
                    barcode: $(this).closest('tr').find(`input[name="delivery_details-td-barcode"]`).val(),
                    cost: $(this).closest('tr').find(`input[name="delivery_details-td-cost"]`).val(),
                    qty: $(this).closest('tr').find(`input[name="delivery_details-td-qty"]`).val()
                };
            }).get();
            // 
            let toFilter = {id: purchase_id};
            let result = await u_tbl(tbl_1, data, toFilter);
            if(result == 200){
                toast0('success', `Update success`);
                window['tbl_' + tbl_1]();
                mdl0.modal('hide');
                <?= user()->type != 1 ? 'po_count(1);' : 'po_count(2);' ?>
            }
        });
    }
    function add_po_product(purchase_id, vp_name){
        return Swal.fire({
            icon: 'info',
            html: 'Product not listed from vendor, add as a new product?',
            showConfirmButton: true,
            showCancelButton: true
        }).then((btn) => {
            if(btn.isConfirmed){
                return $.ajax({
                    url: '<?= base_url() ?>ajax/add_po_product',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        purchase_id: purchase_id,
                        name: vp_name
                    }
                });
            }
        });
    }
    async function mdl_encodedAt(purchase_id){
        const row = await r_tbl(tbl_1, false, {id: purchase_id});
        let purchase_product = await r_tbl('purchase_product', true, {purchase_id: purchase_id});
        let purchase_productTr = '';
        let total_qty = 0;
        let total_amount = 0;
        let ppNum = 0;
        purchase_product.forEach((pp) => {
            const cost = parseFloat(pp.cost), qty = parseFloat(pp.qty), amount = cost * qty;
            purchase_productTr += (`<tr>
                <td>
                    <div class="form-check">
                        <input id="ppNum${ ppNum }" name="encode_details-td-name" class="form-check-input td-name" type="checkbox" value="${ pp.name }" onchange="encode_PoItem(${ purchase_id }, this)" ${ pp.encode_status == 1 ? 'checked' : '' }>
                        <label for="ppNum${ ppNum }" class="form-check-label">${ pp.name }</label>
                    </div>
                </td>
                <td><input name="encode_details-td-barcode" class="form-control input-view" type="number" value="${ pp.barcode }"></td>
                <td><input name="encode_details-td-cost" class="form-control td-cost" type="text" value="${ cost }" onchange="vp_cost(${ purchase_id }, ${ row.vendor_id }, this)"></td>
                <td><input name="encode_details-td-qty" class="form-control input-view td-qty" type="number" value="${ qty }"></td>
                <td class="text-end td-amount">${ number_format(amount, 2) }</td>
            </tr>`);
            total_qty += qty; total_amount += amount; ppNum++;
        });
        modal0(
            'encode_details',
            `<h5>Encode Details ${ row ? `<b>#${ sprintf(6, row.id) }</b>` : '' }</h5>`,
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_name" class="form-control input-view" type="text" value="${ row.vendor_name }">
                        <label for="${ tbl_1 }-vendor_name">Vendor Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_agent_name" class="form-control input-view" type="text" value="${ row.vendor_agent_name ? row.vendor_agent_name : '--' }">
                        <label for="${ tbl_1 }-vendor_agent_name">Agent Name</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-vendor_contact_no" class="form-control input-view" type="text" value="${ row.vendor_contact_no ? row.vendor_contact_no : '--' }">
                        <label for="${ tbl_1 }-vendor_contact_no">Contact No.</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-delivered_at" class="form-control input-view" type="text" value="${ row.delivered_at ? row.delivered_at : '--' }">
                        <label for="${ tbl_1 }-delivered_at">Delivery Date</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="${ tbl_1 }-encoded_at" class="form-control datepicker0 req" type="text">
                        <label for="${ tbl_1 }-encoded_at">Encode Date</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover tbl-sub-encode_details">
                        <thead>
                            <tr class="trth0">
                                <th class="">Product Name</th>
                                <th class="w200px">Barcode</th>
                                <th class="w100px">Cost</th>
                                <th class="w100px">Qty.</th>
                                <th class="w150px">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ purchase_productTr }
                        </tbody>
                        <tfoot>
                            <tr class="text-end">
                                <th class="" colspan="4">Total Qty.:</th>
                                <th class="td-total_qty">${ number_format(total_qty) }</th>
                            </tr>
                            <tr class="text-end">
                                <th class="" colspan="4">Discount:</th>
                                <th><input id="${ tbl_1 }-discount" class="form-control text-end td-discount" type="number"></th>
                            </tr>
                            <tr class="text-end">
                                <th class="" colspan="4">Total Amount:</th>
                                <th class="td-total_amount">${ number_format(total_amount, 2) }</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <textarea id="${ tbl_1 }-encoding_note" class="form-control min-h100px"></textarea>
                        <label for="${ tbl_1 }-encoding_note">Encoding Note</label>
                    </div>
                </div>
            </div>`),
            `<button class="btn btn-primary" type="submit">Submit</button>`,
            `lg`
        );
        $('#encode_details-form').on('submit', async (e) => {
            e.preventDefault();
            if(!check_form('encode_details')) return;
            let fields = ['encoded_at', 'discount', 'encoding_note'];
            let data = build_data(tbl_1, fields);
            // 
            let isValid = true;
            let selectedItems = $(`.tbl-sub-encode_details input[name="encode_details-td-name"]:checked`).length;
            if(selectedItems <= 0){
                toast0('warning', 'Select at least 1 item.');
                isValid = false;
            }
            if(!isValid){
                return;
            }
            data['purchase_product'] = $(`.tbl-sub-encode_details input[name="encode_details-td-name"]`).map(function(){
                return {
                    purchase_id: purchase_id,
                    name: $(this).closest('tr').find(`input[name="encode_details-td-name"]`).val(),
                    barcode: $(this).closest('tr').find(`input[name="encode_details-td-barcode"]`).val(),
                    cost: $(this).closest('tr').find(`input[name="encode_details-td-cost"]`).val(),
                    qty: $(this).closest('tr').find(`input[name="encode_details-td-qty"]`).val()
                };
            }).get();
            // 
            let toFilter = {id: purchase_id};
            let result = await u_tbl(tbl_1, data, toFilter);
            if(result == 200){
                toast0('success', `Update success`);
                window['tbl_' + tbl_1]();
                mdl0.modal('hide');
            }
        });
    }
    function encode_PoItem(purchase_id, el){
        const name = $(el).val();
        const status = $(el).is(':checked') ? 1 : 0;
        $.ajax({
            url: '<?= base_url() ?>ajax/u_purchase_product',
            type: 'post',
            dataType: 'json',
            data: {
                data: { encode_status: status },
                toFilter: {
                    purchase_id: purchase_id,
                    name: name
                }
            }
        });
    }
    function barcode_input(purchase_id, el){
        const name = $(el).closest('tr').find(`input[name="delivery_details-td-name"]`).val();
        const barcode = $(el).val();
        $.ajax({
            url: '<?= base_url() ?>ajax/u_purchase_product',
            type: 'post',
            dataType: 'json',
            data: {
                data: { barcode: barcode },
                toFilter: {
                    purchase_id: purchase_id,
                    name: name
                }
            }
        });
    }
    function po_count(filter){
        $.ajax({
            url: '<?= base_url() ?>ajax/po_count',
            type: 'post',
            dataType: 'json',
            data: { filter },
            success: function(res){
                if(res > 0){
                    $('.sb-purchases').html(`<span class="badge bg-danger">${ res }</span>`);
                }else{
                    $('.sb-purchases').html(``);
                }
            }
        });
    }
    // purchase_payment
    window['mod_' + tbl_2] = async function(purchase_id, id = 0, toDel = false){
        const method = $(`#${ tbl_2 }-method`).val();
        let fields = ['purchase_id', 'paid_at', 'method', 'source', 'reference', 'amount', 'note'];
        if(method == 1){
            const remove = ['source', 'reference'];
            fields = fields.filter(f => !remove.includes(f));
        }
        if(!toDel && !check_form(tbl_2)) return;
        let data = build_data(tbl_2, fields);
        // 
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_2, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_2, data) : await u_tbl(tbl_2, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            window['tbl_' + tbl_1]();
            window['mdl_' + tbl_2](purchase_id);
        }
    }
    window['mdl_' + tbl_2] = async function(purchase_id){
        const row = await r_tbl(tbl_1, false, {id: purchase_id});
        modal0(
            tbl_2,
            (`<div class="">
                <h5>Purchase Payment</h5>
                ${ row ? payment_status(row.total_amount, row.total_paid) : '' }
            </div>`),
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex flex-column gap-3">
                        <input id="${ tbl_2 }-purchase_id" type="hidden" value="${ purchase_id }">
                        <div class="form-floating">
                            <input id="${ tbl_2 }-paid_at" class="form-control req datepicker0" type="text">
                            <label for="${ tbl_2 }-paid_at">Payment Date</label>
                        </div>
                        <div class="form-floating">
                            <select id="${ tbl_2 }-method" class="form-select req">
                                <?php foreach(payment_method() as $pm): ?>
                                <option value="<?= $pm->id ?>"><?= $pm->name ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="${ tbl_2 }-method">Method</label>
                        </div>
                        <div class="form-floating not-cash-cont d-none">
                            <input id="${ tbl_2 }-source" class="form-control not-cash-field" type="text">
                            <label for="${ tbl_2 }-source">Issuing Bank/Source</label>
                        </div>
                        <div class="form-floating not-cash-cont d-none">
                            <input id="${ tbl_2 }-reference" class="form-control not-cash-field" type="text">
                            <label for="${ tbl_2 }-reference">Reference No./Check No.</label>
                        </div>
                        <div class="form-floating">
                            <input id="${ tbl_2 }-amount" class="form-control req" type="number" value="${ row.total_balance }">
                            <label for="${ tbl_2 }-amount">Amount</label>
                        </div>
                        <div class="form-floating">
                            <textarea id="${ tbl_2 }-note" class="form-control min-h100px"></textarea>
                            <label for="${ tbl_2 }-note">Note</label>
                        </div>
                        <div class="btns0">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <table class="table table-sm table-hover tbl-${ tbl_2 }">
                        <thead>
                            <tr class="trth0">
                                <th class="">Payment Date</th>
                                <th class="">Note</th>
                                <th class="">Payment Method</th>
                                <th class="">Other Details</th>
                                <th class="w150px">Amount</th>
                                <th class="w50px"></th>
                            </tr>
                        </thead>
                        <tfoot class="text-end">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Amount:</th>
                                <th class="${ tbl_2 }-total_amount"></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Paid:</th>
                                <th class="${ tbl_2 }-total_paid"></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Discount:</th>
                                <th class="${ tbl_2 }-total_discount"></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Balance:</th>
                                <th class="${ tbl_2 }-total_balance"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>`),
            ``,
            `xl`
        );
        $(`#${ tbl_2 }-method`).on('change', function(){
            const method = $(this).val();
            if(method == 1){
                $('.not-cash-cont').addClass('d-none');
                $('.not-cash-field').removeClass('req');
            }else{
                $('.not-cash-cont').removeClass('d-none');
                $('.not-cash-field').addClass('req');
            }
        });
        $(`#${ tbl_2 }-form`).on('submit', function(e){
            e.preventDefault();
            window['mod_' + tbl_2](purchase_id);
        });
        window['tbl_' + tbl_2](purchase_id);
    }
    window['tbl_' + tbl_2] = async function(purchase_id){
        const purchase = await r_tbl(tbl_1, false, {id: purchase_id});
        $(`.${ tbl_2 }-total_amount`).text(number_format(purchase.total_amount, 2));
        $(`.${ tbl_2 }-total_paid`).text(number_format(purchase.total_paid, 2));
        $(`.${ tbl_2 }-total_discount`).text(number_format(purchase.discount, 2));
        $(`.${ tbl_2 }-total_balance`).text(number_format(purchase.total_balance, 2));
        r_tbl(tbl_2, true, {purchase_id: purchase_id}).done((res) => {
            $(`.tbl-${ tbl_2 }`).DataTable({
                ...dtopt0,
                data: res,
                columns: [
                    {
                        data: 'paid_at',
                        render: (d) => date_MdY(d)
                    },
                    {
                        data: 'note',
                        render: (d) => `<small>${ d }</small>`
                    },
                    { data: 'payment_method_name' },
                    {
                        data: null,
                        render: (d, t, row) => (`<small>
                            ${ row.source ? `Source: ${ row.source }<br>` : `` }
                            ${ row.reference ? `Ref. No.: ${ row.reference }<br>` : `` }
                        </small>`)
                    },
                    {
                        data: 'amount',
                        render: (d) => number_format(d, 2)
                    },
                    {
                        data: 'id',
                        render: (d) => `<button class="btn btn-sm btn-danger" type="button" onclick="mod_${ tbl_2 }(${ purchase_id }, ${ d }, true)"><i class="bi bi-x"></i></button>`,
                        className: 'text-center',
                        orderable: false
                    },
                ]
            });
        });
    }
</script>
