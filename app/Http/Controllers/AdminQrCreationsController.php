<?php namespace App\Http\Controllers;

use App\IdType;
use Session;
use Request;
use DB;
use CRUDBooster;
use Illuminate\Http\Request as IlluminateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GcListImport;
use App\Exports\GCListTemplateExport;
use App\GCList;
use App\QrCreation;
use Mail;
use Illuminate\Support\Facades\Session as UserSession;

	class AdminQrCreationsController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "id";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = true;
			$this->button_edit = true;
			$this->button_delete = true;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "qr_creations";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			// $this->col[] = ["label"=>"ID","name"=>"id"];
			$this->col[] = ["label"=>"Campaign Id","name"=>"campaign_id"];
			$this->col[] = ["label"=>"Gc Description","name"=>"gc_description"];
			$this->col[] = ["label"=>"Gc Value","name"=>"gc_value"];
			$this->col[] = ["label"=>"Number Of Gcs","name"=>"number_of_gcs"];
			$this->col[] = ["label"=>"Redemption Start","name"=>"redemption_start"];
			$this->col[] = ["label"=>"Redemption End","name"=>"redemption_end"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];
			$this->form[] = ['label'=>'Campaign Id','name'=>'campaign_id','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Description','name'=>'gc_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Gc Value','name'=>'gc_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Number Of Gcs','name'=>'number_of_gcs','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Redemption Start Date','name'=>'redemption_start','type'=>'date','validation'=>'required|date','width'=>'col-sm-6'];
			$this->form[] = ['label'=>'Redemption End Date','name'=>'redemption_end','type'=>'date','validation'=>'required|date','width'=>'col-sm-6'];
			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ['label'=>'Campaign Id','name'=>'campaign_id','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Gc Description','name'=>'gc_description','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Gc Value','name'=>'gc_value','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Number Of Gcs','name'=>'number_of_gcs','type'=>'number','validation'=>'required|integer|min:0','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Redemption Start Date','name'=>'redemption_start','type'=>'date','validation'=>'required|date','width'=>'col-sm-6'];
			//$this->form[] = ['label'=>'Redemption End Date','name'=>'redemption_end','type'=>'date','validation'=>'required|date','width'=>'col-sm-6'];
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
	        $this->script_js = '		

				$("#redemption_start").attr("required", "true");
				$("#redemption_start").removeAttr("readonly");
				$("#redemption_start").on("keypress", function(){
					$(this).val("");	
				});
				$("#redemption_end").attr("required", "true");
				$("#redemption_end").removeAttr("readonly");
			';


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
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

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



	    //By the way, you can still create your own method in here... :) 
		public function getEdit($id){

			if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {    
				CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
			}
			
			$data = [];
			$data['page_title'] = 'Upload GC List';
			$data['row'] = DB::table('qr_creations')->find($id);

			$data['valid_ids'] = IdType::get();

			$email_data = ['qr_code'=>'Testing'];
	
			return $this->view('redeem_qr.upload_gc_list',$data);

		}

		public function exportGCListTemplate(){

			return Excel::download(new GCListTemplateExport, 'gc_list_template.xlsx');
		}

		public function uploadGCListPost(IlluminateRequest $request){

			$validatedData = $request->validate([
				'excel_file' => 'required|mimes:xls,xlsx',
			]);
		
			$campaign_id = $request->all()['campaign_id'];

			$uploaded_excel = $request->file('excel_file');
			
			$import = new GcListImport($campaign_id);

			$rows = Excel::import($import, $uploaded_excel);

			$generated_qr_info = QrCreation::find($campaign_id);
			$gc_list_user = GCList::where('campaign_id', $campaign_id)
				->where('is_sent', 0)
				->pluck('id')
				->all();

			
			// foreach($gc_list_user as $user){

			// 	$gcList = GCList::find($user);

			// 	$id = $gcList->id;
			// 	$name = $gcList->name;
			// 	$email = $gcList->email;
			// 	$generated_qr_code = $gcList->qr_reference_number;
			// 	$campaign_id_qr = $generated_qr_info->campaign_id;
			// 	$gc_description = $generated_qr_info->gc_description;
			// 	$redemption_period = date('F j, Y', strtotime($generated_qr_info->redemption_start)).' - '.date('F j, Y', strtotime($generated_qr_info->redemption_end));
	
			// 	$data = array(
			// 		'name'=> $name,
			// 		'id' => $id,
			// 		'qr_reference_number'=>$generated_qr_code,
			// 		'campaign_id_qr' => $campaign_id,
			// 		'gc_description' => $gc_description,
			// 		'redemption_period' => $redemption_period
			// 	);

			// 	Mail::send(['html' => 'redeem_qr.sendemail'], $data, function($message) use ($email) {
			// 		$message->to($email)->subject('Redeem Your QR Code Now!');
			// 		$message->from('punzalan2233@gmail.com', 'Patrick Lester Punzalan');
			// 	});

			// 	$gcList->update([
			// 		'is_sent' => 1
			// 	]);

			// }

			return redirect(route('qr_creations_edit', $campaign_id))->with('success', 'Excel file uploaded successfully. QR codes have been sent to the email addresses.')->send();

		}

	}