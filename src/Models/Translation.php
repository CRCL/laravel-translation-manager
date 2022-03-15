<?php namespace Barryvdh\TranslationManager\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * Translation model
 *
 * @property integer $id
 * @property integer $status
 * @property string  $locale
 * @property string  $group
 * @property string  $key
 * @property string  $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $seen_in_file
 * @property string $seen_in_url
 * @property string $default_missing_value
 * @property string $default_missing_locale
 */
class Translation extends Model{

    const STATUS_SAVED = 0;
    const STATUS_CHANGED = 1;

    protected $table = 'ltm_translations';
    protected $guarded = array('id', 'created_at', 'updated_at');

    public function scopeOfTranslatedGroup($query, $group)
    {
        return $query->where('group', $group)->where(function($q) {
            $q->orWhereNotNull('value')
                ->orWhereNotNull('default_missing_value');
        });
    }

    public function scopeOrderByGroupKeys($query, $ordered) {
        if ($ordered) {
            $query->orderBy('group')->orderBy('key');
        }

        return $query;
    }

    public function scopeSelectDistinctGroup($query)
    {
        $select = '';

        switch (DB::getDriverName()){
            case 'mysql':
                $select = 'DISTINCT `group`';
                break;
            default:
                $select = 'DISTINCT "group"';
                break;
        }

        return $query->select(DB::raw($select));
    }

    public function getDisplayValue(): ?string {
        if(! is_null($this->value)){
            return $this->value;
        }elseif(! is_null($this->default_missing_value)){
            return $this->default_missing_value;
        }else{
            return null;
        }


    }

}
