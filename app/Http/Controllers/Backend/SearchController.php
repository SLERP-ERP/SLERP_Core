<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class SearchController
 * @package App\Http\Controllers\Backend\Search
 */
class SearchController extends Controller
{

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if (!$request->has('q'))
            return redirect()
                ->route('admin.dashboard')
                ->withFlashDanger(trans('strings.backend.search.empty'));

        /**
         * Process Search Results Here
         */
        $results = null;

        return view('backend.search.index')
            ->withSearchTerm($request->get('q'))
            ->withResults($results);
    }


    public function logViewerAll($date)
    {
        return redirect()->route('admin.log-viewer::logs.show', [$date]);
    }
}