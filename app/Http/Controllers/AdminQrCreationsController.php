<?php namespace App\Http\Controllers;

use App\DateToSendCampaigns;
use DB;
use Session;
use Request;
use CRUDBooster;
use Mail;
use Exception;
use App\GCList;
use App\IdType;
use App\QrCreation;
use App\EmailTesting;
use App\Exports\GCListBDOTemplateExport;
use App\Exports\GCListPerCampaignDataExport;
use App\Mail\QrEmail;
use App\Jobs\SendEmailJob;
use App\Jobs\GCListFetchJob;
use App\Jobs\StoreConceptFetchApi;
use App\Imports\GcListImport;
use App\Exports\GCListTemplateExport;
use App\Imports\BDOCampaignImport;
use App\Jobs\BDOCampaignJob;
use App\Jobs\CampaignCreationFetchApi;
use App\Jobs\EmailScheduler;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session as UserSession;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\EmailTemplateImg;
use App\StoreConcept;

class AdminQrCreationsController extends \crocodicstudio\crudbooster\controllers\CBController {
	
	public function __construct() {
			
			DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping("enum", "string");
		}

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
			$this->table = "qr_creations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"QR Type","name"=>"qr_type","join"=>"qr_types,name"];
			$this->col[] = ["label"=>"Campaign Id","name"=>"campaign_id"];
			$this->col[] = ["label"=>"Gc Description","name"=>"gc_description"];
			$this->col[] = ["label"=>"Gc Value","name"=>"gc_value"];
			$this->col[] = ["label"=>"Number Of Gcs","name"=>"batch_number"];
			$this->col[] = ["label"=>"Remaining Gcs","name"=>"upload_limit_control"];
			$this->col[] = ["label"=>"Store Logo","name"=>"store_logo","join"=>"store_logos,name"];
			$this->col[] = ["label"=>"Batch Group","name"=>"batch_group"];
			$this->col[] = ["label"=>"Company Tag","name"=>"company_id","join"=>"company_ids,company_name"];

			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Campaign Id','name'=>'campaign_id','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Description','name'=>'gc_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Value','name'=>'gc_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Number Of Gcs','name'=>'batch_number','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Batch Group','name'=>'batch_group','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			// $this->form[] = ['label'=>'Batch Number','name'=>'batch_number','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Company Name','name'=>'company_id','type'=>'select','validation'=>'required|min:1|max:255',"datatable"=>"company_ids,company_name",'width'=>'col-sm-6'];


			# END FORM DO NOT REMOVE THIS LINE

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
			$this->script_js = '';


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

			CampaignCreationFetchApi::dispatch();
			// StoreConceptFetchApi::dispatch();
			// GCListFetchJob::dispatch();

			$cb_id = CRUDBooster::myId(); 
			$cb_companyId = DB::table('cms_users')
				->where('id', $cb_id)
				->value('company_id');

			if(CRUDBooster::myPrivilegeName() == 'Company'){
				$query->where('campaign_status', 3)->where('company_id', $cb_companyId);
			}else{
				$query->where('campaign_status', 3);
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
		}

		/* 
		| ---------------------------------------------------------------------- 
		| Hook for execute command after add public static function called 
		| ---------------------------------------------------------------------- 
		| @id = last insert id
		| 
		*/
		public function hook_after_add($id) {        
			//Your code here
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
			$cb_id = CRUDBooster::myId();
			$cb_company_id = DB::table('cms_users')->where('id', $cb_id)->value('company_id');
			$qr_creation = QrCreation::find($id);

			if($email['selected_button'] == 'Create Email Template'){

				$qr_creation->update([
					'selected_template' => $email['selected_template'],
					// 'date_to_send' => $email['date_to_send'],
					'pending' => 1
				]);


				// if($email['email_option'] == 2){
				// 	$qr_creation = QrCreation::find($id);
				// 	$email_img = $email['mail_img'];
				// 	$random_str = $email['subject_of_the_email'];
					
				// 	if(!$email_img){
				// 		$postdata['html_email'] = null;
				// 	}else{

				// 		$email_img = $email['mail_img'];

				// 		$filename = 'email_img'."$qr_creation_id".'_'.$random_str.'.'.$email_img->getClientOriginalExtension();
				// 		$image = Image::make($email_img);

				// 		$image->encode($email_img->getClientOriginalExtension(), 100);
						
				// 		$image->save(public_path('uploaded_item/email_img/' . $filename));
				// 		$qr_creation->html_email_img = $filename;
				// 		$qr_creation->save();

				// 		$postdata['html_email'] = null;
				// 	}
				// }else{

				// 	if(!$email['email_content']){
				// 		return CRUDBooster::redirect(CRUDBooster::mainpath("edit/$id"), sprintf("Email content must not leave empty."), "danger");
				// 	}

				// 	$postdata['html_email_img'] = null;
				// }
				// $postdata['status_id'] = 2;	
				
				// QrCreation::find($id)->update([
				// 	'title_of_the_email' => $email['title_of_the_email'],
				// 	'subject_of_the_email' => $email['subject_of_the_email'],
				// 	'html_email' => $email['email_content'],	
				// ]);
			}
		}
		

		/* 
		| ---------------------------------------------------------------------- 
		| Hook for execute command after edit public static function called
		| ----------------------------------------------------------------------     
		| @id       = current id 
		| 
		*/
		public function hook_after_edit($id) {
			
			CRUDBooster::redirect(CRUDBooster::mainpath("edit/$id"), sprintf("Email successfuly setup you can now upload GCList."),"success");
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

		public function getEdit($id){

			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}

			$cb_id = CRUDBooster::myId();
			$cb_company_id = DB::table('cms_users')->where('id', $cb_id)->value('company_id');
			
			$data = [];
			$data['page_title'] = 'Upload GC List';
			$data['row'] = DB::table('qr_creations')->find($id);
			$data['valid_ids'] = IdType::get();
			$data['rowCount'] = GCList::where('campaign_id', $data['row']->id)->count();
			$data['percent'] = $data['row']->total_row ? ($data['rowCount'] / $data['row']->total_row) * 100 : 0;

			$file_name = $data['row']->campaign_id.'-'.$data['row']->id.'.xlsx';
			if (Storage::exists($file_name)) {
				$exist = true;
			}
			$data['download_built_export'] = $exist;

			if(CRUDBooster::isSuperAdmin()){
				$data['email_templates'] = EmailTesting::get();
			}else{
				$data['email_templates'] = EmailTesting::where('company_id', $cb_company_id)->get();
			}
			
			if($data['row']->qr_type == 1){
				return $this->view('qr_creation.bdo_campaign',$data);
			}else if($data['row']->qr_type == 2){
				if($data['row']->pending == null){
					$data['page_title'] = 'Email Template';
					return $this->view('email_testing.email-templates',$data);
				}else {
					return $this->view('redeem_qr.upload_gc_list',$data);
				}
			}


		}

		public function exportGCListTemplate(){
			return Excel::download(new GCListTemplateExport, 'gc_list_template.xlsx');
		}

		public function exportGCListCampaignTemplate(){
			return Excel::download(new GCListBDOTemplateExport, 'gc_list_bdo_template.xlsx');
		}

		public function exportDataCampaign($campaign_id, $campaign){
			$file_name = "$campaign-$campaign_id.xlsx";
			(new GCListPerCampaignDataExport($campaign_id))->queue($file_name);

			return back()->withSuccess('Export started!');
		}

		public function countImportData($campaign_id){
			$rowCount = GCList::where('campaign_id', $campaign_id)->count();

			return $rowCount;
		}

		public function exportBuildData($campaign_id, $campaign){
			$file_name = "$campaign-$campaign_id.xlsx";
			
			// Check if the file exists
			if (Storage::exists($file_name)) {
				// Get the full path to the file
				$filePath = Storage::path($file_name);
		
				// Download the file using Laravel's response()->download()
				return response()->download($filePath, $file_name);
			} else {
				// Handle the case where the file does not exist
				return response()->json(['error' => 'File not found.'], 404);
			}
		}

		// public function exportDataCampaign($campaign_id){
        // Queue the export job
        // $filePath = (new GCListPerCampaignDataExport($campaign_id))->store('exports');

        // // Generate a downloadable link
        // $downloadLink = asset('storage/' . $filePath);

        // // Redirect back with the download link
        // return back()->with('downloadLink', $downloadLink);
    	// }

		// public function uploadGCListPost(IlluminateRequest $request){

		// 	// Validate file type
		// 	$validatedData = $request->validate([
		// 		'excel_file' => 'required|mimes:xls,xlsx',
		// 	]);
			
		// 	$campaign_id = $request->all()['campaign_id'];
		// 	$uploaded_excel = $request->file('excel_file');
			
		// 	$import = new GcListImport(compact('campaign_id'));
		// 	$rows = Excel::import($import, $uploaded_excel);
	
		// 	// Send Email
		// 	$generated_qr_info = QrCreation::find($campaign_id);
		// 	$email_content = $generated_qr_info->html_email_img;

		// 	$gc_list_user = GCList::where('campaign_id', $campaign_id)
		// 		->where('email_is_sent', 0)
		// 		->pluck('id')
		// 		->all();
			
		// 	foreach($gc_list_user as $user){

		// 		$gcList = GCList::find($user);
				
		// 		$id = $gcList->id;
		// 		$name = $gcList->name;
		// 		$email = $gcList->email;
		// 		$generated_qr_code = $gcList->qr_reference_number;
		// 		$campaign_id_qr = $generated_qr_info->campaign_id;
		// 		$gc_description = $generated_qr_info->gc_description;
		// 		$gc_value = $generated_qr_info->gc_value;
		// 		$email_template_id = $gcList->email_template_id;

		// 		$email_template = $generated_qr_info->html_email;
		// 		$email_subject = $generated_qr_info->subject_of_the_email;

		// 		$url = "/g_c_lists/edit/$id?value=$generated_qr_code";
		// 		$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
		// 		$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
				
		// 		$html_email = str_replace(
		// 			['[name]', '[campaign_id]', '[gc_description]', '[qr_code]'],
		// 			[$name, $campaign_id_qr, $gc_description, $qr_code ],
		// 			$email_template
		// 		);
		// 		$html_email_img = $generated_qr_info->html_email_img;
				
		// 		// If email content is image
		// 		if($email_content){

		// 			$data = array(
		// 				'id' => $id,
		// 				'qr_reference_number'=>$generated_qr_code,
		// 				'campaign_id_qr' => $campaign_id_qr,
		// 				'gc_description' => $gc_description,
		// 				'qr_code' => $qr_code,
		// 				'gc_value' => $gc_value,
		// 				'email' => $email,
		// 				'html_email_img' => $html_email_img,
		// 				'email_subject' => $email_subject,
		// 				'store_logo' => $generated_qr_info->store_logo,
		// 				'qrCodeApiUrl' => $qrCodeApiUrl
		// 			);

		// 		}else{

		// 			$data = array(
		// 				'name'=> $name,
		// 				'id' => $id,
		// 				'qr_reference_number'=>$generated_qr_code,
		// 				'campaign_id_qr' => $campaign_id_qr,
		// 				'gc_description' => $gc_description,
		// 				'email' => $email,
		// 				'html_email' => $html_email,
		// 				'email_subject' => $email_subject
		// 			);

		// 		}

		// 		// SendEmailJob::dispatch($data);
		// 	}
			
		// 	$generated_qr_info->status_id = 1;
		// 	$generated_qr_info->save();

		// 	return CRUDBooster::redirect(CRUDBooster::mainpath(),'Excel file uploaded successfully. QR codes have been sent to the email addresses.', 'success')->send();
		// }

		public function uploadGCListPost(IlluminateRequest $request){
			
			$return_inputs = $request->all();
			// Validate file type
			$validatedData = $request->validate([
				'excel_file' => 'required|mimes:xls,xlsx',
			]);
			
			$campaign_id = $request->all()['campaign_id'];
			$uploaded_excel = $request->file('excel_file');

			$generated_qr_info = QrCreation::find($campaign_id);

			$generated_qr_info->date_to_send = $return_inputs['date_to_send'];
			$generated_qr_info->status_id = 1;
			$generated_qr_info->save();
			
			$import = new GcListImport(compact('campaign_id'));
			$rows = Excel::import($import, $uploaded_excel);

			DateToSendCampaigns::updateOrCreate(['date_to_send' => $generated_qr_info->date_to_send],
				[
					'campaign_id'=>$generated_qr_info->id,
					'date_to_send'=>$generated_qr_info->date_to_send,
					'created_by' => CRUDBooster::myId()
				]
			);
			// dd($request->all()['date_to_send']);

			// dd($generated_qr_info->date_to_send);
			// // $email_content = $generated_qr_info->html_email_img;

			// $gc_list_user = GCList::where('campaign_id', $campaign_id)
			// 	->where('email_is_sent', 0)
			// 	->get();

			// foreach($gc_list_user as $gcList){

			// 	$generated_qr_info = QrCreation::find($gcList->campaign_id);
			// 	$id = $gcList->id;
			// 	$name = $gcList->name;
			// 	$email = $gcList->email;
			// 	$generated_qr_code = $gcList->qr_reference_number;
			// 	$campaign_id_qr = $generated_qr_info->campaign_id;
			// 	$gc_description = $generated_qr_info->gc_description;
			// 	$gc_value = $generated_qr_info->gc_value;
			// 	$email_template_id = $gcList->email_template_id;

			// 	$email_template = $generated_qr_info->html_email;
			// 	$email_subject = $generated_qr_info->subject_of_the_email;

			// 	$url = "/g_c_lists/edit/$id?value=$generated_qr_code";
			// 	$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
			// 	$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";

			// 	$emailTesting = EmailTesting::where('id',$gcList->email_template_id)->first();
			// 	$emailTestingImg = EmailTemplateImg::where('header_id', $emailTesting->id)->get();

			// 	$subject_of_the_email = $emailTesting->subject_of_the_email;
			// 	$email_content = $emailTesting->html_email;

			// 	$html_email_img = [];

			// 	foreach($emailTestingImg as $file){
			// 		$filename = $file->file_name;
			// 		$html_email_img[]= $filename;
			// 	}

			// 	$html_email = str_replace(
			// 		['[name]', '[campaign_id]', '[gc_description]'],
			// 		[$name, $campaign_id_qr, $gc_description],
			// 		$email_content
			// 	);

			// 	$data = array(
			// 		'id' => $id,
			// 		'html_email' => $html_email,
			// 		'email_subject' => $subject_of_the_email,
			// 		'html_email_img' => $html_email_img,
			// 		'email' => $email,
			// 		'qrCodeApiUrl' => $qrCodeApiUrl,
			// 		'gc_value' => $gc_value,
			// 		'store_logo' => $generated_qr_info->store_logo,
			// 		'gc_description' => $gc_description,
			// 		'qr_reference_number'=>$generated_qr_code,
			// 		'campaign_id_qr' => $campaign_id_qr,
			// 	);

			// 	SendEmailJob::dispatch($data);
			// }

			return CRUDBooster::redirect(CRUDBooster::mainpath(),'Excel file uploaded successfully. QR codes have been sent to the email addresses.', 'success')->send();
		}

		public function uploadBDOCampaign(IlluminateRequest $request){
			$return_inputs = $request->all();
			// Validate file type
			$validated_data = $request->validate([
				'excel_file' => 'required|mimes:xls,xlsx',
			]);
			
			$excel_file = $return_inputs['excel_file'];
			$campaign = QrCreation::find($return_inputs['campaign_id']);



			$bdo_campaign = new BDOCampaignImport($campaign);
			Excel::import($bdo_campaign, $excel_file);
			$total_rows = $bdo_campaign->getTotalRows();

			$campaign->total_row = $campaign->total_row+$total_rows;
			$campaign->save();

			$file_name = "$campaign->campaign_id-$campaign->id.xlsx";
			
			if (Storage::exists($file_name)) {
				$filePath = Storage::path($file_name);
				unlink($filePath);
			}

			// $data = [];
			// $data['qr_creations'] = $campaign;
			// $data['total_rows'] = $total_rows;
			// $data['created_by'] = CRUDBooster::myId();

			// BDOCampaignJob::dispatch($data);

			// GPT
			// Determine the total number of rows and calculate the number of batches
			$row = $total_rows;
			$batch_size = 1000;
			$num_batches = ceil($row / $batch_size);

			// Loop through the batches and dispatch a job for each batch
			for ($batch = 0; $batch < $num_batches; $batch++) {
				// Calculate the start index and the number of rows for this batch
				$start_index = $batch * $batch_size;
				$batch_rows = min($batch_size, $row - $start_index);

				// Prepare the data for this batch
				$batch_data = [
					'qr_creations' => $campaign,
					'total_rows' => $batch_rows,
					'created_by' => CRUDBooster::myId(),
				];

				// Dispatch the job for this batch
				BDOCampaignJob::dispatch($batch_data);
			}
			// End of GPT

			if ($campaign->upload_limit_control) {
				$upload_limit_control = $campaign->upload_limit_control - $total_rows;
			}else {
				$upload_limit_control = $campaign->batch_number - $total_rows;
			}
			
			$campaign->update([
				'upload_limit_control' => $upload_limit_control
			]);

			return CRUDBooster::redirect(CRUDBooster::mainpath(),'Excel file uploaded successfully', 'success')->send();
		}

		public function EmailTesting(IlluminateRequest $request){

			$email = $request->all();

			$subject_of_the_email = $email['subject_of_the_email'];
			$test_email = $email['test_email'];
			$email_content = $email['email_content'];
			$email_option = $email['email_option'];
			$qr_creation_id = $email['qr_creation_id'];
			$qr_creation = QrCreation::find($qr_creation_id);
			
			// Text Email
			if($email_option == 1){

				$qr_creation->html_email_img = null;
				$qr_creation->save();

				$url = "https://egc.digits.com.ph/admin/login";
				$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
				$qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";
	
				$html_email = str_replace(
					['[name]', '[campaign_id]', '[gc_description]', '[qr_code]'],
					['Test Name', 'Test Campaign ID', 'Test Description', $qr_code],
					$email_content
				);
				
				$data = array(
					'html_email' => $html_email,
					'subject_of_the_email' => $subject_of_the_email
				);
	
				Mail::send(['html' => 'email_testing.email_testing'], $data, function($message) use ($test_email, $data) {
					$message->to($test_email)->subject($data['subject_of_the_email']);
					$message->from(env('MAIL_USERNAME'), env('APP_NAME'));
				});
			}
			// Image
			else{
				
				$email_img = $email['mail_img'];
				$url = "https://egc.digits.com.ph/admin/login";
				$qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);

				if($email_img){

					$filename = 'email_img'."$qr_creation_id".'.'.$email_img->getClientOriginalExtension();
					$image = Image::make($email_img);
					$image->encode($email_img->getClientOriginalExtension(), 100);

					$image->save(public_path('uploaded_item/email_img/' . $filename));
					self::manipulate_image($qr_creation->gc_value, $qrCodeApiUrl, $store->logo);
					
					$qr_creation->html_email_img = $filename;
					$qr_creation->save();
				}
				
				$img_sent = $qr_creation->html_email_img;
				$real_image = self::manipulate_image($qr_creation->gc_value, $qrCodeApiUrl, $qr_creation->store_logo);

				
				$data = array(
					'qr_code_generated' => $real_image,
					'html_email_img' => $qr_creation->html_email_img,
					'subject_of_the_email' => $subject_of_the_email,
					'qr_code' => "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>",
					'store_logo' => $qr_creation->store_logo,
					'gc_value' => $qr_creation->gc_value
				);

				Mail::send(['html' => 'email_testing.email_testing'], $data, function($message) use ($test_email, $data) {
					$message->to($test_email)->subject($data['subject_of_the_email']);
					$message->from(env('MAIL_USERNAME'), env('APP_NAME'));
				});

				unlink(public_path($data['qr_code_generated']));

			}

			return response()->json(['success'=>'success', 'email_img'=>$img_sent]);
		}

		public function backToEmailTemplate($id) {
			
			QrCreation::find($id)->update(['pending' => null]);
			return redirect()->back();
		}

		public function manipulate_image($amount, $qr_api, $store_logo){

			$dw_path = 'store_logo/img/digital_walker';
			$btb_path = 'store_logo/img/beyond_the_box';
			$dw_btb_path = 'store_logo/img/btb_and_dw';
			$os_path = 'store_logo/img/os';
			$dyanamic_img_path = 'email_template_img/img/';
			$iphone_path = 'store_logo/img/Iphone-lunch-qr.png';
			
			$dw_image = Image::make(public_path($dw_path.'.jpg'));
			$btb_image = Image::make(public_path($btb_path.'.jpg'));
			$dw_btb_image = Image::make(public_path($dw_btb_path.'.png'));
			$os_path = Image::make(public_path($os_path.'.jpg'));
			$iphone = Image::make(public_path($iphone_path));
			// $dynamic_image = Image::make(public_path($dyanamic_img_path.$qr_img));

			$save_path = 'e_gift_card/img/';

			if($store_logo == 1){

				$logo_path = $dw_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 85;
				$qr_y_position = 35;
				$color = '#d85a5f';
				$shadow = '#000000';

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $store_logo);
			}

			elseif($store_logo == 2){

				$logo_path = $btb_image;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow,$store_logo);
			}

			elseif($store_logo == 3){

				$logo_path = $dw_btb_image;
				$filename = $save_path.Str::random(10).'.png';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $store_logo);
			}

			if($store_logo == 4){

				$logo_path = $os_path;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $store_logo);
			}
			if($store_logo == 5){

				$logo_path = $iphone;
				$filename = $save_path.Str::random(10).'.jpg';
				$value_width = 510;
				$qr_x_position = 89;
				$qr_y_position = 35;
				$color = '#1a1a1a';
				$shadow = null;

				self::saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow, $store_logo);
			}

			return $filename;
		}

		public function saveImage($amount, $qr_api, $logo_path, $value_width, $filename, $qr_x_position, $qr_y_position, $color, $shadow,$store_logo){

			$text_width = 0; // Initialize outside the closure
	
			$logo_path->text('P'.$amount, 0, -10, function($font) use (&$text_width, $value_width) {
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(55);
				
				$textSize = $font->getBoxSize()['width'];
				
				$calculate_position = ($value_width - $textSize) / 2;
				$text_width = $calculate_position; // Modify the value inside the closure
			});

			if($shadow){
				$real_image = $logo_path->text('P'.$amount, $text_width, 240, function($font) use ($color, $shadow){
					$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
					$font->size(55);
					$font->color($shadow);
				});
			}

			$real_image = $logo_path->text('P'.$amount, $text_width, 237, function($font) use ($color, $shadow){
				$font->file(public_path('font/OpenSans-ExtraBold.ttf'));
				$font->size(55);
				$font->color($color);
			});

			$rectangleImage = Image::canvas(210, 210, 'rgba(255, 255, 255, 1)');
			$rectangleImage->rectangle(0, 0, 209, 209, function ($draw) {
				$draw->border(1, '#000');
			});

			$real_image->insert($rectangleImage, 'bottom-right', $qr_x_position-5, $qr_y_position-5);

			$qrCodeApiLink = $qr_api;
			$content = file_get_contents($qrCodeApiLink);
			$qrCodeImage = Image::make($content);

			if($store_logo == 5){
				$qrCodeImage->resize(200, 200); 
			}


			// Overlay the QR code onto the main image as a watermark
			$real_image->insert($qrCodeImage, 'bottom-right', $qr_x_position, $qr_y_position)
				->save(public_path($filename));
		}

	}