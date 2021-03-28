<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use App\TokenStore\TokenCache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // <LoadViewDataSnippet>
    public function loadViewData() {
        $viewData = [];

        // Check for flash errors
        if (session('error')) {
            $viewData['error'] = session('error');
            $viewData['errorDetail'] = session('errorDetail');
        }

        // Check for logged on user
        if (session('userName'))
        {
            $viewData['userName'] = session('userName');
            $viewData['userEmail'] = session('userEmail');
            $viewData['userTimeZone'] = session('userTimeZone');
            $viewData['isLogged'] = session('isLogged');
        }

        return $viewData;
    }
    // </LoadViewDataSnippet>

    public function getGraph(): Graph {
        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessTokenCredentials();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }

    public function getGraphAPI($appId, $appPassword, $tenantId): Graph {    
        $config = array(
            'OAUTH_APP_ID' => $appId,
            'OAUTH_APP_PASSWORD' => $appPassword,
            'OAUTH_TENANT_ID' => $tenantId,
        );

        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessTokenAPI($config);

        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }
}
