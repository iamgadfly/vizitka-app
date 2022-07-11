<?php

namespace App\Services;


use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Mail\ReportMail;
use App\Mail\SupportMail;
use App\Models\Report;
use App\Repositories\SpecialistRepository;
use Illuminate\Http\UploadedFile;

class MailService
{
    public function __construct(
        protected SpecialistRepository $specialistRepository
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
        \Mail::to(config('custom.report_mail'))->send(new ReportMail($report));

        return true;
    }

    public function sendMailToSupport(array $data, UploadedFile $file)
    {
        $specialist = $this->specialistRepository->findById($data['id']);
        $mail = collect();
        $mail->text = $data['text'];
        $mail->fullName = $specialist->name . " " . $specialist->surname;
        $mail->phoneNumber = $specialist->user->phone_number;
        $mail->file = $file;
        \Mail::to(config('custom.support_mail'))->send(new SupportMail($mail));

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
}
