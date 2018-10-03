<div class="row">
    <div  class="col-lg-12 order-lg-2">
        <div class="section-wrapper mg-t-20">
            <label class="section-title">Pending Reviews</label>
            <p class="mg-b-20 mg-sm-b-40">Kra year 2018</p>
            <div class="table-responsive">

                <table class="table table-bordered">
                    <thead class="thead-colored">
                        <tr>
                            <th class="wd-5p">#</th>
                            <th class="wd-10p">Project Code</th>
                            <th class="wd-10p">Employee</th> 
                            <th class="wd-10p">Manager</th> 
                            <th class="wd-10p">Band</th>
                            <th class="wd-10p">From Date</th>
                            <th class="wd-10p">To Date</th>
                            <th class="wd-5p">Year</th>
                            <th class="wd-10p">Status</th>
                            <!--<th class="wd-5p">Overall Rating</th>-->
                            <th class="wd-5p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 1; 
                    foreach ($employee_kra_details as $kra_details) {
                        ?>
                        <tr>
                            <td><span><?php echo $count ?></span></td>
                            <td><span><?php echo $kra_details[0]['project_name'] ?></span></td>
                            <td><span><?php echo $kra_details[0]['employee_name'] ?></span></td>
                            <td><span><?php echo $kra_details['0']['manager'] ?></span></td>
                            <td><span><?php echo $kra_details['Band']['name'] ?></span></td>
                            <td><span><?php echo !empty($kra_details['EmployeeKraMapping']['from_date'])? date('d-m-Y', strtotime($kra_details['EmployeeKraMapping']['from_date'])):'' ?></span></td>
                            <td><span><?php echo !empty($kra_details['EmployeeKraMapping']['to_date'])? date('d-m-Y', strtotime($kra_details['EmployeeKraMapping']['to_date'])):'Till Now' ?></span></td>
                            <td><span><?php echo $kra_details['EmployeeKraMapping']['year'] ?></span></td>
                            <td><span  class="tx-12 <?php echo $status_class[$kra_details['EmployeeKraMapping']['status']] ?>"><?php echo $status[$kra_details['EmployeeKraMapping']['status']] ?></span></td>
                            <!--<td><span><?php echo $kra_details['EmployeeKraMapping']['overall_rating'] ?></span></td>-->
                            <td>
                                    <a href="<?php echo BASE_PATH; ?>kra_masters/employee_kra/<?php echo $kra_details['Employee']['id']?>/<?php echo $kra_details['EmployeeKraMapping']['id']?>/pm_review" style="color: #5b636a;font-size: 20px;"><i class="icon ion-compose"></i></a>
                            </td>
                        </tr>
                       
<?php  $count++; } ?>
                                    </tbody>

                                    </table>
            </div>
        </div>
    </div>
</div>