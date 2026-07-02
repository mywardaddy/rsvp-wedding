<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\Rsvp;
use App\Models\Checkin;
use App\Models\Scanner;
use App\Models\User;
use App\Models\WishMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $pengantin = User::where('email', 'pengantin@wedding.test')->first();
        $petugas = User::where('email', 'petugas@wedding.test')->first();

        // Create wedding event
        $event = Event::updateOrCreate(
            ['slug' => 'ahmad-siti-wedding'],
            [
                'user_id' => $pengantin->id,
                'title' => 'Pernikahan Ahmad & Siti',
                'groom_name' => 'Ahmad Fauzan',
                'bride_name' => 'Siti Nurhaliza',
                'date' => now()->addDays(30)->format('Y-m-d'),
                'time_start' => '08:00',
                'time_end' => '14:00',
                'venue_name' => 'Grand Ballroom Hotel Mulia',
                'venue_address' => 'Jl. Asia Afrika No. 8, Senayan, Jakarta Selatan 10270',
                'venue_lat' => -6.2297,
                'venue_lng' => 106.8017,
                'description' => 'Dengan memohon Rahmat dan Ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri resepsi pernikahan kami.',
                'love_story' => [
                    ['year' => '2020', 'title' => 'Pertama Bertemu', 'description' => 'Kami bertemu pertama kali di acara seminar kampus.'],
                    ['year' => '2021', 'title' => 'Mulai Dekat', 'description' => 'Setelah setahun berteman, kami mulai saling mengenal lebih dalam.'],
                    ['year' => '2023', 'title' => 'Lamaran', 'description' => 'Ahmad melamar Siti di hadapan kedua keluarga besar.'],
                    ['year' => '2024', 'title' => 'Pernikahan', 'description' => 'Hari bahagia yang telah kami nantikan bersama.'],
                ],
                'gallery' => [],
                'theme_color' => '#C9B037',
                'is_active' => true,
            ]
        );

        // Create guest groups
        $groups = [
            ['name' => 'Keluarga Mempelai Pria', 'color' => '#C9B037'],
            ['name' => 'Keluarga Mempelai Wanita', 'color' => '#9CAF88'],
            ['name' => 'Teman Kantor', 'color' => '#8B7355'],
            ['name' => 'Teman Kuliah', 'color' => '#6B8E9B'],
            ['name' => 'Tetangga', 'color' => '#A0926E'],
        ];

        $groupModels = [];
        foreach ($groups as $group) {
            $groupModels[] = GuestGroup::updateOrCreate(
                ['event_id' => $event->id, 'name' => $group['name']],
                $group + ['event_id' => $event->id]
            );
        }

        // Create scanner assignment
        Scanner::updateOrCreate(
            ['user_id' => $petugas->id, 'event_id' => $event->id],
            ['is_active' => true]
        );

        // Create sample guests
        $guestNames = [
            // Keluarga Pria
            ['name' => 'Bapak Haji Usman', 'category' => 'keluarga', 'group' => 0, 'max_companions' => 3],
            ['name' => 'Ibu Hj. Aminah', 'category' => 'keluarga', 'group' => 0, 'max_companions' => 2],
            ['name' => 'Andi Pratama', 'category' => 'keluarga', 'group' => 0, 'max_companions' => 2],
            ['name' => 'Dewi Lestari', 'category' => 'keluarga', 'group' => 0, 'max_companions' => 1],
            ['name' => 'Rizki Ramadan', 'category' => 'keluarga', 'group' => 0, 'max_companions' => 1],
            // Keluarga Wanita
            ['name' => 'Bapak H. Sulaiman', 'category' => 'keluarga', 'group' => 1, 'max_companions' => 3],
            ['name' => 'Ibu Hj. Fatimah', 'category' => 'keluarga', 'group' => 1, 'max_companions' => 2],
            ['name' => 'Nur Aini', 'category' => 'keluarga', 'group' => 1, 'max_companions' => 2],
            ['name' => 'Syahrul Gunawan', 'category' => 'keluarga', 'group' => 1, 'max_companions' => 1],
            ['name' => 'Laila Azzahra', 'category' => 'keluarga', 'group' => 1, 'max_companions' => 1],
            // Teman Kantor
            ['name' => 'Budi Santoso', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            ['name' => 'Rini Widiastuti', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            ['name' => 'Hendra Wijaya', 'category' => 'reguler', 'group' => 2, 'max_companions' => 2],
            ['name' => 'Mega Putri', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            ['name' => 'Dimas Prasetyo', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            ['name' => 'Ayu Kusuma', 'category' => 'reguler', 'group' => 2, 'max_companions' => 2],
            ['name' => 'Fajar Nugroho', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            ['name' => 'Intan Permata', 'category' => 'reguler', 'group' => 2, 'max_companions' => 1],
            // VIP
            ['name' => 'Dr. Surya Atmaja', 'category' => 'vip', 'group' => 2, 'max_companions' => 3],
            ['name' => 'Prof. Ratna Dewi', 'category' => 'vip', 'group' => 2, 'max_companions' => 2],
            // Teman Kuliah
            ['name' => 'Yoga Perdana', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            ['name' => 'Fitri Handayani', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 2],
            ['name' => 'Gilang Ramadhan', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            ['name' => 'Nisa Aprilia', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            ['name' => 'Teguh Prasetya', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 2],
            ['name' => 'Wulan Dari', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            ['name' => 'Arif Rahman', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            ['name' => 'Diana Sari', 'category' => 'sahabat', 'group' => 3, 'max_companions' => 1],
            // Tetangga
            ['name' => 'Pak RT Sudirman', 'category' => 'reguler', 'group' => 4, 'max_companions' => 2],
            ['name' => 'Bu Kartini', 'category' => 'reguler', 'group' => 4, 'max_companions' => 1],
            ['name' => 'Agus Salim', 'category' => 'reguler', 'group' => 4, 'max_companions' => 2],
            ['name' => 'Mira Lestari', 'category' => 'reguler', 'group' => 4, 'max_companions' => 1],
            ['name' => 'Joko Widodo', 'category' => 'reguler', 'group' => 4, 'max_companions' => 1],
            ['name' => 'Siska Amelia', 'category' => 'reguler', 'group' => 4, 'max_companions' => 1],
            ['name' => 'Rahmat Hidayat', 'category' => 'reguler', 'group' => 4, 'max_companions' => 2],
            ['name' => 'Endang Supriyati', 'category' => 'reguler', 'group' => 4, 'max_companions' => 1],
        ];

        foreach ($guestNames as $i => $guestData) {
            $guest = Guest::updateOrCreate(
                ['event_id' => $event->id, 'name' => $guestData['name']],
                [
                    'event_id' => $event->id,
                    'guest_group_id' => $groupModels[$guestData['group']]->id,
                    'name' => $guestData['name'],
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'email' => Str::slug($guestData['name']) . '@email.com',
                    'category' => $guestData['category'],
                    'max_companions' => $guestData['max_companions'],
                ]
            );

            // Random RSVP for some guests
            if ($i < 25) {
                $status = $i < 20 ? 'hadir' : 'tidak_hadir';
                Rsvp::updateOrCreate(
                    ['guest_id' => $guest->id],
                    [
                        'status' => $status,
                        'number_of_guests' => $status === 'hadir' ? rand(1, $guestData['max_companions']) : 0,
                        'message' => $status === 'hadir'
                            ? collect(['Selamat menempuh hidup baru!', 'Barakallah! Semoga menjadi keluarga sakinah.', 'Turut berbahagia, semoga langgeng!', 'InsyaAllah hadir, selamat ya!'])->random()
                            : 'Mohon maaf tidak bisa hadir. Selamat ya!',
                        'responded_at' => now()->subDays(rand(1, 20)),
                    ]
                );

                // Check in some attending guests
                if ($status === 'hadir' && $i < 12) {
                    Checkin::updateOrCreate(
                        ['guest_id' => $guest->id, 'scanner_user_id' => $petugas->id],
                        [
                            'checked_in_at' => now()->subHours(rand(1, 5)),
                            'method' => 'qr_scan',
                        ]
                    );
                }
            }
        }

        // Create wish messages
        $wishes = [
            'Selamat menempuh hidup baru! Semoga menjadi keluarga yang sakinah, mawaddah, warahmah.',
            'Barakallah! Semoga Allah memberkahi pernikahan kalian berdua.',
            'Happy Wedding! Semoga menjadi pasangan yang saling melengkapi.',
            'Turut berbahagia atas pernikahan kalian. Semoga langgeng sampai Jannah!',
            'MasyaAllah tabarakallah! Selamat untuk Ahmad dan Siti.',
        ];

        foreach ($wishes as $j => $wish) {
            WishMessage::updateOrCreate(
                ['event_id' => $event->id, 'name' => 'Tamu ' . ($j + 1)],
                [
                    'event_id' => $event->id,
                    'name' => $guestNames[$j]['name'],
                    'message' => $wish,
                    'is_approved' => true,
                ]
            );
        }
    }
}
