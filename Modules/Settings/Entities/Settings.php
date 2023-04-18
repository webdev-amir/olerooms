<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{

    use HasEvents;

    protected $table = 'site_settings';

    protected $fillable = ['name', 'val', 'is_show'];

    public static function getSettings($group = '', $locale = '')
    {
        if ($group) {
            static::where('group', $group);
        }
        $all = static::all();
        $res = [];
        foreach ($all as $row) {
            $res[$row->name] = $row->val;
        }

        return $res;
    }

    static public function findBySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }

    public static function item($item, $default = false)
    {
        $val = Settings::where('slug', $item)->first();
        return ($val and $val['val'] != null) ? $val['val'] : $default;
    }

    public function getS3AggrementPdfDownloadPathAttribute()
    {
        return 'settings/' . $this->val;
    }

    public function getAggrementPdfPathAttribute()
    {
        return \Storage::disk('s3')->url('settings/' . $this->val);

        $image = \URL::to('storage/app/public/settings/' . $this->val);
        if (\Storage::exists('/public/settings/' . $this->val)) {
            $filename = $image;
        } else {
            $filename = $this->val;
        }
        return $filename;
    }
}
