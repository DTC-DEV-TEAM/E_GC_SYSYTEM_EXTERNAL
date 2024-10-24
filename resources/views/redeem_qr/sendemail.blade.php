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
                <div style="width: 100%; text-align:center;">
                    <img src="{{ $message->embed(public_path() . "/$qr_code_generated") }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" />
                    {{-- <img src="{{ $message->embed(public_path() . '/store_logo/img/btb_and_dw.png') }}" width="800" style="height: 300px; min-width: 800px; object-fit: contain; text-align: center; pointer-events: none;" /> --}}
                </div>
            </td>
        </tr>
        @if ($store_logo == 1)
        <tr align="center">
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'digital_walker_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'digital_walker_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
        </tr>
        @endif
        @if ($store_logo == 2)
        <tr align="center">
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'beyond_the_box_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'beyond_the_box_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
        </tr>
        @endif
        @if ($store_logo == 3)
        <tr align="center">
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'beyond_the_box_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'beyond_the_box_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
        </tr>
        @endif
        @if ($store_logo == 4)
        <tr align="center">
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'os_terms_and_conditions.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'os_claiming.jpg') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
        </tr>
        @endif
        @if ($store_logo == 6)
        <tr align="center">
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'iphone-terms-conditions.png') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
            <div class="container">
                <img src="{{ $message->embed(public_path() . '/store_logo/img/' . 'iphone-claim.png') }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
            </div>
        </tr>
        @endif
        <tr>
            <td align="center">
                <div class="container">
                    @foreach ($html_email_img as $img)
                        <img src="{{ $message->embed(public_path() . '/email_template_img/img/' . $img) }}" width="800" style="max-height: 800px; min-width: 800px; object-fit: contain; text-align: center;" />
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
