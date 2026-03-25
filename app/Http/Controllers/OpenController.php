<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OpenController extends Controller
{
    public function index(Request $request)
    {
        // $userAgent = strtolower($request->header('user-agent'));

        // // Android → Open Play Store directly
        // if (strpos($userAgent, 'android') !== false) {
        //     return redirect()->away(
        //         'https://play.google.com/store/apps/details?id=com.nrsm.snipe'
        //     );
        // }

        // Others → Website
        return redirect()->away(
            'https://student.nationalroadsafetymission.org/'
        );
    }
}
