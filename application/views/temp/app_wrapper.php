<?php
    $bread_main = '';
    $bread_sub = '';
?>
<!-- SIDEBAR -->
<aside id="sidebar" class="width">
    <div class="d-flex flex-column gap-1 p-2">
        <div class="d-flex justify-content-between d-inline d-md-none"><button class="btn sidebar-toggler ms-auto"><i class="bi bi-x-lg"></i></button></div>
        <a class="sidebar-img-head" href="#"><span><?= user()->fname ?></span><span class="badge bg-success"><?= user()->type_name ?></span></a>
        <!-- NAVIGATIONS -->
        <section><span class="badge text-uppercase text-secondary">Navigation</span></section>
        <?php if(in_array(user()->type, [1])): ?>
        <a class="page link <?php $pmain = 'dashboard'; if($pmain == $view_folder){ $bread_main = str0($pmain); echo 'on'; } ?>" href="<?= base_url($pmain) ?>"><i class="bi bi-columns me-2"></i><?= str0($pmain) ?></a>
        <?php endif; ?>
        <?php foreach(page() as $p): if(!page_access($p->slug)) continue; if(($p->page_sub_count > 0 && user()->upage_sub_count > 0) || ($p->page_sub_count > 0 && in_array(user()->type, [1]))): ?>
        <a class="page link <?php if($p->slug == $view_folder){ $bread_main = $p->name; echo 'on'; } ?>" data-bs-toggle="collapse" data-bs-target=".page-menu.pid<?= $p->id ?>" href="#">
            <i class="bi bi-<?= $p->icon ?> me-2"></i>
            <?= $p->name ?>
            <div class="sb-<?= $p->slug ?>">
                <?= $p->slug == 'bad_order' && $bor_count > 0 ? '<i class="bi bi-circle-fill text-danger"></i>' : '' ?>
            </div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="page-menu pid<?= $p->id ?> collapse <?= $p->slug == $view_folder ? 'show' : '' ?>">
            <div class="d-flex flex-column gap-1 ps-3">
                <?php foreach(page_sub() as $ps): if(!page_sub_access($ps->slug)) continue; if($ps->page_id == $p->id): ?>
                <a class="page-item link sbi<?= $ps->slug ?> <?php if($ps->slug == $view_file){ $bread_sub = $ps->name; echo 'on'; } ?>" href="<?= base_url($p->slug . '/' . $ps->slug)  ?>">
                    <?= $ps->name ?>
                    <div class="sbi-<?= $ps->slug ?>">
                        <?= $ps->slug == 'bo_request' && $bor_count > 0 ? '<span class="badge bg-danger">' . $bor_count . '</span>' : '' ?>
                    </div>
                </a>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <a class="page link <?php if($p->slug == $view_folder){ $bread_main = $p->name; echo 'on'; } ?>" href="<?= base_url($p->slug) ?>">
            <i class="bi bi-<?= $p->icon ?> me-2"></i>
            <?= $p->name ?>
            <div class="sb-<?= $p->slug ?>">
                <?= user()->type != 1 && $p->slug == 'purchases' && po_count(1) > 0 ? '<span class="badge bg-danger">' . po_count(1) . '</span>' : '' ?>
                <?= user()->type == 1 && $p->slug == 'purchases' && po_count(2) > 0 ? '<span class="badge bg-danger">' . po_count(2) . '</span>' : '' ?>
            </div>
        </a>
        <?php endif; endforeach; ?>
        <?php if(in_array(user()->type, [1])): ?>
        <section><span class="badge text-uppercase text-secondary">Option</span></section>
        <a class="page link <?php $pmain = 'users'; if($pmain == $view_folder){ $bread_main = str0($pmain); echo 'on'; } ?>" href="<?= base_url($pmain) ?>"><i class="bi bi-people me-2"></i><?= str0($pmain) ?></a>
        <a class="page link <?php $pmain = 'settings'; if($pmain == $view_folder){ $bread_main = str0($pmain); echo 'on'; } ?>" data-bs-toggle="collapse" data-bs-target=".page-menu.pid<?= $pmain ?>" href="#">
            <i class="bi bi-gear me-2"></i><?= ucwords($pmain) ?><i class="bi bi-chevron-down"></i>
        </a>
        <div class="page-menu pid<?= $pmain ?> collapse <?= $pmain == $view_folder ? 'show' : '' ?>">
            <div class="d-flex flex-column gap-1 ps-3">
                <a class="page-item link <?php $psub = 'system_details'; if($psub == $view_file){ $bread_sub = str0($psub); echo 'on'; } ?>" href="<?= base_url($pmain . '/' . $psub) ?>"><?= str0($psub) ?></a>
                <?php if(user()->type == 1): ?>
                <a class="page-item link <?php $psub = 'page_setup'; if($psub == $view_file){ $bread_sub = str0($psub); echo 'on'; } ?>" href="<?= base_url($pmain . '/' . $psub) ?>"><?= str0($psub) ?></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</aside>
<!-- MAIN -->
<main id="content">
    <div class="content-wrapper d-flex flex-column px-3 pb-3">
        <nav id="navbar" class="navbar navbar-expand-lg position-sticky top-0 start-0 z-1">
            <div class="navbar-wrapper container-fluid">
                <div class="d-flex gap-3">
                    <button class="btn sidebar-toggler" type="button"><i class="bi bi-list"></i></button>
                    <a class="navbar-brand text-uppercase fw-bolder" href="#"><?= sys()->name ?></a>
                </div>
                <div class="dropdown">
                    <button class="btn" type="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle fs-5"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-fill-gear me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url() ?>auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="d-flex flex-column gap-3">
            <header>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
                    <span class="text-uppercase fs-4 fw-bold"><?= $bread_main ?></span>
                    <nav>
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item"><i class="bi bi-speedometer"></i></li>
                            <li class="breadcrumb-item"><?= $bread_main ?></li>
                            <?= $bread_sub && $bread_sub != 'index' ? '<li class="breadcrumb-item">' . $bread_sub . '</li>' : '' ?>
                        </ol>
                    </nav>
                </div>
            </header>
            <?= $app_body ?>
            <footer>
                <p><?= base_foot() ?></p>
            </footer>
        </div>
    </div>
</main>
