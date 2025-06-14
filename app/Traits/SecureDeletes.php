<?php

/**
 * File name: SecureDeletes.php
 * Last modified: 17/06/21, 3:44 PM
 * Author: NearCraft - https://codecanyon.net/user/nearcraft
 * Copyright (c) 2021
 */

namespace App\Traits;

trait SecureDeletes
{
    /**
     * Delete only when there is no reference to other models.
     *
     * @param array $relations
     */
    public function secureDelete(String ...$relations)
    {
        foreach ($relations as $relation) {
            if (method_exists($this, $relation) && $this->$relation()->withTrashed()->exists()) {
                $this->delete();
                return;
            }
        }
        $this->forceDelete();
    }

    /**
     * Check whether the model has any relation and can deletable.
     *
     * @param array $relations
     * @return bool
     */
    public function canSecureDelete(string ...$relations): bool
    {
        foreach ($relations as $relation) {
            if (method_exists($this, $relation) && $this->$relation()->withTrashed()->exists()) {
                return false;
            }
        }
        return true;
    }
}
