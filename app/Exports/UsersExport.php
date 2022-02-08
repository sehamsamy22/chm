<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return ['id', 'name', 'email','phone', 'created_at'];
    }

    /**
     * @return array
     */
    public function collection()
    {
        $users = User::select(['id', 'name', 'email', 'phone','created_at'])->get();
        return $users->map(function ($user) {
            return [
                $user->id,
                $user->name,
                $user->email,
                $user->phone,
                $user->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
