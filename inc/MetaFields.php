<?php

/**
*
*/
class MetaFields
{


	public $callbacks;

    private $boxes;

	public $pages = array();

	public $subpages = array();
    public $settings = array();

	public $sections = array();

	public $fields = array();

	public function register()
	{


        add_action('save_post',  array( $this, 'save_my_meta') ,99,3);

        add_action( 'admin_init', array( $this, 'add_mb' ) );

	}


    public function add_mb()
    {
        global $post;
        $id=get_the_ID() ;
       // $this->save_my_meta($id,$post);
        $this->boxes = $this->mb_fields();


        foreach( $this->boxes as $box )
        {

            //$this->fields=$box;'student_registration'
            add_meta_box(
                $box['id'],
                $box['title'],
                array( $this, 'mb_callback' ),
                'student_registration',
                'normal',
                 'default',
                $box['args']
            );
        }
    }

    # Callback function, uses helper function to print each meta box
    public function mb_callback( $post, $box1 )
    {

		wp_nonce_field( 'reg_nonce' ,'entries_front_pt' );
       echo '<table>';
        foreach($box1['args'] as $fields)
        {

            switch( $fields['field'] )
            {
                case 'textfield':
                    $this->textfield( $fields, $post->ID );
                break;
                case 'checkbox':
                    $this->checkbox( $fields, $post->ID );
                break;
            }

        }
        echo '</table>';


    }

    private function textfield( $box, $post_id )
    {
        $post_meta = get_post_meta( $post_id, $box['id'], true );
        printf(
            '<tr><td><label>%s:</label></td><td> <input type="text" name="%s" value="%s" size="30" /> </td></tr>',
            $box['title'],
            $box['id'],
            $post_meta
        );

    }

    private function checkbox( $box, $post_id )
    {

        $post_meta = get_post_meta( $post_id, $box['id'], true );
        printf(
            '<tr><td><label>%s:</label></td><td> <input type="checkbox" name="%s" value="1" %s /></td></tr>',
            $box['title'],
            $box['id'],
            checked( $post_meta, 1 ,false)
        );
    }
    public function save_post($post_id) {
        ?><script>alert("post saved");</script><?php
        die();
    }
    public function save_my_meta(int $post_id , $post) {

	// nonce check 'student_registration'
	if ( ! isset( $_POST['entries_front_pt'] ) || ! wp_verify_nonce( $_POST[ 'entries_front_pt'], 'reg_nonce' ) ) {
		return $post_id;
	}

	// check current use permissions
	$post_type = get_post_type_object( $post->post_type );

	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Do not save the data if autosave
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// define your own post type here
	if( $post->post_type != 'student_registration' ) {
	//	return $post_id;
	}


    if (isset($post_id))

        $this->boxes = $this->mb_fields();
        foreach($this->boxes as $fields){

            foreach($fields['args'] as $field)
            {

                if (isset($_POST[$field['id']]) ) {
                    $data = $_POST[$field['id']];
                    if (isset($data)) {
                        update_post_meta($post_id, $field['id'], $data);
                    }
                    else {
                       // delete_post_meta($id, $field['id']);

                    }
                    }
            }
        }


    }

    function save_entry_meta_box_data( $post_id ) {


        // Check if our nonce is set.
       // if (isset($_POST['gptask']) && $_POST['gptask'] == 'savepost' && wp_verify_nonce($_POST["_wpnonce"])) {
       //     return $post_id;
       // }

        //var_dump($_POST);
        //echo "sfdjhsdjhfdsfjhsdjkfhsdfh<br>dsdjsddjdshjdfjsdffdsfds".$post_id;
        add_action( 'add_meta_boxes', array( $this, 'add_mb' ) );
    /*

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        }
        else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
    */
        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.


       // Update the meta field in the database.
        $this->boxes = $this->mb_fields();
        foreach($this->boxes as $fields){

            foreach($fields['args'] as $field)
            {

                if (isset($_POST[$field['id']]) ) {
                    $data = sanitize_text_field($_POST[$field['id']]);
                    if (isset($data)) {
                        update_post_meta($post_id, $field['id'], $data);
                    }
                    else {
                       // delete_post_meta($id, $field['id']);

                    }
                    }
            }
        }

    }


    public function mb_fields()
    {
        $all_args = array(
            array('title' => 'ADMISSION-CATEGORY',
                'id' => 'reg_admission_category',
                'args' => array(
                    array(
                        'title' => 'Open-Merit',
                        'id'=>'admission_open_merit',
                        'desc' => 'Enter Open Merit',
                        'field' => 'checkbox',
                    ),
                     array(
                        'title' => 'Sports',
                        'id'=>'admission_sports',
                        'desc' => 'Enter Sports',
                        'field' => 'checkbox',
                    ),
                     array(
                        'title' => 'Disabled',
                        'id'=>'admission_disabled',
                        'desc' => 'Enter disabled',
                        'field' => 'checkbox',
                    ),
                array(
                        'title' => 'FATA/FANA',
                        'id'=>'admission_fata',
                        'desc' => 'Enter Fata Fana',
                        'field' => 'checkbox',
                    ),
                array(
                        'title' => 'Real son of teachers/Employees of concerned stup',
                        'id'=>'admission_real_son',
                        'desc' => 'Enter Real son of Employee',
                        'field' => 'checkbox',
                    )
					)
				),
            array(
                'title' => 'Applicants-Profile',
                'id' => 'Applicants_Profile',
                'args' => array(
                    array(
                        'title' => 'Name(in block letters)',
                        'id'=>'admission_applicant_name',
                        'desc' => 'Enter the Name',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'CNIC/Form B No.',
                        'id'=>'admission_applicant_cnic',
                        'desc' => 'Enter CNIC',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Date of Birth',
                        'id'=>'applicant_birth_date',
                        'desc' => 'Enter date of Birth',
                        'field' => 'textfield',
                    ),
					array(
                        'title' => 'Place of Birth',
                        'id'=>'applicant_Place_of_Birth',
                        'desc' => 'Enter the place of birth',
                        'field' => 'textfield',
                    ),
					array(
                        'title' => 'Nationality',
                        'id'=>'aplicant_nationality',
                        'desc' => 'Enter Nationality',
                        'field' => 'textfield',
					),
					array(
                        'title' => 'Religion',
                        'id'=>'applicant_religion',
                        'desc' => 'Enter Religion',
                        'field' => 'textfield',
					  ),
                array(
                        'title' => 'Residential Status(plz tick relevant box)',
                        'id'=>'Residential_Status',
                        'desc' => 'Enter the Residentia status',
                        'field' => 'textfield',
                ),
				array(
                        'title' => ' Residential Status',
                        'id'=>'admission_residential_status',
                        'desc' => 'Enter the  Residential Status',
                        'field' => 'textfield',
                ),
                array(
                        'title' => 'Present Address',
                        'id'=>'admission_applicant_present_address',
                        'desc' => 'Enter the address',
                        'field' => 'textfield',
                ),
                array(
                        'title' => 'Permanent Residential Address',
                        'id'=>'admission_Permanent_residential_address',
                        'desc' => 'Enter the permanant address',
                        'field' => 'textfield',
                ),
                array(
                        'title' => 'Phone number residence',
                        'id'=>'admission_phone_number_residence',
                        'desc' => 'Enter the Phone number residence',
                        'field' => 'textfield',
                ),
                array(
                        'title' => 'Mobile/Whatsapp',
                        'id'=>'admission_mobile_whatsapp',
                        'desc' => 'Enter the Mobile/Whatsapp',
                        'field' => 'textfield',
                ),
                array(
                        'title' => 'Other',
                        'id'=>'admission_other',
                        'desc' => 'Enter the Other',
                        'field' => 'textfield',
                ),
				array(
                        'title' => 'Email',
                        'id'=>'admission_applicant_Email',
                        'desc' => 'Enter the Email',
                        'field' => 'textfield',
                    )
					)
                ),
				  array(
                'title' => 'Father Profile',
				'id' => 'Father_Profile',
                'args' => array(
                    array(
                        'title' => ' Father Name(in block letters)',
                        'id'=>'father_applicant_name',
                        'desc' => 'Enter the Father Name',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Father CNIC/Form B No.',
                        'id'=>'father_applicant_cnic',
                        'desc' => 'Enter the Father CNIC/Form B No.',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Guardians Name(father is deseased)',
                        'id'=>'father_G_name',
                        'desc' => 'Enter the Guardians Name',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Guardians CNIC/Form B No',
                        'id'=>'father_g_cnic',
                        'desc' => 'Enter the Guardians CNIC/Form B No',
                        'field' => 'textfield',
                    ),
                      array(
                        'title' => 'Guardians relation with award',
                        'id'=>'father_award',
                        'desc' => 'Enter the award',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => "Father's/Guardians Occupation/designation",
                        'id'=>'father_occupation',
                        'desc' => 'Enter the father Occupation',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Father Office Address',
                        'id'=>'father_office_address',
                        'desc' => 'Enter the Father Office Address',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Phone number residence',
                        'id'=>'father_Phone_number_residence',
                        'desc' => 'Enter the Phone number residence',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Mobile/Whatsapp',
                        'id'=>'father_mobile_whatsapp',
                        'desc' => 'Enter the Mobile/Whatsapp',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Office',
                        'id'=>'father_Office',
                        'desc' => 'Enter the father Office',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Email Address',
                        'id'=>'father_email',
                        'desc' => 'Enter the Email Address',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => "Monthly income of Father's/Guardian Rs",
                        'id'=>'father_income',
                        'desc' => 'Enter the father income',
                        'field' => 'textfield',
						)
						)
					),
                     array(
						  'title' => 'Reference',
						  'id' => 'ad_Reference',
						  'args' => array(
                    array(
                        'title' => 'Name',
                        'id'=>'reference_name',
                        'desc' => 'Enter the reference name',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Relation',
                        'id'=>'reference_relation',
                        'desc' => 'Enter the reference relation',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Office/Business Address',
                        'id'=>'reference_business_address',
                        'desc' => 'Enter the reference business address',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Phone number residence',
                        'id'=>'reference_phone',
                        'desc' => 'Enter the Phone number residence',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Mobile/Whatsapp',
                        'id'=>'reference_whatsapp',
                        'desc' => 'Enter the reference whatsapp',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Office',
                        'id'=>'reference_Office',
                        'desc' => 'Enter the reference Office',
                        'field' => 'textfield',
                    ),
                     array(
                        'title' => 'Bus facility',
                        'id'=>'bus_facility',
                        'desc' => 'Enter the bus facility',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Admission location',
                        'id'=>'admission_location',
                        'desc' => 'Enter the admission location',
                        'field' => 'textfield',
                    )
					)
				),

                    array(
                'title' => 'Educational Profile (Matric)',
				'id' => 'ad_ssc_Educational_Profile',
                'args' => array(
                    array(
                        'title' => 'SSc/O-level',
                        'id'=>'ad_ssc_level',
                        'desc' => 'Enter the Name',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Subject',
                        'id'=>'admission_ssc_subject',
                        'desc' => 'Enter the subject',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Roll No',
                        'id'=>'admission_ssc_roll',
                        'desc' => 'Enter the roll no',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Registration No',
                        'id'=>'admission_ssc_reg',
                        'desc' => 'Enter the Registration No.',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Board/Uni',
                        'id'=>'admission_ssc_board',
                        'desc' => 'Enter the Board/Uni',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Year of passing',
                        'id'=>'admission_ssc_ypass',
                        'desc' => 'Enter the Year of passing',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Ist annual/2nd annual',
                        'id'=>'admission_ssc_ist',
                        'desc' => 'Enter the Ist annual/2nd annual',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Marks Obtained',
                        'id'=>'admission_ssc_mo',
                        'desc' => 'Enter the admission_ssc_mo',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Total marks',
                        'id'=>'admission_ssc_tm',
                        'desc' => 'Enter the Total marks',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => ' Name of institution',
                        'id'=>'admission_ssc_ni',
                        'desc' => 'Enter the Name of institution',
                        'field' => 'textfield',
                    )
					)
				),
				 array(
                'title' => 'Educational Profile (HSSC)',
				'id' => 'ad_hssc_educational_Profile',
                'args' => array(
                     array(
                        'title' => 'HSSC/A-level',
                        'id'=>'ad_hssc_level',
                        'desc' => 'Enter the Name',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Subject',
                        'id'=>'admission_hssc_subject',
                        'desc' => 'Enter the Subject',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Roll No',
                        'id'=>'admission_hssc_roll',
                        'desc' => 'Enter the Roll No',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Registration No',
                        'id'=>'admission_hssc_reg',
                        'desc' => 'Enter the Registration No',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Board/Uni',
                        'id'=>'admission_hssc_board',
                        'desc' => 'Enter the Board/Uni',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Year of passing',
                        'id'=>'admission_hssc_ypass',
                        'desc' => 'Enter the Year of passing',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Ist annual/2nd annual',
                        'id'=>'admission_hssc_ist',
                        'desc' => 'Enter the Ist annual/2nd annual',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Marks Obtained',
                        'id'=>'admission_hssc_mo',
                        'desc' => 'Enter the Marks Obtained',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Total marks',
                        'id'=>'admission_hssc_tm',
                        'desc' => 'Enter the Total marks',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Name of institution',
                        'id'=>'admission_hssc_ni',
                        'desc' => 'Enter the Name of institution',
                        'field' => 'textfield',
						)
						)
                    ),
                     array(
                'title' => 'Educational Profile (other)',
				'id' => 'other_educational_Profile',
                'args' => array(
                     array(
                        'title' => 'Other',
                        'id'=>'ad_Other',
                        'desc' => 'Enter the Name',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Subject',
                        'id'=>'admission_other_subject',
                        'desc' => 'Enter the Subject',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Roll No',
                        'id'=>'admission_other_roll',
                        'desc' => 'Enter the Roll No',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Registration No',
                        'id'=>'admission_other_reg',
                        'desc' => 'Enter the Registration No',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Board/Uni',
                        'id'=>'admission_other_board',
                        'desc' => 'Enter the Board/Uni',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Year of passing',
                        'id'=>'admission_other_ypass',
                        'desc' => 'Enter the Year of passing',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Ist annual/2nd annual',
                        'id'=>'admission_other_ist',
                        'desc' => 'Enter the Ist annual/2nd annual',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Marks Obtained',
                        'id'=>'admission_other_mo',
                        'desc' => 'Enter the Marks Obtained',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Total marks',
                        'id'=>'admission_other_tm',
                        'desc' => 'Enter the Total marks',
                        'field' => 'textfield',
                    ),
                    array(
                        'title' => 'Name of institution',
                        'id'=>'admission_other_ni',
                        'desc' => 'Enter the Name of institution',
                        'field' => 'textfield',
                    )
)
)

        );
        return $all_args;


    }


}
