  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<?php echo $html->css('/kra/lib/jquery-toggles/css/toggles-full.css'); ?>
  <style>
  .elatest {
        width: 6%;
    left: 92%;
    position: absolute;
    top: 36%;
  }
  
  </style>
<div class="container">
    <div class="slim-pageheader">
        <ol class="breadcrumb slim-breadcrumb">
            <li class="breadcrumb-item"><a href="#">KRA</a></li>
            <li class="breadcrumb-item active" aria-current="page">Repotees</li>
        </ol>
        <h6 class="slim-pagetitle">Repotees</h6>
    </div>
    <div class="section-wrapper">
        <label class="section-title">Filter</label>
        <p class="mg-b-20 mg-sm-b-40">choose your reportees to review KRA.</p>

        <div class="form-layout">
            <div class="row mg-b-25">
                <div class="col-lg-8">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Employee name or ID : <span class="tx-danger">*</span></label>
                        <!--<input class="form-control" id = 'tags' type="text" name="address" value="" placeholder="Enter employee id or first name or last name">-->
                  <select class="form-control select selectEmployee" id = 'combobox' data-placeholder="Choose country" tabindex="-1" aria-hidden="true">
                      <option selected="selected" value="0">Select one...</option>
    <?php foreach($employeeList as $key => $employeeName) {?>
    <option value="<?php echo $key;?>"><?php echo $employeeName;?></option>
    <?php }?>
  </select>
                        
<!--<button id="toggle">Show underlying select</button>-->
                    </div>
                </div><!-- col-8 -->
                
                <div class="col-lg-1">
                    <div class="form-group mg-b-10-force">
                        <label class="form-control-label">Reviewed </label>
                            <div class=" toggle toggle-light success mg-t-10"></div>
                    </div>
                </div>
            </div><!-- row -->

            <!--            <div class="form-layout-footer">
                          <button class="btn btn-primary bd-0">submit</button>
                          <button class="btn btn-secondary bd-0">Reset</button>
                        </div> form-layout-footer -->
        </div><!-- form-layout -->
    </div> 
    <div class="section-wrapper mg-t-20 emloyeeKraDetails">
       
    </div>
</div>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
     
    </script>
     <?php echo $javascript->link('/kra/lib/jquery-toggles/js/toggles.min.js');
      echo $javascript->link('/kra/js/reportees.js');?>
   
