<div class="row g-3">
    <div class="col-12">
        <form id="sys-form" class="card card-body gap-3" novalidate>
            <div class="flex1">
                <span class="text-uppercase fs-6">System Details</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-borderless">
                    <thead>
                        <tr>
                            <th class="w150px"></th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><label for="sys-name">Name</label></th>
                            <td><input id="sys-name" class="form-control req" type="text" value="<?= sys() ? sys()->name : '' ?>" placeholder="e.g. John Doe Store"></td>
                        </tr>
                        <tr>
                            <th><label for="sys-address">Address</label></th>
                            <td><textarea id="sys-address" class="form-control req min-h100px" placeholder="e.g. USA"><?= sys() ? sys()->address : '' ?></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="sys-timezone">Timezone</label></th>
                            <td>
                                <select id="sys-timezone" class="form-select select0 req">
                                    <option value="" selected disabled>-- Select --</option>
                                    <?php foreach(timezones() as $t): ?>
                                    <option value="<?= $t ?>" <?= sys() && sys()->timezone == $t ? 'selected' : '' ?>><?= $t ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="sys-default_upass">New User Password</label></th>
                            <td><input id="sys-default_upass" class="form-control req no-space" type="text" value="<?= sys() ? sys()->default_upass : '' ?>" minlength="8" placeholder="e.g. johndoe123"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="btns0">
                <button class="btn btn-success" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
    $('#sys-form').on('submit', async function(e){
        e.preventDefault();
        if(!check_form('sys')) return;
        let fields = ['name', 'address', 'timezone', 'default_upass'];
        let data = build_data('sys', fields);
        let toFilter = {id: 1};
        let result = await u_tbl('sys', data, toFilter);
        if(result == 200){ toast0('success', `Update success`); }
    });
</script>