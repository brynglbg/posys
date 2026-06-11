<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Bad Order Monitoring</span>
            </div>
            <table class="table table-sm table-hover bo_tbl">
                <thead>
                    <tr class="trth0">
                        <th class="w50px">BO No.</th>
                        <th class="w150px">Date Created</th>
                        <th class="">Vendor</th>
                        <th class="w150px">Picked Up By</th>
                        <th class="w100px">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    function bo_tbl(){
        $.ajax({
            url: '<?= base_url() ?>ajax/bo',
            type: 'post',
            dataType: 'json',
            success: function(res){
                $('.bo_tbl').DataTable({
                    ...dtopt0,
                    order: [[0, 'desc']],
                    data: res,
                    columns: [
                        {
                            data: 'id',
                            render: (d, t, row) => row.encoded_at ? `<a href="#" onclick="bo_view(${ d })">${ sprintf(6, d) }</a>` : `<a href="#" onclick="bo_encode(${ d })">${ sprintf(6, d) }</a>`,
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            render: (d) => date_MdY(d)
                        },
                        { data: 'vendor_name' },
                        { data: 'pickedup_by' },
                        {
                            data: null,
                            render: (d, t, row) => row.encoded_at ? '<span class="badge bg-success">Encoded</span>' : '<span class="badge bg-info">Picked up</span>'
                        },
                    ]
                });
            }
        });
    }
    $(() => { bo_tbl(); });
    async function bo_encode(bo_id){
        const bo = await r_tbl('bo', false, {id: bo_id});
        const bo_product = await r_tbl('bo_product', true, {bo_id: bo_id});
        let bopTr = '';
        let bopNum = 0;
        bo_product.forEach(function(bop){
            bopTr += (`<tr>
                <td>
                    <input name="bo_encode-td-id" type="hidden" value="${ bop.id }">
                    <div class="form-check">
                        <input id="bopNum${ bopNum }" name="bo_encode-td-name" class="form-check-input td-name" type="checkbox" value="${ bop.name }">
                        <label for="bopNum${ bopNum }" class="form-check-label">${ bop.name }</label>
                    </div>
                </td>
                <td><input name="bo_encode-td-barcode" class="form-control input-view" type="number" value="${ bop.barcode }"></td>
                <td><input name="bo_encode-td-expired_at" class="form-control input-view" type="text" value="${ bop.expired_at }"></td>
                <td><input name="bo_encode-td-qty" class="form-control td-qty input-view" type="number" value="${ bop.qty }"></td>
            </tr>`);
            bopNum++;
        });
        modal0(
            'bo_encode',
            '<h5>Encode Bad Order</h5>',
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_encode-vendor_name" class="form-control input-view" type="text" value="${ bo.vendor_name }">
                        <label for="bo_encode-vendor_name">Vendor</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_encode-pickedup_at" class="form-control input-view" type="text" value="${ bo.pickedup_at }">
                        <label for="bo_encode-pickedup_at">Pick Up Date</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_encode-pickedup_by" class="form-control input-view" type="text" value="${ bo.pickedup_by }">
                        <label for="bo_encode-pickedup_by">Picked Up By</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_encode-encoded_at" class="form-control datepicker0 req" type="text" value="">
                        <label for="bo_encode-encoded_at">Date Encode</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover bo_encode-tbl">
                        <thead>
                            <tr class="trth0">
                                <th>Product Name</th>
                                <th class="w150px">Barcode</th>
                                <th class="w150px">Expiration Date</th>
                                <th class="w150px">Qty.</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ bopTr }
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
        $('#bo_encode-form').on('submit', async function(e){
            e.preventDefault();
            let isValid = true;
            const encoded_at = $('#bo_encode-encoded_at');
            if(!encoded_at.val()){
                check_field(encoded_at, 'Enter encoding date.');
                isValid = false;
            }
            let allbop = $('input[name="bo_encode-td-name"]');
            let checkedbop = $('input[name="bo_encode-td-name"]:checked');
            if(checkedbop.length !== allbop.length){
                if(checkedbop.length <= 0){
                    toast0('warning', 'You did not select a product.');
                }else{
                    toast0('warning', `You still have ${ allbop.length - checkedbop.length } product/s left to select.`);
                }
                isValid = false;
            }
            if(!isValid){
                return;
            }
            const bo_product = $(`.bo_encode-tbl tbody input[name="bo_encode-td-name"]:checked`).map(function(){
                return {
                    id: $(this).closest('tr').find(`input[name="bo_encode-td-id"]`).val(),
                    bo_id: bo_id,
                    name: $(this).closest('tr').find(`input[name="bo_encode-td-name"]`).val(),
                    barcode: $(this).closest('tr').find(`input[name="bo_encode-td-barcode"]`).val(),
                    expired_at: $(this).closest('tr').find(`input[name="bo_encode-td-expired_at"]`).val(),
                    qty: $(this).closest('tr').find(`input[name="bo_encode-td-qty"]`).val()
                };
            }).get();
            Swal.fire({
                icon: 'info',
                html: `Encoding BO#<b>${ sprintf(6, bo_id) }</b>, please confirm to continue.`,
                showConfirmButton: true,
                showCancelButton: true
            }).then((btn) => {
                if(btn.isConfirmed){
                    $.ajax({
                        url: '<?= base_url() ?>ajax/bo_encode',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            bo_id: bo_id,
                            encoded_at: encoded_at.val(),
                            bo_product: bo_product
                        },
                        success: function(result){
                            if(result == 200){
                                mdl0.modal('hide');
                                bo_tbl();
                            }
                        }
                    });
                }
            });
        });
    }
    async function bo_view(bo_id){
        const bo = await r_tbl('bo', false, {id: bo_id});
        const bo_product = await r_tbl('bo_product', true, {bo_id: bo_id});
        let bopTr = '';
        let bopNum = 0;
        bo_product.forEach(function(bop){
            bopTr += (`<tr>
                <td>${ bop.name }</td>
                <td><input name="bo_view-td-barcode" class="form-control input-view" type="number" value="${ bop.barcode }"></td>
                <td><input name="bo_view-td-expired_at" class="form-control input-view" type="text" value="${ bop.expired_at }"></td>
                <td><input name="bo_view-td-qty" class="form-control td-qty input-view" type="number" value="${ bop.qty }"></td>
            </tr>`);
            bopNum++;
        });
        modal0(
            'bo_view',
            `<h5>Bad Order #${ sprintf(6, bo_id) }</h5>`,
            (`<div class="row g-3">
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_view-vendor_name" class="form-control input-view" type="text" value="${ bo.vendor_name }">
                        <label for="bo_view-vendor_name">Vendor</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_view-pickedup_at" class="form-control input-view" type="text" value="${ date_MdY(bo.pickedup_at) }">
                        <label for="bo_view-pickedup_at">Pick Up Date</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_view-pickedup_by" class="form-control input-view" type="text" value="${ bo.pickedup_by }">
                        <label for="bo_view-pickedup_by">Picked Up By</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input id="bo_view-encoded_at" class="form-control input-view" type="text" value="${ date_MdY(bo.encoded_at) }">
                        <label for="bo_view-encoded_at">Date Encode</label>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-sm table-hover bo_view-tbl">
                        <thead>
                            <tr class="trth0">
                                <th>Product Name</th>
                                <th class="w150px">Barcode</th>
                                <th class="w150px">Expiration Date</th>
                                <th class="w150px">Qty.</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ bopTr }
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
            '',
            'lg'
        );
    }
</script>
