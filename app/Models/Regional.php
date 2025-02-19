<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use \Illuminate\Support\Facades\Log;

class Regional extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = "regional";
    protected $primaryKey = "kode_regional";
    protected $guarded = [];
    public $incrementing = false;
    protected $keyType = 'string';

    public function getKey()
    {
        return $this->kode_regional; // Pastikan ini sesuai dengan primary key di tabel
    }
    public function getActivitylogOptions(): LogOptions
    {
        Log::info('Spatie Log Activity dipanggil untuk Regional'); // Debugging
        return LogOptions::defaults()
            ->logOnly(['kode_regional', 'nama_regional'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Regional {$this->kode_regional} telah {$eventName}");
    }
}
