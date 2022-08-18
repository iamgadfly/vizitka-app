<?php

namespace App\Services;


use App\Exceptions\SpecialistNotFoundException;
use App\Models\Report;
use App\Repositories\ClientRepository;
use App\Repositories\SpecialistRepository;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class MailService
{
    public function __construct(
        protected SpecialistRepository $specialistRepository,
        protected ClientRepository $clientRepository
    ){}

    /**
     * @throws SpecialistNotFoundException
     */
    public function sendReportMail(array $data): bool
    {
        $specialist = $this->specialistRepository->findById($data['id']);
        $report = collect();
        $report->phoneNumber = $specialist->user->phone_number;
        $report->reason = __('users.reports.' . $data['reason']);
        //TODO: Update when I get new mail service
//        \Mail::to(config('custom.report_mail'))->send(new ReportMail($report));
        $this->sendMessage($report);
        return true;
    }

    public function sendMailToSupportAsSpecialist(array $data, UploadedFile $file)
    {
        $specialist = $this->specialistRepository->findById($data['id']);
        $mail = collect();
        $mail->text = $data['text'];
        $mail->fullName = $specialist->name . " " . $specialist?->surname;
        $mail->phoneNumber = $specialist->user->phone_number;
        $mail->email = $data['email'];
        $mail->file = $file;
        //TODO: Update when I get new mail service
//        \Mail::to(config('custom.support_mail'))->send(new SupportMail($mail));

        return true;
    }

    public function sendMailToSupportAsClient(array $data, UploadedFile $file)
    {
        $client = $this->clientRepository->whereFirst(['id' => $data['id']]);
        $mail = collect();
        $mail->text = $data['text'];
        $mail->fullName = $client->name . " " . $client?->surname;
        $mail->phoneNumber = $client->user->phone_number;
        $mail->email = $data['email'];
        $mail->file = $file;
        //TODO: Update when I get new mail service
//        \Mail::to(config('custom.support_mail'))->send(new SupportMail($mail));
        return true;
    }

    public function getReportReasons(): array
    {
        $reports = Report::all();
        $output = [];
        foreach ($reports as $report) {
            $output[] = [
                'name' => $report->name,
                'value' => __('users.reports.' . $report->name)
            ];
        }
        return $output;
    }

    private function sendMessage($report)
    {
        $client = new Client();
        $html = view('emails.report', ['report' => $report])->render();

        $res = $client->request('POST', 'http://smtp.mailganer.com/api/v2/mail/send', [
            'headers' => [
                'Authorization' => 'CodeRequest 359fdc947d443f62c0207390d2d268e5'
            ],
            'json' => [
                "email_from" => "Domain <from@domain.com>",
                "email_to" => "oleg.voloshin@softlex.pro",
                "subject" => "Жалоба на специалиста",
                "message_text" => $html,
                "headers" => [
                    "foo1" => "bar1",
                    "foo2" => "bar2"
                ],
                "params" => [
                    "user" => "Вася",
                    "other" => "шмель"
                ]
            ]
        ]);

        dd($res->getBody());
    }
}
