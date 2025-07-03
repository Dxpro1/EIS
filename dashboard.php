<?php
	require('session.php');
	require('config/config.php');
	require('classes/api.php');
    
	$api = new Api;
	$page_title = 'Dashboard';

	# Check role permission
	$page_access = $api->check_role_permissions($username, 23);
	$record_attendance = $api->check_role_permissions($username, 68);
	$scan_qr_code_attendance = $api->check_role_permissions($username, 69);
	$get_location = $api->check_role_permissions($username, 111);
	$telephone_log_approval_page = $api->check_role_permissions($username, 167);
	$transmittal_page = $api->check_role_permissions($username, 189);
    $company_settings_details = $api->get_data_details_one_parameter('company', '1');
    $health_declaration = $company_settings_details[0]['HEALTH_DECLARATION'];

    $get_all_incoming_outgoing_transmittal_count = $api->get_transmittal_count('all incoming/outgoing', $username);
    $get_incoming_transmittal_count = $api->get_transmittal_count('incoming', $username);
    $get_outgoing_transmittal_count = $api->get_transmittal_count('outgoing', $username);

	if($page_access == 0){
		header('location: 404-page.php');
	}
?>
        <?php
            require('views/_head.php');
        ?>
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body data-sidebar="dark">
        <?php
            require('views/_preloader.php');
        ?>
        <div id="layout-wrapper">
            <?php
                require('views/_nav_header.php');
                require('views/_menu.php');
            ?>

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">



                        





                        <div class="row">
                           <div class="col-xl-5">
                                <div class="card overflow-hidden">
                                    <div class="bg-primary bg-soft">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="text-primary p-3">
                                                    <h5 class="text-primary">Welcome!</h5>
                                                    <p>EIS Dashboard pogi</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="avatar-md profile-user-wid mb-4">
                                                    <img src="<?php echo $profile_image . '?' . rand(); ?>" alt="" class="img-thumbnail rounded-circle">
                                                </div>
                                                <h5 class="font-size-15 text-truncate"><?php echo $employee_name; ?></h5>
                                                <p class="text-muted mb-0 text-truncate"><?php echo $position; ?></p>
                                            </div>
                            
                                            <div class="col-sm-8">
                                                <div class="pt-4">
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <p class="text-muted mb-0">Quick Access</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <a href="profile.php" class="btn btn-primary waves-effect waves-light btn-sm me-2">
                                                            View Profile <i class="mdi mdi-arrow-right ms-1"></i>
                                                        </a>
                                                        
                                                  <?php if(isset($_SESSION['sso_token'])): ?>
                                                        <a href="https://hris.encorefinancials.com/sso_login.php?token=<?php echo $_SESSION['sso_token']; ?>&emp_id=<?php echo $_SESSION['employee_id']; ?>" 
                                                           class="btn btn-success waves-effect waves-light btn-sm"
                                                           data-bs-toggle="tooltip" 
                                                           title="Access HR functions including attendance, leave management, and more">
                                                            Access HRIS <i class="mdi mdi-account-cog ms-1"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <!-- Sales Partner Quota Monitoring -->
                        <div class="col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="card-title mb-4">Sales Partner Quota Monitoring</h4>
                                        </div>
                                    </div>
                                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#sales-partner-monthly-tab" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-calendar-alt"></i></span>
                                                <span class="d-none d-sm-block">Sales Partner Monthly</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#branch-monthly-tab" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-calendar-check"></i></span>
                                                <span class="d-none d-sm-block">Branch Monthly</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#sales-partner-to-date-tab" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-user-clock"></i></span>
                                                <span class="d-none d-sm-block">Sales Partner To Date</span>    
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#branch-to-date-tab" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-file-alt"></i></span>
                                                <span class="d-none d-sm-block">Branch To Date</span>    
                                            </a>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content p-3 text-muted">
                                        <div class="tab-pane active" id="sales-partner-monthly-tab" role="tabpanel">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <canvas id="sales-partner-monthly-booking-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="branch-monthly-tab" role="tabpanel">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <canvas id="branch-monthly-booking-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="sales-partner-to-date-tab" role="tabpanel">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <canvas id="sales-partner-to-date-booking-chart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="branch-to-date-tab" role="tabpanel">
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <canvas id="branch-to-date-booking-chart"></canvas>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            

                        </div>





                            <!-- transmittal -->
                            <div class="col-xl-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Transmittal</p>
                                                        <h4 class="mb-0"><?php echo number_format($get_all_incoming_outgoing_transmittal_count); ?></h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                            <span class="avatar-title">
                                                                <i class="bx bx-archive font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Incoming Transmittal</p>
                                                        <h4 class="mb-0"><?php echo number_format($get_incoming_transmittal_count); ?></h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-archive-in font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Outgoing Transmittal</p>
                                                        <h4 class="mb-0"><?php echo number_format($get_outgoing_transmittal_count); ?></h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-archive-out font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="card-title mb-4">Latest Transmittal</h4>
                                            </div>
                                            <?php
                                                if($transmittal_page > 0){
                                                    echo '<div class="col-md-6">
                                                            <div class="float-end">
                                                            <a href="transmittal.php" class="btn btn-primary btn-sm waves-effect waves-light me-2 w-md mb-2">View Transmittal</a>
                                                            </div>
                                                        </div>';
                                                }
                                            ?>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="dashboard-transmittal-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="all" style="width:30%">Description</th>
                                                            <th class="all">Transmitted From</th>
                                                            <th class="all">Transmitted To</th>
                                                            <th class="all">Transmittal Date</th>
                                                            <th class="all">Status</th>
                                                            <th class="all">Incoming/Outgoing</th>
                                                            <th class="all">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

 
                    </div>
                </div>
                <?php
                    require('views/_footer.php');
                ?>
            </div>
        </div>
        
        <script src="https://maps.google.com/maps/api/js?key=AIzaSyCtSAR45TFgZjOs4nBFFZnII-6mMHLfSYI"></script>
        <?php
	        require('views/_scripts.php');
        ?>
        <script src="assets/js/click-events.js"></script>
        <script src="assets/js/on-change-events.js"></script>
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/datatable.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/jquery.repeater/jquery.repeater.min.js"></script>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/gmaps/gmaps.min.js"></script>
        <script src="assets/libs/html5-qr-code/html5-qrcode.min.js"></script>
    </body>
</html>
