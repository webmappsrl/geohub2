<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Wm\WmPackage\Facades\HoquClient;


class StoreApiTest extends TestCase
{
  /**
   * A basic test example.
   *
   * @return void
   */
  public function test_store_to_hoqu_http_call()
  {
    $params = [
      'job_name' => 'AddLocationsToPoint',
      'geometry' => json_encode(['type' => 'Point', 'coordinates' => [1, 2]])
    ];

    $url = config('wm-package.hoqu_url');

    Http::fake([
      $url . '*' => Http::sequence()->pushStatus(200)
    ]);

    HoquClient::store($params);

    Http::assertSentCount(1);

    #https://laravel.com/docs/9.x/http-client#recording-requests-and-responses
    $recorded = Http::recorded(function (Request $request, Response $response) use ($url, $params) {
      return $request->hasHeader('content', 'application/jsno') &&
        $request->url() == $url . '/api/store'  &&
        $request->body() == $params &&
        $response->successful();
    });
  }
}
