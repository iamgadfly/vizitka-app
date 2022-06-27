<?php

namespace App\Services;


use App\Exceptions\SpecialistNotFoundException;
use App\Mail\ReportMail;
use App\Models\Report;
use App\Repositories\SpecialistRepository;

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
