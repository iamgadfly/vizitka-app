<?php

namespace App\Http\Resources\WorkScheduleSettings;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkTimeResource extends JsonResource
{
    protected string $start;
    protected string $end;
    public function __construct($start, $end)
    {
        parent::__construct($start, $end);
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'start' => Carbon::parse($this->start)->format('H:i'),
            'end' => Carbon::parse($this->end)->format('H:i')
        ];
    }
}
