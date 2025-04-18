<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\WeatherLog;
use App\Events\WeatherUpdated;
use Carbon\Carbon;

class WeatherService
{
    public function fetchWeatherForUser(User $user): ?array
    {
        try {
            file_put_contents('log.txt', print_r("fetchWeatherForUser for user {$user->id}", true), FILE_APPEND);
            $response = Http::timeout(5)->get(env('WEATHER_SERVICE_ENDPOINT'), [
                'lat'  => $user->latitude,
                'lon'  => $user->longitude,
                'appid'=> env('WEATHER_SERVICE_APPID'),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function storeWeather(User $user, array $weatherData): WeatherLog
    {
        $main   = $weatherData['weather'][0]['main'] ?? '';
        $desc   = $weatherData['weather'][0]['description'] ?? '';
        $icon   = $weatherData['weather'][0]['icon'] ?? '';
        $temp   = $weatherData['main']['temp'] ?? 0;
        $pressure = $weatherData['main']['pressure'] ?? 0;
        $humidity = $weatherData['main']['humidity'] ?? 0;
        $visibility = $weatherData['visibility'] ?? 0;
        $wind_speed = $weatherData['wind']['speed'] ?? 0;
        $wind_deg = $weatherData['wind']['deg'] ?? 0;
        $cloudiness = $weatherData['clouds']['all'] ?? 0;
        $datetime = isset($weatherData['dt']) ? Carbon::createFromTimestamp($weatherData['dt']) : null; 
        $country  = $weatherData['sys']['country'] ?? '';
        $city     = $weatherData['name'] ?? '';
        $sunrise  = isset($weatherData['sys']['sunrise']) ? Carbon::createFromTimestamp($weatherData['sys']['sunrise']) : null;
        $sunset   = isset($weatherData['sys']['sunset']) ? Carbon::createFromTimestamp($weatherData['sys']['sunset']) : null;

        $newWeatherLog = WeatherLog::create(
            [
                'user_id'     => $user->id,
                'main'        => $main,
                'icon'        => $icon,
                'description' => $desc,
                'temp'        => $temp,
                'pressure'    => $pressure,
                'humidity'    => $humidity,
                'visibility'  => $visibility,
                'wind_speed'  => $wind_speed,
                'wind_deg'    => $wind_deg,
                'cloudiness'  => $cloudiness,
                'datetime'    => $datetime,
                'country'     => $country,
                'city'        => $city,
                'sunrise'     => $sunrise,
                'sunset'      => $sunset,
            ]
        );

        event(new WeatherUpdated($user->id, $newWeatherLog));

        return $newWeatherLog;
    }
}
