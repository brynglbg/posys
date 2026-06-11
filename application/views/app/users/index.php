<div class="row g-3">
    <div class="col-12">
        <div class="card card-body gap-3">
            <div class="flex1">
                <span class="text-uppercase fs-6">Users Management</span>
                <button class="btn btn-primary" type="button" onclick="mdl_user()"><i class="bi bi-plus me-2"></i>Add User</button>
            </div>
            <table class="table table-sm table-hover tbl-user">
                <thead>
                    <tr class="trth0">
                        <th class="">Name</th>
                        <th class="">Username</th>
                        <th class="">User Type</th>
                        <th class="w100px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    // ===== USER =====
    const tbl_u = 'user';
    window['mod_' + tbl_u] = async function(id = 0, toDel = false){
        if(!check_form(tbl_u) && !toDel) return;
        let fields = ['fname', 'lname', 'uname', 'type'];
        let data = build_data(tbl_u, fields);
        let toFilter = {id: id};
        let result = 500;
        if(toDel){ result = await d_tbl(tbl_u, toFilter); }
        else{ result = id == 0 ? await c_tbl(tbl_u, data) : await u_tbl(tbl_u, data, toFilter); }
        if(result == 200){
            toast0('success', `${ toDel ? 'Deletion' : (id == 0 ? 'Creation' : 'Update') } success`);
            window['tbl_' + tbl_u]();
            if(!toDel) window['mdl_' + tbl_u](id);
        }
    }
    window['mdl_' + tbl_u] = async function(id = 0, toView = false){
        const row = await r_tbl(tbl_u, false, {id: id});
        modal0(
            tbl_u,
            `<h5>${ !toView ? (!row ? 'Add ' : 'Edit ') : '' }User</h5>`,
            `<div class="row g-3 ${ toView ? 'to-view' : '' }">
                <div class="col-12">
                    <div class="form-floating">
                        <input id="${ tbl_u }-fname" class="form-control req" type="text" value="${ row ? row.fname : '' }" placeholder="">
                        <label for="${ tbl_u }-fname">First Name</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input id="${ tbl_u }-lname" class="form-control req" type="text" value="${ row ? row.lname : '' }" placeholder="">
                        <label for="${ tbl_u }-lname">Last Name</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input id="${ tbl_u }-uname" class="form-control req" type="text" minlength="8" value="${ row ? row.uname : '' }" placeholder="">
                        <label for="${ tbl_u }-uname">Username</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <select id="${ tbl_u }-type" class="form-select req">
                            <?php foreach($user_type as $ut): ?>
                            <option value="<?= $ut->id ?>" ${ row && row.type == <?= $ut->id ?> ? 'selected' : '' }><?= $ut->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="${ tbl_u }-type">User Type</label>
                    </div>
                </div>
            </div>`,
            `${ !toView ? '<button class="btn btn-primary" type="submit">Submit</button>' : '' }`,
            'sm'
        );
        $(`#${ tbl_u }-form`).off('submit').on('submit', async (e) => {
            e.preventDefault();
            window['mod_' + tbl_u](id);
        });
    }
    window['tbl_' + tbl_u] = function(){
        r_tbl(tbl_u).done(res => {
            $(`.tbl-${ tbl_u }`).DataTable({
                ...dtopt0,
                data: res,
                columns: [
                    {
                        data: 'lname',
                        render: (d, t, row) => row.lname + ', ' + row.fname
                    },
                    {
                        data: 'uname',
                        className: 'text-center'
                    },
                    {
                        data: 'type',
                        render: (d, t, row) => row.type_name,
                        className: 'text-center'
                    },
                    {
                        data: null,
                        render: (d, t, row) => dropdown0(`
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_u }(${ row.id }, true)">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="mdl_${ tbl_u }(${ row.id })">Edit</a></li>
                            <li><a class="dropdown-item" href="#" onclick="change_pass(${ row.id })">Change Password</a></li>
                            ${ row.type != 1 ? `<li><a class="dropdown-item" href="#" onclick="mdl_user_page_access(${ row.id })">Page Access</a></li>` : '' }
                            <li><hr></li>
                            <li><a class="dropdown-item" href="#" onclick="mod_${ tbl_u }(${ row.id }, true)">Delete</a></li>
                        `),
                        className: 'text-center',
                        orderable: false
                    }
                ]
            });
        });
    }
    $(() => { window['tbl_' + tbl_u](); });
    async function change_pass(user_id){
        const row = await r_tbl('user', false, {id: user_id});
        modal0(
            'change_pass',
            (`<div class="">
                <span class="fs-5">Change Password</span><br>
                For: <b>${ row.lname }, ${ row.fname }</b>
            </div>`),
            (`<div class="row g-3">
                <div class="col-12">
                    <div class="form-floating">
                        <input id="change_pass-upass_old" class="form-control req pass-field" type="password" minlength="8">
                        <label for="change_pass-upass_old">Old Password</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input id="change_pass-upass_new" class="form-control req pass-field" type="password" minlength="8">
                        <label for="change_pass-upass_new">New Password</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input id="change_pass-upass" class="form-control req pass-field" type="password" minlength="8">
                        <label for="change_pass-upass">Confirm New Password</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input id="change_pass-upass_show" class="form-check-input show-pass" type="checkbox">
                        <label for="change_pass-upass_show" class="form-check-label">Show password</label>
                    </div>
                </div>
            </div>`),
            '<button class="btn btn-primary" type="submit">Submit</button>',
            'sm'
        );
        $('#change_pass-form').on('submit', async (e) => {
            e.preventDefault();
            const upass_old = $('#change_pass-upass_old');
            let oldPassValid = await check_upass(user_id, upass_old.val());
            const upass_new = $('#change_pass-upass_new');
            const upass = $('#change_pass-upass');
            let isValid = check_form('change_pass');
            let newPassValid = upass_new.val() == upass.val();
            if(!isValid || !oldPassValid || !newPassValid){
                if(!oldPassValid) check_field(upass_old, 'Wrong password.');
                if(!newPassValid) check_field(upass, 'New passwords are not the same.');
                return;
            }
            Swal.fire({
                icon: 'info',
                html: `Changing password for <b>${ row.fname } ${ row.lname }</b>.`,
                showConfirmButton: true,
                showCancelButton: true
            }).then((btn) => {
                if(btn.isConfirmed){
                    $.ajax({
                        url: '<?= base_url() ?>ajax/change_upass',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            user_id: user_id,
                            upass: upass.val()
                        },
                        success: function(result){
                            if(result == 200){
                                toast0('success', 'Password changed.');
                                mdl0.modal('hide');
                            }
                        }
                    });
                }
            });
        });
    }
    function check_upass(user_id, upass){
        return $.ajax({
            url: '<?= base_url() ?>ajax/check_upass',
            type: 'post',
            dataType: 'json',
            data: {
                user_id: user_id,
                upass: upass
            }
        });
    }
    // ===== USER PAGE ACCESS =====
    const tbl_upa = 'user_page_access';
    window['mod_' + tbl_upa] = async function(user_id){
        const row = await r_tbl('user_page_access', false, {user_id: user_id});
        let data = {
            user_id: user_id,
            page: $(`input[name="${ tbl_upa }-page"]:checked`).map(function(){ return $(this).val(); }).get(),
            page_sub: $(`input[name="${ tbl_upa }-page_sub"]:checked`).map(function(){ return $(this).val(); }).get()
        }
        let toFilter = {user_id: user_id};
        let result = 500;
        if(row){ result = await u_tbl(tbl_upa, data, toFilter); }
        else{ result = await c_tbl(tbl_upa, data); }
        if(result == 200){
            toast0('success', `${ user_id == 0 ? 'Creation' : 'Update' } success`);
            window['tbl_' + tbl_u]();
            window['mdl_' + tbl_upa](user_id);
        }
    }
    window['mdl_' + tbl_upa] = async function(id){
        const row = await r_tbl(tbl_u, false, {id: id});
        const upage = row.upage || [];
        const upage_sub = row.upage_sub || [];
        const page = await r_tbl('page');
        let pageTr = '';
        for(const p of page){
            const page_sub = await r_tbl('page_sub', true, {page_id: p.id});
            let page_subTr = '';
            for(const ps of page_sub){
                let psAccess = false;
                if(upage_sub.includes(ps.id)) psAccess = true;
                page_subTr += (`<label for="ps${ ps.id }" class="form-check">
                    <input id="ps${ ps.id }" name="${ tbl_upa }-page_sub" class="form-check-input" type="checkbox" value="${ ps.id }" ${ psAccess ? 'checked' : '' }>
                    <span class="form-check-label">${ ps.name }</span>
                </label>`);
            }
            let pAccess = false;
            if(upage.includes(p.id)) pAccess = true;
            pageTr += (`<tr>
                <td>
                    <label for="p${ p.id }" class="form-check">
                        <input id="p${ p.id }" name="${ tbl_upa }-page" class="form-check-input" type="checkbox" value="${ p.id }" ${ pAccess ? 'checked' : '' }>
                        <span class="form-check-label">${ p.name }</span>
                    </label>
                </td>
                <td>${ page_subTr }</td>
                <td></td>
            </tr>`);
        }
        modal0(
            tbl_upa,
            `<h5>User Page Access</h5>`,
            (`<div class="row g-3">
                <div class="col-12">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Main Pages</th>
                                <th>Sub Pages</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            ${ pageTr }
                        </tbody>
                    </table>
                </div>
            </div>`),
            '<button class="btn btn-primary" type="submit">Submit</button>',
            'lg'
        );
        $(`#${ tbl_upa }-form`).off('submit').on('submit', async (e) => {
            e.preventDefault();
            window['mod_' + tbl_upa](id);
        });
    }
</script>
