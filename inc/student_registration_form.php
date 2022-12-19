<?php
function gpp_shortcode( $atts ) {
 extract ( shortcode_atts (array(
        'cat' => '1',
        'redirect' => bloginfo('url'),
    ), $atts ) );
ob_start();
if(!(isset($_GET['post_id']) &&  $_GET['post_id']>0)){
$start_date=get_option('gpp_reg_start_date');
$end_date=get_option('gpp_reg_close_date');
//echo $end_date. ' < '.date('Y-m-d') . ' : '.($end_date<date('Y-m-d'));
$today=date('Y-m-d');
if($start_date<$today && $end_date>$today){
    ?>
<form class="form-horizontal" id="form" name="form" method="post" enctype="multipart/form-data">
<input type="hidden" name="ispost" value="1" />
<input type="hidden" name="userid" value="" />
<input type="hidden" name="gptask" value="savepost" />

<div class="row r-4" style="margin-top: 30px;">
  <fieldset id="admission_category">
<div class="col-md-12"><h3>ADMISSION CATEGORY</h3></div>

<div class="form-group row">
    <label for="cat" class="col-sm-3 col-form-label"><strong>Discipline</strong></label>
    <div class="col-sm-9">

	 <select name="cat" id="cat" class="select-input" required>
	 <option value=""><strong>Select Discipline</strong></option>
	 <option value="icom1"><strong>I.Com I</strong></option>
	  <option value="icom2"><strong>I.Com II</strong></option>
	  <option value="adp"><strong>ADP(A & F, Commerce, Business Administration)</strong></option>
    <optgroup label="I.C.S. I">
        <option value="ics1_stat">Math Stat Computer</option>
        <option value="ics1_economic">Math Economic computer</option>
    </optgroup>
	<optgroup label="I.C.S. II">
        <option value="ics2_stat">Math Stat Computer</option>
        <option value="ics2_economic">Math Economic computer</option>
    </optgroup>
	<optgroup label="B.S">
        <option value="bs_account">Accounting & Finance</option>
        <option value="bs_ba">Business Administration</option>
		<option value="bs_commerce">Commerce</option>
    </optgroup>

</select>

    </div>
  </div>


  <div class="col-md-12">
<div class="form-check form-check-inline">
  <input class="form-check-input admission_category" type="checkbox" name="admission_open_merit" id="admission_open_merit">
  <label class="form-check-label" for="admission_open_merit">Open Merit</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input admission_category" type="checkbox" name="admission_sports" id="admission_sports">
  <label class="form-check-label" for="admission_sports">Sports</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input admission_category" type="checkbox" name="admission_disabled" id="admission_disabled">
  <label class="form-check-label" for="admission_disabled">Disabled</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input admission_category" type="checkbox" name="admission_fata" id="admission_fata">
  <label class="form-check-label" for="admission_fata">FATA/FANA</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input admission_category" type="checkbox" name="admission_real_son" id="admission_real_son">
  <label class="form-check-label" for="admission_real_son">Real son of teachers/Employees of concerned stup</label>
</div>
</div>
</fieldset>
</div>


<label for="file">Photograph:</label>
<small>(Add Passport size photograph.)</small>
<input type="file" name="applicant_image" id="applicant_image">

<div class="row r-5">
  <div class="col-md-12"><h3>Applicants Profile</h3></div>
  <div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Name(in block letters)" aria-label="admission_applicant_name" name="admission_applicant_name" id="admission_applicant_name"   required>
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="CNIC" aria-label="admission_applicant_cnic" name="admission_applicant_cnic" id="admission_applicant_cnic" maxlength="13" pattern="\d{13}$"  onkeypress="return isNumber(event)"  required>
</div>
  </div>

</div>


<div class="row r-6">
<div class="col-md-12">
  <table style="border: 1px solid #ced4da;">
    <thead><tr><th >Date of Birth</th><th>Place of Birth</th><th>Nationality</th><th>Religion</th><th>Residential Status(plz tick relevant box)</th></tr></thead>

<tbody>
  <tr>
    <td ><input type="date" class="form-control" placeholder="14/12/1990" aria-label="applicant_birth_date" name="applicant_birth_date" id="applicant_birth_date" ></td>

    <td><input type="text" class="form-control"  aria-label="applicant_Place_of_Birth" name="applicant_Place_of_Birth" id="applicant_Place_of_Birth"></td>
    <td><input type="text" class="form-control"  aria-label="aplicant_nationality" name="aplicant_nationality" id="aplicant_nationality"></td>
    <td><input type="text" class="form-control" holder="Religion" aria-label="applicant_religion" name="applicant_religion" id="applicant_religion"></td>
    <td>


    <div class="form-check">
  <input class="form-check-input" type="radio" name="admission_residential_status" value="admission_islamabad_city"  id="admission_islamabad_city">
  <label class="form-check-label" for="admission_islamabad_city">Islamabad City</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="admission_residential_status" value="admission_rawalpindi_city" id="admission_rawalpindi_city">
  <label class="form-check-label" for="admission_rawalpindi_city">Rawalpindi City</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="admission_residential_status" value="admission_federal" id="admission_federal" >
  <label class="form-check-label" for="admission_federal">Federal area islamabad</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="radio" name="admission_residential_status" value="admission_other" id="admission_other" >
  <label class="form-check-label" for="admission_other">Other</label>
</div>


    </td></tr>
</tbody>
  </table>

  </div>
</div>


<div class="row r-7" style="margin-top: 20px;">

 <div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Present Address" aria-label="admission_applicant_present_address" name="admission_applicant_present_address" id="admission_applicant_present_address">
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Permanent Residential Address" aria-label="admission_Permanent_residential_address" name="admission_Permanent_residential_address" id="admission_Permanent_residential_address">
</div>
</div>


 <div class="col-md-4">
<div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Phone number residence" aria-label="admission_phone_number_residence" name="admission_phone_number_residence" id="admission_phone_number_residence">
</div>
</div>
<div class="col-md-4">
<div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Mobile/Whatsapp" aria-label="admission_mobile_whatsapp" name="admission_mobile_whatsapp" id="admission_mobile_whatsapp" >
</div>
</div>
<div class="col-md-4">
<div class="input-group mb-3">
   <span class="input-group-text">@</span>
  <input type="email" class="form-control" placeholder="Email" aria-label="admission_applicant_Email" name="admission_applicant_Email" id="admission_applicant_Email" required>
</div>
  </div>

</div>



<div class="row r-8">
  <div class="col-md-12"><h3>Father Profile</h3></div>
 <div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Name(in block letters)" aria-label="father_applicant_name" name="father_applicant_name" id="father_applicant_name"  required>
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="CNIC" aria-label="father_applicant_cnic" name="father_applicant_cnic" id="father_applicant_cnic" maxlength="13"  pattern="\d{13}$"  onkeypress="return isNumber(event)"  required>
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Guardians Name(father is deseased)" aria-label="father_G_name" name="father_G_name" id="father_G_name" >
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Guardians CNIC" aria-label="father_g_cnic" name="father_g_cnic" id="father_g_cnic" maxlength="13"  pattern="\d{13}$"  onkeypress="return isNumber(event)" >
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Guardians relation with award" aria-label="father_award" name="father_award" id="father_award">

</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Father's/Guardians Occupation/designation" aria-label="father_occupation" name="father_occupation" id="father_occupation">
</div>
</div>
<div class="col-md-12">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Office Address" aria-label="father_office_address" name="father_office_address" id="father_office_address">
</div>
</div>
 <div class="col-md-4">
<div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Phone number residence" aria-label="father_Phone_number_residence" name="father_Phone_number_residence" id="father_Phone_number_residence">
</div>
</div>
<div class="col-md-4">
<div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Mobile/Whatsapp" aria-label="father_mobile_whatsapp" name="father_mobile_whatsapp"  id="father_mobile_whatsapp">
</div>
</div>
<div class="col-md-4">
<div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Office" aria-label="father_Office" name="father_Office" id="father_Office">
</div>
  </div>

  <div class="col-md-6">
<div class="input-group mb-3">
  <span class="input-group-text">@</span>
  <input type="email" class="form-control" placeholder="Email Address" aria-label="father_email" name="father_email" id="father_email">
</div>
</div>
<div class="col-md-6">
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Monthly income of Father's/Guardian Rs" aria-label="father_income" name="father_income" id="father_income">
</div>
  </div>


</div>




<div class="row r-9">
  <div class="col-md-12"><h3>Reference</h3></div>

<div class="col-md-6">
  <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Name" aria-label="reference_name" name="reference_name" id="reference_name">
</div>
</div>
<div class="col-md-6">
  <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Relation" aria-label="reference_relation" name="reference_relation" id="reference_relation">
</div>
</div>
<div class="col-md-12">
  <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Office/Business Address" aria-label="reference_business_address" name="reference_business_address" id="reference_business_address">
</div>
</div>
<div class="col-md-4">
  <div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Phone number residence" aria-label="reference_phone" name="reference_phone" id="reference_phone">
</div>
</div>
<div class="col-md-4">
  <div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Mobile/Whatsapp" aria-label="reference_whatsapp" name="reference_whatsapp" id="reference_whatsapp">
</div>
</div>
<div class="col-md-4">
  <div class="input-group mb-3">
  <input type="number" class="form-control" placeholder="Office" aria-label="reference_Office" name="reference_Office" id="reference_Office">
</div>
</div>
<div class="col-md-4">
    <p>Do you want to avail college bus facility</p>

</div>
<div class="col-md-8">
  <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="bus_facility" id="admission_yes" value="admission_yes">
   <label class="form-check-label" for="admission_yes">Yes</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="bus_facility" id="admission_No" value="admission_No">
  <label class="form-check-label" for="admission_No">No</label>
</div>

</div>

<div class="col-md-12 admission_location-div" style="display:none;">
  <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio"  name="admission_location" id="admission_i_8" value="admission_i_8">
  <label class="form-check-label" for="admission_i_8">I-8</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_i_9" value="admission_i_9">
  <label class="form-check-label" for="admission_i_9">I-9</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_i_10" value="admission_i_10" >
  <label class="form-check-label" for="admission_i_10">I-10</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_6" value="admission_g_6" >
  <label class="form-check-label" for="admission_g_6">G-6</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_7" value="admission_g_7" >
  <label class="form-check-label" for="admission_g_7">G-7</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_8" value="admission_g_8" >
  <label class="form-check-label" for="admission_g_8">G-8</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_9" value="admission_g_9" >
  <label class="form-check-label" for="admission_g_9">G-9</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_10" value="admission_g_10" >
  <label class="form-check-label" for="admission_g_10">G-10</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_g_11" value="admission_g_11" >
  <label class="form-check-label" for="admission_g_11">G-11</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_f_6" value="admission_f_6" >
  <label class="form-check-label" for="admission_f_6">F-6</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_rawal_dam_chowk" value="admission_rawal_dam_chowk" >
  <label class="form-check-label" for="admission_rawal_dam_chowk">Rawal Dam chowk</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_chack_shahzad" value="admission_chack_shahzad" >
  <label class="form-check-label" for="admission_chack_shahzad">Chack shahzad</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_bharakhau" value="admission_bharakhau" >
  <label class="form-check-label" for="admission_bharakhau">bharakhau</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_khanna_pul" value="admission_khanna_pul" >
  <label class="form-check-label" for="admission_khanna_pul">khanna pul</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_kural_chowk" value="admission_kural_chowk" >
  <label class="form-check-label" for="admission_kural_chowk">kural chowk</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_kak_pul" value="admission_kak_pul" >
  <label class="form-check-label" for="admission_kak_pul">kak pul</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="admission_location" id="admission_nill" value="admission_nill" >
  <label class="form-check-label" for="admission_nill">nill</label>
</div>
</div>

</div>



<div class="row r-9" style="margin-top: 20px;">
<div class="col-md-12"><h3>Educational Profile</h3></div>
<div class="col-md-12">
 <table style="border: 1px solid #ced4da;">
    <thead><tr><th>Examination passed</th><th>Subject</th><th>Roll No</th><th>Registration No</th><th>Board/Uni</th><th>Year of passing</th><th>Marks Obtained</th><th>Total marks</th><th>Name of institution</th></tr></thead>

<tbody>
  <tr>
    <td>SSc/O-level</td>
    <td><input type="text" class="form-control"  aria-label="admission_ssc_subject" name="admission_ssc_subject" id="admission_ssc_subject"></td>
    <td><input type="number" class="form-control"  aria-label="admission_ssc_roll" name="admission_ssc_roll" id="admission_ssc_roll"></td>
    <td><input type="number" class="form-control"  aria-label="admission_ssc_reg" name="admission_ssc_reg" id="admission_ssc_reg"></td>
    <td ><input type="text" class="form-control"  aria-label="admission_ssc_board" name="admission_ssc_board" id="admission_ssc_board"></td>
    <td><input type="text" class="form-control"  aria-label="admission_ssc_ypass" name="admission_ssc_ypass" id="admission_ssc_ypass"></td>
   <td><input type="text" class="form-control"  aria-label="admission_ssc_mo" name="admission_ssc_mo" id="admission_ssc_mo"></td>
   <td><input type="text" class="form-control"  aria-label="admission_ssc_tm" name="admission_ssc_tm" id="admission_ssc_tm"></td>
   <td><input type="text" class="form-control"  aria-label="admission_ssc_ni" name="admission_ssc_ni" id="admission_ssc_ni"></td>

 </tr>

  <tr>
    <td>HSSC/A-level</td>
    <td><input type="text" class="form-control"  aria-label="admission_hssc_subject" name="admission_hssc_subject" id="admission_hssc_subject"></td>
    <td><input type="number" class="form-control"  aria-label="admission_hssc_roll" name="admission_hssc_roll" id="admission_hssc_roll"></td>
    <td><input type="number" class="form-control"  aria-label="admission_hssc_reg" name="admission_hssc_reg" id="admission_hssc_reg"></td>
    <td><input type="text" class="form-control"  aria-label="admission_hssc_board" name="admission_hssc_board" id="admission_hssc_board"></td>
    <td><input type="text" class="form-control"  aria-label="admission_hssc_ypass" name="admission_hssc_ypass" id="admission_hssc_ypass"></td>
   <td><input type="text" class="form-control" aria-label="admission_hssc_mo" name="admission_hssc_mo" id="admission_hssc_mo"></td>
   <td><input type="text" class="form-control"  aria-label="admission_hssc_tm" name="admission_hssc_tm" id="admission_hssc_tm"></td>
   <td><input type="text" class="form-control"  aria-label="admission_hssc_ni" name="admission_hssc_ni" id="admission_hssc_ni"></td>

 </tr>
  <tr>
    <td>Other</td>
    <td><input type="text" class="form-control"  aria-label="admission_other_subject" name="admission_other_subject" id="admission_other_subject"></td>
    <td><input type="number" class="form-control"  aria-label="admission_other_roll" name="admission_other_roll" id="admission_other_roll"></td>
    <td><input type="number" class="form-control"  aria-label="admission_other_reg" name="admission_other_reg" id="admission_other_reg"></td>
    <td><input type="text" class="form-control"  aria-label="admission_other_board" name="admission_other_board" id="admission_other_board"></td>
    <td><input type="text" class="form-control"  aria-label="admission_other_ypass" name="admission_other_ypass" id="admission_other_ypass"></td>
   <td><input type="text" class="form-control" aria-label="admission_other_mo" name="admission_other_mo" id="admission_other_mo"></td>
   <td><input type="text" class="form-control"  aria-label="admission_other_tm" name="admission_other_tm" id="admission_other_tm"></td>
   <td><input type="text" class="form-control"  aria-label="admission_other_ni" name="admission_other_ni" id="admission_other_ni"></td>

 </tr>

</tbody>
  </table>
  </div>
  </div>
  <?php  wp_nonce_field(); ?>
  <input type="hidden" value="<?php echo $redirect  ?>" name="redirect">
  <div class="r-10" style="margin-top: 20px;">
 <input type="button"  class="btn btn-primary" value="SUBMIT" name="submit_post" id="submit_post">
</div>


</form>
<script src="<?php echo plugins_url( '/assets/js/jquery.js', __FILE__ );?>"></script>
<script src="<?php echo plugins_url( '/assets/js/jquery.validate.js', __FILE__ );?>"></script>
<script>
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
$("#form").validate({
			rules: {
				admission_applicant_name: {
          required:true
        },
				admission_applicant_cnic: {
					required: true,
				},
        admission_applicant_email: {
					required: true,
					email: true
				},
				admission_location:{
          required: {
            depends: function(element) {
              return $('#admission_yes').is(":checked");
            }
        }
        }
			},
			messages: {
				admission_applicant_name:
        {
					required: "Please enter your Applicant Name",
					minlength: "Applicant Name must be at least 2 characters long"
				},
				admission_applicant_cnic: {
					required:"Please enter CNIC"
},
				admission_applicant_email:{
					required: "Please enter a valid email address"
}
			}
		});
    $(document).ready(function(){

      $('#submit_post').click(function(){
    console.log('submit_post Click');
    var discipline=$('#cat').val();
    if($('#cat').val()==''){
      console.log('discipline Empty');
      $('#cat').focus();
      alert('Please select Discipline.');
    }
if(discipline=='icom1' || discipline=='icom2' || discipline=='ics1_stat' || discipline=='ics1_economic' || discipline=='ics2_stat' || discipline=='ics2_economic'){
console.log('Inter Class');
      if($('#admission_ssc_mo').val()==''){
        $('#admission_ssc_mo').focus();
        alert('Please enter Obtained Marks.');return false;
      }
      if($('#admission_ssc_tm').val()==''){
        $('#admission_ssc_tm').focus();
        alert('Please enter Total Marks.');return false;
      }
      per_ssc=0;
      var v1_ssc=parseFloat($('#admission_ssc_mo').val());
      var v2_ssc=parseFloat($('#admission_ssc_tm').val());
      if($('#admission_ssc_mo').val()!='' && $('#admission_ssc_tm').val()!=''){
        var per_ssc=(v1_ssc/v2_ssc)*100;
      }
    }else if(discipline=='bs_account' || discipline=='bs_ba' || discipline=='bs_commerce' || discipline=='adp'){
    console.log('BS Class');
      if($('#admission_ssc_mo').val()==''){
        $('#admission_ssc_mo').focus();
        alert('Please enter Obtained Marks.');return false;
      }
      if($('#admission_ssc_tm').val()==''){
        $('#admission_ssc_tm').focus();
        alert('Please enter Total Marks.');return false;
      }
        if($('#admission_hssc_mo').val()==''){
          $('#admission_hssc_mo').focus();
          alert('Please enter Obtained Marks.');return false;
        }
        if($('#admission_hssc_tm').val()==''){
          $('#admission_hssc_tm').focus();
          alert('Please enter Total Marks.');return false;
        }
        per_ssc=0;
        var v1_ssc=parseFloat($('#admission_ssc_mo').val());
        var v2_ssc=parseFloat($('#admission_ssc_tm').val());
        if($('#admission_ssc_mo').val()!='' && $('#admission_ssc_tm').val()!=''){
          var per_ssc=(v1_ssc/v2_ssc)*100;
        }
        per_hssc=0;
        var v1_hssc=parseFloat($('#admission_hssc_mo').val());
        var v2_hssc=parseFloat($('#admission_hssc_tm').val());
        if($('#admission_hssc_mo').val()!='' && $('#admission_hssc_tm').val()!=''){
          var per_hssc=(v1_hssc/v2_hssc)*100;
        }
        if((discipline=='bs_account' || discipline=='bs_ba' || discipline=='bs_commerce') && per_hssc<50){
        alert('Sorry! Your Marks are less than 50%, We can not offer you admission!');
        return false;
      }else if(discipline=='adp' && per_hssc<45){
      alert('Sorry! Your Marks are less than 45%, We can not offer you admission!');
      return false;
        }
      }
$('#form').submit();
return true;

  });

    $('#admission_yes').click(function(){
    $('.admission_location-div').show();
    });
        $('#admission_No').click(function(){
    $('.admission_location-div').hide();
    });
    });
</script>
  <?php
}else{ ?>
  <div class="row r-9" style="margin-top: 20px;">
  <div class="col-md-12"><h3>
    <?php
    if($start_date>$today){?>
    This Form is not available. you can access this form between <?php echo date('d M, Y',strtotime($start_date));?> and <?php echo date('d M, Y',strtotime($end_date));?>
<?php }elseif($end_date<$today){?>
  This Form is not available. Last date for submission was <?php echo date('d M, Y',strtotime($end_date));?>
<?php }else{?>
  This Form is not available.
  <?php }?>
  </h3></div>
  </div>
<?php }
}else{
  $upload = wp_upload_dir();
  $upload_dir = $upload['baseurl'];
  $filename = $upload_dir . '/pdf/admissions/'.$_GET['post_id'].'.pdf';
  add_post_meta($_GET['post_id'], 'form_path', $filename);
  ?>
  <div class="row r-9" style="margin-top: 20px;">
  <div class="col-md-12"><h3>Data has been saved</h3></div>
  <div class="col-md-12">
<div><a class="btn btn-primary btn-primary-large" href="<?php echo $filename;?>">View Form</a></div>

</div>
</div>
<?php
}
$output = ob_get_contents();
    ob_end_clean();
    return $output;
}
?>