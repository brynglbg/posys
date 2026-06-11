<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Bad Order Request</span>
                <button class="btn btn-primary" type="button" onclick="bor_add()"><i class="bi bi-plus me-2"></i>Add BO</button>
            </div>
            <table class="table table-sm table-hover bor_tbl">
                <thead>
                    <tr class="trth0">
                        <th class="w50px">BO Request No.</th>
                        <th class="w150px">Date Created</th>
                        <th class="">Vendor</th>
                        <th class="w50px"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    function bor_tbl(){
        $.ajax({
            url: '<?= base_url() ?>ajax/bor',
            type: 'post',
            dataType: 'json',
            success: function(res){
                $('.bor_tbl').DataTable({
                    ...dtopt0,
                    order: [[0, 'desc']],
                    data: res,
                    columns: [
                        {
                            data: 'id',
                            render: (d) => `<a href="#" onclick="bor_pickup(${ d })">${ sprintf(6, d) }</a>`,
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            render: (d) => date_MdY(d)
                        },
                        { data: 'vendor_name' },
                        {
                            data: 'id',
                            render: (d) => dropdown0(`
                                <li><a class="dropdown-item" href="#" onclick="bor_view(${ d })">View</a></li>
                                <li><a class="dropdown-item" href="#" onclick="bor_edit(${ d })">Edit</a></li>
                                <li><hr></li>
                                <li><a class="dropdown-item" href="#" onclick="bor_delete(${ d })">Delete</a></li>
                            `),
                            className: 'text-center'
                        },
                    ]
                });
            }
        });
    }
    $(() => { bor_tbl(); });
    async function bor_pickup(bor_id){
        const bor = await r_tbl('bor', false, {id: bor_id});
        const bor_product = await r_tbl('bor_product', true, {bor_id: bor_id});
        if(bor_product.length > 0){
            let borpTr = '';
            let borpNum = 0;
            bor_product.forEach(function(borp){
                borpTr += (`<tr>
                    <td>
                        <input name="bor_pickup-td-id" type="hidden" value="${ borp.id }">
                        <div class="form-check">
                            <input id="borpNum${ borpNum }" name="bor_pickup-td-name" class="form-check-input td-name" type="checkbox" value="${ borp.name }">
                            <label for="borpNum${ borpNum }" class="form-check-label">${ borp.name }</label>
                        </div>
                    </td>
                    <td><input name="bor_pickup-td-barcode" class="form-control input-view" type="number" value="${ borp.barcode }"></td>
                    <td><input name="bor_pickup-td-expired_at" class="form-control input-view" type="text" value="${ borp.expired_at }"></td>
                    <td><input name="bor_pickup-td-qty" class="form-control td-qty input-view" type="number" value="${ borp.qty }"></td>
                </tr>`);
                borpNum++;
            });
            modal0(
                'bor_pickup',
                '<h5>Pickup Bad Order</h5>',
                (`<div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input id="bor_pickup-vendor_name" class="form-control input-view" type="text" value="${ bor.vendor_name }">
                            <label for="bor_pickup-vendor_name">Vendor</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input id="bor_pickup-pickedup_at" class="form-control input-view" type="text" value="<?= date('Y-m-d') ?>">
                            <label for="bor_pickup-pickedup_at">Pick Up Date</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input id="bor_pickup-pickedup_by" class="form-control req" type="text">
                            <label for="bor_pickup-pickedup_by">Picked Up By</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-hover bor_pickup-tbl">
                            <thead>
                                <tr class="trth0">
                                    <th>Product Name</th>
                                    <th class="w150px">Barcode</th>
                                    <th class="w150px">Expiration Date</th>
                                    <th class="w150px">Qty.</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${ borpTr }
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-end" colspan="3">Total Qty.:</th>
                                    <th class="td-total_qty"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>`),
                '<button class="btn btn-primary" type="submit">Submit</button>',
                'xl'
            );
            $('#bor_pickup-form').on('submit', async function(e){
                e.preventDefault();
                let isValid = true;
                const pickedup_by = $('#bor_pickup-pickedup_by');
                const pickedup_at = $('#bor_pickup-pickedup_at');
                if(!pickedup_by.val()){
                    check_field(pickedup_by, 'Enter pick up name.');
                    isValid = false;
                }
                let checkedBorp = $('input[name="bor_pickup-td-name"]:checked');
                if(checkedBorp.length <= 0){
                    toast0('warning', 'Select at least 1 product.');
                    isValid = false;
                }
                if(!isValid){
                    return;
                }
                const bor_product = $(`.bor_pickup-tbl tbody input[name="bor_pickup-td-name"]:checked`).map(function(){
                    return {
                        id: $(this).closest('tr').find(`input[name="bor_pickup-td-id"]`).val(),
                        bor_id: bor_id,
                        name: $(this).closest('tr').find(`input[name="bor_pickup-td-name"]`).val(),
                        barcode: $(this).closest('tr').find(`input[name="bor_pickup-td-barcode"]`).val(),
                        expired_at: $(this).closest('tr').find(`input[name="bor_pickup-td-expired_at"]`).val(),
                        qty: $(this).closest('tr').find(`input[name="bor_pickup-td-qty"]`).val()
                    };
                }).get();
                Swal.fire({
                    icon: 'info',
                    html: 'Creating Bad Order, please confirm to continue.',
                    showConfirmButton: true,
                    showCancelButton: true
                }).then((btn) => {
                    if(btn.isConfirmed){
                        $.ajax({
                            url: '<?= base_url() ?>ajax/bo_create',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                bor_id: bor_id,
                                pickedup_by: pickedup_by.val(),
                                pickedup_at: pickedup_at.val(),
                                bor_product: bor_product
                            },
                            success: function(result){
                                if(result == 200){
                                    bor_pickup(bor_id);
                                    bor_tbl();
                                    bor_count();
                                }
                            }
                        });
                    }
                });
            });
        }else{
            mdl0.modal('hide');
        }
    }
    // 
    function bor_add(){
        modal0(
            'bor_add',
            '<h5>Add Bad Order</h5>',
            (`<div class="row g-3">
                <div class="col-md-4">
                    <label for="bor_add-vendor_id">Vendor</label>
                    <select id="bor_add-vendor_id" class="form-select select-mdl0 req">
                        <option value="">-- SELECT VENDOR --</option>
                        <?php foreach(vendor() as $v): ?>
                        <option value="<?= $v->id ?>"><?= $v->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover bor_add-tbl">
                        <thead>
                            <tr class="trth0">
                                <th>Product Name</th>
                                <th class="w150px">Barcode</th>
                                <th class="w150px">Expiration Date</th>
                                <th class="w150px">Qty.</th>
                                <th class="w50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <select id="bor_add-vp_name" class="form-select select-mdl1">
                                        <option value="">-- SELECT VENDOR FIRST --</option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                    <button id="bor_add-btn" class="btn btn-sm btn-info" type="button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end" colspan="3">Total Qty.:</th>
                                <th class="td-total_qty"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>`),
            '<button class="btn btn-primary" type="submit">Submit</button>',
            'xl'
        );
        $('#bor_add-vendor_id').on('change', async function(){
            const vendor_id = $(this).val();
            const vendor_product = await r_tbl('vendor_product', true, {vendor_id: vendor_id});
            let vpOpt = '';
            vendor_product.forEach(function(vp){
                vpOpt += (`<option value="${ vp.name }">${ vp.name }</option>`);
            });
            $('#bor_add-vp_name').html(vpOpt);
            $('.bor_add-tbl tbody').html('');
        });
        $('#bor_add-btn').on('click', async function(){
            let isValid = true;
            const vendor_id = $('#bor_add-vendor_id').val();
            if(!vendor_id){
                toast0('warning', 'Select vendor first.');
                isValid = false;
            }
            const vp_name = $('#bor_add-vp_name').val();
            if(vp_name){
                let exists = false;
                $(`input[name="bor_add-td-name"]`).each(function(){
                    if($(this).val() == vp_name){
                        exists = true;
                        return false;
                    }
                });
                if(exists){
                    toast0('info', 'Product is already on the list.');
                    isValid = false;
                }
            }
            if(!isValid){
                return;
            }
            const vp = await r_tbl('vendor_product', false, {vendor_id: vendor_id, name: vp_name});
            let vp_nameValue = '';
            if(vp){
                vp_nameValue = vp.name;
            }else{
                const vp_add = await vendor_product_add(vendor_id, vp_name);
                if(vp_add == 200){
                    vp_nameValue = vp_name;
                }
            }
            if(vp_nameValue){
                $('.bor_add-tbl tbody').append(`<tr>
                    <td><input name="bor_add-td-name" class="form-control input-view" type="text" value="${ vp_nameValue }"></td>
                    <td><input name="bor_add-td-barcode" class="form-control" type="number" value=""></td>
                    <td><input name="bor_add-td-expired_at" class="form-control datepicker0" type="text" value=""></td>
                    <td><input name="bor_add-td-qty" class="form-control td-qty" type="number" value=""></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger tr-remove" type="button">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                </tr>`);
            }
        });
        $('#bor_add-form').on('submit', async function(e){
            e.preventDefault();
            let isValid = true;
            const vendor_id = $('#bor_add-vendor_id').val();
            if(!vendor_id){
                toast0('warning', 'Select vendor first.');
                isValid = false;
            }
            if($(`input[name="bor_add-td-name"]`).length <= 0){
                toast0('warning', 'Select at least 1 product first.');
                isValid = false;
            }
            if(!isValid){
                return;
            }
            let data = build_data('bor_add', ['vendor_id']);
            data['bor_product'] = $(`.bor_add-tbl tbody input[name="bor_add-td-name"]`).map(function(){
                return {
                    bor_id: 0,
                    name: $(this).closest('tr').find(`input[name="bor_add-td-name"]`).val(),
                    barcode: $(this).closest('tr').find(`input[name="bor_add-td-barcode"]`).val(),
                    expired_at: $(this).closest('tr').find(`input[name="bor_add-td-expired_at"]`).val(),
                    qty: $(this).closest('tr').find(`input[name="bor_add-td-qty"]`).val()
                };
            }).get();
            let result = await c_tbl('bor', data);
            if(result == 200){
                bor_add();
                bor_tbl();
                bor_count();
            }
        });
    }
    function vendor_product_add(vendor_id, vp_name){
        return Swal.fire({
            icon: 'info',
            html: 'Product not listed from vendor, add as a new product?',
            showConfirmButton: true,
            showCancelButton: true
        }).then((btn) => {
            if(btn.isConfirmed){
                return $.ajax({
                    url: '<?= base_url() ?>ajax/vendor_product_add',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        vendor_id: vendor_id,
                        name: vp_name
                    }
                });
            }
        });
    }
    async function bor_view(bor_id){
        const bor = await r_tbl('bor', false, {id: bor_id});
        const bor_product = await r_tbl('bor_product', true, {bor_id: bor_id});
        let borpTr = '';
        let borpNum = 0;
        let total_qty = 0;
        bor_product.forEach(function(borp){
            borpTr += (`<tr>
                <td>${ borp.name }</td>
                <td>${ borp.barcode }</td>
                <td>${ borp.expired_at }</td>
                <td>${ borp.qty }</td>
            </tr>`);
            borpNum++;
            total_qty += parseFloat(borp.qty);
        });
        modal0(
            'bor_view',
            '<h5>Bad Order</h5>',
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bor_view-vendor_name" class="form-control input-view" type="text" value="${ bor.vendor_name }">
                        <label for="bor_view-vendor_name">Vendor</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover bor_view-tbl">
                        <thead>
                            <tr class="trth0">
                                <th>Product Name</th>
                                <th class="w150px">Barcode</th>
                                <th class="w150px">Expiration Date</th>
                                <th class="w150px">Qty.</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ borpTr }
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-end" colspan="3">Total Qty.:</th>
                                <th>${ number_format(total_qty) }</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>`),
            '',
            'xl'
        );
    }
    async function bor_edit(bor_id){
        const bor = await r_tbl('bor', false, {id: bor_id});
        const bor_product = await r_tbl('bor_product', true, {bor_id: bor_id});
        let borpTr = '';
        let borpNum = 0;
        bor_product.forEach(function(borp){
            borpTr += (`<tr>
                <td><input name="bor_edit-td-name" class="form-control input-view" type="text" value="${ borp.name }"></td>
                <td><input name="bor_edit-td-barcode" class="form-control" type="number" value="${ borp.barcode }"></td>
                <td><input name="bor_edit-td-expired_at" class="form-control datepicker0" type="text" value="${ borp.expired_at }"></td>
                <td><input name="bor_edit-td-qty" class="form-control td-qty" type="number" value="${ borp.qty }"></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger tr-remove" type="button">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            </tr>`);
            borpNum++;
        });
        modal0(
            'bor_edit',
            '<h5>Edit Bad Order</h5>',
            (`<div class="row g-3">
                <div class="col-md-4">
                    <input id="bor_edit-vendor_id" type="hidden" value="${ bor.vendor_id }">
                    <div class="form-floating">
                        <input id="bor_edit-vendor_name" class="form-control input-view" type="text" value="${ bor.vendor_name }">
                        <label for="bor_edit-vendor_name">Vendor</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover bor_edit-tbl">
                        <thead>
                            <tr class="trth0">
                                <th>Product Name</th>
                                <th class="w150px">Barcode</th>
                                <th class="w150px">Expiration Date</th>
                                <th class="w150px">Qty.</th>
                                <th class="w50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ borpTr }
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><select id="bor_edit-vp_name" class="form-select select-mdl0"></select></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center">
                                    <button id="bor_edit-btn" class="btn btn-sm btn-info" type="button">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-end" colspan="3">Total Qty.:</th>
                                <th class="td-total_qty"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>`),
            '<button class="btn btn-primary" type="submit">Submit</button>',
            'xl'
        );
        const vendor_product = await r_tbl('vendor_product', true, {vendor_id: bor.vendor_id});
        let vpOpt = '';
        vendor_product.forEach(function(vp){
            vpOpt += (`<option value="${ vp.name }">${ vp.name }</option>`);
        });
        $('#bor_edit-vp_name').html(vpOpt);
        $('#bor_edit-btn').on('click', async function(){
            let isValid = true;
            const vp_name = $('#bor_edit-vp_name').val();
            if(vp_name){
                let exists = false;
                $(`input[name="bor_edit-td-name"]`).each(function(){
                    if($(this).val() == vp_name){
                        exists = true;
                        return false;
                    }
                });
                if(exists){
                    toast0('info', 'Product is already on the list.');
                    isValid = false;
                }
            }
            if(!isValid){
                return;
            }
            const vp = await r_tbl('vendor_product', false, {vendor_id: bor.vendor_id, name: vp_name});
            $('.bor_edit-tbl tbody').append(`<tr>
                <td><input name="bor_edit-td-name" class="form-control input-view" type="text" value="${ vp.name }"></td>
                <td><input name="bor_edit-td-barcode" class="form-control" type="number" value=""></td>
                <td><input name="bor_edit-td-expired_at" class="form-control datepicker0" type="text" value=""></td>
                <td><input name="bor_edit-td-qty" class="form-control td-qty" type="number" value=""></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger tr-remove" type="button">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            </tr>`);
        });
        $('#bor_edit-form').on('submit', async function(e){
            e.preventDefault();
            let isValid = true;
            if($(`input[name="bor_edit-td-name"]`).length <= 0){
                toast0('warning', 'Select at least 1 product first.');
                isValid = false;
            }
            if(!isValid){
                return;
            }
            let data = build_data('bor_edit', ['vendor_id']);
            data['bor_product'] = $(`.bor_edit-tbl tbody tr`).map(function(){
                let tr = $(this);
                return {
                    name: tr.find(`input[name="bor_edit-td-name"]`).val(),
                    barcode: tr.find(`input[name="bor_edit-td-barcode"]`).val(),
                    expired_at: tr.find(`input[name="bor_edit-td-expired_at"]`).val(),
                    qty: tr.find(`input[name="bor_edit-td-qty"]`).val()
                };
            }).get();
            let result = await u_tbl('bor', data, {id: bor_id});
            if(result == 200){
                bor_edit(bor_id);
                bor_tbl();
                bor_count();
            }
        });
    }
    async function bor_delete(bor_id){
        let result = await d_tbl('bor', {id: bor_id});
        if(result == 200){
            toast0('success', 'BO request deleted.');
            bor_count();
            bor_tbl();
        }
    }
    // 
    function bor_count(){
        $.ajax({
            url: '<?= base_url() ?>ajax/bor_count',
            type: 'post',
            dataType: 'json',
            success: function(res){
                if(res > 0){
                    $('.sb-bad_order').html('<i class="bi bi-circle-fill text-danger"></i>');
                    $('.sbi-bo_request').html(`<span class="badge bg-danger">${ res }</span>`);
                }else{
                    $('.sb-bad_order').html('');
                    $('.sbi-bo_request').html(``);
                }
            }
        });
    }
</script>
