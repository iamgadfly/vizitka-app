<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\RecordIsAlreadyExistsException;
use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use App\Helpers\TimeHelper;
use App\Repositories\WorkSchedule\SingleWorkScheduleRepository;
use App\Repositories\WorkSchedule\WorkScheduleDayRepository;
use Carbon\Carbon;


class SingleWorkScheduleService
{
    public function __construct(
        protected SingleWorkScheduleRepository $repository,
        protected AppointmentService $appointmentService
    ) {}

    /**
     * @throws BaseException
     */
    public function create(array $data): bool|int
    {
        $saveFlag = $data['save'];
        $isBreak = $data['is_break'];
        if (is_null($saveFlag) && !$isBreak) {
            $count = $this->appointmentService->getAppointmentsInInterval([
                'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
                'start' => $data['weekend']['start']['value'],
                'end' => $data['weekend']['end']['value']
            ]);
            if ($count > 0) {
                return $count;
            }
        }
        if (!$saveFlag && !$isBreak) {
            $this->appointmentService->deleteAppointmentsBetweenTwoDates([
                'specialist_id' => AuthHelper::getSpecialistIdFromAuth(),
                'start' => $data['weekend']['start']['value'],
                'end' => $data['weekend']['end']['value']
            ]);
        }
        if ($data['is_break']) {
            return $this->createBreak($data['break']);
        }
        $dates = TimeHelper::getDateInterval(
            $data['weekend']['start']['value'],
            $data['weekend']['end']['value']
        );
        foreach ($dates as $date) {
            $weekday = str(Carbon::parse($date)->shortEnglishDayOfWeek)->lower();
            $weekend = [
                'day_id' => WorkScheduleDayRepository::getDayFromString($weekday)->id
                    ?? WorkScheduleDayRepository::getDayIndexFromDate($date)->id,
                'date' => $date,
                'start' => null,
                'end' => null,
                'is_break' => false
            ];
            $record = $this->repository->whereGet($weekend);
            if ($record->isNotEmpty()) {
                throw new RecordIsAlreadyExistsException;
            }
            $this->repository->create($weekend);
        }
        return true;
    }

    /**
     * @throws SpecialistNotFoundException
     */
    public function createWorkday(array $data): bool
    {
        foreach ($data['dates'] as $date) {
            $weekday = str(Carbon::parse($date)->shortEnglishDayOfWeek)->lower();
            $dayId = WorkScheduleDayRepository::getDayFromString($weekday)->id
                ?? WorkScheduleDayRepository::getDayIndexFromDate($date)->id;

            $record = $this->repository->whereGet([
                'date' => $date,
                'day_id' => $dayId
            ])->map
                ->only(['id'])
                ->flatten()
                ->toArray();

            $this->repository->massDelete($record);

            $recordWorkday = [
                'day_id' => $dayId,
                'date' => $date,
                'start' => $data['workTime']['start'],
                'end' => $data['workTime']['end'],
                'is_break' => false
            ];
            $this->repository->create($recordWorkday);
            foreach ($data['breaks'] as $break) {
                $recordBreak = [
                    'day_id' => $dayId,
                    'date' => $date,
                    'start' => $break['start'],
                    'end' => $break['end'],
                    'is_break' => true
                ];
                $this->repository->create($recordBreak);
            }
        }
        return true;
    }

    /**
     * @param array $data
     * @return bool
     * @throws RecordIsAlreadyExistsException
     * @throws SpecialistNotFoundException
     * @throws BaseException
     */
    public function createBreak(array $data): bool
    {
        $data['date'] = $data['date']['value'];
        $weekday = str(Carbon::parse($data['date'])->shortEnglishDayOfWeek)->lower();
        $appo = $this->appointmentService->getAllByDay($data['date'])->appointments;

        $data['is_break'] = true;
        $data['day_id'] = WorkScheduleDayRepository::getDayFromString($weekday)?->id
            ?? WorkScheduleDayRepository::getDayIndexFromDate($data['date'])?->id;

//        foreach ($appo as $item) {
//            if ($item['status'] != 'break') {
//                if (
//                    !(
//                        (Carbon::parse($data['time']['start'])->format('H:i') < Carbon::parse($item['time']['start'])->format('H:i') &&
//                            Carbon::parse($data['time']['end'])->format('H:i') < Carbon::parse($item['time']['start'])->format('H:i')) ||
//                        Carbon::parse($data['time']['start'])->format('H:i') > Carbon::parse($item['time']['end'])->format('H:i')
//                    ) ||
//                    !(
//                        (Carbon::parse($data['time']['start'])->format('H:i') > Carbon::parse($item['time']['end'])->format('H:i') &&
//                            Carbon::parse($data['time']['end'])->format('H:i') > Carbon::parse($item['time']['end'])->format('H:i')) ||
//                        Carbon::parse($data['time']['start'])->format('H:i') < Carbon::parse($item['time']['start'])->format('H:i')
//                    )
//                ) {
//                    throw new BaseException('Есть запись в данный период времени', 422);
//                }
//            }
//            if ($item['status'] == 'break') {
//                if (
//                    !(
//                        (Carbon::parse($data['time']['start'])->format('H:i') < Carbon::parse($item['interval'][0])->format('H:i') &&
//                            Carbon::parse($data['time']['end'])->format('H:i') < Carbon::parse($item['interval'][0])->format('H:i')) ||
//                        Carbon::parse($data['time']['start'])->format('H:i') > Carbon::parse(end($item['interval']))->format('H:i')
//                    ) ||
//                    !(
//                        (Carbon::parse($data['time']['start'])->format('H:i') > Carbon::parse(end($item['interval']))->format('H:i') &&
//                            Carbon::parse($data['time']['end'])->format('H:i') > Carbon::parse(end($item['interval']))->format('H:i')) ||
//                        Carbon::parse($data['time']['start'])->format('H:i') < Carbon::parse($item['interval'][0])->format('H:i')
//                    )
//                ) {
//                    throw new BaseException('Есть запись в данный период времени', 422);
//                }
//            }
//        }
        //TODO KOLYA
        if (isset($data['time']['start']) && isset($data['time']['end']) ) {
            $data['start'] = $data['time']['start'];
            $data['end'] = $data['time']['end'];
        }
        $records = $this->repository->whereGet([
            'day_id' => $data['day_id'],
            'start' => $data['start'],
            'end' => $data['end'],
            'is_break' => true
        ]);

        if ($records->isNotEmpty()) {
            throw new RecordIsAlreadyExistsException;
        }
        $this->repository->create($data);
        return true;
    }

    public function delete(int $id)
    {
        return $this->repository->deleteById($id);
    }
}
