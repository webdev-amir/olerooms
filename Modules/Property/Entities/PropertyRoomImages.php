<?php

namespace Modules\Property\Entities;

use Illuminate\Database\Eloquent\Model;

class PropertyRoomImages extends Model
{
    protected $table = "property_room_images";

    protected $fillable = [
        'property_id',
        'property_room_id',
        'room_image',
        'room_type',
    ];
    
    public function property()
    {
        return $this->belongsTo("Modules\Property\Entities\Property", "property_id")->withTrashed();
    }
    
    public function propertyRooms()
    {
        return $this->belongsTo("Modules\Property\Entities\PropertyRooms", "property_room_id");
    }

    public function getRoomImageRealAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->property->user_id . '/rooms/' . $this->room_image);
        //down code used for local system upload file only
        $image = \URL::to('storage/app/public/property/'.$this->property->user_id.'/rooms/' . $this->room_image);
        if ($this->room_image && \Storage::exists('/public/property/'.$this->property->user_id.'/rooms/' . $this->room_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }
        return $filename;
    } 

    public function getRoomImageThunbnailAttribute()
    {
        return \Storage::disk('s3')->url('property/' . $this->property->user_id . '/rooms/thumbnail/' . $this->room_image);
        //down code used for local system upload file only
        $image = \URL::to('storage/app/public/property/'.$this->property->user_id.'/rooms/thumbnail/' . $this->room_image);
        if ($this->room_image && \Storage::exists('/public/property/'.$this->property->user_id.'/rooms/thumbnail/' . $this->room_image)) {
            $filename = $image;
        } else {
            $filename = \URL::to('images/noimage.png');
        }
        return $filename;
    }
}