# Import Legacy Registration Data via Artisan Tinker

## Overview

Import 14 legacy registration records into the `registrations` table using a PHP script executed via `php artisan tinker`.

## Data Source

Tab-separated values with the following columns (left-to-right):

| # | Column | Maps To | Notes |
|---|--------|---------|-------|
| 1 | Timestamp | `created_at` | Format: `m/d/Y H:i:s` |
| 2 | Email | `email` | |
| 3 | Full Name | `full_name` | |
| 4 | Nickname | `nickname` | |
| 5 | Birth Info | `birth_place` + `birth_date` | Combined field; needs parsing |
| 6 | Address | `address` | |
| 7 | Phone | `phone` | |
| 8 | Instagram | `instagram` | |
| 9 | Occupation | `occupation` | |
| 10 | Shirt Size | `shirt_size` | Enum: XXS, XS, S, M, L, XL, XXL, XXXL |
| 11 | Vehicle Model | `vehicle_model` | |
| 12 | Vehicle Year | `vehicle_year` | |
| 13 | VIN/Chassis | `vehicle_color` | Stored in `vehicle_color` field (repurposed) |
| 14 | Color | *(skipped)* | Per user instruction |
| 15 | License Plate | `license_plate` | |

## Birth Info Parsing Logic

The 5th column contains combined birth place and date in various formats. The import script must handle these patterns:

| Pattern | Example | birth_place | birth_date |
|---------|---------|-------------|------------|
| `Place , DD Month` | `Surabaya , 26 Mei` | `Surabaya` | `1970-05-26` (fallback year) |
| `Place / DD MONTH YYYY` | `SURABAYA / 29 APRIL 1995` | `SURABAYA` | `1995-04-29` |
| `Place, DD Month YYYY` | `Rembang, 6 Maret 1981` | `Rembang` | `1981-03-06` |
| `Place, DD-Month-YYYY` | `Surabaya, 19-11-1969` | `Surabaya` | `1969-11-19` |
| `DD Month YYYY` (no place) | `10 desember 1984` | *(empty string)* | `1984-12-10` |
| `Place DD Month YYYY` (no comma) | `Solo 17 Januari 1973` | `Solo` | `1973-01-17` |

**Month name mapping:** Indonesian month names and English month names are supported (Januari/January, Februari/February, Maret/March, April, Mei/May, Juni/June, Juli/July, Agustus/August, September, Oktober/October, November, Desember/December).

## Membership Status

All imported records will have `membership_status = 'Pending'` by default (model default).

## Implementation

### File: `plans/import-registrations.php`

Create this PHP script file at [`plans/import-registrations.php`](plans/import-registrations.php). The script contains:

1. All 14 records as an array of associative arrays
2. A `parseBirthInfo()` helper function to handle the various birth info formats
3. A `parseIndonesianDate()` helper to convert Indonesian month names to English
4. A loop that creates `Registration` records using `Registration::create()`
5. Progress output showing success/failure for each record

### PHP Script Content

```php
<?php

/**
 * Import Legacy Registration Data
 *
 * Run: php artisan tinker --execute="require 'plans/import-registrations.php'"
 *   OR: php artisan tinker >>> require 'plans/import-registrations.php'
 */

use App\Models\Registration;

// ---------------------------------------------------------------------------
// 1. HELPER: Parse Indonesian month names to English
// ---------------------------------------------------------------------------
function parseIndonesianDate(string $raw): ?string
{
    $raw = trim($raw);

    $monthMap = [
        'januari'  => 'January',
        'februari' => 'February',
        'maret'    => 'March',
        'april'    => 'April',
        'mei'      => 'May',
        'juni'     => 'June',
        'juli'     => 'July',
        'agustus'  => 'August',
        'september'=> 'September',
        'oktober'  => 'October',
        'november' => 'November',
        'desember' => 'December',
    ];

    // Replace Indonesian month names with English
    $english = str_ireplace(
        array_keys($monthMap),
        array_values($monthMap),
        $raw
    );

    // Try parsing with various formats
    $formats = [
        'd F Y',   // 6 Maret 1981, 16 April 1990
        'd-m-Y',   // 19-11-1969
        'j F Y',   // 4 Jan 1977
        'd F',     // 26 Mei (no year)
        'j F',     // 26 Mei (no year, no leading zero)
    ];

    foreach ($formats as $format) {
        $dt = \DateTime::createFromFormat($format, $english);
        if ($dt !== false) {
            // If no year was provided (d F format without Y), default to 1970
            if (!preg_match('/\d{4}/', $english)) {
                $dt->setDate(1970, (int)$dt->format('m'), (int)$dt->format('d'));
            }
            return $dt->format('Y-m-d');
        }
    }

    return null;
}

// ---------------------------------------------------------------------------
// 2. HELPER: Parse birth info (combined place + date)
// ---------------------------------------------------------------------------
function parseBirthInfo(string $raw): array
{
    $raw = trim($raw);
    $place = '';
    $date  = null;

    if (empty($raw)) {
        return [$place, $date];
    }

    // Pattern: "Place / DD Month YYYY"   e.g. "SURABAYA / 29 APRIL 1995"
    if (preg_match('/^(.+?)\s*\/\s*(\d{1,2}\s+[A-Za-z]+\s+\d{4})$/', $raw, $m)) {
        $place = trim($m[1]);
        $date  = parseIndonesianDate(trim($m[2]));
    }
    // Pattern: "Place, DD-Month-YYYY"  e.g. "Surabaya, 19-11-1969"
    elseif (preg_match('/^(.+?),?\s*(\d{1,2}[-]\d{1,2}[-]\d{4})$/', $raw, $m)) {
        $place = trim($m[1], " ,\t\n\r\0\x0B");
        $date  = parseIndonesianDate(trim($m[2]));
    }
    // Pattern: "Place, DD Month YYYY" or "Place DD Month YYYY"
    // e.g. "Rembang, 6 Maret 1981" or "Solo 17 Januari 1973"
    elseif (preg_match('/^(.+?)[, ]+\s*(\d{1,2}\s+[A-Za-z]+\s+\d{4})$/', $raw, $m)) {
        $place = trim($m[1], " ,\t\n\r\0\x0B");
        $date  = parseIndonesianDate(trim($m[2]));
    }
    // Pattern: "Place, DD Month" (no year)  e.g. "Surabaya , 26 Mei"
    elseif (preg_match('/^(.+?)[, ]+\s*(\d{1,2}\s+[A-Za-z]+)$/', $raw, $m)) {
        $place = trim($m[1], " ,\t\n\r\0\x0B");
        $date  = parseIndonesianDate(trim($m[2]));
    }
    // Pattern: Only "DD Month YYYY"  e.g. "10 desember 1984"
    elseif (preg_match('/^(\d{1,2}\s+[A-Za-z]+\s+\d{4})$/', $raw, $m)) {
        $place = '';
        $date  = parseIndonesianDate(trim($m[1]));
    }
    // Fallback: just treat the whole thing as place
    else {
        $place = $raw;
    }

    return [$place, $date];
}

// ---------------------------------------------------------------------------
// 3. DATA: All records
// ---------------------------------------------------------------------------
$records = [
    [
        'created_at'    => '2026-06-22 15:02:59',
        'email'         => 'joewono@sby.dnet.net.id',
        'full_name'     => 'Henry Joewono',
        'nickname'      => 'HJ',
        'birth_info'    => 'Surabaya , 26 Mei',
        'address'       => 'Graha Famili E 15',
        'phone'         => '0818501625',
        'instagram'     => 'Henry_joewono',
        'occupation'    => 'Direktur',
        'shirt_size'    => 'XL',
        'vehicle_model' => 'SL 63',
        'vehicle_year'  => 2012,
        'vin'           => 'WDD2314742F006084',
        'license_plate' => 'B 8 ALC',
    ],
    [
        'created_at'    => '2026-06-22 15:07:38',
        'email'         => 'jonathanenrico@ymail.com',
        'full_name'     => 'Jonathan Enrico Susanto',
        'nickname'      => 'Jonathan',
        'birth_info'    => 'SURABAYA / 29 APRIL 1995',
        'address'       => 'APARTMENT PRAXIS UNIT 1508',
        'phone'         => '087881332747',
        'instagram'     => '@jonathan_enrico',
        'occupation'    => 'JUAL BELI MOBIL PREMIUM - @autotradersby',
        'shirt_size'    => 'XL',
        'vehicle_model' => 'Mercedes Benz C55 AMG ESTATE',
        'vehicle_year'  => 2003,
        'vin'           => 'WDB2032762F605688',
        'license_plate' => 'S 203',
    ],
    [
        'created_at'    => '2026-06-22 15:25:12',
        'email'         => 'ari_suburjaya_trans@yahoo.com',
        'full_name'     => 'Ary wibowo prasetyo',
        'nickname'      => 'Ary',
        'birth_info'    => 'Rembang, 6 Maret 1981',
        'address'       => 'Bukit Golf International GC6-30 Citraland',
        'phone'         => '081228757777',
        'instagram'     => 'Art.w_777',
        'occupation'    => 'Wiraswasta',
        'shirt_size'    => 'L',
        'vehicle_model' => 'G 63',
        'vehicle_year'  => 2022,
        'vin'           => 'W1N4632762X425129',
        'license_plate' => 'B 7 GBU',
    ],
    [
        'created_at'    => '2026-06-22 16:20:27',
        'email'         => 'vetomok@yahoo.com',
        'full_name'     => 'Veto Mok',
        'nickname'      => 'Veto',
        'birth_info'    => "S'pore,  27/4/1971",
        'address'       => 'AB9/12 Imperial Golf Regensi, Pakuwon Indah',
        'phone'         => '08123002093',
        'instagram'     => 'vetomok',
        'occupation'    => 'Director',
        'shirt_size'    => 'XL',
        'vehicle_model' => 'CLS63AMG',
        'vehicle_year'  => 2012,
        'vin'           => 'WDD2183742A042166',
        'license_plate' => 'L63TT',
    ],
    [
        'created_at'    => '2026-06-22 16:27:34',
        'email'         => 'andishafira@gmail.com',
        'full_name'     => 'Andi Alamsyah',
        'nickname'      => 'Andi',
        'birth_info'    => 'Jombang, 6 Feb 1965',
        'address'       => 'Jl. Jemursari Timur 14/5 Surabaya',
        'phone'         => '0811324300',
        'instagram'     => '@andishafira',
        'occupation'    => 'Swasta',
        'shirt_size'    => 'M',
        'vehicle_model' => 'G63',
        'vehicle_year'  => 2023,
        'vin'           => 'Abcde',
        'license_plate' => 'L 1151 CXA',
    ],
    [
        'created_at'    => '2026-06-23 10:15:30',
        'email'         => 'fiqihparamaartha88@gmail.com',
        'full_name'     => 'Tetuko Fyqhih Paramaartha',
        'nickname'      => 'Arthur',
        'birth_info'    => 'Tulungagung, 27 Desember 1997',
        'address'       => 'Grand Permata Jingga, 6th Avenue no.18, Malang, East Java',
        'phone'         => '081323423388',
        'instagram'     => 'Arthfiq88',
        'occupation'    => 'Business',
        'shirt_size'    => 'XL',
        'vehicle_model' => 'AMG A35',
        'vehicle_year'  => 2024,
        'vin'           => 'MHL 177 151 RJ 000 633',
        'license_plate' => 'N 1 AGP',
    ],
    [
        'created_at'    => '2026-06-23 14:09:35',
        'email'         => 'joko_jr74@yahoo.com',
        'full_name'     => 'Pandu Januardi',
        'nickname'      => 'Pandu',
        'birth_info'    => 'Sidoarjo, 11 Januari 1994',
        'address'       => 'Jl Hang Tuah 2, Sidoarjo',
        'phone'         => '081919232332',
        'instagram'     => 'keanuarkhan_',
        'occupation'    => 'Tukang Foto',
        'shirt_size'    => 'L',
        'vehicle_model' => 'G63',
        'vehicle_year'  => 2022,
        'vin'           => 'WIN4632762X417752',
        'license_plate' => 'B2501SXY',
    ],
    [
        'created_at'    => '2026-06-23 14:38:11',
        'email'         => 'wenshaoliong@gmail.com',
        'full_name'     => 'Soesanto Liang',
        'nickname'      => 'Ashao',
        'birth_info'    => 'Surabaya, 19-11-1969',
        'address'       => 'Raya Trawas km 2,5 Mojosari',
        'phone'         => '085311651165',
        'instagram'     => 'liangsoesanto',
        'occupation'    => 'Wiraswasta',
        'shirt_size'    => 'M',
        'vehicle_model' => 'G63 amg',
        'vehicle_year'  => 2022,
        'vin'           => 'W1N4632762X435244',
        'license_plate' => 'S-1-AU',
    ],
    [
        'created_at'    => '2026-06-23 16:02:47',
        'email'         => 'yusupsunaryo90@gmail.com',
        'full_name'     => 'Yusuf Sunaryo',
        'nickname'      => 'Yusuf',
        'birth_info'    => 'Surabaya, 16 April 1990',
        'address'       => 'Malang',
        'phone'         => '081336409727',
        'instagram'     => 'Yusuf sunaryo',
        'occupation'    => 'Wiraswasta',
        'shirt_size'    => 'XXL',
        'vehicle_model' => 'GLA 35',
        'vehicle_year'  => 2022,
        'vin'           => 'Mhl247751nj000348',
        'license_plate' => 'N 1847 ACS',
    ],
    [
        'created_at'    => '2026-06-24 13:05:34',
        'email'         => 'elvinwil1885@gmail.com',
        'full_name'     => 'Vin lei',
        'nickname'      => 'Vin lei',
        'birth_info'    => 'Surabaya, 18 September 1985',
        'address'       => 'Surabaya',
        'phone'         => '081236688888',
        'instagram'     => 'Tidak ada',
        'occupation'    => 'Pengusaha',
        'shirt_size'    => 'L',
        'vehicle_model' => 'Mercedes slk 55 amg',
        'vehicle_year'  => 2009,
        'vin'           => 'WDB 1714732f227272',
        'license_plate' => 'B 2822 sxy',
    ],
    [
        'created_at'    => '2026-06-24 15:08:35',
        'email'         => 'suryadamar86@gmail.com',
        'full_name'     => 'Andy firmansyah',
        'nickname'      => 'Andy',
        'birth_info'    => '10 desember 1984',
        'address'       => 'Surya inti permata juanda blok c 12 sedati agung sedati sidoarjo jawa timur',
        'phone'         => '0817329266',
        'instagram'     => 'Niki_express',
        'occupation'    => 'Wiraswasta',
        'shirt_size'    => 'XL',
        'vehicle_model' => 'Cla 45s sedan',
        'vehicle_year'  => 2023,
        'vin'           => 'W1k1183542N426906',
        'license_plate' => 'W 54 RI',
    ],
    [
        'created_at'    => '2026-06-25 17:56:04',
        'email'         => 'bintoro.liauw@gmail.com',
        'full_name'     => 'Bintoro',
        'nickname'      => 'Bink',
        'birth_info'    => 'Malang , 10 feb 1972',
        'address'       => 'jl laks Martadinata 109 Malang',
        'phone'         => '0811303132',
        'instagram'     => '@bintorobink',
        'occupation'    => 'bengkel DP Auto Tuning',
        'shirt_size'    => 'L',
        'vehicle_model' => 'CLS63',
        'vehicle_year'  => 2011,
        'vin'           => 'wdd2183742a010678',
        'license_plate' => 'N 8 INK',
    ],
    [
        'created_at'    => '2026-06-26 08:34:41',
        'email'         => 'caturlimas@yahoo.com',
        'full_name'     => 'Catur Limas',
        'nickname'      => 'Kahan',
        'birth_info'    => 'Solo 17 Januari 1973',
        'address'       => 'Jl Kenjeran 395-399 Surabaya',
        'phone'         => '0812900060',
        'instagram'     => 'caturlimas',
        'occupation'    => 'Manufacturing',
        'shirt_size'    => 'M',
        'vehicle_model' => 'G63',
        'vehicle_year'  => 2020,
        'vin'           => 'W1N4632762X3645408',
        'license_plate' => 'L 1 MAS',
    ],
    [
        'created_at'    => '2026-06-29 15:40:21',
        'email'         => 'tirtoherman368@gmail.com',
        'full_name'     => 'Herman Tirto',
        'nickname'      => 'Herman',
        'birth_info'    => 'Surabaya , 4 Jan 1977',
        'address'       => 'VBR 1 PC 5 no 3 , Pakuwon Indah',
        'phone'         => '08175117777',
        'instagram'     => 'sisilia_ismyname',
        'occupation'    => 'Wiraswasta',
        'shirt_size'    => 'XXL',
        'vehicle_model' => 'G 63',
        'vehicle_year'  => 2022,
        'vin'           => 'W1N4632762X458832',
        'license_plate' => 'L 77 MEN',
    ],
];

// ---------------------------------------------------------------------------
// 4. IMPORT LOOP
// ---------------------------------------------------------------------------
$imported = 0;
$failed   = 0;

foreach ($records as $idx => $rec) {
    $rowNum = $idx + 1;

    try {
        // Parse birth info
        [$birthPlace, $birthDate] = parseBirthInfo($rec['birth_info']);

        $data = [
            'full_name'         => $rec['full_name'],
            'nickname'          => $rec['nickname'],
            'birth_place'       => $birthPlace,
            'birth_date'        => $birthDate ?? date('Y-m-d'),
            'address'           => $rec['address'],
            'phone'             => $rec['phone'],
            'email'             => $rec['email'],
            'instagram'         => $rec['instagram'],
            'occupation'        => $rec['occupation'],
            'shirt_size'        => $rec['shirt_size'],
            'vehicle_model'     => $rec['vehicle_model'],
            'vehicle_year'      => $rec['vehicle_year'],
            'vehicle_color'     => $rec['vin'],
            'license_plate'     => $rec['license_plate'],
            'membership_status' => 'Pending',
            'created_at'        => $rec['created_at'],
            'updated_at'        => $rec['created_at'],
        ];

        Registration::create($data);
        $imported++;

        echo "[OK] Row {$rowNum}: {$rec['full_name']} ({$rec['license_plate']})\n";

    } catch (\Exception $e) {
        $failed++;
        echo "[FAIL] Row {$rowNum}: {$rec['full_name']} - {$e->getMessage()}\n";
    }
}

// ---------------------------------------------------------------------------
// 5. SUMMARY
// ---------------------------------------------------------------------------
echo "\n========================================\n";
echo "Import complete!\n";
echo "Successfully imported: {$imported}\n";
echo "Failed: {$failed}\n";
echo "Total records: " . count($records) . "\n";
echo "========================================\n";
```

### Execution

Run from the project root:

```bash
php artisan tinker --execute="require 'plans/import-registrations.php'"
```

Or interactively:

```bash
php artisan tinker
>>> require 'plans/import-registrations.php'
```

## Data Verification

After import, verify with:

```bash
php artisan tinker
>>> App\Models\Registration::count();
# Should return 14

>>> App\Models\Registration::all();
# Review all records
```

## Rollback (if needed)

```bash
php artisan tinker
>>> App\Models\Registration::truncate();
```
