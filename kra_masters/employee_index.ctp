<style>
    .overflow{
        overflow-y: hidden;
    }
    .overflow:hover {
        overflow-y: scroll;
    }
</style>
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
                <label class="tx-primary">htl Experience</label>
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
                <label class="tx-primary">Last Rating</label>
                <h6 class="text-dark card_heading"><?php echo (!empty($last_rating))?$last_rating : '-'; ?></h6>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
          </div><!-- row -->
        </div>
        </div>
<div class="row">
    <div  class="col-lg-12 order-lg-2">
        <div class="section-wrapper mg-t-20 ">
            <div class="back_button">
            <div><label class="section-title">KRA Details</label>
            <p>KRA Year 2018 - 2019</p>
            </div>
            <div>
                <label class="section-title">Reference Notes
                    <span href="#" id ='event' class="messages-compose cursor-pointer mg-l-25"><i class="text-primary icon ion-compose"></i></span>
                </label><!--<a href="javascript:;" onclick="javascript:back_button()"><i class="fa fa-arrow-left"></i>Back</a>-->
            </div>
            </div>
            <div>
            <div class="table-responsive">

                <table class="table table-bordered">
                    <thead class="thead-colored">
                        <tr>
                            <th class="wd-5p">#</th>
                            <th class="wd-15p">Reporting Manager</th> 
                            <th class="wd-5p">Band</th> 
                            <th class="wd-10p"><?php echo $sub_practice; ?></th>
                            <th class="wd-10p">From Date</th>
                            <th class="wd-10p">To Date</th>
                            <th class="wd-5p">Year</th>
                            <th class="wd-10p">Status</th>
                            <th class="wd-5p">Rating</th>
                            <th class="wd-5p">Employee Remarks</th>
                            <th class="wd-5p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 1;  
                    if(empty($employee_kra_details)){
                        echo "<tr><td class='text-center' colspan='11'>KRA Not Assigned</td></tr>";
                    }else{
                    foreach ($employee_kra_details as $kra_details) { 
                        ?>
                        <tr>
                            <td><span><?php echo $count ?></span></td>
                            <!--<td><span><?php //echo $kra_details[0]['projectmanager'] ?></span></td>-->
                            <td><span><?php echo $kra_details[0]['manager'] ?></span></td>
                            <td><span><?php echo $kra_details['Band']['name'] ?></span></td>
                            <td><span><?php echo $kra_details['CompanyStructure']['name'] ?></span></td>
                            <td><span><?php echo !empty($kra_details['EmployeeKraMapping']['from_date'])? date('d-m-Y', strtotime($kra_details['EmployeeKraMapping']['from_date'])):'' ?></span></td>
                            <td><span><?php echo !empty($kra_details['EmployeeKraMapping']['to_date'])? date('d-m-Y', strtotime($kra_details['EmployeeKraMapping']['to_date'])):'Till Now' ?></span></td>
                            <td><span><?php echo $kra_details['EmployeeKraMapping']['year'] ?></span></td>
                            <td><span  class="tx-12 <?php echo $status_class[$kra_details['EmployeeKraMapping']['status']] ?>"><?php echo $kra_details['ConfigurationValue']['configuration_value'] ?></span></td>
                            <td><span><?php echo ($kra_details['EmployeeKraMapping']['status']=='n')?$kra_details['EmployeeKraMapping']['overall_rating']:'' ?></span></td>
                            <?php if($kra_details['EmployeeKraMapping']['status']=='n'){  ?>
                                <?php if(is_null($kra_details['EmployeeKraMapping']['employee_feedback'])){  ?>
                                <td class="text-center"><a href="<?php echo BASE_PATH; ?>kra_masters/employee_view/0/<?php echo $kra_details['EmployeeKraMapping']['id']?>" style="color: #5b636a;font-size: 20px;"><i class="fa fa-pencil"></i></a></td>
                                <?php }else{ ?>
                                <td><span class="text-capitalize"><?php echo $kra_details['EmployeeKraMapping']['employee_feedback']; ?></span></td>
                                <?php } ?>
                            <?php }else{ ?>
                                <td></td>
                            <?php } ?>
                            <td class="text-center">
                                <?php if(in_array($kra_details['EmployeeKraMapping']['status'], $edit_status)){ ?>
                                    <a href="<?php echo BASE_PATH; ?>kra_masters/employee_kra/0/<?php echo $kra_details['EmployeeKraMapping']['id']?>" style="color: #5b636a;font-size: 20px;"><i class="icon ion-compose"></i></a>
                                <?php } ?>
                                    <a href="<?php echo BASE_PATH; ?>kra_masters/employee_view/0/<?php echo $kra_details['EmployeeKraMapping']['id']?>" style="color: #5b636a;font-size: 20px;"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                       
                    <?php  $count++; } } ?>
                                    </tbody>

                                    </table>
            </div>
        </div>
    </div>
</div>
<div id="eventmodal" class="modal fade" mapping_id='<?php echo $employee_kra_details[0]['EmployeeKraMapping']['id']  ?>'>
    <div class="modal-dialog modal-lg wd-700" role="document">
        <div class="modal-content bd-0 tx-14 ht-350">
            <div class="modal-header pd-x-20">
                <h6 class="tx-14 mg-b-0 tx-uppercase tx-inverse tx-bold">Reference Notes</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div id="comments-container" class="overflow mg-t-20 mg-b-30"></div>
        </div>
    </div><!-- modal-dialog -->
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.0/jquery.textcomplete.js"></script>
<script type="text/javascript">
    var usersArray=[
                    {id:<?php echo $employee_detail[0]['Employee']['id'] ?>,fullname:"<?php echo $employee_detail[0]['Employee']['first_name']; ?>",profile_picture_url:"<?php echo !empty($employee_detail['Employee']['employee_photo']) ? file_exists(WEB_ROOT_DIRECTORY . "employee_photos/" . $employee_detail['Employee']['employee_photo']) ? BASE_PATH . "uploads/employee_photos/" . $employee_detail['Employee']['employee_photo'] : BASE_PATH . "img/profile_pic.jpg" : BASE_PATH . "img/profile_pic.jpg";  ?>"},
                    {id:<?php echo $employee_detail[0]['Manager']['id'] ?>,fullname:"<?php echo $employee_detail[0]['Manager']['first_name']; ?>",profile_picture_url:"<?php echo !empty($employee_detail['Manager']['employee_photo']) ? file_exists(WEB_ROOT_DIRECTORY . "employee_photos/" . $employee_detail['Manager']['employee_photo']) ? BASE_PATH . "uploads/employee_photos/" . $employee_detail['Manager']['employee_photo'] : BASE_PATH . "img/profile_pic.jpg" : BASE_PATH . "img/profile_pic.jpg";  ?>"}
                ];
</script>
          
<?php  
    echo $javascript->link('/kra/js/employee_index.js'); 
    echo $javascript->link('/kra/lib/jquery-comments/js/jquery-comments.js'); 
    echo $javascript->link('/kra/lib/moment/js/moment.js');
    echo $html->css('/kra/lib/jquery-comments/css/jquery-comments.css');
 ?>

                                       
