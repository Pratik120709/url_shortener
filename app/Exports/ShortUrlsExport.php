<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShortUrlsExport implements FromCollection, WithHeadings
{
    protected $shortUrls;

    public function __construct($shortUrls)
    {
        $this->shortUrls = $shortUrls;
    }

    public function collection()
    {
        return $this->shortUrls->map(function ($url) {
            return [
                'Short Code' =>     $url->short_code,
                'Original URL' => $url->original_url,
                'User' => $url->user->name ?? '-',
                'Company' => $url->company->name ?? '-',
                'Created At' => $url->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
             'Short Code',
            'Original URL',
            'User',
            'Company',
            'Created At',
        ];
    }
}

