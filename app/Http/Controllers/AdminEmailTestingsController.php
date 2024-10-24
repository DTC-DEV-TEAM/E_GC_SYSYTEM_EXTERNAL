<?php namespace App\Http\Controllers;

use App\QrCreation;
use Session;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Request as Input;
	use DB;
	use CRUDBooster;
	use App\EmailTesting;
	use App\Models\EmailTemplateImg;
	use URL;
	use Mail;
	use Intervention\Image\Facades\Image;
	use File;

	class AdminEmailTestingsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {
			
			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = true;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "email_testings";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			// $this->col[] = ["label"=>"Id","name"=>"id"];
			$this->col[] = ["label"=>"Title of the email","name"=>"title_of_the_email"];
			$this->col[] = ["label"=>"Subject","name"=>"subject_of_the_email"];
			$this->col[] = ["label"=>"Created by","name"=>"created_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Created at","name"=>"created_at"];
			$this->col[] = ["label"=>"Updated by","name"=>"updated_by","join"=>"cms_users,name"];
			$this->col[] = ["label"=>"Updated at","name"=>"updated_at"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Email Subject','name'=>'subject_of_the_email','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-3'];
			$this->form[] = ['label'=>'Html Email','name'=>'html_email','type'=>'wysiwyg','validation'=>'required|string','width'=>'col-sm-8'];
			
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Html Email','name'=>'html_email','type'=>'wysiwyg','validation'=>'required|string','width'=>'col-sm-8'];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();
			if(CRUDBooster::getCurrentMethod() == 'getIndex'){
				$this->index_button[] = ["label"=>"Create Template","icon"=>"fa fa-plus-circle","url"=>CRUDBooster::mainpath('add-template'),"color"=>"success"];
			}

	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        $this->load_js[] = asset("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js");
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        $this->load_css[] = asset("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css");
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
			$cb_id = CRUDBooster::myId(); 
			$cb_companyId = DB::table('cms_users')
				->where('id', $cb_id)
				->value('company_id');

			if(CRUDBooster::myPrivilegeName() == 'Company'){
				$query->where("email_testings.company_id", $cb_companyId);
			}
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here
			$email = Input::all();
			$postdata['title_of_the_email'] = $email['title_of_the_email'];
			$postdata['subject_of_the_email'] = $email['subject_of_the_email'];
			$postdata['html_email'] = $email['email_content'];
			$postdata['created_by'] = CRUDBooster::myId();
			
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
			$fields = Input::all();
			$header = EmailTesting::where(['created_by' => CRUDBooster::myId()])->orderBy('id','desc')->first();

			$files 	= $fields['mail_img'];
			$images = [];
			if (!empty($files)) {
				$counter = 0;
				foreach($files as $file){
					$counter++;
					$name = $header->id .'-'. time().rand(1,50) . '.' . $file->getClientOriginalExtension();
					$filename = $name;
					$file->move('email_template_img/img',$filename);
					$images[]= $filename;

					$header_images = new EmailTemplateImg;
					$header_images->header_id 		        = $header->id;
					$header_images->file_name 		        = $filename;
					$header_images->ext 		            = $file->getClientOriginalExtension();
					$header_images->created_by 		        = CRUDBooster::myId();
					$header_images->save();
				}
			}

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
			$email = Input::all();
			$postdata['title_of_the_email'] = $email['title_of_the_email'];
			$postdata['subject_of_the_email'] = $email['subject_of_the_email'];
			$postdata['html_email'] = $email['email_content'];
			date_default_timezone_set("Asia/Manila");
			date_default_timezone_get();
			$postdata['updated_by'] = CRUDBooster::myId();
			$postdata['updated_at'] = date('Y-m-d H:i:s');

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
			$fields = Input::all();
			$files 	= $fields['mail_img'];
			$images = [];
			if (!empty($files)) {
				$counter = 0;
				foreach($files as $file){
					$counter++;
					$name = $id .'-'. time().rand(1,50) . '.' . $file->getClientOriginalExtension();
					$filename = $name;
					$file->move('email_template_img/img',$filename);
					$images[]= $filename;

					$header_images = new EmailTemplateImg;
					$header_images->header_id 		        = $id;
					$header_images->file_name 		        = $filename;
					$header_images->ext 		            = $file->getClientOriginalExtension();
					$header_images->created_by 		        = CRUDBooster::myId();
					$header_images->save();
				}
			}
	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

		public function getAddTemplate() {
			//Create an Auth
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Create Email Template';
			$data['store_logos'] = DB::table('store_logos')->where('status','ACTIVE')->get();
			
			//Please use view method instead view method from laravel
			return $this->view('email_testing.add_email',$data);
		}

		public function getEdit($id) {
			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Edit Email Template';

			$data['email_template'] = EmailTesting::datas($id);
			$data['email_template_imgs'] = EmailTemplateImg::images($id);
			$data['store_logos'] = DB::table('store_logos')->where('status','ACTIVE')->get();
			return $this->view('email_testing.edit-email',$data);
		}

		public function deleteImages(Request $reuqest) {
			$id = $reuqest->id;
		
			$getImages = DB::table('email_template_img')->where('id',$id)->first();

			//foreach($getImages as $img){
				$removeImg = public_path('email_template_img/img/').$getImages->file_name;
				if(file_exists($removeImg)){
					@unlink($removeImg);
		
				}
			//}
			
			DB::table('email_template_img')->where('id',$id)->delete();

			$message = ['status'=>'success', 'message' => 'Deleted!'];

			echo json_encode($message);
		}

		public function selectedHeader(Request $request) {
			$fields 		= Input::all();
			$id = $fields['id'];
			$campaign_id = $fields['campaign_id'];
	
			$search 		= $fields['header_request_id'];

			$data['emailContent'] = '';

			$data['CampaignId'] = QrCreation::where('id', $campaign_id)->first();
			$data['EmailHeader'] = EmailTesting::where('id',$id)->first();
			$data['EmailHeaderImgs'] = EmailTemplateImg::where('header_id',$id)->get();
		
			$data['emailContent'] .= '
			    <div style="border: 1px solid black; padding: 10px; border-radius:5px">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="add_email_header"> <span></span> Template Name</label>
								<input type="hidden" class="form-control" id="email_testings_id" name="email_testings_id" value="'.$data['EmailHeader']->id.'">
								<input type="text" class="form-control" name="title_of_the_email" value="'.$data['EmailHeader']->title_of_the_email.'" readonly>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="add_email_header" for="">Subject</label>
								<input type="text" class="form-control" name="subject_of_the_email" value="'.$data['EmailHeader']->subject_of_the_email.'" readonly>
							</div>
						</div>
					</div>

					<div class="row show_email">
						<div class="col-lg-12">
							<div class="form-group">
								<label class="add_email_header" for="">Email Content</label>
								<textarea id="email-test" name="email_content" readonly>
									'.$data['EmailHeader']->html_email .'
								</textarea>
							</div>
						</div>
					</div>
					<hr>
			';

			$tableRow = 1;
			$total = 0;
			$data['emailContent'] .='
				<label> Images</label>
					<div class="row">
						<div class="col-md-12">
			';

			// DW
			if($data['CampaignId']->store_logo == 1){
				$data['emailContent'] .='		
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'digital_walker.jpg' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'digital_walker_terms_and_conditions.jpg' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'digital_walker_claiming.jpg' .'"> 
					</div>
				';
			}
			// BTB
			else if($data['CampaignId']->store_logo == 2){
				$data['emailContent'] .='		
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'beyond_the_box.jpg' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'beyond_the_box_terms_and_conditions.jpg' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'beyond_the_box_claiming.jpg' .'"> 
					</div>
				';
			}
			// BTB and DW
			else if($data['CampaignId']->store_logo == 3){
				$data['emailContent'] .='		
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'btb_and_dw.png' .'"> 
				</div>
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'beyond_the_box_terms_and_conditions.jpg' .'"> 
				</div>
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'beyond_the_box_claiming.jpg' .'"> 
				</div>
				';
			}
			// Open Source
			else if($data['CampaignId']->store_logo == 4){
				$data['emailContent'] .='		
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'os.jpg' .'"> 
				</div>
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'os_terms_and_conditions.jpg' .'"> 
				</div>
				<div class="col-md-4">		
					<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'os_claiming.jpg' .'"> 
				</div>
				';
			}
			// Iphone Launch
			else if($data['CampaignId']->store_logo == 6){
				$data['emailContent'] .='		
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'Iphone-lunch-qr.png' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'iphone-terms-conditions.png' .'"> 
					</div>
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('store_logo/img').'/'.'iphone-claim.png' .'"> 
					</div>
				';
			}



			foreach($data['EmailHeaderImgs'] as $image){
				$tableRow++;
				$total++;
				$data['emailContent'] .='		
					<div class="col-md-4">		
						<img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain; text-align: center; margin-top: 5px;" src="'.URL::to('email_template_img/img').'/'.$image->file_name .'"> 
					</div>
				';
			}
			$data['emailContent'] .='
						</div>
					</div>
				</div>
			';

			$data['emailContent'] .= "
				<script type='text/javascript'>
					let clicked_btn;
					$('#email-test').summernote('disable');
					$('#email-sample').summernote();
					const note_editable_check_text = $('.note-editable').eq(0).text();
					$('.note-editable').eq(1).attr('contenteditable', 'false');
					$('.note-editable').eq(0).attr('contenteditable', 'false');
					if (/\w/gi.test(note_editable_check_text)) {
						$('.note-editable').eq(0).css('height', '100%');
					}else{
						$('.note-editable').eq(0).css('height', '300px');
					}
					
				</script>
			";

			return $data;
			

		}

		public function sendEmailTesting(Request $request){

			$fields = $request->all();
			$email_send_type = $fields['email_send_type'];
			$store_logo_id = $fields['store_logo_id'];
			$subject_of_the_email = $fields['subject_of_the_email'];
			$myEmail = DB::table('cms_users')->where('id',CRUDBooster::myId())->first();
			$test_email = $myEmail->email;
			$email_content = $fields['email_content'];
			$email_option = $fields['email_option'];
			$qr_creation_id = $fields['qr_creation_id'];

			
			//existing images if update method
			if($email_send_type == "update"){
				$email_template_id = $fields['email_template_id'];
				$existing_img = DB::table('email_template_img')->select('*')->where('header_id',$email_template_id)->get();
				
				$existing_filename = [];
				foreach($existing_img as $exist_img){
					$existing_filename[]= $exist_img->file_name;
				}
			}
			
			//dd($existing_filename);
		
			$files 	= $fields['mail_img'];
			$counter = 0;
			$html_email_img = [];
		
			if (!empty($files)) {
				foreach($files as $file){
					$counter++;
					$name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();
					$filename = $name;
					$file->move('uploaded_item/send_test_images',$filename);
					// $image = Image::make($file);
					// $image->encode($file->getClientOriginalExtension(), 100);
					// $image->save(public_path('uploaded_item/send_test_images/' . $filename));
					$html_email_img[]= $filename;
				}
			}

			
			$html_email = str_replace(
				['[name]', '[campaign_id]', '[gc_description]'],
				['Test Name', 'Test Campaign ID', 'Test Description'],
				$email_content
			);
			
			$data = array(
				'html_email'           => $html_email,
				'subject_of_the_email' => $subject_of_the_email,
				'html_email_img'       => $html_email_img,
				'test_email'           => $test_email,
				'store_logo_id'        => $store_logo_id,
				'email_send_type'      => $email_send_type,
				'existing_filename'    => $existing_filename	
			);

			Mail::send(['html' => 'email_testing.send-test-email'], $data, function($message) use ($test_email, $data) {
				$message->to($test_email)->subject($data['subject_of_the_email']);
				$message->from(env('MAIL_USERNAME'), env('APP_NAME'));
			});

			foreach($html_email_img as $img){
				unlink(public_path('uploaded_item/send_test_images/'.$img));
			}
			
			return response()->json(['success'=>'success', 'email_img'=>$img_sent]);
		}


	}