<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Model
{
    use HasFactory;

    protected $primaryKey = "PersonalDataID";

    //obtenemos al usuario correspondiente
    public function user(){
        return $this->belongsTo(User::class,"UserID");
    }

    public function createPersonalData(){
        $newDatata = new PersonalData();

        $newDatata->Nickname = session()->get("nickname");
        $newDatata->Date = session()->get("date");

        $newDatata->save();

        return $newDatata->PersonalDataID;
    }
}
