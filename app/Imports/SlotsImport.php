<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SlotsImport implements ToModel, WithHeadingRow
{
    private function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-:]/', '', $string); // Removes special chars.
    }

    /**
     * @param array $row
     *
     * @return Slot|null
     */
    public function model(array $row)
    {
        try {
            if ($row['date'] == "") {
                return null;
            }

            $row['date'] = floatval(trim($row['date']));
            $unixDate = ($row['date'] - 25569) * 86400;
            $row['date'] =  gmdate("Y-m-d", $unixDate);

            // $row['start_time'] = floatval(trim($row['start_time']));
            if (is_numeric($row['start_time'])) {
                $unixDate = ($row['start_time'] - 25569) * 86400;
                $row['start_time'] =  gmdate("H:i", $unixDate);
            } else {
                $row['start_time'] = ($this->clean($row['start_time']));
            }

            // $row['end_time'] = floatval(trim($row['end_time']));
            if (is_numeric($row['end_time'])) {
                $unixDate = ($row['end_time'] - 25569) * 86400;
                $row['end_time'] =  gmdate("H:i", $unixDate);
            } else {
                $row['end_time'] = ($this->clean($row['end_time']));
            }

            $slotExist = Slot::where([
                'date' => $row['date'],
                'start_time' => $row['start_time'],
                'branch_id' => $row['branch_id'],
            ])->exists();

            if ($slotExist) {
                $branch = Branch::find($row['branch_id']);

                Session::push('slots_error', $branch->name . " - " . Carbon::parse($row['date'])->format('d.m.Y') . " @ " . $row['start_time']);
                return null;
            } else {
                return new Slot([
                    'name' => NULL,
                    'date' => $row['date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'branch_id' => $row['branch_id'],
                    'desc' => NULL,
                    'status' => 'available'
                ]);
            }
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
}
