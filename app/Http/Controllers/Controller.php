<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Base Api",
 *      description="API do projeto Base",
 *      @OA\Contact(
 *          email="email@test.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * ),
 *
 * @OA\Server(
 *      url="http://localhost:8000",
 *      description="Base API"
 * ),
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     scheme="bearer",
 *     securityScheme="apiAuth",
 *     bearerFormat="JWT",
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="/api/auth/auth",
 *         scopes={}
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
