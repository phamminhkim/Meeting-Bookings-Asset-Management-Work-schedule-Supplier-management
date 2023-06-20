<?php

namespace App\Jobs;

use App\Mail\EmailDocumentRequest;
use App\Mail\EmailDocumentSuccess;
use App\Models\shared\DocumentType;
use App\Models\shared\Timeline;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Ultils\Pdf\ThienLongPDF;
use App\Ultils\Ultils;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoareCostin\FileVault\Facades\FileVault;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class SignPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $document;
    protected $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($document,$url)
    {
        $this->document = $document;
        $this->url = $url;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $document = $this->document;
        $title= "";
        $name= "";
        $index = 1;
        foreach ($document->filesigns as  $filesign) {
            foreach ($filesign->attachment_file as $file) {
                $new_url =   $file->url ;
                $index = $index + 1;
                FileVault::decryptCopy( $file->url.".enc");
                // FileVault::decryptCopy( $file->url.".enc", "/". $new_url);

                $PDF = new ThienLongPDF();

                $PDF->LoadFont("DejaVuSansCondensed", '' ,"DejaVuSansCondensed.ttf");
                $PDF->LoadFont("DejaVuSansCondensed", 'B' ,"DejaVuSansCondensed-Bold.ttf");
                $PDF->LoadFont("DejaVuSansCondensed", 'I' ,"DejaVuSansCondensed-Oblique.ttf");
                $PDF->LoadFont("DejaVuSansCondensed", 'BI' ,"DejaVuSansCondensed-BoldOblique.ttf");
                $PDF->SetDefaultFont("DejaVuSansCondensed");
                $file_path =Storage::disk('local')->path($file->url);
                $outputFile = $file_path;
                $PDF->LoadTemplate($file_path);

                foreach ($file->signs as  $sign) {
                    // $PDF->CreateTextField(1, "SIGN".$sign, $sign->cx, $sign->cy);
                    // $PDF->FillData("SIGN".$sign, "Xin chao");
                    $title = "Đã ký/Duyệt";
                    foreach ($document->approveds as $approved) {
                        if ($approved->user_id == $sign->sign && $approved->online == 'X') {
                            $date =Carbon::parse($approved->checkin);
                            $name = $sign->show_sign ? $approved->user->name : "";
                            $PDF->CreateSignatureField($sign->page, 'SIGN'.$sign->sign, $sign->cx, $sign->cy,$sign->width_c,$sign->height_c);
                            $PDF->FillSignature( 'SIGN'.$sign->sign, $title, $date->format('d/m/Y H:i:s'), public_path().'/img/tick.png', $name);
                            break;
                        }

                        //Người tạo chứng từ
                        if ($sign->sign == $document->user_id) {
                                //$send_date = Carbon::createFromFormat('d/m/Y H:i:s', $document->send_date) ;
                                //$title = "Đã ký";
                                $date = Carbon::parse($document->send_date);
                                $PDF->CreateSignatureField($sign->page, 'SIGN'.$sign->sign, $sign->cx, $sign->cy,$sign->width_c,$sign->height_c);
                                $name = $sign->show_sign ? $document->user->name : "";
                                $PDF->FillSignature( 'SIGN'.$sign->sign, $title, $date->format('d/m/Y H:i:s'), public_path().'/img/tick.png', $name);
                        }

                    }

                    // if ($sign->sign == '0') {
                    //     // $send_date = Carbon::createFromFormat('d/m/Y H:i:s', $document->send_date) ;
                    //     $title = "Đã ký";
                    //     $date = Carbon::parse($document->send_date);
                    //     $PDF->CreateSignatureField($sign->page, 'SIGN'.$sign->sign, $sign->cx, $sign->cy,$sign->width_c,$sign->height_c);
                    //     $name = $sign->show_sign ? $document->user->name : "";
                    //     $PDF->FillSignature( 'SIGN'.$sign->sign, $title, $date->format('d/m/Y H:i:s'), public_path().'/img/tick.png', $name);

                    // }else {
                    //     $title= "Đã duyệt";
                    //     foreach ($document->approveds as $approved) {
                    //         if ($approved->sign == $sign->sign && $approved->online == 'X') {
                    //             $date =Carbon::parse($approved->checkin);
                    //             $name = $sign->show_sign ? $approved->user->name : "";
                    //             $PDF->CreateSignatureField($sign->page, 'SIGN'.$sign->sign, $sign->cx, $sign->cy,$sign->width_c,$sign->height_c);
                    //             $PDF->FillSignature( 'SIGN'.$sign->sign, $title, $date->format('d/m/Y H:i:s'), public_path().'/img/tick.png', $name);
                    //             break;
                    //         }
                    //     }

                    // }

                    $filesign->signed = 'X';
                    $filesign->status = 1;
                    $filesign->save();
                    // $PDF->FillSignature( 'SIGN'.$sign->sign, $title, date("d/m/Y H:i:s", mktime(8, 32, 27, 1, 10, 2022)), public_path().'/img/tick.png', 'Tô Trần Nhật Trường');
                }
                $PDF->RenderPDFData(true);
                $PDF->ExportFileToDisk($outputFile);
                FileVault::encrypt($file->url);
                //Gửi phản hồi thông tin
                $data = new NotiBaseModel;
                $data->title = "File trong " . $document->serial_num . " đã được ký";
                $data->icon = "fas fa-signature";
                $data->url = $this->url;
                $data->content = $file->name;

                $email = new EmailDocumentSuccess($data,$document->user,$document);
                $document->user->notify(new CommondNotification($data,$document->user,$email) );

      }
    }
  }


}
