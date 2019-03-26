<?php

namespace App\Ship\Database\Eloquent;

use Jenssegers\Mongodb\Relations\BelongsTo;

class CastIntBelongsTo extends BelongsTo
{
    /**
     * @inheritdoc
     */
    public function addConstraints()
    {
        if (static::$constraints) {
            // For belongs to relationships, which are essentially the inverse of has one
            // or has many relationships, we need to actually query on the primary key
            // of the related models matching on the foreign key that's on a parent.
            $this->query->where($this->getOwnerKey(), '=', (int)$this->parent->{$this->foreignKey});
        }
    }
}