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

        .card_note{
            font-style: italic;
            color: red;
        }

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
        <div class='panel-heading'>Add Form</div>
        <form method='post' action='{{CRUDBooster::mainpath('add-save')}}' autocomplete="off" enctype='multipart/form-data'>
        @csrf
        <div class='panel-body'>
                <br>
                <div class="row">
                    <div class="col-md-6" style="border-right: 1px solid #000">
                        <div class="form-group">
                            <label class="add_email_header"> <span></span> Template Name</label>
                            <input type="text" class="form-control" name="title_of_the_email" value="{{ $row->title_of_the_email }}" required>
                        </div>

                        <div class="form-group">
                            <label class="add_email_header" for="">Subject</label>
                            <input type="text" class="form-control" name="subject_of_the_email" value="{{ $row->subject_of_the_email }}" required>
                        </div>

                        <div class="show_email">
                            <div class="form-group">
                                <label class="add_email_header" for="">Email Content</label>
                                <textarea id="email-test" name="email_content">
                                    {{ $row->html_email }}
                                </textarea>
                            </div>  
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="add_email_header" for="">Sample Email Content</label>
                            <textarea id="email-sample">
                                <h4><b>QR Code Redemption</b></h4><p>Dear [name],</p><p>We are pleased to provide you with your exclusive QR code for redemption. This QR code represents exciting offers and discounts that you can avail yourself of at our selected stores. Simply present this QR code at the designated store to claim your special offers.</p><p>Campaign ID: [campaign_id]</p><p>GC Description: [gc_description]</p><p>We kindly ask you to bring a valid government ID when redeeming your code. This is necessary for identification purposes and to ensure a smooth and secure process. We also request that you review the terms and conditions that apply to the code redemption process. You can find the detailed terms and conditions by visiting the following link: [Insert link to terms and conditions]<p></p><p>If you have any questions or need assistance, please don't hesitate to contact our customer support team. We are here to ensure a smooth and rewarding redemption experience for you.</p><p>Thank you for choosing our services. We appreciate your business and look forward to serving you again in the future.</p><p><br></p><p>Best regards,</p><p>BPG Department</p><p>Digits Trading Corp</p>
                            </textarea>
                        </div>
                    </div>
                </div>

     

                <div class="row">
                    <div class="col-lg-6 hide">
                        <div class="form-group">
                            <label class="add_email_header" for="">Gmail Preview</label>
                            <img src="{{ asset('img/sample_email1.PNG') }}" alt="" style="width: 100%; object-fit: contain; border: 1px solid rgba(0,0,0,.1);">
                        </div>
                    </div>
                    <hr>
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
                    <div class="col-lg-6 mail_img_content">
                        <label class="add_email_header" for="">Attach Image. <span class="card_note">(Note: The Gift Card must be selected first or in first row)</span></label>
                        <input type="file" name="mail_img[]" id="mail_img" multiple="multiple" required>
                        <div class="col-md-6">
                            <div class="gallery" style="margin-bottom:5px; margin-top:15px; text-align:center"></div>
                            <a class="btn btn-xs btn-danger" style="display:none; margin-left:10px" id="removeImageHeader" href="#"><i class="fa fa-remove"></i></a>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">Cancel</a>
                <input class='btn btn-success pull-right' id='create_email' value='Create Email Template' name="selected_button" style="margin-left: 10px;" readonly/>
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
                            $($.parseHTML('<img style="max-width: 500px; max-width: 500px; object-fit: contain;" class="header_images" hspace="10" data-action="zoom">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
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
            event.preventDefault()
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!',
                returnFocus: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#test_email').removeAttr('required');
                    $('#testing').removeAttr('name');
                    clicked_btn = null;
                    $('#hidden-submit').click();
                }
            })
        });

        $('#testing').click(function(event){
            //alert('test');
            Swal.fire({
                title: 'Are you sure?',
                // text: "You won't be able to revert this!",
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
        
        $('form').on('submit', function(event){
            if(clicked_btn == 'test_email'){

                $('.sk-chase-position').show();
                event.preventDefault();

                const subject_of_the_email = $("input[name='subject_of_the_email']").val();
                const store_logo = $("#store_logo").val();
                const email_content = $("textarea[name='email_content']").val();
                //const mail_img = $("input[name='mail_img']")[0].files[0];
                const email_content_val = $('#email_option').val();
                
                const formData = new FormData();
                formData.append('email_send_type', 'create');
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
                $('#open_sourse').hide();
		        $('#iphone').show();
            }

        });
    });




    </script>
@endsection

@push('bottom')
    <script type="text/javascript" src="{{asset('vendor/crudbooster/assets/summernote/summernote.min.js')}}"></script>
@endpush