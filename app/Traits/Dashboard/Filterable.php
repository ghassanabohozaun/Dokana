<?php

namespace App\Traits\Dashboard;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Generic scope to filter models based on request parameters.
     *
     * @param Builder $query
     * @param array $filters
     * @param array $searchColumns Columns to search for 'keyword'
     * @param array $exactMatches Fields that require exact match (e.g. ['status', 'category_id'])
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filters, array $searchColumns = ['name'], array $exactMatches = [], array $rangeFilters = [])
    {
        // 1. Keyword Search (Support for standard, translatable, and related columns)
        if (!empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function ($q) use ($keyword, $searchColumns) {
                foreach ($searchColumns as $column) {
                    if (str_contains($column, '.')) {
                        [$relation, $subColumn] = explode('.', $column);
                        $q->orWhereHas($relation, function ($rq) use ($keyword, $subColumn) {
                            $relatedModel = $rq->getModel();
                            if (method_exists($relatedModel, 'isTranslatableAttribute') && $relatedModel->isTranslatableAttribute($subColumn)) {
                                $rq->where($subColumn . '->' . app()->getLocale(), 'like', '%' . $keyword . '%');
                            } else {
                                $rq->where($subColumn, 'like', '%' . $keyword . '%');
                            }
                        });
                    } else {
                        if (method_exists($this, 'isTranslatableAttribute') && $this->isTranslatableAttribute($column)) {
                            $q->orWhere($column . '->' . app()->getLocale(), 'like', '%' . $keyword . '%');
                        } else {
                            $q->orWhere($column, 'like', '%' . $keyword . '%');
                        }
                    }
                }
            });
        }

        // 2. Exact Matches (IDs, Status, etc.)
        foreach ($exactMatches as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $query->where($field, $filters[$field]);
            }
        }

        // 3. Range Filters (Min/Max)
        foreach ($rangeFilters as $column => $keys) {
            $minKey = $keys['min'] ?? $column . '_min';
            $maxKey = $keys['max'] ?? $column . '_max';

            if (isset($filters[$minKey]) && $filters[$minKey] !== '') {
                $query->where($column, '>=', $filters[$minKey]);
            }
            if (isset($filters[$maxKey]) && $filters[$maxKey] !== '') {
                $query->where($column, '<=', $filters[$maxKey]);
            }
        }

        // 4. Specific Search Column (like location) if provided in filters but not as keyword
        // This is a special case for when we want a dedicated filter for a column
        foreach ($searchColumns as $column) {
            if (isset($filters[$column]) && $filters[$column] !== '') {
                $val = $filters[$column];
                $query->where(function($q) use ($column, $val) {
                    if (method_exists($this, 'isTranslatableAttribute') && $this->isTranslatableAttribute($column)) {
                        $q->where($column . '->ar', 'like', '%' . $val . '%')
                          ->orWhere($column . '->en', 'like', '%' . $val . '%');
                    } else {
                        $q->where($column, 'like', '%' . $val . '%');
                    }
                });
            }
        }

        return $query;
    }
}
