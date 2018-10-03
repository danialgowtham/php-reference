<?php

App::import('Controller', 'KraMasters');

class TrainingCalendersController extends AppController {

    var $name = 'TrainingCalenders';
    var $helpers = array('Html', 'Form', 'Ajax', 'Javascript', 'DatePicker', 'Xls');
    var $uses = array('DlMaster', 'ConfigurationValue', 'Employee', 'ProjectTeamAllocation', 'CompanyLocation', 'City', 'Band', 'CompanyStructure', 'TrainingCalender', 'TrainingDuration', 'TraineeNominationDetail', 'CustomerWorkLocation');
    var $components = array('RequestHandler', 'Email', 'Session', 'Common');

    function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('add_training', 'training_view', 'edit_training', 'remove_duration', 'list_page', 'nomination', 'get_employee_details', 'export_training', 'cancel_nomination', 'attendance_entry','timesheet_design');
    }
    
    function timesheet_design(){
         $this->layout = "training";
    }

    function add_training($type = '') {
        $this->layout = "training";
        if ($this->RequestHandler->isAjax()) {
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                $this->autoRender = false;
                if ($type == 'submit')
                    $status = 'o';
                else if ($type == 'save') {
                    $status = 's';
                }
                $flag = false;
                $save_traing_calenders = $_POST['data'];
                $save_traing_calenders['traing_calenders']['id'] = (isset($save_traing_calenders['traing_calenders']['id']) && !empty($save_traing_calenders['traing_calenders']['id'])) ? $save_traing_calenders['traing_calenders']['id'] : '';
                $save_traing_calenders['traing_calenders']['date'] = (isset($save_traing_calenders['traing_calenders']['date']) && !empty($save_traing_calenders['traing_calenders']['date'])) ? date('Y-m-d', strtotime($save_traing_calenders['traing_calenders']['date'])) : NULL;
                $save_traing_calenders['traing_calenders']['band'] = $str = implode(",", $save_traing_calenders['traing_calenders']['band']);
                $save_traing_calenders['traing_calenders']['status'] = $status;
                $save_traing_calenders['traing_calenders']['created_by'] = $this->Auth->user("employee_id");
                $save_traing_calenders['traing_calenders']['created_on'] = date('Y-m-d H:i:s');
                $save_traing_calenders['traing_calenders']['deleted'] = 0;
                if ($this->TrainingCalender->save($save_traing_calenders['traing_calenders']))
                    $flag = true;
                if ((isset($_POST['data']['traing_calenders']['id']) && empty($_POST['data']['traing_calenders']['id']))) {
                    $traing_calenders_id = $this->TrainingCalender->getLastInsertId();
                } else {
                    $traing_calenders_id = $_POST['data']['traing_calenders']['id'];
                }
                $save_training_durations_array = $_POST['datelist'];
                $save_training_duration = array();
                foreach ($save_training_durations_array as $save_training_durations) {
                    $save_training_duration['training_calender_id'] = $traing_calenders_id;
                    $save_training_duration['deleted'] = 0;
                    if (!empty($save_training_durations['start_time']) && !empty($save_training_durations['end_time']) && !empty($save_training_durations['date'])) {
                        $save_training_duration['id'] = (isset($save_training_durations['id']) && !empty($save_training_durations['id'])) ? $save_training_durations['id'] : '';
                        $save_training_duration['duration_date'] = date('Y-m-d', strtotime($save_training_durations['date']));
                        $save_training_duration['start_time'] = $save_training_durations['start_time'];
                        $save_training_duration['end_time'] = $save_training_durations['end_time'];
                        $save_training_duration['time_difference'] = $save_training_durations['time_difference'];
                        if ($this->TrainingDuration->save($save_training_duration))
                            $flag = true;
                    }
                }
                if ($flag) {
                    echo 'SUCCESS';
                    exit();
                }
            }
        }
        $emp_id = $this->Auth->user("employee_id");
        if (empty($emp_id)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $nomination_type = $this->ConfigurationValue->find('list', array('fields' => array('Configuration_inself.configuration_key', 'Configuration_inself.configuration_value'),
            'joins' => array(array(
                    'table' => 'configuration_values',
                    'alias' => 'Configuration_inself',
                    'type' => 'INNER',
                    'conditions' => array('Configuration_inself.parent_id = ConfigurationValue.id')
                )),
            'conditions' => array('ConfigurationValue.configuration_value' => 'nomination_type'
            ))
        );
        $band = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL')));
        $company_unit_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructureChild.id', 'CompanyStructureChild.name'),
            'joins' => array(array(
                    'table' => 'company_structures',
                    'alias' => 'CompanyStructureChild',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyStructure.id = CompanyStructureChild.Parent_id'
                    ))
            ), 'conditions' => array('CompanyStructure.parent_id' => 0)));
        $company_location_list = $this->CompanyLocation->find('list', array('fields' => array('CompanyLocation.id', 'City.city'),
            'joins' => array(array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyLocation.city_id = City.id'
                    ))
            ), 'conditions' => array('CompanyLocation.deleted' => 0)));
        unset($company_unit_list[43]);
        $this->set('band', $band);
        $this->set('nomination_type', $nomination_type);
        $this->set('company_location_list', $company_location_list);
        $this->set('company_unit_list', $company_unit_list);
    }

    function edit_training($traing_calenders_id) {
        $this->layout = "training";
        $emp_id = $this->Auth->user("employee_id");
        if (empty($emp_id)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        } else if (!empty($traing_calenders_id)) {
            $edit_data = $this->TrainingCalender->find('first', array('fields' => array('TrainingCalender.*', 'CompanyLocation.id'),
                'joins' => array(
                    array(
                        'table' => 'company_locations',
                        'alias' => 'CompanyLocation',
                        'type' => 'left',
                        'conditions' => array(
                            'TrainingCalender.venue = CompanyLocation.id'
                        ))
                ),
                'conditions' => array('TrainingCalender.id' => $traing_calenders_id, 'TrainingCalender.deleted' => 0)
            ));
            $duration_edit_data = $this->TrainingDuration->find('all', array('fields' => array('TrainingDuration.*'),
                'conditions' => array('TrainingDuration.training_calender_id' => $traing_calenders_id, 'TrainingDuration.deleted' => 0)
            ));
            $band = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL')));
            $company_unit_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructureChild.id', 'CompanyStructureChild.name'),
                'joins' => array(array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructureChild',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyStructure.id = CompanyStructureChild.Parent_id'
                        ))
                ), 'conditions' => array('CompanyStructure.parent_id' => 0)));
            $company_location_list = $this->CompanyLocation->find('list', array('fields' => array('CompanyLocation.id', 'City.city'),
                'joins' => array(array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyLocation.city_id = City.id'
                        ))
                ), 'conditions' => array('CompanyLocation.deleted' => 0)));

            $nomination_type = $this->ConfigurationValue->find('list', array('fields' => array('Configuration_inself.configuration_key', 'Configuration_inself.configuration_value'),
                'joins' => array(array(
                        'table' => 'configuration_values',
                        'alias' => 'Configuration_inself',
                        'type' => 'INNER',
                        'conditions' => array('Configuration_inself.parent_id = ConfigurationValue.id')
                    )),
                'conditions' => array('ConfigurationValue.configuration_value' => 'nomination_type'
                ))
            );

            unset($company_unit_list[43]);
            $this->set('nomination_type', $nomination_type);
            $this->set('band', $band);
            $this->set('edit_data', $edit_data);
            $this->set('duration_edit_data', $duration_edit_data);
            $this->set('edit_page', 'edit_page');
            $this->set('company_location_list', $company_location_list);
            $this->set('company_unit_list', $company_unit_list);
            $this->render('add_training');
        }
    }

    function remove_duration($id) {
        if ($this->RequestHandler->isAjax()) {
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                $this->autoRender = false;
                $update_training_duration['id'] = $id;
                $update_training_duration['deleted'] = 1;
                $this->TrainingDuration->save($update_training_duration);
            }
        }
    }

    function list_page($view) {
        $emp_id = $this->Auth->user("employee_id");
        if (empty($emp_id)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $this->layout = "training";
        $this->set('view', $view);
        $cond = " TrainingCalender.deleted=0";

                    if ($view == "hr_view") {
                        $cond .= " and TrainingCalender.status IN ('o','s','c')";
                    } elseif ($view == "employee_view") {
                        $cond .= "  and TrainingCalender.status IN ('o') and TrainingCalender.date>CURDATE()";
                    }
        $training_details = $this->TrainingCalender->find('all', array('fields' => array('TrainingCalender.*','COUNT(TraineeNominationDetail.id) as total_nominated','CompanyStructure.name','City.city','ConfigurationValue.configuration_value'),
                'joins' => array(
                    array(
                        'table' => 'trainee_nomination_details',
                        'alias' => 'TraineeNominationDetail',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'TraineeNominationDetail.training_calender_id = TrainingCalender.id',
                            'TraineeNominationDetail.deleted'=>0
                        )),
                    array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructure',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyStructure.id = TrainingCalender.su_id'
                        )),
                    array(
                        'table' => 'company_locations',
                        'alias' => 'CompanyLocation',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyLocation.id = TrainingCalender.venue'
                        )),
                    array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'left',
                        'conditions' => array(
                            'City.id = CompanyLocation.city_id'
                        )),
                    array(
                        'table' => 'configuration_values',
                        'alias' => 'ConfigurationValueParent',
                        'type' => 'left',
                        'conditions' => array(
                            'ConfigurationValueParent.configuration_value'=>'learning_status'
                        )),
                    array(
                        'table' => 'configuration_values',
                        'alias' => 'ConfigurationValue',
                        'type' => 'left',
                        'conditions' => array(
                            'ConfigurationValueParent.id = ConfigurationValue.parent_id',
                            ' TrainingCalender.status=ConfigurationValue.configuration_key'
                        )),
                ),
                'conditions' => array($cond),
                'group' => 'TrainingCalender.id'));
        
        $band_list = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL')));
        $status_class = array('Open' => 'badge badge-success', 'Save' => 'badge badge-info', 'Closed' => 'badge badge-danger');
        $edit_status = array('Open', 'Save');
        $this->set('training_details',$training_details);
        $this->set('band_list',$band_list);
        $this->set('status_class',$status_class);
        $this->set('edit_status',$edit_status);
    }

    function training_view($traing_calenders_id, $view) {
        $this->layout = "training";
        $this->set('view', $view);
        $emp_id = $this->Auth->user("employee_id");
        if (empty($emp_id)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        } else if (!empty($traing_calenders_id)) {
            $edit_data = $this->TrainingCalender->find('first', array('fields' => array('TrainingCalender.*', 'City.city', 'CompanyStructure.name','TrainingCalender.max_nomination - count(TraineeNominationDetail.id) as remaining_nomination'),
                'joins' => array(
                    array(
                            'table' => 'trainee_nomination_details',
                            'alias' => 'TraineeNominationDetail',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'TraineeNominationDetail.training_calender_id = TrainingCalender.id',
                                'TraineeNominationDetail.deleted'=>0
                         )),
                    array(
                        'table' => 'company_locations',
                        'alias' => 'CompanyLocation',
                        'type' => 'left',
                        'conditions' => array(
                            'TrainingCalender.venue = CompanyLocation.id'
                        )),
                    array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyLocation.city_id = City.id'
                        )),
                    array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructure',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyStructure.id = TrainingCalender.su_id'
                        ))
                ),
                'conditions' => array('TrainingCalender.id' => $traing_calenders_id, 'TrainingCalender.deleted' => 0),
                'group' => 'TrainingCalender.id'
            ));
            $duration_edit_data = $this->TrainingDuration->find('all', array('fields' => array('TrainingDuration.*'),
                'conditions' => array('TrainingDuration.training_calender_id' => $traing_calenders_id, 'TrainingDuration.deleted' => 0)
            ));
            $band = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL')));
            $company_unit_list = $this->CompanyStructure->find('list', array('fields' => array('CompanyStructureChild.id', 'CompanyStructureChild.name'),
                'joins' => array(array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructureChild',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyStructure.id = CompanyStructureChild.Parent_id'
                        ))
                ), 'conditions' => array('CompanyStructure.parent_id' => 0)));
            $company_location_list = $this->CompanyLocation->find('list', array('fields' => array('CompanyLocation.id', 'City.city'),
                'joins' => array(array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyLocation.city_id = City.id'
                        ))
                ), 'conditions' => array('CompanyLocation.deleted' => 0)));
            $bands = explode(',', $edit_data['TrainingCalender']['band']);
            $band_list2 = $this->Band->find('list', array('fields' => array('Band.id'), 'conditions' => array('Band.parent_id' => $bands)));
            //$select_band = implode(',', $select_band);
            if ($edit_data['TrainingCalender']['su_id'] != 'ALL') {
                $cond = "Employee.structure_name=" . $edit_data['TrainingCalender']['su_id'];
            } else {
                $cond = 'Employee.structure_name is not null';
            }
            $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee'),
                'joins' => array(array(
                        'table' => 'trainee_nomination_details',
                        'alias' => 'TraineeNominationDetail',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'TraineeNominationDetail.employee_id = Employee.id', 'TraineeNominationDetail.training_calender_id' => $traing_calenders_id, 'TraineeNominationDetail.deleted=0'
                        ))
                ), 'conditions' => array('Employee.manager' => $this->Auth->user('employee_id'), 'Employee.employment_status NOT IN ("r","t","b","q","o","y")', 'TraineeNominationDetail.employee_id is null', 'Employee.band_id' => $band_list2, $cond)));
            $reportees_list = array();
            foreach ($employee_list as $employees) {
                $reportees_list[$employees['Employee']['id']] = $employees[0]['employee'];
            }
            $nominated_list = $this->TraineeNominationDetail->find('all', array('fields' => array('TraineeNominationDetail.employee_location', 'TraineeNominationDetail.nominated_on', 'Band.name', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee'),
                'joins' => array(
                    array(
                        'table' => 'employees',
                        'alias' => 'Employee',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TraineeNominationDetail.employee_id = Employee.id',
                            'TraineeNominationDetail.training_calender_id' => $traing_calenders_id,
                            'TraineeNominationDetail.deleted=0'
                        )),
                    array(
                        'table' => 'bands',
                        'alias' => 'Band',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Band.id = Employee.band_id'
                        ))
                ),
                'conditions' => array('TraineeNominationDetail.nominated_by' => $this->Auth->user('employee_id'), 'TraineeNominationDetail.employee_id != TraineeNominationDetail.nominated_by')));
            $this->Employee->recursive = 1;
            $self_nominated = $this->Employee->find('all', array('fields' => array('Employee.id', 'TraineeNominationDetail.id'),
                'joins' => array(array(
                        'table' => 'trainee_nomination_details',
                        'alias' => 'TraineeNominationDetail',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'TraineeNominationDetail.employee_id = Employee.id',
                            'TraineeNominationDetail.training_calender_id' => $traing_calenders_id,
                            'TraineeNominationDetail.deleted=0'
                        ))),
                'conditions' => array('Employee.id' => $this->Auth->user('employee_id'), 'Employee.band_id' => $band_list2, $cond)));
            $this->set('nominated_list', $nominated_list);
            $this->set('self_nominated', $self_nominated);

            unset($company_unit_list[43]);
            $this->set('band', $band);
            $this->set('edit_data', $edit_data);
            $this->set('duration_edit_data', $duration_edit_data);
            $this->set('edit_page', 'edit_page');
            $this->set('company_location_list', $company_location_list);
            $this->set('company_unit_list', $company_unit_list);
            if ($view == 'hr_view') {
                $nominated_overall_list = $this->TrainingCalender->find('all', array('fields' => array('Employee.id', 'TraineeNominationDetail.*',
                        'TraineeNominationDetail.employee_location', 'TraineeNominationDetail.nominated_on', 'Band.name', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',
                        'CONCAT(NominatedBy.employee_number," - ",NominatedBy.first_name," ", NominatedBy.last_name) as nominated_by', 'MAX(TrainingDuration.duration_date) as max_Date', 'SEC_TO_TIME( SUM( TIME_TO_SEC( TrainingDuration.time_difference) ) ) as total_hours'),
                    'joins' => array(
                        array(
                            'table' => 'trainee_nomination_details',
                            'alias' => 'TraineeNominationDetail',
                            'type' => 'INNER',
                            'conditions' => array(
                                'TraineeNominationDetail.training_calender_id = TrainingCalender.id'
                            )),
                        array(
                            'table' => 'training_durations',
                            'alias' => 'TrainingDuration',
                            'type' => 'INNER',
                            'conditions' => array(
                                'TrainingDuration.training_calender_id = TrainingCalender.id'
                            )),
                        array(
                            'table' => 'employees',
                            'alias' => 'Employee',
                            'type' => 'INNER',
                            'conditions' => array(
                                'TraineeNominationDetail.employee_id = Employee.id'
                            )),
                        array(
                            'table' => 'employees',
                            'alias' => 'NominatedBy',
                            'type' => 'INNER',
                            'conditions' => array(
                                'TraineeNominationDetail.nominated_by = NominatedBy.id'
                            )),
                        array(
                            'table' => 'bands',
                            'alias' => 'Band',
                            'type' => 'left',
                            'conditions' => array(
                                'Band.id = Employee.band_id'
                            ))
                    ),
                    'conditions' => array('TrainingCalender.id' => $traing_calenders_id, 'TraineeNominationDetail.deleted=0'),
                    'group' => 'TraineeNominationDetail.id'));
                $this->set('attendance_view', true);
                $this->set('nominated_overall_list', $nominated_overall_list);
                $employee_list = $this->Employee->find('all', array('fields' => array('Employee.id', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee'),
                    'joins' => array(array(
                            'table' => 'trainee_nomination_details',
                            'alias' => 'TraineeNominationDetail',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'TraineeNominationDetail.employee_id = Employee.id', 'TraineeNominationDetail.training_calender_id' => $traing_calenders_id, 'TraineeNominationDetail.deleted=0'
                            ))
                    ), 'conditions' => array('Employee.employment_status NOT IN ("r","t","b","q","o","y")', 'TraineeNominationDetail.employee_id is null', 'Employee.band_id' => $band_list2, $cond)));
                $reportees_list = array();
                foreach ($employee_list as $employees) {
                    $reportees_list[$employees['Employee']['id']] = $employees[0]['employee'];
                }
            }
            $this->set('reportees_list', $reportees_list);
        }
    }

    function nomination($type,$view) {
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                if ($type == 'self_nominate') {
                    $save_data = array();
                    $this->ProjectTeamAllocation->recursive = -1;
                    $location_list = $this->ProjectTeamAllocation->query("SELECT
                                    CONCAT('HTL - ',cmpCity.city) AS company_location,
                                    CASE WHEN pta.location_table = 'company_locations'
                                    THEN CONCAT('HTL - ',workCmpCity.city)
                                    ELSE CONCAT(cwl.address_shortcode,' ',workCustCity.city)  END AS work_location
                                    FROM employees AS emp
                                    LEFT JOIN project_team_allocations AS pta ON(emp.id = pta.employee_id AND pta.deleted = 0 AND pta.end_date>CURDATE())
                                    LEFT JOIN customer_work_locations AS cwl ON(pta.work_location_id = cwl.id)
                                    LEFT JOIN company_locations AS cl ON(pta.work_location_id = cl.id)
                                    LEFT JOIN projects AS pjt ON(pta.project_id = pjt.id AND pjt.project_status ='e')
                                    LEFT JOIN company_locations AS bl ON(emp.current_location_id = bl.id)
                                    LEFT JOIN cities AS cmpCity ON(bl.city_id = cmpCity.id)
                                    LEFT JOIN cities AS workCmpCity ON(cl.city_id = workCmpCity.id)
                                    LEFT JOIN cities AS workCustCity ON(cwl.city_id = workCustCity.id)
                                    WHERE emp.id =" . $this->Auth->user('employee_id') . "  and emp.employment_status NOT IN ('r','t','b','q','o','y') ORDER BY pta.id LIMIT 1 ");
                    $save_data['training_calender_id'] = $_POST['training_calender_id'];
                    $save_data['employee_id'] = $this->Auth->user('employee_id');
                    $save_data['employee_location'] = !empty($location_list[0][0]['work_location']) ? $location_list[0][0]['work_location'] : $location_list[0][0]['company_location'];
                    $save_data['nominated_by'] = $this->Auth->user('employee_id');
                    $save_data['nominated_on'] = date('Y-m-d H:i:s');
                    $save_data['deleted'] = 0;
                    $this->TraineeNominationDetail->create();
                    if ($this->TraineeNominationDetail->save($save_data) && $this->_mail_table_creation($view,$_POST['training_calender_id'], $type)) {
                        echo 'SUCCESS';
                    }
                } else if ($type == 'reportees_nominate') {
                    $flag = false;
                    $save_data = array();
                    $employee_list = $this->data;
                    foreach ($employee_list as $employee_id => $location) {
                        $save_data['training_calender_id'] = $_POST['training_calender_id'];
                        $save_data['employee_id'] = $employee_id;
                        $save_data['employee_location'] = $location['location'];
                        $save_data['nominated_by'] = $this->Auth->user('employee_id');
                        $save_data['nominated_on'] = date('Y-m-d H:i:s');
                        $save_data['deleted'] = 0;
                        $this->TraineeNominationDetail->create();
                        if ($this->TraineeNominationDetail->save($save_data))
                            $flag = true;
                        if ($_POST['nomination_by'] != 'hr_nomination') {
                            $this->_mail_table_creation($view,$_POST['training_calender_id'], $type, $employee_id);
                        }
                    }
                    if ($flag)
                        echo 'SUCCESS';
                }
            }
        }
    }

    function get_employee_details() {
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                $employee_ids = implode(',', $this->data);

                $employee_detail = $this->Employee->query("SELECT CONCAT(emp.id)as emp_id,CONCAT(Band.name) as bname,CONCAT(emp.employee_number,' - ',emp.first_name,' ', emp.last_name) as employee,
                                    CONCAT('HTL - ',cmpCity.city) AS company_location,
                                    CASE WHEN pta.location_table = 'company_locations'
                                    THEN CONCAT('HTL - ',workCmpCity.city)
                                    ELSE CONCAT(cwl.address_shortcode,' ',workCustCity.city)  END AS work_location
                                    FROM employees AS emp
                                    LEFT JOIN project_team_allocations AS pta ON(emp.id = pta.employee_id AND pta.deleted = 0 AND pta.end_date>CURDATE())
                                    LEFT JOIN customer_work_locations AS cwl ON(pta.work_location_id = cwl.id)
                                    LEFT JOIN company_locations AS cl ON(pta.work_location_id = cl.id)
                                    LEFT JOIN projects AS pjt ON(pta.project_id = pjt.id AND pjt.project_status ='e')
                                    LEFT JOIN company_locations AS bl ON(emp.current_location_id = bl.id)
                                    LEFT JOIN cities AS cmpCity ON(bl.city_id = cmpCity.id)
                                    LEFT JOIN cities AS workCmpCity ON(cl.city_id = workCmpCity.id)
                                    LEFT JOIN cities AS workCustCity ON(cwl.city_id = workCustCity.id)
                                    LEFT JOIN bands  AS Band ON(Band.id = emp.band_id)
                                    WHERE emp.id in(" . $employee_ids . ") and emp.employment_status NOT IN ('r','t','b','q','o','y') group by emp.id ORDER BY emp.id ");
                $message = array('SUCCESS');
                $response = array('message' => $message,
                    'options' => $employee_detail);
                echo json_encode($response);
            }
        }
    }

    function _mail_table_creation($view,$traing_calenders_id, $type, $employee_id = '') {

        if ($employee_id == '') {
            $employee_id = $this->Auth->user('employee_id');
        }

        $training_data_mail = $this->TrainingCalender->find('first', array('conditions' => array('TrainingCalender.id' => $_POST['training_calender_id'])));
        $company_location_list = $this->CompanyLocation->find('list', array('fields' => array('CompanyLocation.id', 'City.city'),
            'joins' => array(array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'left',
                    'conditions' => array(
                        'CompanyLocation.city_id = City.id'
                    ))
            ), 'conditions' => array('CompanyLocation.deleted' => 0)));
        $temp_venue = $company_location_list[$training_data_mail['TrainingCalender']['venue']];
        $training_data_mail['TrainingCalender']['venue'] = !empty($temp_venue) ? $temp_venue : $training_data_mail['TrainingCalender']['venue'];

        $this->Employee->recursive = -1;
        $mail_detail = $this->Employee->find('first', array('fields' => array('Employee.first_name', 'Employee.last_name', 'Employee.work_email_address', 'Employee.employee_number',
                'EmployeeManager.first_name', 'EmployeeManager.last_name', 'EmployeeManager.work_email_address', 'EmployeeManager.employee_number'),
            'joins' => array(
                array(
                    'table' => 'employees',
                    'alias' => 'EmployeeManager',
                    'type' => 'INNER',
                    'conditions' => array(
                        'EmployeeManager.id = Employee.manager'
                    ))), 'conditions' => array('Employee.id =' => $employee_id)));
        $mail_cc = $this->DlMaster->find('first', array('fields' => array('DlMaster.dl_mail'), 'conditions' => array('DlMaster.description="lms_mail"')));
        $to_mail = $mail_detail['Employee']['work_email_address'];
        $cc_email[] = $mail_detail['EmployeeManager']['work_email_address'];
        $cc_email[] = $mail_cc['DlMaster']['dl_mail'];
        $to_name = $mail_detail['Employee']['first_name'] . " " . $mail_detail['Employee']['last_name'];
        $rm_name = $mail_detail['EmployeeManager']['first_name'] . " " . $mail_detail['EmployeeManager']['last_name'];
        $duration_edit_data = $this->TrainingDuration->find('all', array('fields' => array('TrainingDuration.*'),
            'conditions' => array('TrainingDuration.training_calender_id' => $traing_calenders_id, 'TrainingDuration.deleted' => 0)
        ));
        $table = "<style>table{border-collapse: collapse;}tr td, tr th { border:1px solid #7E7E7E; padding: 8px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}";
        $table .= "tr th { background-color:#CFDBEC;padding: 2px 10px;font-weight:bold; }</style>";
        $table .= "<table border='0'><tr><th>Date</th><th>Start Time</th><th>End Time</th><th>Total Hours</th></tr>";
        foreach ($duration_edit_data as $duration_data) {
            $table .= '<tr>';
            $table .= '<td>' . date('d-m-Y', strtotime($duration_data['TrainingDuration']['duration_date'])) . '</td>';
            $table .= '<td>' . $duration_data['TrainingDuration']['start_time'] . '</td>';
            $table .= '<td>' . $duration_data['TrainingDuration']['end_time'] . '</td>';
            $table .= '<td>' . $duration_data['TrainingDuration']['time_difference'] . '</td>';
            $table .= '</tr>';
        }
        $table .= "</table>";
        Configure::load('messages');


        //set correct content-type-header
        $this->Email->to = $to_mail;
        $this->Email->cc = $cc_email;
        if ($type == 'self_nominate') {
            $subjectBody = Configure::read('LMS_EMPLOYEE');
            $this->Email->html_body = sprintf($subjectBody['body'], $to_name, $training_data_mail['TrainingCalender']['title'], $training_data_mail['TrainingCalender']['venue'], $training_data_mail['TrainingCalender']['program_detail'], $table);
        } else if ($type == 'reportees_nominate') {
            if($view=='hr_view'){
            $subjectBody = Configure::read('LMS_HR');
            }
            else{
                $subjectBody = Configure::read('LMS_RM');
            }
            $this->Email->html_body = sprintf($subjectBody['body'], $to_name, $rm_name, $training_data_mail['TrainingCalender']['title'], $training_data_mail['TrainingCalender']['venue'], $training_data_mail['TrainingCalender']['program_detail'], $table);
        }
        $this->Email->subject = sprintf($subjectBody['subject'], $training_data_mail['TrainingCalender']['title']);
        $result = $this->Email->send();


        return true;
    }

    function export_training($id) {
        $emp_id = $this->Auth->user("employee_id");
        if (empty($emp_id)) {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $this->autoRender = false;
        $set = WWW_ROOT . 'PHPExcel' . DS . 'PHPExcel.php';
        require_once($set);
        if ($id == 'export_all') {
            $excelRow[] = array('Title', 'Venue', 'Date', 'Program Detail', 'Band', 'Unit', 'Trainer Name', 'Trainer Detail', 'Present', 'Absent', 'Status');

            $SearchColumns = array('TrainingCalender.id', 'TrainingCalender.title', 'TrainingCalender.venue', 'TrainingCalender.date',
                'TrainingCalender.trainer_name', 'TrainingCalender.status');

            $sWhere = " WHERE TrainingCalender.deleted=0";

            $sWhere .= " and TrainingCalender.status IN ('o','s','c')";
            if ($_POST['main_search'] != "") {
                $sWhere .= " AND ( ";
                for ($i = 0; $i < count($SearchColumns); $i++) {
                    $sWhere .= $SearchColumns[$i] . " LIKE '%" . mysql_real_escape_string($_POST['main_search']) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= " )";
            }

            for ($i = 0; $i < count($SearchColumns); $i++) {
                if ($_POST['induvidual_search_' . $i] != '') {
                    if ($sWhere == "") {
                        $sWhere = "WHERE ";
                    } else {
                        $sWhere .= " AND (";
                    }
                    if ($i == 3) {
                        $_POST['induvidual_search_' . $i] = date('Y-m-d', strtotime($_POST['induvidual_search_' . $i]));
                    }
                    if ($i == 2) {
                        $sWhere .= "TrainingCalender.venue LIKE '%" . mysql_real_escape_string($_POST['induvidual_search_' . $i]) . "%'" . " OR City.city Like '%" . mysql_real_escape_string($_POST['induvidual_search_' . $i]) . "%'";
                    } else {
                        $sWhere .= $SearchColumns[$i] . " LIKE '%" . mysql_real_escape_string($_POST['induvidual_search_' . $i]) . "%'";
                    }
                    $sWhere .= " )";
                }
            }

            $result_temp = "SELECT TrainingCalender.band,TrainingCalender.title,TrainingCalender.trainer_deatils,TrainingCalender.program_detail, TrainingCalender.venue, TrainingCalender.date,TrainingCalender.su_id,
                    TrainingCalender.trainer_name, ConfigurationValue.configuration_value as status_name, City.city,CompanyStructure.name,
                    SUM(CASE WHEN TraineeNominationDetail.attendance ='Present' THEN 1 ELSE 0 END) as total_present,
                    SUM(CASE WHEN TraineeNominationDetail.attendance ='Absent' THEN 1 ELSE 0 END)as total_absent
                    FROM   training_calenders AS TrainingCalender
                    LEFT JOIN company_locations AS `CompanyLocation` ON (`CompanyLocation`.`id` = `TrainingCalender`.`venue`)
                    LEFT JOIN cities AS `City` ON (`City`.`id` = `CompanyLocation`.`city_id`)
                    LEFT JOIN trainee_nomination_details AS `TraineeNominationDetail` ON (`TrainingCalender`.`id` = `TraineeNominationDetail`.`training_calender_id`)
                    LEFT JOIN company_structures  AS CompanyStructure ON(CompanyStructure.id = TrainingCalender.su_id)
                    LEFT JOIN configuration_values as ConfigurationValueParent on(ConfigurationValueParent.configuration_value='learning_status')
                    LEFT JOIN configuration_values as ConfigurationValue on(ConfigurationValue.parent_id=ConfigurationValueParent.id AND
                              TrainingCalender.status=ConfigurationValue.configuration_key)
		$sWhere  GROUP BY TrainingCalender.id";
            $result = $this->Employee->query($result_temp);
            $band_list = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL')));

            ob_end_clean();
            foreach ($result as $training_details) {

                $bands = explode(',', $training_details['TrainingCalender']['band']);
                $select_band = array();
                foreach ($bands as $key => $value) {
                    $select_band[] = $band_list[$value];
                }
                $select_band = implode(',', $select_band);


                $training_details['TrainingCalender']['Title'] = ($training_details['TrainingCalender']['title'] != '') ? $training_details['TrainingCalender']['title'] : '';
                $training_details['TrainingCalender']['Venue'] = ($training_details['City']['city'] != '') ? 'HTL - ' . $training_details['City']['city'] : $training_details['TrainingCalender']['city'];
                $training_details['TrainingCalender']['Date'] = ( $training_details['TrainingCalender']['date'] != '') ? date('d-m-Y', strtotime($training_details['TrainingCalender']['date'])) : '';
                $training_details['TrainingCalender']['ProgramDetail'] = ( $training_details['TrainingCalender']['program_detail'] != '') ? $training_details['TrainingCalender']['program_detail'] : '';
                $training_details['TrainingCalender']['Band'] = ( $select_band != '') ? $select_band : '';
                $training_details['TrainingCalender']['Unit'] = ( $training_details['CompanyStructure']['name'] != '') ? $training_details['CompanyStructure']['name'] : $training_details['TrainingCalender']['su_id'];
                $training_details['TrainingCalender']['TrainerName'] = ( $training_details['TrainingCalender']['trainer_name'] != '') ? $training_details['TrainingCalender']['trainer_name'] : '';
                $training_details['TrainingCalender']['TrainerDetail'] = ( $training_details['TrainingCalender']['trainer_deatils'] != '') ? $training_details['TrainingCalender']['trainer_deatils'] : '';
                $training_details['TrainingCalender']['Present'] = ( $training_details[0]['total_present'] != '') ? $training_details[0]['total_present'] : '';
                $training_details['TrainingCalender']['Absent'] = ( $training_details[0]['total_absent'] != '') ? $training_details[0]['total_absent'] : '';
                $training_details['TrainingCalender']['Status'] = ( $training_details['ConfigurationValue']['status_name'] != '') ? $training_details['ConfigurationValue']['status_name'] : '';
                $excelRow[] = array($training_details['TrainingCalender']['Title'], $training_details['TrainingCalender']['Venue'],
                    $training_details['TrainingCalender']['Date'], $training_details['TrainingCalender']['ProgramDetail'],
                    $training_details['TrainingCalender']['Band'], $training_details['TrainingCalender']['Unit'], $training_details['TrainingCalender']['TrainerName'],
                    $training_details['TrainingCalender']['TrainerDetail'], $training_details['TrainingCalender']['Present'],
                    $training_details['TrainingCalender']['Absent'], $training_details['TrainingCalender']['Status']
                );
            }
            $obj = new PHPExcel();
            $obj->setActiveSheetIndex(0);
            $obj->getProperties()->setCreator("Ideal");
            $obj->getProperties()->setTitle("Training");
            $obj->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
            $obj->getActiveSheet()->getStyle('A1:K1')->getFont()->getColor()->setRGB('FFFFFF');
            $obj->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '9ea1a2')
                        )
                    )
            );

            foreach (range('A', 'K') as $columnID) {
                $obj->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            $obj->getActiveSheet()->fromArray($excelRow);

            $filename = "Training_" . date('d-m-Y H:i:s') . ".xls";

            header('Content-Type: application/vnd.ms-excel');

            header('Content-Disposition: attachment;filename="' . $filename . '"');

            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
            $objWriter->save('php://output');
        } else {


            $nominated_overall_list = $this->TrainingCalender->find('all', array('fields' => array('Employee.id', 'TraineeNominationDetail.*', 'TrainingCalender.*', 'CompanyStructureParent.name',
                    'TraineeNominationDetail.employee_location', 'TraineeNominationDetail.nominated_on', 'Band.name', 'CONCAT(Employee.employee_number," - ",Employee.first_name," ", Employee.last_name) as employee',
                    'CONCAT(NominatedBy.employee_number," - ",NominatedBy.first_name," ", NominatedBy.last_name) as nominated_by',
                    'CONCAT(EmployeeManager.employee_number," - ",EmployeeManager.first_name," ", EmployeeManager.last_name) as manager', 'CompanyStructure.name'),
                'joins' => array(
                    array(
                        'table' => 'trainee_nomination_details',
                        'alias' => 'TraineeNominationDetail',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TraineeNominationDetail.training_calender_id = TrainingCalender.id'
                        )),
                    array(
                        'table' => 'training_durations',
                        'alias' => 'TrainingDuration',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TrainingDuration.training_calender_id = TrainingCalender.id'
                        )),
                    array(
                        'table' => 'employees',
                        'alias' => 'Employee',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TraineeNominationDetail.employee_id = Employee.id'
                        )),
                    array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructure',
                        'type' => 'left',
                        'conditions' => array(
                            'Employee.structure_name_subgroup = CompanyStructure.id'
                        )),
                    array(
                        'table' => 'employees',
                        'alias' => 'EmployeeManager',
                        'type' => 'INNER',
                        'conditions' => array(
                            'EmployeeManager.id = Employee.manager'
                        )),
                    array(
                        'table' => 'employees',
                        'alias' => 'NominatedBy',
                        'type' => 'INNER',
                        'conditions' => array(
                            'TraineeNominationDetail.nominated_by = NominatedBy.id'
                        )),
                    array(
                        'table' => 'bands',
                        'alias' => 'Band',
                        'type' => 'left',
                        'conditions' => array(
                            'Band.id = Employee.band_id'
                        )),
                    array(
                        'table' => 'company_structures',
                        'alias' => 'CompanyStructureParent',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyStructureParent.id = TrainingCalender.su_id'
                        ))
                ),
                'conditions' => array('TrainingCalender.id' => $id, 'TraineeNominationDetail.deleted=0', 'TrainingDuration.deleted=0'),
                'group' => 'TraineeNominationDetail.id'));

            $band_list = $this->Band->find('list', array('fields' => array('Band.id', 'Band.name'), 'conditions' => array('Band.parent_id IS NULL OR Band.parent_id=31', 'Band.name <> "C"')));

            $company_location_list = $this->CompanyLocation->find('list', array('fields' => array('CompanyLocation.id', 'City.city'),
                'joins' => array(array(
                        'table' => 'cities',
                        'alias' => 'City',
                        'type' => 'left',
                        'conditions' => array(
                            'CompanyLocation.city_id = City.id'
                        ))
                ), 'conditions' => array('CompanyLocation.deleted' => 0)));
            $temp_venue = $company_location_list[$nominated_overall_list[0]['TrainingCalender']['venue']];
            $venue = !empty($temp_venue) ? $temp_venue : $nominated_overall_list[0]['TrainingCalender']['venue'];
            $bands = explode(',', $nominated_overall_list[0]['TrainingCalender']['band']);
            $select_band = array();
            foreach ($bands as $key => $value) {
                $select_band[] = $band_list[$value];
            }
            $select_band = implode(',', $select_band);

            ob_end_clean();
            $nominated_overall_list[0]['CompanyStructureParent']['name'] = !empty($nominated_overall_list[0]['CompanyStructureParent']['name']) ? $nominated_overall_list[0]['CompanyStructureParent']['name'] : $nominated_overall_list[0]['TrainingCalender']['su_id'];
            $excelRow[] = array('', 'Title', 'Venue', 'Date', 'Program Detail');
            $excelRow[] = array('', $nominated_overall_list[0]['TrainingCalender']['title'], $venue, date('d-m-Y', strtotime($nominated_overall_list[0]['TrainingCalender']['date'])), $nominated_overall_list[0]['TrainingCalender']['program_detail']);
            $excelRow[] = array('', 'Band', 'Unit', 'Trainer Name', 'Trainer Detail');
            $excelRow[] = array('', $select_band, $nominated_overall_list[0]['CompanyStructureParent']['name'], $nominated_overall_list[0]['TrainingCalender']['trainer_name'], $nominated_overall_list[0]['TrainingCalender']['trainer_deatils']);


            $excelRow[] = '';
            $excelRow[] = array('Employee Name', 'Rm Name', 'Band', 'Unit', 'Location', 'Attendance', 'Attendance Hours', 'Nominated On', 'Nominated By');
            foreach ($nominated_overall_list as $training_details) {
                $training_details['TrainingCalender']['Employee'] = ($training_details[0]['employee'] != '') ? $training_details[0]['employee'] : '';
                $training_details['TrainingCalender']['RM'] = ($training_details[0]['manager'] != '') ? $training_details[0]['manager'] : '';
                $training_details['TrainingCalender']['Band'] = ( $training_details['Band']['name'] != '') ? $training_details['Band']['name'] : '';
                $training_details['TrainingCalender']['Unit'] = ( $training_details['CompanyStructure']['name'] != '') ? $training_details['CompanyStructure']['name'] : '';
                $training_details['TrainingCalender']['Location'] = ( $training_details['TraineeNominationDetail']['employee_location'] != '') ? $training_details['TraineeNominationDetail']['employee_location'] : '';
                $training_details['TrainingCalender']['Attendance'] = ( $training_details['TraineeNominationDetail']['attendance'] != '') ? $training_details['TraineeNominationDetail']['attendance'] : '';
                $training_details['TrainingCalender']['AttendanceHours'] = ( $training_details['TraineeNominationDetail']['attendance_hours'] != '') ? $training_details['TraineeNominationDetail']['attendance_hours'] : '';
                $training_details['TrainingCalender']['NominatedOn'] = ( $training_details['TraineeNominationDetail']['nominated_on'] != '') ? date('d-m-Y H:i:s', strtotime($training_details['TraineeNominationDetail']['nominated_on'])) : '';
                $training_details['TrainingCalender']['NominatedBy'] = ($training_details[0]['nominated_by'] != '') ? $training_details[0]['nominated_by'] : '';
                $excelRow[] = array(
                    $training_details['TrainingCalender']['Employee'], $training_details['TrainingCalender']['RM'],
                    $training_details['TrainingCalender']['Band'], $training_details['TrainingCalender']['Unit'], $training_details['TrainingCalender']['Location'],
                    $training_details['TrainingCalender']['Attendance'], $training_details['TrainingCalender']['AttendanceHours'],
                    $training_details['TrainingCalender']['NominatedOn'], $training_details['TrainingCalender']['NominatedBy']
                );
            }
            $obj = new PHPExcel();
            $obj->setActiveSheetIndex(0);
            $obj->getProperties()->setCreator("Ideal");
            $obj->getProperties()->setTitle("Training");

            $obj->getActiveSheet()->getStyle('A6:I6')->getFont()->setBold(true);
            $obj->getActiveSheet()->getStyle('A6:I6')->getFont()->getColor()->setRGB('FFFFFF');
            $obj->getActiveSheet()->getStyle('A6:I6')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '9ea1a2')
                        )
                    )
            );

            foreach (range('A', 'I') as $columnID) {
                $obj->getActiveSheet()->getColumnDimension($columnID)
                        ->setAutoSize(true);
            }
            $obj->getActiveSheet()->getStyle('B1:E1')->getFont()->setBold(true);
            $obj->getActiveSheet()->getStyle('B1:E1')->getFont()->getColor()->setRGB('FFFFFF');
            $obj->getActiveSheet()->getStyle('B1:E1')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '9ea1a2')
                        )
                    )
            );


            $obj->getActiveSheet()->getStyle('B3:E3')->getFont()->setBold(true);
            $obj->getActiveSheet()->getStyle('B3:E3')->getFont()->getColor()->setRGB('FFFFFF');
            $obj->getActiveSheet()->getStyle('B3:E3')->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '9ea1a2')
                        )
                    )
            );


            $obj->getActiveSheet()->fromArray($excelRow);

            $filename = "Training_" . date('d-m-Y H:i:s') . ".xls";

            header('Content-Type: application/vnd.ms-excel');

            header('Content-Disposition: attachment;filename="' . $filename . '"');

            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
            $objWriter->save('php://output');
        }
    }

    function attendance_entry($traing_calenders_id) {
        if ($this->RequestHandler->isAjax()) {
            $this->autoRender = false;
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                $falg = 0;
                $save_data = $this->data;
                foreach ($save_data as $nomination_id => $attendance_data) {
                    $attendance_save['id'] = $nomination_id;
                    $attendance_save['attendance'] = $attendance_data['attendance'];
                    $attendance_save['attendance_hours'] = $attendance_data['attendance_hours'];
                    if ($this->TraineeNominationDetail->save($attendance_save)) {
                        $flag++;
                    }
                }
                $this->TrainingCalender->updateAll(array('TrainingCalender.status' => "'c'"), array('TrainingCalender.id' => $traing_calenders_id));
                if ($flag)
                    echo 'SUCCESS';
            }
        }
    }

    function cancel_nomination($traing_calenders_id,$view,$training_id) {
        if ($this->RequestHandler->isAjax() && $traing_calenders_id && $view && $training_id) {
            $this->autoRender = false;
            $emp_id = $this->Auth->user("employee_id");
            if (empty($emp_id)) {
                echo 'Session Expired';
                exit();
            } else {
                $query = "Update trainee_nomination_details set deleted=1,cancelled_reason='" . $this->data . "', cancelled_on='" . date('Y-m-d H:i:s') . "',cancelled_by=" . $this->Auth->user('employee_id')." Where id=" . $traing_calenders_id;
                $this->TraineeNominationDetail->query($query);
                
                $canceled_by = $this->TraineeNominationDetail->find('first', array('fields' => array(
                    'CONCAT(Employee.first_name," ", Employee.last_name) as employee',
                    ),
                'joins' => array(
                    array(
                        'table' => 'employees',
                        'alias' => 'Employee',
                        'type' => 'INNER',
                        'conditions' => array(
                            'Employee.id = TraineeNominationDetail.cancelled_by'
                        ))),
                    'conditions'=>array('TraineeNominationDetail.id'=>$traing_calenders_id)
                    ));
                $employee_id=$this->TraineeNominationDetail->field('TraineeNominationDetail.employee_id',array('TraineeNominationDetail.id'=>$traing_calenders_id));
                $mail_detail = $this->Employee->find('first', array('fields' => array('Employee.first_name', 'Employee.last_name', 'Employee.work_email_address', 'Employee.employee_number',
                        'EmployeeManager.first_name', 'EmployeeManager.last_name', 'EmployeeManager.work_email_address', 'EmployeeManager.employee_number'),
                    'joins' => array(
                        array(
                            'table' => 'employees',
                            'alias' => 'EmployeeManager',
                            'type' => 'INNER',
                            'conditions' => array(
                                'EmployeeManager.id = Employee.manager'
                            ))), 'conditions' => array('Employee.id =' => $employee_id)));

                $training_data_mail = $this->TrainingCalender->find('first', array('conditions' => array('TrainingCalender.id' => $training_id)));


                $mail_cc = $this->DlMaster->find('first', array('fields' => array('DlMaster.dl_mail'), 'conditions' => array('DlMaster.description="lms_mail"')));
                $to_mail = $mail_cc['DlMaster']['dl_mail'];
                $cc_email[] = $mail_detail['EmployeeManager']['work_email_address'];
                $cc_email[] = $mail_detail['Employee']['work_email_address'];
                $to_name = $mail_detail['Employee']['first_name'] . " " . $mail_detail['Employee']['last_name'];
                $rm_name = $mail_detail['EmployeeManager']['first_name'] . " " . $mail_detail['EmployeeManager']['last_name'];

                Configure::load('messages');
                $this->Email->to = $to_mail;
                $this->Email->cc = $cc_email;
                if($view=='hr_view'){
                    $subjectBody = Configure::read('LMS_CANCEL_HR');
                    $this->Email->html_body = sprintf($subjectBody['body'], $to_name, $training_data_mail['TrainingCalender']['title'],$canceled_by[0]['employee'], $this->data);
                }else{
                    $subjectBody = Configure::read('LMS_CANCEL');
                    $this->Email->html_body = sprintf($subjectBody['body'], $to_name, $training_data_mail['TrainingCalender']['title'], $this->data);
                }

                $this->Email->subject = sprintf($subjectBody['subject'], $training_data_mail['TrainingCalender']['title']);
                $this->Email->send();
                echo 'SUCCESS';
            }
        }
    }

}

?>