<?php

namespace App\Exports;

use App\Models\Contacts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Carbon\Carbon;
use Str;
class ContactsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $contacts = [];
        $my_contacts = Contacts::with('tag')
            ->with('group')
            ->where('contacts.user_id', auth()->user()->id)
            ->get();

        foreach ( $my_contacts as $contact) {
            $contacts[] = [
                $contact->name,
                $contact->phone,
                $contact->tag->name ?? '',
                $contact->group->name ?? '',
                Carbon::parse($contact->created_at)->format('d/m/Y H:i:s'),
            ];
        };

        return collect($contacts);
    }

    public function headings(): array
    {
        return [
            'name',
            'phone',
            'tag_name',
            'group_name',
            'created_at',
        ];
    }

}
