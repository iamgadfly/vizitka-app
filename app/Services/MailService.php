<?php

namespace App\Services;


use App\Exceptions\SpecialistNotFoundException;
use App\Models\Report;
use App\Repositories\ClientRepository;
use App\Repositories\SpecialistRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\UploadedFile;

class MailService
{
    public function __construct(
        protected SpecialistRepository $specialistRepository,
        protected ClientRepository $clientRepository,
        protected ImageService $imageService
    ){}

    /**
     * @throws SpecialistNotFoundException
     * @throws GuzzleException
     */
    public function sendReportMail(array $data): bool
    {
        $specialist = $this->specialistRepository->findById($data['id']);
        $report = collect();
        $report->phoneNumber = $specialist->user->phone_number;
        $report->reason = __('users.reports.' . $data['reason']);

        $html = view('emails.report', ['report' => $report])->render();
        $this->sendMessage($html, 'Жалоба на специалиста');
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
        $filePath = $this->imageService->storeImage($file);
        $mail->file = env('APP_URL') . $filePath;

        $html = view('emails.support', ['data' => $mail])->render();
        $this->sendMessage($html, 'Обращение от специалиста');

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

        $filePath = $this->imageService->storeImage($file);
        $mail->file = env('APP_URL') . $filePath;

        $html = view('emails.support', ['data' => $mail])->render();
        $this->sendMessage($html, 'Обращение от клиента');
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

    /**
     * @param $html
     * @param $subject
     * @return void
     * @throws GuzzleException
     */
    private function sendMessage($html, $subject)
    {
        $client = new Client();

        $client->request('POST', 'http://smtp.mailganer.com/api/v2/mail/send', [
            'headers' => [
                'Authorization' => 'CodeRequest MGXiNaI0gxMSo6dXpLVTA+WDx1PlMzIztySDp2VTQkQV4+VD86WHp6PkVmdTdaVF5adUhEOVpKSVZeXlk5PVc='
            ],
            'json' => [
                "email_from" => "VIZITKA<" . config('custom.from_mail') . ">",
                "email_to" => config('custom.from_mail'),
                "subject" => $subject,
                "message_text" => $html
            ]
        ]);
    }
}
