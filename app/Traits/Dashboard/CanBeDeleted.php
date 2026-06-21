<?php

namespace App\Traits\Dashboard;

use App\Exceptions\DeleteRestrictionException;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property array $restrictiveRelations
 */
trait CanBeDeleted
{
    /**
     * Boot the trait and register the deleting event listener.
     */
    public static function bootCanBeDeleted()
    {
        static::deleting(function ($model) {
            $model->checkRestrictiveRelations();
        });
    }

    /**
     * Check if the model has any restrictive relationships that prevent deletion.
     *
     * @return bool
     * @throws DeleteRestrictionException
     */
    public function checkRestrictiveRelations()
    {
        if (isset($this->restrictiveRelations) && is_array($this->restrictiveRelations)) {
            foreach ($this->restrictiveRelations as $relation => $messageKey) {
                if ($this->{$relation}()->count() > 0) {
                    throw new DeleteRestrictionException(__($messageKey));
                }
            }
        }

        return true;
    }
}
