<?php

namespace App\Services;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\CardBackgroundHelper;
use App\Helpers\TimeHelper;
use App\Http\Resources\ActivityKindResource;
use App\Http\Resources\OnboardingResource;
use App\Models\ActivityKind;
use App\Models\Onboarding;
use App\Models\Specialist;
use App\Repositories\SpecialistRepository;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

class MiscService
{
    public function __construct(
        protected SpecialistRepository $specialistRepository
    ){}

    /**
     * @throws GuzzleException
     */
    public function getCountries(): array
    {
        $cache = \Cache::get('countries');

        if (!is_null($cache)) {
            return json_decode($cache, true);
        }

        $client = new Client();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all', [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ]);

        $body = json_decode($response->getBody()->getContents());
        $output = [];
        foreach ($body as $item) {
            $country = [
                'name' => $item->translations->rus->common,
                'flag' => $item->flags->png,
            ];
            if (count((array) $item->idd) > 0) {
                if (count($item->idd->suffixes) > 1) {
                    $country['code'] = $item->idd->root;
                } else {
                    $country['code'] = $item->idd->root . $item->idd->suffixes[0];
                }
            } else {
                continue;
            }

            $output[] = $country;
        }
        \Cache::put('countries', json_encode($output), Carbon::now()->addMonth());

        return $output;
    }

    public function getBackgrounds(): Collection
    {
        return CardBackgroundHelper::getAll();
    }

    public function getOnboardings(): AnonymousResourceCollection
    {
        return OnboardingResource::collection(Onboarding::all());
    }

    public function getActivityKinds(): AnonymousResourceCollection
    {
        return ActivityKindResource::collection(ActivityKind::all());
    }

    public function getWeekDates(string $date): array
    {
        return TimeHelper::getWeekdays($date);
    }

    public function isSpecialistExists(string $phoneNumber): bool
    {
        try {
            $this->specialistRepository->findByPhoneNumber($phoneNumber);
            return true;
        } catch (SpecialistNotFoundException) {
            return false;
        }
    }
}
