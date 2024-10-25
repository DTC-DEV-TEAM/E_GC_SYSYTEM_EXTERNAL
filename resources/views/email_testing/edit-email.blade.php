<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')

@push('head')
    {{-- Summernote --}}
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/crudbooster/assets/summernote/summernote.css')}}">
    {{-- Jquery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    {{-- Css --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- Swal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .select2-selection__choice{
            font-size:14px !important;
            color:black !important;
        }
        .select2-selection__rendered {
            line-height: 31px !important;
        }
        .select2-container .select2-selection--single {
            height: 35px !important;
        }
        .select2-selection__arrow {
            height: 34px !important;
        }

        .swal2-popup {
            /* padding: 100px 0; */
            font-size: 17px !important;
            color: rgb(0, 0, 0) !important;
        }
        input[type=file]{
                width: 100%;
                border: 1px solid #a7a5a5ee;
                padding: 5px;
        }

        input[type=file]::file-selector-button {
            margin-right: 20px;
            border: none;
            background: #605ca8;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }

        input[type=file]::file-selector-button:hover {
            opacity: 0.7;
        }
    </style>
@endpush

@section('content')
    <!-- Your html goes here -->
    <div class="sk-chase-position" style="display: none;">
        <div class="sk-chase">
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
            <div class="sk-chase-dot"></div>
        </div>
        <div class="sk-chase-text">
            <p>Please wait, system is on process...</p>
        </div>
    </div>
    
    <p><a title='Return' href='{{ CRUDBooster::mainpath() }}'><i class='fa fa-chevron-circle-left '></i>&nbsp; Back To Email Home</a></p>
    <div class='panel panel-default'>
        <div class='panel-heading'>Edit Form</div>
        <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$email_template->id)}}' autocomplete="off" enctype='multipart/form-data'>
        <input type="hidden" id="email_template_id" name="email_template_id" value="{{ $email_template->id }}">
            @csrf
        <div class='panel-body'>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="add_email_header"> <span></span> Template Name</label>
                            <input type="text" class="form-control" name="title_of_the_email" value="{{ $email_template->title_of_the_email }}" required>
                        </div>
                    </div>
    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="add_email_header" for="">Subject</label>
                            <input type="text" class="form-control" name="subject_of_the_email" value="{{ $email_template->subject_of_the_email }}" required>
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="add_email_header" for="">Email content</label>
                            <select class="form-control" id="email_option" name="email_option" required>
                                <option value="" selected disabled>Choose one</option>
                                @if (!$row->html_email && !$row->html_email_img)   
                                <option value="1" disabled>Text</option>
                                <option value="2">Image</option>
                                @elseif ($row->html_email)
                                <option value="1" selected>Text</option>
                                <option value="2">Image</option>
                                @elseif ($row->html_email_img)
                                <option value="1" disabled>Text</option>
                                <option value="2" selected>Image</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label class="add_email_header" for="">Your Test Email</label>
                            <input type="text" class="form-control" id="test_email" name="test_email" placeholder="youremail@gmail.com" required>
                            <p style="margin-top:10px; color: red;">Required only for testing button.</p>
                        </div>
                    </div>
                </div> --}}

                <div class="row show_email">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="add_email_header" for="">Email Content</label>
                            <textarea id="email-test" name="email_content">
                                {{ $email_template->html_email }}
                            </textarea>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6">
                        <div class="form-group">
                            <label class="add_email_header" for="">Sample Email Content</label>
                            <textarea id="email-sample">
                                <h4><b>QR Code Redemption</b></h4><p>Dear [name],</p><p>We are pleased to provide you with your exclusive QR code for redemption. This QR code represents exciting offers and discounts that you can avail yourself of at our selected stores. Simply present this QR code at the designated store to claim your special offers.</p><p>QR Information</p><p>[qr_code]</p><p>Campaign ID: [campaign_id]</p><p>GC Description: [gc_description]</p><p>We kindly ask you to bring a valid government ID when redeeming your code. This is necessary for identification purposes and to ensure a smooth and secure process. We also request that you review the terms and conditions that apply to the code redemption process. You can find the detailed terms and conditions by visiting the following link: [Insert link to terms and conditions]<p></p><p>If you have any questions or need assistance, please don't hesitate to contact our customer support team. We are here to ensure a smooth and rewarding redemption experience for you.</p><p>Thank you for choosing our services. We appreciate your business and look forward to serving you again in the future.</p><p><br></p><p>Best regards,</p><p>BPG Department</p><p>Digits Trading Corp</p>
                            </textarea>
                        </div>
                    </div> --}}
                </div>
    
                {{-- <div class="row" style="margin-left:10px text-align:center">
                  
                   
                </div> --}}

                <div class="row">
                    <div class="col-lg-6 mail_img_content" style="border-right: 1px solid #000">
                       
                            <?php   $count = 1; ?>
                            @foreach($email_template_imgs as $email_template_img)       
                                <div class="col-md-12" style="position: relative; margin-bottom: 20px">           
                                    @if ($email_template_img->file_name)
                                    <span><button class="btn btn-xs btn-danger imgValue" id="deleteImg{{$count}}" data-id="{{$count}}" value="{{$email_template_img->id}}" style="position:absolute; bottom:0; right: -10px; z-index: 100"><i class="fa fa-trash"></i></button></span>    
                                    <img id="uploaded_img" style="max-height: 500px; width: 100%; max-width: 500px; object-fit: contain;" src="{{URL::to('email_template_img/img').'/'.$email_template_img->file_name}}"> 
    
                                    @else
                                    
                                    @endif     
                                </div>            
                                <?php   $count++; ?>                        
                            @endforeach
                        
                        <label class="add_email_header" for="">Add New Images</label>
                        <input type="file" name="mail_img[]" id="mail_img" multiple>
                        <div class="gallery" style="margin-bottom:5px; margin-top:15px"></div>
                        <a class="btn btn-xs btn-danger" style="display:none; margin-left:10px" id="removeImageHeader" href="#"><i class="fa fa-remove"></i></a>                 
                    </div>

                    <div class="col-lg-6">
                        <label> Choose Store Logo</label>
                        <select required selected data-placeholder="Please select concept" id="store_logo" name="store_logo" class="form-select select2" style="width:100%;" required>
                            @foreach($store_logos as $store_logo)
                                <option value=""></option>
                                <option value="{{ $store_logo->id }}">{{ $store_logo->name }}</option>
                            @endforeach
                        </select>
                        <div class="col-md-6">
                            <div id="btb" style="margin-bottom:5px; margin-top:15px; text-align:center; display:none">
                                <img src="{{URL::to('store_logo/img/for-test-btb.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10"><br>
                                <img src="{{URL::to('store_logo/img/beyond_the_box_terms_and_conditions.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">                    
                                <img src="{{URL::to('store_logo/img/beyond_the_box_claiming.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                            </div>
                            <div id="dw" style="margin-bottom:5px; margin-top:15px; text-align:center; display:none">
                                <img src="{{URL::to('store_logo/img/for-test-dw.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                                <img src="{{URL::to('store_logo/img/digital_walker_terms_and_conditions.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">         
                                <img src="{{URL::to('store_logo/img/digital_walker_claiming.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                            </div>
                            <div id="btb_dw" style="margin-bottom:5px; margin-top:15px; text-align:center; display:none">
                                <img src="{{URL::to('store_logo/img/for-test-btb-dw.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                                <img src="{{URL::to('store_logo/img/os_terms_and_conditions.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">            
                                <img src="{{URL::to('store_logo/img/beyond_the_box_claiming.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                            </div>
                            <div id="open_source" style="margin-bottom:5px; margin-top:15px; text-align:center; display:none">
                                <img src="{{URL::to('store_logo/img/for-test-os.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                                <img src="{{URL::to('store_logo/img/os_terms_and_conditions.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">                          
                                <img src="{{URL::to('store_logo/img/os_claiming.jpg')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                            </div>
                            <div id="iphone" style="margin-bottom:5px; margin-top:15px; text-align:center; display:none">
                                <img src="{{URL::to('store_logo/img/Iphone-lunch-qr.png')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                                <img src="{{URL::to('store_logo/img/iphone-terms-conditions.png')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                                <img src="{{URL::to('store_logo/img/iphone-claim.png')}}" style="max-width: 500px; max-width: 500px; object-fit: contain;" class="btb" hspace="10">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
                <input class='btn btn-success pull-right' id='create_email' value='Edit Email Template' name="selected_button" style="margin-left: 10px; width: 160px;" readonly/>
                <input class='btn btn-primary pull-right' id='testing' value='Send Test Email' name="selected_button" readonly/>
                <button class="hide" id="hidden-submit" type="submit">submit</button>
            </div>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#store_logo').select2();
        let clicked_btn;
        $('#email-test').summernote();
        $('#email-sample').summernote();
        const note_editable_check_text = $('.note-editable').eq(0).text();
        $('.note-editable').eq(1).attr('contenteditable', 'false');
        
        if (/\w/gi.test(note_editable_check_text)) {
            $('.note-editable').eq(0).css('height', '100%');
        }else{
            $('.note-editable').eq(0).css('height', '300px');
        }

         //preview image before save
         $(function() {
            // Multiple images preview in browser
            var imagesPreview = function(input, placeToInsertImagePreview) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            $($.parseHTML('<img height="120px" class="header_images" width="180px;" hspace="10" data-action="zoom">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }
            };

            $('#mail_img').on('change', function() {
                imagesPreview(this, 'div.gallery');
                $('.header_images').remove();
                $("#removeImageHeader").toggle(); 
            });
        });
        
        //remove image header from preview
        $("#removeImageHeader").click(function(e) {
            e.preventDefault(); // prevent default action of link
            $('.header_images').attr('src', ""); //clear image src
            $("#mail_img").val(""); // clear image input value
            $('.header_images').remove();
            $("#removeImageHeader").toggle();
        });

        $('#create_email').click(function(event){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#test_email').removeAttr('required');
                    $('#testing').removeAttr('name');
                    clicked_btn = null;
                    $('#hidden-submit').click();
                }
            })
        })

        $('#testing').click(function(event){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Send test email!',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#test_email').attr('required', true);
                    clicked_btn = 'test_email';
                    $('#hidden-submit').click();
                }
            })      
        })

        //delete img
 
        $('.imgValue').click(function(event){
            event.preventDefault()
            var id =  $(this).attr("data-id");
            var id_value =  $("#deleteImg"+id).val();
            console.log(id_value);
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete-images') }}",
                        dataType: 'json',
                        type: 'POST',
                        data: {
                            id: id_value
                        },
                        success: function(response){
                            if(response.status == 'success'){
                                Swal.fire({
                                    type: response.status,
                                    title: response.message,
                                    icon: 'success'
                                }).then(function(){
                                    location.reload();
                                });
                            }else{
                                Swal.fire({
                                    type: 'error',
                                    title: 'Something went wrong. Please contact the administrator!',
                                    icon: 'error'
                                }).then(function(){
                                    location.reload();
                                });
                            }
                        },
                        error: function(error){
                            console.log(error);
                            Swal.fire(
                                'Error Occured!',
                                'Make sure the input data are correct.',
                                'error'
                            )
                        }
                
                    })
                }
            })      
        })
       
        
        $('form').on('submit', function(event){
            if(clicked_btn == 'test_email'){

                $('.sk-chase-position').show();
                event.preventDefault();
                const email_template_id = $('#email_template_id').val();
                const subject_of_the_email = $("input[name='subject_of_the_email']").val();
                const store_logo = $("#store_logo").val();
                const email_content = $("textarea[name='email_content']").val();
                //const mail_img = $("input[name='mail_img']")[0].files[0];
                const email_content_val = $('#email_option').val();
                
                const formData = new FormData();
                formData.append('email_send_type', 'update');
                formData.append('email_template_id', email_template_id);
                formData.append('subject_of_the_email', subject_of_the_email);
                //formData.append('test_email', test_email);
                formData.append('email_content', email_content);
                    const totalImages = $("input[name='mail_img[]']")[0].files.length;
                    let images = $("input[name='mail_img[]']")[0];
                    for (let i = 0; i < totalImages; i++) {
                        formData.append('mail_img[]', images.files[i]);
                    }
                //formData.append('mail_img', mail_img || '');
                formData.append('email_option', email_content_val);
                formData.append('qr_creation_id', '{{ $row->id }}');
                formData.append('store_logo_id', store_logo);
                $.ajax({
                    url: "{{ route('send-email-testing') }}",
                    dataType: 'json',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(response){
                        console.log(response);
                        var timestamp = new Date().getTime();

                        $('.sk-chase-position').hide();
                        
                        Swal.fire(
                            'Good job!',
                            'Email successfully sent.',
                            'success'
                        )
                    
                        if(response.email_img){
                            $('#uploaded_img').attr('src', '{{ asset('uploaded_item/email_img/') }}' + '/' + response.email_img+ '?' + timestamp);
                        }
                    },
                    error: function(error){
                        console.log(error);
                        //$('.sk-chase-position').hide();

                        Swal.fire(
                            'Error Occured!',
                            'Make sure the input data are correct.',
                            'error'
                        )
                    }
                
                })
            }
        })

        // $('#email_option').on('change',function(){
        //     const email_content_val = $(this).val();

        //     if(email_content_val == 1){
        //         $('.show_email').show();
        //         $('.mail_img_content').hide();
        //         $("input[name='mail_img']").removeAttr('required');
        //     }else{
        //         $('.show_email').hide();
        //         $('.mail_img_content').show();
        //         $('.note-editable').eq(0).text('');
        //         $("input[name='mail_img']").attr('required');
        //     }
        // });

        if("{{ $row->html_email_img }}"){
            $('.mail_img_content').show();
        }else if("{{ $row->html_email }}"){
            $('.show_email').show();
            $("input[name='mail_img']").removeAttr('required');
        }

        $('#store_logo').change(function () {
            if(this.value == 1){
                $('#dw').show();
                $('#btb').hide();
                $('#btb_dw').hide();
                $('#open_source').hide();
                $('#iphone').hide();
            }else if(this.value == 2){
                $('#dw').hide();
                $('#btb').show();
                $('#btb_dw').hide();
                $('#open_source').hide();
                $('#iphone').hide();
            }else if(this.value == 3){
                $('#dw').hide();
                $('#btb').hide();
                $('#btb_dw').show();
                $('#open_source').hide();
                $('#iphone').hide();
            }else if(this.value == 4){
                $('#dw').hide();
                $('#btb').hide();
                $('#btb_dw').hide();
                $('#open_source').show();
                $('#iphone').hide();
            }else if(this.value == 5){
                $('#dw').hide();
                $('#btb').hide();
                $('#btb_dw').hide();
                $('#open_source').hide();
                $('#iphone').show();
            }
        });
    });


    </script>
@endsection

@push('bottom')
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
@endpush