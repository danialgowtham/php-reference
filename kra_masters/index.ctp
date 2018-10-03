<!--        <div class="slim-pageheader">
          <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="slim-pagetitle">Welcome <?php echo $username['Employee']['first_name'] ?></h6>
        </div> slim-pageheader -->
<style>
    .overflow{
        overflow-y: hidden;
    }
    .overflow:hover {
        overflow-y: scroll;
    }
</style>
<div class="report-summary-header">
    <div>
        <h4 class="tx-inverse mg-b-3">Overall KRA Summary</h4>
        <p class="mg-b-0"><i class="icon ion-calendar mg-r-3"></i> 2018 - 2019</p>
    </div>

    <?php if ($view) { ?>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" id = 'buHeadBtn'class ="dashboardGrpBtn btn btn-secondary btn-outline-primary pd-x-25">BU Head</button>
            <div class=" buHead dropdown-menu pd-30 pd-sm-40 wd-sm-350 hide" >
                <?php foreach ($employee_menu_list[1] as $key => $employeelist) { ?>
                    <label class="ckbox">
                        <input id ="test1" value ="<?php echo $key; ?>" type="checkbox"><span><?php echo $employeelist['Employee_name']; ?></span>
                    </label>
                <?php } ?>
                <button class="btn btn-primary buhsubbtn btn-block pd-y-10 mg-b-30">submit</button>


            </div>
            <div class = "btn-group">
                <button type="button" id ='functionHeadBtn'class=" dashboardGrpBtn btn btn-secondary btn-outline-primary active pd-x-25">Function</button>
                <div class=" functionHead dropdown-menu pd-30 pd-sm-40 wd-sm-350 hide" >
                    <?php foreach ($employee_menu_list[2] as $key => $employeelist) { ?>
                        <label class="ckbox">
                            <input id ="test1"  value ="<?php echo $key; ?>" type="checkbox"><span><?php echo $employeelist['Employee_name']; ?></span>
                        </label>
                    <?php } ?>
                    <button class="btn btn-primary funHeadSubtn btn-block pd-y-10 mg-b-30">submit</button>
                </div>
            </div>
            <div class = "btn-group">
                <button type="button" id ='nextFunctionHeadBtn' class=" dashboardGrpBtn btn btn-secondary btn-outline-primary pd-x-25">Next Level Function</button>
                <div class=" nextFunctionHead dropdown-menu pd-30 pd-sm-40 wd-sm-350 hide" >
                    <?php foreach ($employee_menu_list[3] as $key => $employeelist) { ?>
                        <label class="ckbox">
                            <input id ="test1"  value ="<?php echo $key; ?>" type="checkbox"><span><?php echo $employeelist['Employee_name']; ?></span>
                        </label>
                    <?php } ?>
                    <button class="btn btn-primary nextFunHeadSubtn btn-block pd-y-10 mg-b-30">submit</button>
                </div></div>
            <div class = "btn-group">
                <button type="button" id='practiceHeadBtn' class=" dashboardGrpBtn btn btn-secondary btn-outline-primary pd-x-25">Practice</button>
                <div class=" practiceHead dropdown-menu pd-30 pd-sm-40 wd-sm-350 hide" >
                    <?php foreach ($employee_menu_list[4] as $key => $employeelist) { ?>
                        <label class="ckbox">
                            <input id ="test1"  value ="<?php echo $key; ?>" type="checkbox"><span><?php echo $employeelist['Employee_name']; ?></span>
                        </label>
                    <?php } ?>
                    <button class="btn btn-primary practiceHeadSubBtn btn-block pd-y-10 mg-b-30">submit</button>
                </div></div>
        </div>
    <?php } ?>
    <div>

        <a href="<?php echo BASE_PATH; ?>kra_masters/employee_index" class="btn btn-secondary"><i class="icon ion-ios-clock-outline tx-22"></i> Your KRA</a>
        <a href="<?php echo BASE_PATH; ?>kra_masters/reportees_kra" class="btn btn-secondary"><i class="icon ion-ios-gear-outline tx-24"></i> Reportees KRA</a>
    </div>
</div><!-- d-flex -->
<div class="row no-gutters dashboard-chart-one mg-t-10">
    <div class="col ">
        <div class="card card-total">
            <div>
                <h1 data-container="body" data-popover-color="pink" data-placement="left" title="Total Employees" 
                    data-content="<?php 
                    if (!empty($totalEmployee)) {
                        foreach ($totalEmployee as $key => $totemp) {
                            echo ($key + 1) . ". " . $totemp['employee_name'] . " \n";
                        }
                    } else {
                        echo "There is no record ";
                    }
                    ?>"><?php echo count($totalEmployee); ?></h1>
                <p>Total Reportees</p>
            </div>
            <div>
                <div class="tx-24 mg-b-10 tx-center op-5">
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        
                        if (round(($first_bar) / 10) > $i) {
                            
                            ?>
                            <i class="icon ion-man tx-gray-600"></i>
                        <?php } else { ?>
                            <i class="icon ion-man tx-gray-400"></i>
                        <?php
                        }
                    }
                    ?>
                </div>
<!--                <label>Project (<?php echo $repotiesIn_project; ?>)</label>-->
                <div class="progress mg-b-10">
                    <div class="progress-bar bg-primary progress-bar-xs wd-<?php echo $first_bar; ?>p" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div>  <!--  progress 

                <label>Non Project (<?php echo $repotiesnotIn_project; ?>)</label>
                <div class="progress">
                    <div class="progress-bar bg-danger progress-bar-xs wd-<?php echo $repotiesnotIn_project_progress; ?>p" role="progressbar" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100"></div>
                </div> progress -->
            </div>
        </div><!-- card -->
    </div><!-- col -->
    <div class="col">
        <div class="card card-total">
            <div>
                <h1 data-container="body" data-popover-color="pink" data-placement="left" title="Total Employees" 
                    data-content="<?php
                    if (!empty($notMapped)) {
                        foreach ($notMapped as $key => $notmap) {
                            echo ($key + 1) . ". " . $notmap['employee_name'] . " \n";
                        }
                    } else {
                        echo "There is no record ";
                    }
                    ?>"><?php echo (count($notMapped)); ?></h1>
                <p>Reportees without KRAs</p>
            </div>
            <div>
                <div class="tx-16 mg-b-15 tx-center op-5">
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        if (round(($second_bar) / 10) > $i) {
                            ?>
                            <i class="icon ion-cube tx-gray-600"></i>
                        <?php } else { ?>
                            <i class="icon ion-cube tx-gray-400"></i>
                        <?php
                        }
                    }
                    ?>

                </div>
<!--                <label>Project (<?php echo ($repoties_without_kras_In_project); ?>)</label>-->
                <div class="progress mg-b-10">
                    <div class="progress-bar bg-purple progress-bar-xs wd-<?php echo $second_bar; ?>p" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div> <!--    progress 
 
                 <label>Non project (<?php echo ($repoties_without_kras_not_in_project); ?>)</label>
                 <div class="progress">
                     <div class="progress-bar bg-pink progress-bar-xs wd-<?php echo ($repoties_without_kras_not_in_project_progress); ?>p" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                 </div> progress -->

            </div>
        </div><!-- card -->
    </div><!-- col -->
    <div class="col">
        <div class="card card-total">
            <div>
                <h1 data-container="body" data-popover-color="pink" data-placement="left" title="Total Employees" 
                    data-content="<?php
                    $i = 1;
                    if (!empty($mappedEmployeeWaiting)) {
                        foreach ($mappedEmployeeWaiting as $key => $mappedEmpwait) {
                            if(!$view){
                                 echo "<a href ='" . BASE_PATH . "kra_masters/employee_kra/" . $key . "/" . $mappedEmployeeWaiting[$key]['kra_mappind_id'] . "/rm_view" . "' style='color: #ffffff;'>" . $mappedEmpwait['employee_name'] . " \n </a>";
                            } else{
                                echo $mappedEmpwait['employee_name']. " \n";
                            }
                            $i++;
                        }
                    } else {
                        echo "There is no record ";
                    }
                    ?>"><?php echo count($mappedEmployeeWaiting); ?></h1>                <p>Waiting for review</p>
            </div>
            <div>
                <div class="tx-22 mg-b-10 tx-center op-5">
                    <?php
                    for ($i = 0; $i < 10; $i++) {
                        if (round(($third_bar) / 10) > $i) {
                            ?>
                            <i class="icon ion-location tx-gray-600"></i>
                        <?php } else { ?>
                            <i class="icon ion-location tx-gray-400"></i>
                        <?php
                        }
                    }
                    ?>
<!--                    <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-600"></i>
           <i class="icon ion-location tx-gray-400"></i>
           <i class="icon ion-location tx-gray-400"></i>
           <i class="icon ion-location tx-gray-400"></i>-->
                </div>
<!--                <label>Open (<?php echo $open; ?>)</label>-->
                <div class="progress mg-b-10">
                    <div class="progress-bar bg-success progress-bar-xs wd-<?php echo $third_bar; ?>p" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div> <!-- progress 

              <label>Draft (<?php echo $draft; ?>)</label>
              <div class="progress">
                  <div class="progress-bar bg-warning progress-bar-xs wd-<?php echo $draft_progress; ?>p" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
              </div> progress -->
            </div>
        </div><!-- card -->
    </div><!-- col -->
    <div class="col">
        <div class="card card-total">
            <div>
                <h1 data-container="body" data-popover-color="pink" data-placement="left" title="Total Employees" 
                    data-content="<?php
                    if (!empty($mappedEmployeeOpen)) {
                        foreach ($mappedEmployeeOpen as $key => $mappedEmpopen) {
                            echo ($key + 1) . ". " . $mappedEmpopen['employee_name'] . " \n";
                        }
                    } else {
                        echo "There is no record ";
                    }
                    ?>"><?php echo count($mappedEmployeeOpen); ?></h1>                <p> REPORTEE Yet to Complete</p>
            </div>
            <div>
                <div class="tx-22 mg-b-10 tx-center op-5">
                     <?php
                    for ($i = 0; $i < 10; $i++) {
                        if (round(($fourth_bar) / 10) > $i) {
                            ?>
                            <i class="icon ion-location tx-gray-600"></i>
                        <?php } else { ?>
                            <i class="icon ion-location tx-gray-400"></i>
                        <?php
                        }
                    }
                    ?>
<!--                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-600"></i>
                    <i class="icon ion-location tx-gray-400"></i>
                    <i class="icon ion-location tx-gray-400"></i>
                    <i class="icon ion-location tx-gray-400"></i>-->
                </div>
<!--                <label>Open (<?php echo $open; ?>)</label>-->
                <div class="progress mg-b-10">
                    <div class="progress-bar bg-success progress-bar-xs wd-<?php echo $fourth_bar; ?>p" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>    <!-- progress 
 
                 <label>Draft (<?php echo $draft; ?>)</label>
                 <div class="progress">
                     <div class="progress-bar bg-warning progress-bar-xs wd-<?php echo $draft_progress; ?>p" role="progressbar" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                 </div> progress -->
            </div>
        </div><!-- card -->
    </div><!-- col -->
</div>
<div class="row row-xs">
    <div class="col-md-6 col-lg-4 order-lg-1">

        <div class="card card-activities pd-20 ">
            <h6 class="slim-card-title">NEWS Feeds</h6>
            <p>Last activity was 1 hour ago</p>

            <div class="media-list news_feeds">

            </div><!-- media-list -->
        </div><!-- card -->


    </div><!-- col-3 -->
    <div class="col-md-6 col-lg-4 mg-t-10 mg-md-t-0 order-lg-3">
        <div class="card">
            <div class="card-body pd-b-0-force">
                <h6 class="slim-card-title mg-b-20">Reference Notes</h6>
                <select class="form-control select2" id="select_employee">
                    <option value="">--Select Employee--</option>
                <?php foreach($total_reporties_data as $reportee){ ?>
                    <option value="<?php echo $reportee['EmployeeKraMapping']['id']; ?>"><?php echo $reportee['Employee']['first_name'].' '.$reportee['Employee']['last_name'] ?></option>
                <?php } ?>
                </select>
                <div id="comments-container" class="overflow mg-t-20 mg-b-10 ht-250"></div>
            </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-3 -->
    <div class="col-lg-4 mg-t-10 mg-lg-t-0 order-lg-2">
        <div class="card card-customer-overview">
            <div class="card-header">
                <h6 class="slim-card-title">Rating overview</h6>
                <nav class="nav">
                    <!--                    <a href="" class="nav-link active">Day</a>
                                        <a href="" class="nav-link">Week</a>
                                        <a href="" class="nav-link">Month</a>-->
                </nav>
            </div><!-- card-header -->
            <div class="card-body">
                <div id="flotArea1" class="ht-300 ht-sm-300"></div>
            </div><!-- card-body -->
        </div><!-- card -->


<!--        <div class="card card-quick-post mg-t-10">
            <h6 class="slim-card-title">Tracking Notes</h6>
            <div class="form-group">
                <textarea class="form-control" rows="3" placeholder="What's on your mind?"></textarea>
            </div> form-group 
            <div class="card-footer">
                <button class="btn btn-purple">Record</button>
                <nav>
                    <a href=""><i class="icon ion-images"></i></a>
                    <a href=""><i class="icon ion-aperture"></i></a>
                    <a href=""><i class="icon ion-calendar"></i></a>
                </nav>
            </div> card-footer 
        </div> card -->
    </div><!-- col-6 -->
</div><!-- row -->
</div><!-- container -->
<div id="modaldemo3" class="modal fade">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon ion-ios-checkmark-outline tx-50 tx-success lh-1 mg-t-1 d-inline-block" id="icon"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_success"></h4>
                <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-success pd-x-25" data-dismiss="modal" aria-label="Close" id="ok_btn_success">Ok</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<div id="modaldemo5" class="modal fade">
    <div class="modal-dialog wd-30p" role="document">
        <div class="modal-content tx-size-sm">
            <div class="modal-body tx-center pd-y-20 pd-x-20">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="icon icon ion-ios-close-outline tx-50 tx-danger lh-1 mg-t-1 d-inline-block"></i>
                <h4 class=" tx-14 mg-b-20" id="msg_text_failure"></h4>
                            <!--<p class="mg-b-20 mg-x-20">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration.</p>-->
                <button type="button" class="btn btn-danger pd-x-25" da ta-di sm iss="modal" aria-label=" Close" id="ok_btn_failure">OK</button>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.0/jquery.textcomplete.js"></script>

<script>
    $(function () {
        'use strict'




        //        var newCust = [[0, 6], [1, 9], [2, 6], [3, 5], [4, 7], [5, 11]];
        var newCust = '';
        var retCust = <?php echo $graphData; ?>;

        var plot = $.plot($('#flotArea1'), [
            {
                data: newCust,
                label: 'Target Rating',
                color: '#1B84E7'
            },
            {
                data: retCust,
                label: 'Actual Rating',
                color: '#4E6577'
            }],
                {
                    series: {
                        lines: {
                            show: true,
                            lineWidth: 0,
                            fill: 0.8
                        },
                        shadowSize: 0
                    },
                    points: {
                        show: false,
                    },
                    legend: {
                        noColumns: 1,
                        position: 'nw'
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderColor: '#ddd',
                        borderWidth: 0,
                        labelMargin: 5,
                        backgroundColor: '#fff'
                    },
                    yaxis: {
                        min: 0,
                        max: <?php echo count($mappedEmployee); ?>,
                        color: '#eee',
                        font: {
                            size: 10,
                            color: '#999'
                        }
                    },
                    xaxis: {
                        min: 0,
                        max: 5,
                        color: '#eee',
                        font: {
                            size: 10,
                            color: '#999'
                        }
                    }
                });
//        $.plot("#flotBar1", [{
//                data: [[1, 0], [2, 8], [3, 5], [4, 13], [5, 5]]
//            }], {
//            series: {
//                bars: {
//                    show: true,
//                    lineWidth: 0,
//                    fillColor: '#4E6577'
//                }
//            },
//            grid: {
//                borderWidth: 1,
//                borderColor: '#D9D9D9'
//            },
//            yaxis: {
//                tickColor: '#d9d9d9',
//                font: {
//                    color: '#666',
//                    size: 10
//                }
//            },
//            xaxis: {
//                tickColor: '#d9d9d9',
//                font: {
//                    color: '#666',
//                    size: 10
//                }
//            }
//        });

        // Donut chart
        $('.peity-donut').peity('donut');
    });
    $(function () {
        'use strict';

        // Initialize popover
        $('[data-toggle="popover"]').popover();
        console.log('szgsdg sdgds gmdhsjgkhjk');
        $('[data-popover-color="pink"]').popover({
            template: '<div class="popover popover-primary" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body" style="white-space: pre-wrap;"></div></div>',
            html: true
        });
    });
    
     var emp_count=0;
     var usersArray=[
     <?php foreach($total_reporties_data as $reportee){ ?>
        {id:<?php echo $reportee['Employee']['id'] ?>,fullname:"<?php echo $reportee['Employee']['first_name']; ?>",profile_picture_url:"<?php echo !empty($reportee['Employee']['employee_photo']) ? file_exists(WEB_ROOT_DIRECTORY . "employee_photos/" . $reportee['Employee']['employee_photo']) ? BASE_PATH . "uploads/employee_photos/" . $reportee['Employee']['employee_photo'] : BASE_PATH . "img/profile_pic.jpg" : BASE_PATH . "img/profile_pic.jpg";  ?>"},
     <?php } ?>];
</script>

<?php
echo $javascript->link('/kra/lib/Flot/js/jquery.flot.js');
echo $javascript->link('/kra/lib/Flot/js/jquery.flot.resize.js');
echo $javascript->link('/kra/lib/peity/js/jquery.peity.js');
echo $javascript->link('/kra/lib/jquery-comments/js/jquery-comments.js'); 
echo $javascript->link('/kra/lib/moment/js/moment.js');
echo $html->css('/kra/lib/jquery-comments/css/jquery-comments.css');
echo $javascript->link('/kra/js/kra_dashboard.js'); ?>
