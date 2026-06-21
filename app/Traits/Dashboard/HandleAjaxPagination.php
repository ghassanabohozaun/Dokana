<?php

namespace App\Traits\Dashboard;

use Illuminate\Pagination\Paginator;

trait HandleAjaxPagination
{
    /**
     * Handle empty page after deletion for AJAX requests.
     * This ensures that if a page becomes empty (e.g. after deleting the last item),
     * the system automatically reverts to the last available page.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function applyAjaxPagination($request, $query, $perPage = null)
    {
        $perPage = $perPage ?: config('app.pagination');
        $paginated = $query->paginate($perPage);

        // If AJAX request, page is empty, there is at least one page, and we are beyond the last page
        if ($request->ajax() && $paginated->isEmpty() && $paginated->lastPage() > 0 && $request->get('page') > $paginated->lastPage()) {
            
            // Re-run the pagination with the last page explicitly set
            $paginated = $query->paginate($perPage, ['*'], 'page', $paginated->lastPage());
        }

        return $paginated;
    }
}
