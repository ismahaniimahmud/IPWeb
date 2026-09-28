<?php

namespace Database\Seeders;

use App\Models\Action;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

class ActionSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/Excel management dashboard data.csv');

        if (! is_file($path)) {
            throw new RuntimeException("CSV file not found: {$path}");
        }

        $handle = fopen($path, 'rb');
        $headers = fgetcsv($handle, 0, "\t");

        if ($headers === false) {
            throw new RuntimeException('CSV file is empty.');
        }

        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $records = [];

        while (($row = fgetcsv($handle, 0, "\t")) !== false) {
            if (count($row) !== count($headers)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $records[] = [
                'action_code' => $data['ID'],
                'reference' => $data['Rujukan'] ?: null,
                'theme' => $data['Tema'] ?: null,
                'required_action' => $data['Tindakan Diperlukan'] ?: null,
                'owner' => $data['Pemilik Tindakan'] ?: null,
                'target' => $data['Sasaran / Tempoh'] ?: null,
                'status' => $data['Status'] ?: null,
                'priority' => $data['Keutamaan'] ?: null,
                'basis' => $data['Asas Tindakan'] ?: null,
                'progress' => $data['Kemajuan Terkini'] ?: null,
                'evidence' => $data['Bukti / Keputusan'] ?: null,
                'updated_on' => $data['Tarikh Kemas Kini']
                    ? Carbon::parse($data['Tarikh Kemas Kini'])->toDateString()
                    : null,
                'source_note' => $data['Catatan Sumber'] ?: null,
                'meeting_date' => Carbon::parse($data['Tarikh Mesyuarat'])->toDateString(),
            ];
        }

        fclose($handle);

        Action::query()->upsert(
            $records,
            ['action_code'],
            array_keys($records[0])
        );
    }
}
