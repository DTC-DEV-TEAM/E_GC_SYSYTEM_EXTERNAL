<?php

namespace App\Http\Controllers;

use App\EmailTesting;
use App\GCList;
use App\Mail\QrEmail;
use App\Models\EmailTemplateImg;
use App\QrCreation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ManualSendCampaignController extends Controller
{
    public function sendCampaignData($id){
        $toSendEmails = GCList::where('email_is_sent', 0)
            ->where('id', $id)
            ->get();

        Log::info(json_encode($toSendEmails));

        foreach ($toSendEmails as $gcList) {
            $generated_qr_info = QrCreation::find($gcList->campaign_id);
                $id = $gcList->id;
                $name = $gcList->name;
                $email = $gcList->email;
                $generated_qr_code = $gcList->qr_reference_number;
                $campaign_id_qr = $generated_qr_info->campaign_id;
                $gc_description = $generated_qr_info->gc_description;
                $gc_value = $generated_qr_info->gc_value;
                $email_template_id = $gcList->email_template_id;

                $email_template = $generated_qr_info->html_email;
                $email_subject = $generated_qr_info->subject_of_the_email;

                $url = "/g_c_lists/edit/$id?value=$generated_qr_code";
                $qrCodeApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
                $qr_code = "<div id='qr-code-download'><div id='download_qr'><a href='$qrCodeApiUrl' download='qr_code.png'> <img src='$qrCodeApiUrl' alt='QR Code'> </a></div></div>";

                $emailTesting = EmailTesting::where('id', $gcList->email_template_id)->first();
                $emailTestingImg = EmailTemplateImg::where('header_id', $emailTesting->id)->get();

                $subject_of_the_email = $emailTesting->subject_of_the_email;
                $email_content = $emailTesting->html_email;

                $html_email_img = [];

                foreach($emailTestingImg as $file){
                    $filename = $file->file_name;
                    $html_email_img[]= $filename;
                }

                $html_email = str_replace(
                    ['[name]', '[campaign_id]', '[gc_description]'],
                    [$name, $campaign_id_qr, $gc_description],
                    $email_content
                );

                $data = array(
                    'id' => $id,
                    'html_email' => $html_email,
                    'email_subject' => $subject_of_the_email,
                    'html_email_img' => $html_email_img,
                    'email' => $email,
                    'qrCodeApiUrl' => $qrCodeApiUrl,
                    'qr_code' => $qr_code,
                    'gc_value' => $gc_value,
                    'store_logo' => $generated_qr_info->store_logo,
                    'gc_description' => $gc_description,
                    'qr_reference_number'=>$generated_qr_code,
                    'campaign_id_qr' => $campaign_id_qr,
                    // 'qr_img' => $qr_img
                );



                try{
                    $path = (new AdminQrCreationsController)->manipulate_image($data['gc_value'], $data['qrCodeApiUrl'], $data['store_logo']);

                    $data['qr_code_generated'] = $path;

                    $email = new QrEmail($data);
                    Log::debug($data);
                    Log::debug($email);
                    Mail::to($data['email'])->send($email);

                }catch(Exception $e){
                    Log::error($e->getMessage());
                }
        }

        GCList::where('id', $id)
            ->update([
                'email_is_sent' => 1
            ]);
        return response()->json([
            'status' => 'success',
            'message' => "Email Sent!"
        ], 200);
    }
}
