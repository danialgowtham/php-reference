<?php if (!empty($employee_detail)) { ?>
    <div class="card card-dash-one mg-t-20">
          <div class="row no-gutters">
            <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-analytics-outline"></i>-->
              <div class="dash-content">
                  <label class="tx-primary">Name</label>
                <h6 class="text-dark card_heading"><?php echo $employee_detail[0][0]['employee_name'] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-pie-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary">Date Of Joining</label>
                <h6  class="text-dark card_heading"><?php echo date('d-m-Y', strtotime($employee_detail[0]['Employee']['joined_date']))  ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-stopwatch-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary">Designation</label>
                <h6  class="text-dark card_heading"><?php echo $employee_detail[0]['Designation']['designation'] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-world-outline"></i>-->
                   <div class="dash-content">
                <label class="tx-primary">Band</label>
                <h6 class="text-dark card_heading"><?php echo $employee_detail[0]['Band']['name'] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 bd-b pr-0  bg-white ">
              <!--<i class="icon ion-ios-analytics-outline"></i>-->
                  <div class="col-lg-6 col-md-6 pt-0 pb-0 bd-r pl-0 pr-0  bg-white ">
              <div class="dash-content">
                <label class="tx-primary">Total Experience</label>
                <h6 class="text-dark card_heading"><?php echo $work_experience['total']; ?></h6>
              </div><!-- dash-content -->
              </div><!-- dash-content -->
                  <div class="col-lg-6 col-md-6 pt-0 pb-0 pl-0 pr-0  bg-white ">
              <div class="dash-content">
                <label class="tx-primary">HTL Experience</label>
                <h6 class="text-dark card_heading"><?php echo $work_experience['htl']; ?></h6>
              </div><!-- dash-content -->
              </div><!-- dash-content -->
            <!--</div> col-3 -->
          </div><!-- row -->
          </div>
          <div class="row no-gutters">
            <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white ">
              <!--<i class="icon ion-ios-analytics-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary">Reporting Manager</label>
                <h6 class="text-dark card_heading"><?php echo $employee_detail[0][0]['manager_name'] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-pie-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary"><?php echo $practice; ?></label>
                <h6  class="text-dark card_heading"><?php echo $employee_detail[0]['CompanyStructure']['name']  ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-stopwatch-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary"><?php echo $sub_practice; ?></label>
                <h6  class="text-dark card_heading"><?php echo $employee_detail[0]['CompanyStructureGroup']['name'] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-2 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-world-outline"></i>-->
              <div class="dash-content">
                <label class="tx-primary">Category</label>
                <h6 class="text-dark card_heading"><?php echo $billable_status[$employee_detail[0]['Employee']['billable']] ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
            <div class="col-lg-3 col-md-6 pt-2 pb-0 pl-0 pr-0 bd-b bg-white">
              <!--<i class="icon ion-ios-world-outline"></i>-->
               <div class="col-lg-6 col-md-6 pt-0 pb-0 pl-0 pr-0  bd-r bg-white ">
              <div class="dash-content">
                <label class="tx-primary">Last Promotion</label>
                <h6 class="text-dark card_heading"><?php echo (isset($last_promotion) || !empty($last_promotion))?$last_promotion : '-'; ?></h6>
              </div><!-- dash-content -->
              </div><!-- dash-content -->
                  <div class="col-lg-6 col-md-6 pt-0 pb-0 pl-0 pr-0  bg-white ">
              <div class="dash-content">
                <label class="tx-primary">Last Rating</label><?php  ?>
                <h6 class="text-dark card_heading"><?php echo (!empty($last_rating))?$last_rating : '-'; ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
          </div><!-- row -->
        </div>
        </div>
<?php
}
if ($view == 'pm_review') {
    echo $this->element('kras/pm_review', $emp_kra_details);
} else if($view =='rm_feedback'){
     echo $this->element('kras/rm_feedback', $emp_kra_details);
} else {
    echo $this->element('kras/employee_kra', $emp_kra_details);
}

?>
