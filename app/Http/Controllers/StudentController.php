<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\ShortenedUrl;

use App\Jobs\GenerateStudentShortUrlsJob;


class StudentController extends Controller
{
   public function index($encodedId)
{
    $user = DB::connection('pgsql')->selectOne(
    "
    SELECT
        st.*,
        st.phone as student_phone,
        st.name as student_name,
        st.email as student_email,
        sc.city as school_city,
        sc.state as school_state,
        sc.name as school_name,
        u.email,
        u.phone as user_phone,
        u.phone as parent_phone,
        u.name as parent_name,
        u.password_name,
        sc.*

    FROM students st
    JOIN users u ON st.user_id = u.id
    LEFT JOIN schools sc ON st.school_id = sc.id
    WHERE encode(
            hmac(st.id::text, 'my-32-byte-secret-key-123456789012', 'sha256'),
            'hex'
          ) = ?
    LIMIT 1
    ",
    [$encodedId]
);

unset($user->image); // Remove sensitive data


    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Student not found'
        ], 404);
    }

    // print_r($user); exit;
   return view('studentinfo', ['user' => $user]);
}


public function get_encodedList()
{
    $csvPath = public_path('encoded_idvs_student_id.csv');
    $rows = array_map('str_getcsv', file($csvPath));

    $header = $rows[0];

    if (!in_array('shortened_url', $header)) {
        $header[] = 'shortened_url';
    }

    $encIndex   = array_search('enc_student_id', $header);
    $shortIndex = array_search('shortened_url', $header);

    $data = array_slice($rows, 1);

    // 🔥 CHUNK SIZE (adjustable)
    $chunkSize = 5000;

    foreach (array_chunk($data, $chunkSize) as $chunk) {
        GenerateStudentShortUrlsJob::dispatch($chunk, $encIndex, $shortIndex);
    }

    return response()->json([
        'message' => 'Jobs dispatched successfully',
        'chunks'  => ceil(count($data) / $chunkSize)
    ]);
}



}
