<div class="col-md-3 col-xl-2">
    <div class="card">
        <div class="list-group list-group-flush" role="tablist">
            <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('main', $_GET['m']); echo $app->sidebar_active('view_more', $_GET['m']); ?>" href="home.php?p=act&m=main" role="tab">
                Main
            </a>
            <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('upload', $_GET['m']); ?>" href="home.php?p=act&m=upload" role="tab">
                Upload
            </a>
            <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('nyu', $_GET['m']); ?>" href="home.php?p=act&m=nyu" role="tab">
                Not Yet Uploaded <span class="badge bg-danger rounded float-right"><?php echo number_format($app->notif_nyu()); ?></span>
            </a>
            <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('findings', $_GET['m']); echo $app->sidebar_active('view_dqa', $_GET['m']);  ?>" href="home.php?p=act&m=findings" role="tab">
                Findings <span class="badge bg-danger rounded float-right">34</span>
            </a>
            <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('activity_logs', $_GET['m']); ?>" href="home.php?p=act&m=activity_logs" role="tab">
                Activity Logs
            </a>
            <div class="list-group-item list-group-item-action dropdown show <?php echo $app->sidebar_active('search', $_GET['p']); ?>">
            <a data-toggle="dropdown" data-display="static">
                Search
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="home.php?p=search&modality=ipcdd_drom">IPCDD DROM</a>
                    <a class="dropdown-item" href="home.php?p=search&modality=ncddp_drom">NCDDP DROM</a>
                    <a class="dropdown-item" href="home.php?p=search&modality=af_cbrc">KC-AF CBRC</a>
                </div>
            </a>
            </div>

        </div>
    </div>
</div>