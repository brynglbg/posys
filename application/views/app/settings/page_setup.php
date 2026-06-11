<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Pages</span>
                <button class="btn btn-primary" type="button" onclick="mdl_page()"><i class="bi bi-plus me-2"></i>Add Page</button>
            </div>
            <table class="table table-sm tbl-page">
                <thead>
                    <tr class="trth0">
                        <th>Page Name</th>
                        <th class="w100px">Slug</th>
                        <th class="w100px">Icon</th>
                        <th class="w100px">No. of Sub-Pages</th>
                        <th class="w100px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    const tbl_p = 'page';
    // ===== PAGE =====
    window['mod_' + tbl_p] = async function(id = 0, toDel = false){
        if(!check_form(tbl_p) && !toDel) return;
        let fields = ['name', 'slug', 'icon'];
        let data = build_data(tbl_p, fields);
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_p, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_p, data) : await u_tbl(tbl_p, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            window['tbl_' + tbl_p]();
            if(!toDel) window['mdl_' + tbl_p](id);
        }
    }
    window['mdl_' + tbl_p] = async function(id = 0, toView = false){
        const row = await r_tbl(tbl_p, false, {id: id});
        modal0(
            tbl_p,
            `<h5>${ !toView ? (!row ? 'Add ' : 'Edit ') : '' }Page</h5>`,
            (`<div class="row g-3">
                ${`<div class="col-12">
                    <div class="row g-3 ${ toView ? 'to-view' : '' }">
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input id="${ tbl_p }-name" class="form-control req" type="text" value="${ row ? row.name : '' }" placeholder="">
                                <label for="${ tbl_p }-name">Page Name</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input id="${ tbl_p }-slug" class="form-control req" type="text" value="${ row ? row.slug : '' }" placeholder="">
                                <label for="${ tbl_p }-slug">Slug</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input id="${ tbl_p }-icon" class="form-control req" type="text" value="${ row ? row.icon : '' }" placeholder="">
                                <label for="${ tbl_p }-icon">Icon</label>
                            </div>
                        </div>
                    </div>
                </div>`}
                ${`<div class="col-12 ${ !toView ? 'd-none' : '' }">
                    <div class="card card-body gap-3">
                        <div class="flex1">
                            <span class="text-uppercase fs-6">Sub Pages</span>
                            <button class="btn btn-primary" type="button" onclick="mdl_page_sub(${ row ? row.id : 0 })"><i class="bi bi-plus me-2"></i>Add Sub Page</button>
                        </div>
                        <table class="table table-sm tbl-page_sub">
                            <thead>
                                <tr class="trth0">
                                    <th>Sub Page</th>
                                    <th>Slug</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>`}
            </div>`),
            `${ !toView ? '<button class="btn btn-primary" type="submit">Submit</button>' : '' }`,
            'lg'
        );
        if(toView && row) tbl_page_sub(row.id);
        $(`#${ tbl_p }-form`).off('submit').on('submit', async (e) => {
            e.preventDefault();
            window['mod_' + tbl_p](id);
        });
    }
    window['tbl_' + tbl_p] = function(){
        r_tbl(tbl_p).done(res => {
            $(`.tbl-${ tbl_p }`).DataTable({
                ...dtopt0,
                data: res,
                columns: [
                    {data: 'name'},
                    {data: 'slug'},
                    {
                        data: null,
                        render: (d, t, row) => `<i class="bi bi-${ row.icon }"></i>`,
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'page_sub_count',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: null,
                        render: (d, t, row) => dropdown0(`
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_p }(${ row.id }, true)">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_p }(${ row.id })">Edit</a></li>
                            <li><hr></li>
                            <li><a class="dropdown-item" href="#" onclick="mod_${ tbl_p }(${ row.id }, true)">Delete</a></li>
                        `),
                        className: 'text-center',
                        orderable: false
                    }
                ]
            });
        });
    }
    $(() => { window['tbl_' + tbl_p](); });
    // 
    const tbl_ps = 'page_sub';
    window['mod_' + tbl_ps] = async function(page_id, id = 0, toDel = false){
        if(!check_form(tbl_ps) && !toDel) return;
        let fields = ['page_id', 'name', 'slug'];
        let data = build_data(tbl_ps, fields);
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_ps, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_ps, data) : await u_tbl(tbl_ps, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            window['tbl_' + tbl_ps]();
            if(!toDel) window['mdl_' + tbl_ps](page_id, id);
        }
    }
    window['mdl_' + tbl_ps] = async function(page_id, id = 0, toView = false){
        const row = await r_tbl(tbl_ps, false, {id: id});
        modal1(
            tbl_ps,
            `<h5>${ !toView ? (!row ? 'Add ' : 'Edit ') : '' }Sub Page</h5>`,
            (`<div class="row g-3 ${ toView ? 'to-view' : '' }">
                <input id="${ tbl_ps }-page_id" type="hidden" value="${ page_id }">
                <div class="col-12">
                    <div class="form-floating">
                        <input id="${ tbl_ps }-name" class="form-control req" type="text" value="${ row ? row.name : '' }" placeholder="">
                        <label for="${ tbl_ps }-name">Sub Page Name</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input id="${ tbl_ps }-slug" class="form-control req" type="text" value="${ row ? row.slug : '' }" placeholder="">
                        <label for="${ tbl_ps }-slug">Slug</label>
                    </div>
                </div>
            </div>`),
            `${ !toView ? '<button class="btn btn-primary" type="submit">Submit</button>' : '' }`,
            'sm'
        );
        $(`#${ tbl_ps }-form`).off('submit').on('submit', async (e) => {
            e.preventDefault();
            window['mod_' + tbl_ps](page_id, id);
        });
    }
    window['tbl_' + tbl_ps] = function(page_id){
        r_tbl(tbl_ps, true, {page_id: page_id}).done(res => {
            $(`.tbl-${ tbl_ps }`).DataTable({
                ...dtopt0,
                data: res,
                columns: [
                    {data: 'name'},
                    {data: 'slug'},
                    {
                        data: null,
                        render: (d, t, row) => dropdown0(`
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_ps }(${ row.page_id }, ${ row.id }, true)">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_ps }(${ row.page_id }, ${ row.id })">Edit</a></li>
                            <li><hr></li>
                            <li><a class="dropdown-item" href="#" onclick="mod_${ tbl_ps }(${ row.page_id }, ${ row.id }, true)">Delete</a></li>
                        `),
                        className: 'text-center',
                        orderable: false
                    }
                ]
            });
        });
    }
</script>
