 
<?php $__env->startSection('content'); ?>                   
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header" style="background: linear-gradient(-90deg, #75009673 0%, #CB6CE673 100%)">
                    <h5 class="mb-0" style="color: #750096"><i class="fas fa-cogs me-2"></i>Add New Staff</h5>
                </div>   
                
                <div class="card-body">
            
                    <form id="form1" action="#" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        
                            
                               
                            <div class="row">
                                  
                                <h4 class="">Basic Information </h4>

                                <div class="around10">
                                                                        
                                        <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="employeeId">Staff ID</label><small class="req"> *</small>
                                                <input autofocus="" id="employee_id" name="employee_id" placeholder="" type="text" class="form-control" value="" autocomplete="off">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Role</label><small class="req"> *</small>
                                                <select id="role" name="role" class="form-control">
                                                    <option value="">Select</option>
                                                                                                            <option value="1">Admin</option>
                                                                                                            <option value="2">Accountant</option>
                                                                                                            <option value="3">Doctor</option>
                                                                                                            <option value="4">Pharmacist</option>
                                                                                                            <option value="5">Pathologist</option>
                                                                                                            <option value="6">Radiologist</option>
                                                                                                            <option value="7">Super Admin</option>
                                                                                                            <option value="8">Receptionist</option>
                                                                                                            <option value="9">Nurse</option>
                                                                                                            <option value="10">Clinical staff</option>
                                                                                                    </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Designation</label>
                                                <select id="designation" name="designation" placeholder="" type="text" class="form-control">
                                                    <option value="">Select</option>
                                                                                                            <option value="1">MBBS (Bachelor of Medicine, Bachelor of Surgery)</option>
                                                                                                            <option value="2">DM (Doctorate of Medicine)</option>
                                                                                                            <option value="3">MS (Master of Surgery)</option>
                                                                                                            <option value="4">MD (Doctor of Medicine)</option>
                                                                                                            <option value="5">Bachelors</option>
                                                                                                            <option value="6">Masters</option>
                                                                                                    </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Department</label>
                                                <select id="department" name="department" placeholder="" type="text" class="form-control">
                                                    <option value="">Select</option>
                                                                                                            <option value="1">Cardiology, Cardiothoracic Surgery</option>
                                                                                                            <option value="2">Neurology, Neurosurgery</option>
                                                                                                            <option value="3">Pulmonology / Respiratory Medicine</option>
                                                                                                            <option value="4">Urology</option>
                                                                                                            <option value="5">Gastroenterology</option>
                                                                                                            <option value="6">Hepatology</option>
                                                                                                            <option value="7">Hematology</option>
                                                                                                            <option value="8">Administrative staff</option>
                                                                                                            <option value="9">Pharmacy</option>
                                                                                                            <option value="10">Pathology</option>
                                                                                                            <option value="11">Radiology</option>
                                                                                                    </select> 
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
										<div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Specialist</label>
                                                <select id="specialistOpt" name="specialist[]" class="form-control">
                                                </select>
                                            </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">First Name</label><small class="req"> *</small>
                                                <input id="name" name="name" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Last Name</label>
                                                <input id="surname" name="surname" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Father Name</label>
                                                <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Mother Name</label>
                                                <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile"> Gender</label><small class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value="">Select</option>
                                                                                                            <option value="Male">Male</option>
                                                                                                                <option value="Female">Female</option>
                                                                                                        </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Marital Status</label>
                                                <select class="form-control" name="marital_status">
                                                    <option value="">Select</option>
                                                                                                            <option value="Single">Single</option>
                                                                                                            <option value="Married">Married</option>
                                                                                                            <option value="Widowed">Widowed</option>
                                                                                                            <option value="Separated">Separated</option>
                                                                                                            <option value="Not Specified">Not Specified</option>
                                                                                                    </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Blood Group</label>
                                                <select class="form-control" name="blood_group">
                                                    <option value="">Select</option>
                                                                                                            <option value="O+">O+</option>
                                                                                                            <option value="A+">A+</option>
                                                                                                            <option value="B+">B+</option>
                                                                                                            <option value="AB+">AB+</option>
                                                                                                            <option value="O-">O-</option>
                                                                                                            <option value="A-">A-</option>
                                                                                                            <option value="B-">B-</option>
                                                                                                            <option value="AB-">AB-</option>
                                                                                                        
                                                </select>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Date Of Birth</label><small class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text" class="form-control date" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>                                      
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Date Of Joining</label>
                                                <input id="date_of_joining" name="date_of_joining" placeholder="" type="text" class="form-control date" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Phone</label>
                                                <input id="mobileno" name="contactno" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Emergency Contact</label>
                                                <input id="emgmobileno" name="emgcontactno" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email</label><small class="req"> *</small>
                                                <input id="email" name="email" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Photo</label>
                                                <div><div class="dropify-wrapper"><div class="dropify-message"><p><i class="fa fa-cloud-upload dropi18"></i>Drop a file here or click</p><p class="dropify-error">Ooops, something wrong appended.</p></div><div class="dropify-loader"></div><div class="dropify-errors-container"><ul></ul></div><input class="filestyle form-control" type="file" name="file" id="file" size="20"><button type="button" class="dropify-clear">Remove</button><div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p><p class="dropify-infos-message">Drag and drop or click to replace</p></div></div></div></div>
                                                </div>
                                                <span class="text-danger"></span></div>
                                        </div>                          
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Current Address </label>
                                                <div><textarea name="address" class="form-control"></textarea>
                                                </div>
                                                <span class="text-danger"></span></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Permanent Address</label>
                                                <div><textarea name="permanent_address" class="form-control"></textarea>
                                                </div>
                                                <span class="text-danger"></span></div>
                                        </div>                          
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Qualification</label>
                                                <textarea id="qualification" name="qualification" placeholder="" class="form-control"></textarea>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Work Experience</label>
                                                <textarea id="work_exp" name="work_exp" placeholder="" class="form-control"></textarea>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Specialization</label>
                                                <div><textarea name="specialization" class="form-control"></textarea>
                                                </div>
                                                <span class="text-danger"></span></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Note</label>
                                                <div><textarea name="note" class="form-control"></textarea>
                                                </div>
                                                <span class="text-danger"></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Pan Number</label>
                                                <input id="pan_number" name="pan_number" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>National Identification Number</label>
                                                <input id="identification_number" name="identification_number" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Local Identification Number</label>
                                                <input id="local_identification_number" name="local_identification_number" placeholder="" type="text" class="form-control" value="">
                                                <span class="text-danger"></span></div>
                                        </div>                          
                                    <div class="">                                         
                                                                                
                                    </div> 
                                    </div>
                                </div>
                            </div>
                            
                        
                        
                            <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/staff/addStaff.blade.php ENDPATH**/ ?>