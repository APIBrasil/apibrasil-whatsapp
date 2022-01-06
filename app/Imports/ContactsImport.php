<?php

namespace App\Imports;

use App\Models\Contacts;
use App\Models\Tags;
use App\Models\Groups;
use Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ContactsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $tag_id = Tags::where('name', 'like', '%'.$row[2].'%' )
        ->where('user_id', '=', auth()->user()->id)
        ->first();

        if( !isset($tag_id->id) ){

            $tag_id = Tags::create([
                'name' => $row[2],
                'description' => $row[2],
                'color' => '#ff0000',
                'user_id' => auth()->user()->id,
            ]);
        }

        if($row[1] != '' and $row[1] != 'phone' and $row[1] != 'name' and $row[0] != ''){

            return new Contacts([
                "imported" => true,
                "name" => $row[0],
                "phone" => $row[1],
                "tag_id" => isset($tag_id->id) ? $tag_id->id : null,
                'user_id' => auth()->user()->id,
            ]);

        }
    }

}
