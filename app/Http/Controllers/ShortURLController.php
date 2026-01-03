<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessUrlVisit;
use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ShortURLController extends Controller
{
    /**
     * Redirect the user to the intended destination URL. If the default
     * route has been disabled in the config but the controller has
     * been reached using that route, return HTTP 404.
     */
    public function __invoke(Request $request, string $shortURLKey): RedirectResponse
    {

        // Get URL from cache ONLY - no database query
        $shortURL = Url::findByKey($shortURLKey);

        // If not in cache or not found, return 404
        if (! $shortURL) {
            abort(404);
        }

        // Validate if URL is still active
        if (! $shortURL->isActive()) {
            $shortURL->forgetCache();
            abort(404);
        }

        // Handle visit tracking asynchronously
        ProcessUrlVisit::dispatch(
            ip: $request->ip(),
            userAgent: $request->userAgent(),
            referer: $request->headers->get('referer'),
            headers: $request->headers->all(),
            url: $shortURL
        );

        // Forword query param if provided
        if ($shortURL->forward_query_params) {
            return Redirect::away(path: $this->forwardQueryParams($request, $shortURL), status: $shortURL->redirect_status_code);
        }

        return Redirect::away(path: $shortURL->destination_url, status: $shortURL->redirect_status_code);
    }

    /**
     * Add the query parameters from the request to the end of the
     * destination URL that the user is to be forwarded to.
     */
    private function forwardQueryParams(Request $request, Url $shortURL): string
    {
        $queryString = parse_url($shortURL->destination_url, PHP_URL_QUERY);

        if (empty($request->query())) {
            return $shortURL->destination_url;
        }

        $separator = $queryString ? '&' : '?';

        return $shortURL->destination_url.$separator.http_build_query($request->query());
    }
}
