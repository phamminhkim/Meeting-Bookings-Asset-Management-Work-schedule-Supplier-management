<?php

namespace App\Models\shared;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConfigUser extends Model
{
    protected $fillable = [
        'value', 'code', 'user_id'
    ];

    protected $primaryKey = ['user_id', 'code'];
    public $incrementing = false;
    

    //Hai hàm bên dưới override để có thể lưu bảng có 2 khoá chính 
    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

/**
 * Get the primary key value for a save query.
 *
 * @param mixed $keyName
 * @return mixed
 */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }


}
