<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'users_addresses';
    protected $fillable = [
        'users_uuid',
        'street',
        'number',
        'zip',
        'city',
        'country',
        'latitude',
        'longitude',
        'region',
    ];

    public function getLatAndLon($street, $number, $zip, $city, $country)
    {
        $client = new Client();

        $response = $client->request('GET', 'https://nominatim.openstreetmap.org/search', [
            'query' => [
                'q' => $street . ', ' . $number . ', ' . $zip . ', ' . $city . ', ' . $country,
                'format' => 'json'
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}