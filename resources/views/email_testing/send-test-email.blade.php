<!DOCTYPE html>
<html>
<head>
    <!-- Add the html2canvas library -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <style>
        /* Inline CSS styles */
        html{
            width: 100%;
        }

        body {
            /* font-family: Arial, sans-serif; */
            color: #333;
            
        }
    </style>
</head>
<body> 

    <div class="container1">
        {!! html_entity_decode($html_email) !!}
    </div>
    <table>
        <tr>
            <td align="center">
                <div class="container">
                    @if($store_logo_id == 1)
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/for-test-dw.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/digital_walker_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/digital_walker_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @elseif($store_logo_id == 2)
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/for-test-btb.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @elseif($store_logo_id == 3)
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/for-test-btb-dw.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/os_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/beyond_the_box_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @elseif($store_logo_id == 6)
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/Iphone-lunch-qr.png') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/iphone-terms-conditions.png') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/iphone-claim.png') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @else
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/for-test-os.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/os_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        <img src="{{ $message->embed(public_path() . '/store_logo/img/os_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @endif
                    @if($email_send_type === 'update')
                        @foreach ($existing_filename as $existFile)
                            <img src="{{ $message->embed(public_path() . '/email_template_img/img/' . $existFile) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                        @endforeach
                    @endif
                    @foreach ($html_email_img as $img)
                        <img src="{{ $message->embed(public_path() . '/uploaded_item/send_test_images/' . $img) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center; margin-top: 5px;" />
                    @endforeach
                </div>
            </td>
        </tr>
    </table>

    <script>
        $(document).ready(function() {

            $('#download').on('click', function() {
                var divToConvert = $('#download_qr');
                
                html2canvas(divToConvert[0]).then(function(canvas) {
                    var imageDataURL = canvas.toDataURL('image/png');
                    
                    // Create a temporary link element
                    var link = document.createElement('a');
                    link.href = imageDataURL;
                    link.download = 'qr_image.png'; // Set the desired file name
                    
                    // Trigger a click event on the link to start the download
                    link.click();
                    
                });
            });
        });  

    </script>


</body>
</html>
