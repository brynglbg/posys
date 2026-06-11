<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Vendor List</span>
                <button class="btn btn-primary" type="button" onclick="mdl_vendor()"><i class="bi bi-plus me-2"></i>Add Vendor</button>
            </div>
            <div class="row g-3">
                <table class="table table-sm table-hover tbl-vendor">
                    <thead>
                        <tr class="trth0">
                            <th class="">Vendor Name</th>
                            <th class="">Agent Name</th>
                            <th class="w100px">Contact No.</th>
                            <th class="w50px"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // vendor
    const tbl_1 = 'vendor';
    window['mod_' + tbl_1] = async function(id = 0, toDel = false){
        if(!toDel && !check_form(tbl_1)) return;
        let fields = ['name', 'agent_name', 'contact_no'];
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
            data['vendor_product'] = $(`.tbl-sub-${ tbl_1 } input[name="${ tbl_1 }-td-name"]`).map(function(){
                return {
                    vendor_id: id,
                    name: $(this).val(),
                    cost: $(this).closest('tr').find(`input[name="${ tbl_1 }-td-cost"]`).val()
                };
            }).get();
        }
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_1, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_1, data) : await u_tbl(tbl_1, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            window['tbl_' + tbl_1]();
            if(!toDel) window['mdl_' + tbl_1](id);
        }
    }
    window['mdl_' + tbl_1] = async function(id = 0, toView = false){
        const row = await r_tbl(tbl_1, false, {id: id});
        let product = [];
        let productTr = '';
        if(row){
            product = await r_tbl('vendor_product', true, {vendor_id: row.id});
            product.forEach((p) => {
                productTr += (`<tr>
                    <td><input name="${ tbl_1 }-td-name" class="form-control" type="text" value="${ p.name }"></td>
                    <td><input name="${ tbl_1 }-td-cost" class="form-control" type="text" value="${ p.cost }"></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-danger tr-remove ${ toView ? 'd-none' : '' }" type="button">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                </tr>`);
            });
        }
        modal0(
            tbl_1,
            `<h5>${ !toView ? (!row ? 'Add ' : 'Edit ') : '' }Vendor</h5>`,
            (`<div class="row g-3 ${ toView ? 'to-view' : '' }">
                <div class="col-12">
                    ${`<div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input id="${ tbl_1 }-name" class="form-control req" type="text" value="${ row ? row.name : '' }">
                                <label for="${ tbl_1 }-name">Vendor Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input id="${ tbl_1 }-agent_name" class="form-control" type="text" value="${ row ? row.agent_name : '' }">
                                <label for="${ tbl_1 }-agent_name">Agent Name</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input id="${ tbl_1 }-contact_no" class="form-control" type="number" value="${ row ? row.contact_no : '' }">
                                <label for="${ tbl_1 }-contact_no">Contact No.</label>
                            </div>
                        </div>
                    </div>`}
                </div>
                <div class="col-12">
                    ${`<div class="row g-3">
                        <div class="col-12">
                            <div class="flex1">
                                <span class="text-uppercase fs-6">Product List</span>
                                ${
                                    !toView ?
                                    (`
                                        <input id="${ tbl_1 }-product_list" class="d-none" type="file">
                                        <button class="btn btn-success" type="button" onclick="$('#${ tbl_1 }-product_list').click()">
                                            <i class="bi bi-file-earmark-arrow-up me-2"></i>
                                            Upload Product List
                                        </button>
                                    `)
                                    : ''
                                }
                            </div>
                        </div>
                        <div class="col-12">
                            <table class="table table-sm table-hover tbl-sub-${ tbl_1 }">
                                <thead>
                                    <tr class="trth0">
                                        <th>Product Name</th>
                                        <th>Cost</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${ productTr }
                                </tbody>
                                <tfoot class="${ toView ? 'd-none' : '' }">
                                    <tr>
                                        <td><input id="${ tbl_1 }-td-name" class="form-control" type="text" placeholder="e.g. 1.5L Coke"></td>
                                        <td><input id="${ tbl_1 }-td-cost" class="form-control" type="number" placeholder="e.g. 80.50"></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" type="button" onclick="append_tr0('${ tbl_1 }', ['name', 'cost'])">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>`}
                </div>
            </div>`),
            `${ !toView ? '<button class="btn btn-primary" type="submit">Submit</button>' : '' }`,
            'lg'
        );
        $(`.tbl-sub-${ tbl_1 }`).DataTable({
            ...dtopt2,
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        if (type === 'sort' || type === 'type') {
                            return $(data).val ? $(data).val() : $(data).find('input').val();
                        }
                        return data;
                    }
                },
                {
                    targets: 1,
                    render: function (data, type, row, meta) {
                        if (type === 'sort' || type === 'type') {
                            return $(data).val ? $(data).val() : $(data).find('input').val();
                        }
                        return data;
                    }
                }
            ]
        });
        $(`#${ tbl_1 }-product_list`).on('change', function(e){
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onload = function(e){
                let data = new Uint8Array(e.target.result);
                let workbook = XLSX.read(data, { type: 'array' });
                let sheetName = workbook.SheetNames[0];
                let sheet = workbook.Sheets[sheetName];
                let json = XLSX.utils.sheet_to_json(sheet);
                let isValid = true;
                if(json.length === 0) isValid = false;
                let headers = Object.keys(json[0]);
                let nameKey = headers.find(h => h.toLowerCase().includes('name') || h.toLowerCase().includes('product'));
                let costKey = headers.find(h => h.toLowerCase().includes('cost') || h.toLowerCase().includes('price'));
                if(!nameKey || !costKey) isValid = false;
                if(!isValid){
                    toast0('warning', 'No related column data found');
                    $(`#${ tbl_1 }-product_list`).val('');
                    return;
                }
                let result = $.map(json, function(row){
                    return {
                        name: row[nameKey] || '',
                        cost: row[costKey] || ''
                    };
                });
                append_tr1(tbl_1, result);
                $(`#${ tbl_1 }-product_list`).val('');
            };
            reader.readAsArrayBuffer(file);
        });
        $(`#${ tbl_1 }-form`).off('submit').on('submit', async (e) => {
            e.preventDefault();
            window['mod_' + tbl_1](id);
        });
    }
    window['tbl_' + tbl_1] = function(){
        r_tbl(tbl_1).done(res => {
            $(`.tbl-${ tbl_1 }`).DataTable({
                ...dtopt0,
                data: res,
                columns: [
                    { data: 'name' },
                    { data: 'agent_name' },
                    { data: 'contact_no' },
                    {
                        data: null,
                        render: (d, t, row) => dropdown0(`
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_1 }(${ row.id }, true)">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_1 }(${ row.id })">Edit</a></li>
                            <li><hr></li>
                            <li><a class="dropdown-item" href="#" onclick="mod_${ tbl_1 }(${ row.id }, true)">Delete</a></li>
                        `),
                        className: 'text-center',
                        orderable: false
                    }
                ]
            });
        });
    }
    $(() => { window['tbl_' + tbl_1](); });
    function append_tr0(tbl, fields){
        let tbody = $(`.tbl-sub-${ tbl } tbody`);
        let td = '';
        let isValid = true;
        fields.forEach((f) => {
            let val = $(`#${ tbl }-td-${ f }`).val();
            if(f == 'name' && !val){
                toast0('warning', 'Enter a product name.');
                isValid = false;
            }
            if(!isValid) return;
            td += (`<td><input name="${ tbl }-td-${ f }" class="form-control" type="text" value="${ val }"></td>`);
        });
        if(!isValid) return;
        fields.forEach((f) => {
            $(`#${ tbl }-td-${ f }`).val('');
        });
        let tr = (`<tr>
            ${ td }
            <td class="text-center">
                <button class="btn btn-sm btn-danger tr-remove" type="button">
                    <i class="bi bi-x"></i>
                </button>
            </td>
        </tr>`);
        tbody.append(tr);
    }
    function append_tr1(tbl, rows){
        let tbody = $(`.tbl-sub-${ tbl } tbody`);
        rows.forEach((row) => {
            let td = '';
            $.each(row, function(key, value){
                td += `<td><input name="${ tbl }-td-${ key }" class="form-control" type="text" value="${ value }"></td>`;
            });
            let tr = (`<tr>
                ${ td }
                <td class="text-center">
                    <button class="btn btn-sm btn-danger tr-remove" type="button">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            </tr>`);
            tbody.append(tr);
        });
    }
</script>
