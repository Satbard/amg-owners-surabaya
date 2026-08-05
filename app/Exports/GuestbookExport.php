<?php

namespace App\Exports;

use App\Models\Guestbook;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestbookExport implements FromCollection, WithHeadings, WithMapping
{
    protected $guestbook;

    public function __construct(Guestbook $guestbook)
    {
        $this->guestbook = $guestbook;
    }

    public function collection()
    {
        return $this->guestbook->entries()->latest()->get();
    }

    public function headings(): array
    {
        $labels = $this->guestbook->fields->pluck('label')->toArray();

        return array_merge($labels, ['Waktu Input']);
    }

    public function map($entry): array
    {
        $data = $entry->data ?? [];

        $row = [];

        foreach ($this->guestbook->fields as $field) {
            $row[] = $data[$field->id] ?? '';
        }

        $row[] = $entry->created_at
            ? Carbon::parse($entry->created_at)->format('d M Y H:i:s')
            : '-';

        return $row;
    }
}
