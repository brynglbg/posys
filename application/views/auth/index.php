<div class="container-fluid py-3 h-100">
    <div class="row g-3 h-100">
        <div class="col-12 col-md-6 col-lg-5 col-xl-4 h-100">
            <div class="card card-body overflow-y-auto justify-content-center gap-3 p-3 p-md-5 h-100 bg-light">
                <div class="d-flex align-items-center gap-3 text-primary">
                    <i class="bi bi-cash-stack fs-1"></i>
                    <span class="text-uppercase fw-bolder fs-3"><?= sys() ? sys()->name : '' ?></span>
                </div>
                <form id="login-form" novalidate>
                    <div class="row g-3">
                        <div class="col-12">
                            Welcome back! Please enter your details.
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <div class="form-floating">
                                    <input id="login-uname" class="form-control req" type="text" minlength="8" placeholder="">
                                    <label for="login-uname">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <div class="form-floating">
                                    <input id="login-upass" class="form-control req pass-field" type="password" minlength="8" placeholder="">
                                    <label for="login-upass">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input id="login-show_upass" class="form-check-input show-pass" type="checkbox">
                                <label for="login-show_upass" class="form-check-label">Show password</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 p-3" type="submit">Login</button>
                        </div>
                    </div>
                </form>
                <p><?= base_foot() ?></p>
            </div>
        </div>
    </div>
</div>
<script>
    $('#login-form').on('submit', (e) => {
        e.preventDefault();
        const uname = $('#login-uname'), upass = $('#login-upass');
        let isValid = true;
        isValid = check_form('login');
        if(!isValid){ return; }
        spinner0();
        $.ajax({
            url: '<?= base_url() ?>auth/login',
            type: 'post',
            dataType: 'json',
            data: {
                uname: uname.val(),
                upass: upass.val()
            },
            success: (result) => {
                if(result === 200){ location.reload(); return; }
                spinner0('hide');
                if(result === 404){ check_field(uname, 'No user found'); return; }
                if(result === 401){ check_field(upass, 'Wrong password'); return; }
                toast0('error');
            }
        });
    });
</script>
