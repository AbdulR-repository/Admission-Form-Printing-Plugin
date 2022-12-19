<?php
/*
Plugin Name: Students Registration
Plugin URI: #
Description: IIMCS Students Registration.
Version: 1.2
Author: Abdul R
Author URI: /
*/

add_action( 'wp_enqueue_scripts', 'gpp_stylesheet' );

require_once(dirname(__FILE__)."/inc/student_registration_form.php");
require_once(dirname(__FILE__)."/inc/MetaFields.php");
$field_class=new MetaFields();
add_action( 'init', array( $field_class, 'register' ) );
function gpp_stylesheet() {
    wp_register_style( 'prefix-style', plugins_url('style.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
    wp_register_style( 'boot-style', plugins_url('bootstrap.css', __FILE__) );
    wp_enqueue_style( 'boot-style' );
}

function gpp_activate() {
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir . '/pdf/admissions';
    if (! is_dir($upload_dir)) {
       wp_mkdir_p( $upload_dir, 0700 );
    }
}
register_activation_hook(__FILE__, 'gpp_activate');


function gpp_notice() {
    $check= get_option('gpp_postAuthor');
    if(!$check){
      ?>
      <div class="notice notice-info is-dismissible">
          <p><?php _e( 'Please select a user with contributor role under plugin settings for plugin shortcode to work!', 'sample-text-domain' ); ?></p>
      </div>
      <?php
  }
}
add_action( 'admin_notices', 'gpp_notice' );



add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'gpp_links' );

function gpp_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'edit.php?post_type=student_registration&page=students-registration.php') ) .'">Settings</a>';

   return $links;
}


if ( is_admin() ){

add_action('admin_menu' , 'gpp_settings');

 }

    function gpp_settings() {
        add_submenu_page('edit.php?post_type=student_registration',
                        'Student Registrations',
                        'Settings',
                        'administrator',
                        basename(__FILE__),
                        'gpp_registerSettingsPage');

      add_action( 'admin_init', 'gpp_registerSettings' );
    }

    function gpp_registerSettings() {
      add_option("gpp_reg_start_date", "", "", "yes");
    add_option("gpp_reg_close_date", "", "", "yes");
      register_setting( 'gpp_registerSettingsGroup', 'gpp_postAuthor' );
      register_setting( 'gpp_registerSettingsGroup', 'gpp_reg_start_date' );
      register_setting( 'gpp_registerSettingsGroup', 'gpp_reg_close_date' );
    }

  function gpp_registerSettingsPage() {
     ?>
     <div class="wrap">
      <h1> Students Registration Form Setting:</h1>
        <form method="post" action="options.php">
          <?php settings_fields( 'gpp_registerSettingsGroup' ); ?>
          <?php do_settings_sections( 'gpp_registerSettingsGroup' ); ?>
          <?php //wp_nonce_field( 'gpp_uaction', 'gpp_ufield' ); ?>
          <table class="form-table">

              <tr valign="top">
                <th scope="row"><label for="blogname">Students Incharge <small>(contributor)</small></label></th>
                <td>
                  <select name="gpp_postAuthor">
                    <?php
                      $args = array(
                                   'role' => 'contributor',
                                   'orderby' => 'user_nicename',
                                   'order' => 'ASC'
                                  );
                                   $contributors = get_users($args);

                    foreach ($contributors as $user) {
                          echo '<option value='.$user->ID.'>'.$user->display_name.'</option>';
                        }
                    ?>
                  </select>
                </td>
              </tr>
              <tr><th colspan=2>Dates For Students Registrations </th></tr>
              <tr><th>Admissions Start by</th><td><input type="date" class="regular-text" name="gpp_reg_start_date" value="<?php echo esc_attr( get_option('gpp_reg_start_date') )  ?>" placeholder="Please enter Starting Date"></td></tr>
              <tr><th>Admissions Close by </th><td><input type="date" class="regular-text" name="gpp_reg_close_date" value="<?php echo esc_attr( get_option('gpp_reg_close_date') )  ?>" placeholder="Please enter Starting Date"></td></tr>


          </table>
          <?php submit_button(); ?>
          </form>
    </div>

    <?php

}



	function gpp_save(){
		//var_dump($_POST);

  if(isset($_POST['gptask']) && $_POST['gptask'] == 'savepost' && wp_verify_nonce($_POST["_wpnonce"])){

               //ob_start();
                        $title = sanitize_text_field( $_POST["admission_applicant_name"] );
                        $email = sanitize_email( $_POST["admission_applicant_Email"] );
                        $phone=is_int( $_POST["admission_mobile_whatsapp"] );
                        $gname = sanitize_text_field($_POST["admission_applicant_name"]);
						$gcat = $_POST["cat"];
                       // $redirecturl = esc_url( $_POST["redirect"]);


                   //$user_id = get_post_meta( $post->ID, 'gpp_postAuthor' );
                        $user_id= get_option('gpp_postAuthor');
                   //Post Properties
                    $new_post = array(
                            'post_title'    => $gname,
                            'post_status'   => 'publish',
                            'post_type'     => 'student_registration',
                            'post_author'   => $user_id

                    );
                    $applicant_image='';
                    $_POST['applicant_image']='';
                    if (isset($_FILES)) {
                      if (isset($_FILES['applicant_image'])){
                        if (0 === $_FILES['applicant_image']['error']) {
                    //wp-admin/includes/file.php
                          if ( ! function_exists( 'wp_handle_upload' ))
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                            $uploadedfile = $_FILES['applicant_image'];
                            $upload_overrides = array( 'test_form' => false );
                            $movefile=wp_handle_upload($uploadedfile,$upload_overrides);
                            $applicant_image=$movefile['file'];
                            //print_r($movefile);
                            $_POST['applicant_image']=$movefile['file'];
                        }
                      }
                    }
                    //save the new post
                    $pid = wp_insert_post($new_post);
					$set_terms = wp_set_object_terms( $pid, $gcat , 'discipline' );

                    add_post_meta($pid, 'meta_key', true);
                    $meta_class=new MetaFields();
                    $meta_class->save_entry_meta_box_data($pid);
                    $image_path="";

                    /* Insert Form data into Custom Fields */
                    add_post_meta($pid, 'guest-name', $gname, true);
                    add_post_meta($pid, 'guest-email', $email, true);
                    add_post_meta($pid, 'guest-phone', $phone, true);
      add_post_meta($pid, 'admission_applicant_Email', $_POST["admission_applicant_Email"], true);

//ob_end_flush();
require('fpdf/fpdf.php');
          require_once('inc/html_table.php');
          //echo 'fpdf/fpdf.php';
          //$inc=get_included_files();
        //  print_r($inc);

    //  echo 'POST ID :'.$pid;

 $dicipline = $_POST["cat"];
		  $term = get_term_by('slug',$dicipline,"discipline");
		  $name = ucwords(str_replace('_',' ',$term->name));
//echo 'dicipline : '.$dicipline exit;
        $pdf=new PDF();
        $pdf->AddPage();
          //$pdf->SetFont('Arial','',12);

          $logo = dirname(__FILE__).'/inc/assets/img/logo.jpg';
          $pdf->Image($logo,10,6,20);

              // Arial bold 15
          $pdf->SetFont('Arial','B',14);
              // Move to the right
          $pdf->Cell(80);
              // Title
          $pdf->Cell(30,10,'Islamabad Model Postgraduate College of Commerce',0,0,'C');
              // Line break
          $pdf->Ln(5);
          $pdf->SetFont('Arial','B',12);
          $pdf->Cell(80);
          $pdf->Cell(30,12,'H-8/4 Islamabad',0,0,'C');
          $pdf->Ln(5);
          $pdf->Cell(80);
          //$pdf->SetDrawColor(255,0,0);
             $pdf->SetFillColor(230,230,0);
             $pdf->SetTextColor(220,50,50);
          //$pdf->Cell(30,12,'Admission Form of '.str_replace('&amp;','&',$name),0,0,'C');
          $formno=$pid;

          if($dicipline=='icom1'){
            $formno=date('Y').'icom-i-'.$pid;
          }elseif($dicipline=='icom2'){
            $formno=date('Y').'icom-ii-'.$pid;
          }elseif($dicipline=='adp'){
            $formno=date('Y').'adp-'.$pid;
          }elseif($dicipline=='ics1_stat'){
            $formno=date('Y').'ics-i-'.$pid;$name='ICS I '.$name;
          }elseif($dicipline=='ics1_economic'){
            $formno=date('Y').'ics-i-'.$pid;$name='ICS I '.$name;
          }elseif($dicipline=='ics2_stat'){
            $formno=date('Y').'ics-ii-'.$pid;$name='ICS II '.$name;
          }elseif($dicipline=='ics2_economic'){
            $formno=date('Y').'ics-ii-'.$pid;$name='ICS II '.$name;
          }elseif($dicipline=='bs_account'){
            $formno=date('Y').'BSAF-'.$pid;$name='BS '.$name;
          }elseif($dicipline=='bs_ba'){
            $formno=date('Y').'BSBA-'.$pid;$name='BS '.$name;
          }elseif($dicipline=='bs_commerce'){
            $formno=date('Y').'BSCOM-'.$pid;$name='BS '.$name;
          }
          $pdf->Cell(30,12,'Admission Form',0,0,'C');
          $pdf->Ln(5);
          $pdf->Cell(80);
          $pdf->Cell(30,12,str_replace('&amp;','&',$name),0,0,'C');
          $pdf->Ln(9);
          $pdf->Cell(73);
          $pdf->SetDrawColor(0,0,0);
          $pdf->SetFillColor(68, 71, 68);
          $pdf->SetTextColor(255,255,255);
          $pdf->Cell(44,8,'Session '.date('Y').'-'.date('Y', strtotime('+2 year')),1,1,'C',true);
          $bak_y = $pdf->GetY();
          $pdf->Ln(1);
          $pdf->Cell(90);
          $pdf->SetDrawColor(0,0,0);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->SetTextColor(0,0,0);
          $pdf->SetY(18);
          $pdf->SetX(162);

          //$pdf->SetFont('Arial','B',10);
          //
          if($_POST['applicant_image']<>''){
                      $pdf->SetFont('Arial','B',10);
                      $pdf->Cell(28,28,'',0,1,'C');
                      $pdf->Image($_POST['applicant_image'],160,17,30);
                    }else{
                      $pdf->SetFont('Arial','B',10);
                      $pdf->Cell(28,28,'Photograph',1,1,'C');

                    }

          //Form //
          $bak_y = $pdf->GetY();
          /*$pdf->Ln(1);
          $pdf->Cell(90);
          $pdf->SetDrawColor(0,0,0);
          $pdf->SetFillColor(255, 255, 255);
          $pdf->SetTextColor(0,0,0);
          */
          $pdf->SetY(28);
          $pdf->SetX(16);

          $pdf->SetFont('Arial','',10);
          $pdf->Cell(30,10,"",1,1);
          $pdf->SetY(27);
          $pdf->SetX(18);
          $pdf->SetFont('Arial','B',10);
          $pdf->Cell(25,8,'Application No',0,0,'C');
          $pdf->SetY(31);
          $pdf->SetX(18);
          $pdf->SetFont('Arial','',10);
         // $pdf->Cell(28,8,date('Y').str_replace(array(' ','.'),array('-',''),strtolower($name)).'-'.$pid,0,0,'C');
      $pdf->Cell(28,8,$formno,0,0,'C');
          $pdf->SetY($bak_y);

          $pdf->SetY($bak_y-7);
          $pdf->Cell(1,1, '', 0, 0);
          $pdf->schoolTitle('Admission Category');

          $pdf->Ln(10);
		  $pdf->Cell(1);
          $pdf->schoolData('Discipline');

          $pdf->schoolData(str_replace('&amp;','&',$name),1,0,1);
          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->SetFont('','',10);
		  if(isset($_POST["admission_open_merit"]) && $_POST["admission_open_merit"]=='on'){
          $check = "4";
          }else{
            $check = "";
          }
          //$pdf->SetDrawColor(0,0,0);
          $pdf->SetTextColor(0,0,0);
          //$pdf->SetFillColor(0,0,0);
          $pdf->SetFont('ZapfDingbats','B', 10);
          $pdf->Cell(5,7,$check, 1, 0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Open Merit');

          $pdf->Cell(2,7, '', 0, 0);
          if(isset($_POST["admission_sports"]) && $_POST["admission_sports"]=='on'){
          $check = "4";
          }else{
            $check = "";
          }
          $pdf->SetFont('ZapfDingbats','B', 10);
          $pdf->Cell(5,7,$check, 1, 0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Sports');

          $pdf->Cell(2,7, '', 0, 0);
          if(isset($_POST["admission_disabled"]) && $_POST["admission_disabled"]=='on'){
          $check = "4";
          }else{
            $check = "";
          }
          $pdf->SetFont('ZapfDingbats','B', 10);
          $pdf->Cell(5,7,$check, 1, 0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Disabled');

          $pdf->Cell(2,7, '', 0, 0);
          if(isset($_POST["admission_fata"]) && $_POST["admission_fata"]=='on'){
          $check = "4";
          }else{
            $check = "";
          }
          $pdf->SetFont('ZapfDingbats','B', 10);
          $pdf->Cell(5,7,$check, 1, 0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('FATA/FANA');

          $pdf->Cell(2,7, '', 0, 0);
          if(isset($_POST["admission_real_son"]) && $_POST["admission_real_son"]=='on'){
          $check = "4";
          }else{
            $check = "";
          }
          $pdf->SetFont('ZapfDingbats','B', 10);
          $pdf->Cell(5,7,$check, 1, 0);
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine(' Real son of teachers/Employees of concerned stup');


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolTitle('Applicant\'s Profile');
          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Name');
          $pdf->schoolData($_POST["admission_applicant_name"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('CNIC');
          $pdf->schoolData($_POST["admission_applicant_cnic"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Date of Birth');
          $pdf->schoolData($_POST["applicant_birth_date"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Place of Birth');
          $pdf->schoolData($_POST["applicant_Place_of_Birth"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Nationality');
          $pdf->schoolData($_POST["aplicant_nationality"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Religion');
          $pdf->schoolData($_POST["applicant_religion"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Residential Status');
          $pdf->schoolData(ucwords(str_replace(array('admission_','_'),array('',' '),$_POST["admission_residential_status"])),1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Present Address');
          $pdf->schoolData($_POST["admission_applicant_present_address"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Residential Address');
          $pdf->schoolData($_POST["admission_Permanent_residential_address"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInline('Phone Residence');
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["admission_phone_number_residence"]);
          $pdf->Cell(1,7, '', 0, 0);
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Mobile/Whatsapp');
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["admission_mobile_whatsapp"]);
          $pdf->Cell(1,7, '', 0, 0);
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Email');
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["admission_applicant_Email"]);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolTitle('Father\'s Profile');

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Student Father Name');
          $pdf->schoolData($_POST["father_applicant_name"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Student Father CNIC');
          $pdf->schoolData($_POST["father_applicant_cnic"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Guardians Name(father is deseased)');
          $pdf->schoolData($_POST["father_G_name"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Guardians CNIC/Form B No.');
          $pdf->schoolData($_POST["father_g_cnic"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Guardians relation with award');
          $pdf->schoolData($_POST["father_award"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Father\'s/Guardians Occupation');
          $pdf->schoolData($_POST["father_occupation"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Office Address');
          $pdf->schoolData($_POST["father_office_address"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Monthly income Rs.');
          $pdf->schoolData($_POST["father_income"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Phone Office');
          $pdf->schoolData($_POST["father_Office"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInline('Phone Residence');
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["father_Phone_number_residence"]);
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Mobile/Whatsapp');
          $pdf->SetFont('Arial','',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["father_mobile_whatsapp"]);



          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->schoolDataInLine('Email');
          $pdf->SetFont('Arial','B',10);
          $pdf->SetFillColor(255,255,255);
          $pdf->schoolDataInLine($_POST["father_email"]);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolTitle('Reference');
          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Name');
          $pdf->schoolData($_POST["reference_name"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Relation');
          $pdf->schoolData($_POST["reference_relation"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Office/Business Address');
          $pdf->schoolData($_POST["reference_business_address"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Phone number residence');
          $pdf->schoolData($_POST["reference_phone"],1,0,1);

          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Mobile/Whatsapp');
          $pdf->schoolData($_POST["reference_whatsapp"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Office');
          $pdf->schoolData($_POST["reference_Office"],1,0,1);


          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Do you want to avail college bus facility');
          if(isset($_POST["bus_facility"]) && $_POST["bus_facility"]=='admission_yes'){
            $pdf->schoolData('Yes',1,0,1);
          //  $pdf->Cell(5,7,4, 1, 0);
          }else{
          $pdf->schoolData('No',1,0,1);
          }
          if(isset($_POST["bus_facility"]) && $_POST["bus_facility"]=='admission_yes'){
          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolData('Pickup/Drop In Point');
          $loc=explode('_',strtoupper($_POST["admission_location"]));
          $loc=implode(' ',$loc);
          $pdf->schoolData(str_replace('ADMISSION ','',$loc),1,0,1);
          }
          $pdf->Ln(10);
          $pdf->Cell(1);
          $pdf->schoolTitle('Educational Profile');
          $pdf->Ln(11);
          // Column widths 2, 1,1,2,2,2,1
              $w = array(20,17,14,20,18,15,21,18,46);
              $h = array(5,10,10,10,5,5,5,5,10);
              // Header ,'Ist Annual/ 2nd Annual'
              $header=array('Exam Passed','Subject','RollNo','Reg. No','Board/ University','Year of Passing','Marks Obtained','Total Marks','Name of Institution');
          $data=array('admission_ssc_subject','admission_ssc_roll','admission_ssc_reg','admission_ssc_board','admission_ssc_ypass','admission_ssc_mo','admission_ssc_tm','admission_ssc_ni');
          $exams=array('ssc','hssc','other');
          $pdf->SetFont('Arial','B',9);
          $pdf->SetFillColor(232, 232, 232);
          $pdf->SetTextColor(0,0,0);
          $i=0;
          $y=$pdf->GetY();
          $x=$pdf->GetX();
          $new_x=$x;
          $i=0;
          foreach($header as $col){
            $pdf->MultiCell($w[$i],$h[$i],$col,1,'L',true);
            $pdf->SetY($y);
            $new_x+=$w[$i++];
            $pdf->SetX($new_x);

          }
                  //$this->Cell($w[$i],7,$header[$i],1,0,'C');
                  $pdf->Ln(10);
                      // Data
                      $pdf->SetFont('Arial','',8);
                      $pdf->SetFillColor(255, 255, 255);
                      $pdf->SetTextColor(0,0,0);

       $nb=0;
                foreach($exams as $exam) {
                  $i=0;

$nb=max($nb,$pdf->NbLines($w[$i++],$exam));
   foreach($data as $value){
       $nb=max($nb,$pdf->NbLines($w[$i++],$value));
   }
   $row_h=4 * $nb;
        $x_axis=$pdf->GetX();
        $y_axis=$pdf->GetY();

                    //$pdf->SetY($y);
                  //$pdf->Cell($w[$i++],5,strtoupper($exam),1,0,'L',false);

   $i=0;
 $pdf->Rect($x_axis,$y_axis,$w[$i],$row_h);
 $pdf->MultiCell($w[$i],4,strtoupper($exam),0,'L',false);
 $pdf->SetXY($x_axis+$w[$i++],$y_axis);
                      foreach($data as $value)
                      {
                        $post_field=str_replace('_ssc_','_'.$exam.'_',$value);
                        $v=isset($_POST[$post_field])?$_POST[$post_field]:'';
                        if($value=='admission_'.$exam.'_ist'){
                        $post_field2='admission_'.$exam.'_board';
                        $v2=isset($_POST[$post_field2])?$_POST[$post_field2]:'';
                        if($v2=='') $v='';
                        }
                          //$pdf->Cell($w[$i++],7,$v,1,0,'L',false);

            $x_axis=$pdf->GetX();
        $y_axis=$pdf->GetY();
                          $pdf->Rect($x_axis,$y_axis,$w[$i],$row_h);
                          $pdf->MultiCell($w[$i],4,$v,0,'L',false);
                          $pdf->SetXY($x_axis+$w[$i++],$y_axis);
                        //  Cell($w,7,$title,$border,0,$align,true);
                      }
     // $y+=$row_h;
                    //$pdf->SetY($y);
                    //echo 'Row H :'.$row_h;
                      $pdf->Ln($row_h);
                  }
          //$pdf->Output();exit;
          $upload = wp_upload_dir();
          $upload_dir = $upload['basedir'];
          $filename = $upload_dir . '/pdf/admissions/'.$pid.'.pdf';
          $pdf->Output('F',$filename, true); // save into some other location

          $admin_email = get_option('admin_email');
                    $to=$_POST['admission_applicant_Email'];
      $headers = array('Content-Type: text/html; charset=UTF-8');
     $attachments = array($filename);
      if($to<>''){
       $message = '<!DOCTYPE html>
                          <html>

                          <body>
                              <table>

                                  <tr>
                                      <td>
                                          <p>Hi ,</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>'.$_POST["admission_applicant_name"].'</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>Thanks for registering with us.</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>Kindly Check the attachment with the email.</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>Take a printout of the attached file and this email.</p>
                                      </td>
                                  </tr>
                              </table>

                          </body>

                          </html>';

//wp_mail($to, "Your Registration Form has been Generated.", $message, $headers,$attachments);
      }
       $message = '<!DOCTYPE html>
                          <html>

                          <body>
                              <table>

                                  <tr>
                                      <td>
                                          <p>Dear Admin ,</p>
                                      </td>
                                  </tr>

                                  <tr>
                                      <td>
                                          <p>A new registeration has been created</p>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <p>Kindly Check the attachment form.</p>
                                      </td>
                                  </tr>

                              </table>

                          </body>

                          </html>';
    //wp_mail($admin_email, "News Registration", $message, $headers,$attachments);
        //  wp_redirect( get_permalink( 32 ) );

        $current_url = get_permalink( get_the_ID() );

          wp_redirect($current_url.'?post_id='.$pid);
                  exit;
              //  header("Location: $redirecturl");

         }

}

add_action("wp", "gpp_save");

	$check= get_option('gpp_postAuthor');
    if($check) {
    add_shortcode( 'guest-post', 'gpp_shortcode' );
    }



add_action( 'init', 'gpp_create' );
function gpp_create() {
/*****************************************************/
 $labels = array(
        'name'                  => ( 'Student Registrations'),
        'singular_name'         => ( 'Student'),
        'menu_name'             => ( 'Student Registration'),
        'add_new_item'          => __( 'Add New Student', 'textdomain' ),
        'new_item'              => __( 'New Student', 'textdomain' ),
        'edit_item'             => __( 'Edit Student', 'textdomain' ),
        'view_item'             => __( 'View Student', 'textdomain' ),
        'all_items'             => __( 'All Students', 'textdomain' ),
        'search_items'          => __( 'Search Student', 'textdomain' ),
        'not_found'             => __( 'No Students found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No Students found in Trash.', 'textdomain' ),
        );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-businessman',
        'rewrite'            => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'thumbnail'  ),
    );

register_post_type( 'student_registration', $args );

/*****************************************************/

$labels = array(
  'name' => _x( 'Discipline', 'taxonomy general name' ),
  'singular_name' => _x( 'Discipline', 'taxonomy singular name' ),
  'search_items' =>  __( 'Search Discipline' ),
  'all_items' => __( 'All Disciplines' ),
  'parent_item' => __( 'Parent Discipline' ),
  'parent_item_colon' => __( 'Parent Discipline:' ),
  'edit_item' => __( 'Edit Discipline' ),
  'update_item' => __( 'Update Discipline' ),
  'add_new_item' => __( 'Add New Discipline' ),
  'new_item_name' => __( 'New Discipline Name' ),
  'menu_name' => __( 'Disciplines' ),
);

// Now register the taxonomy
register_taxonomy('discipline',array('student_registration'), array(
  'hierarchical' => true,
  'labels' => $labels,
  'show_ui' => true,
  'show_in_rest' => true,
  'show_admin_column' => true,
'show_in_nav_menus' => true,
  'query_var' => true,
'show_in_menu' => 'entry-manager',
  'rewrite' => array( 'slug' => 'discipline' ),
));

/*****************************************************/
}




add_filter('manage_student_registration_posts_columns', function($columns) {
	return array_merge($columns, ['verified' => __('Form', 'textdomain')]);
});

add_action("manage_student_registration_posts_custom_column", function($column_key, $post_id) {
	if ($column_key == 'verified') {
		$verified = get_post_meta($post_id, 'form_path', true);
		if ($verified) {
			echo '<span style="color:green;"><a href='.$verified.'>FORM</a></span>';
		} else {
			echo '<span style="color:red;">'; _e('No', 'textdomain'); echo '</span>';
		}
	}
}, 10, 2);

?>