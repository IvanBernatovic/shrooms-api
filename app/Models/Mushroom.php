<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mushroom extends Model
{
    protected $fillable = ['capShape', 'capSurface', 'capColor',
        'bruises','odor','gillAttachment','gillSpacing','gillSize','gillColor',
        'stalkShape','stalkRoot','stalkSurfaceAboveRing','stalkSurfaceBelowRing',
        'stalkColorAboveRing','stalkColorBelowRing','veilType','veilColor','ringNumber',
        'ringType','sporePrintColor','population','habitat'];
    
    
}
