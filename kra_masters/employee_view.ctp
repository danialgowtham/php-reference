
<?php 
echo $this->element('kras/employee_view',$emp_kra_details);  ?>

 <?php  
    echo $javascript->link('/kra/js/employee_view.js'); 
    echo $javascript->link('/kra/lib/jquery-comments/js/jquery-comments.js'); 
    echo $javascript->link('/kra/lib/moment/js/moment.js');
    echo $html->css('/kra/lib/jquery-comments/css/jquery-comments.css');
 ?>