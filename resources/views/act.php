<div class="row">
    <div class="col-md-3 col-xl-2">

        <div class="card">
            <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('main', $_GET['m']); ?>" href="home.php?p=act&m=main" role="tab">
                     Main
                </a>
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('upload', $_GET['m']); ?>" href="home.php?p=act&m=upload" role="tab">
                    Upload
                </a>
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('nyu', $_GET['m']); ?>" href="home.php?p=act&m=nyu" role="tab">
                    Not Yet Uploaded <span class="badge bg-danger rounded float-right">1,334</span>
                </a>
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('findings', $_GET['m']); ?>" href="home.php?p=act&m=findings" role="tab">
                    Findings <span class="badge bg-danger rounded float-right">34</span>
                </a>
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('activity_logs', $_GET['m']); ?>" href="home.php?p=act&m=activity_logs" role="tab">
                    Activity Logs
                </a>
                <a class="list-group-item list-group-item-action <?php echo $app->sidebar_active('search', $_GET['m']); ?>" href="home.php?p=act&m=search" role="tab">
                    Search
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-9 col-xl-10">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="2">Stage 1 & 2</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: large">
                        <!--<tr>
                            <td style="width: 90%;">MDRRMC Tribal Meeting</td>
                            <td style="width: 10%;"><strong>98%</strong></td>
                        </tr>
                        <tr>
                            <td style="width: 90%;">MDRRMC Tribal Meeting IP Leader</td>
                            <td style="width: 10%;"><strong>98%</strong></td>
                        </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>