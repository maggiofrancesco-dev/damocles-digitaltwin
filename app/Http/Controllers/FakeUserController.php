<?php

namespace App\Http\Controllers;

use App\Models\FakeUser;
use Illuminate\Http\Request;

class FakeUserController extends Controller
{
    public function destroy(int $fakeUserId)
    {
        $digitalTwin = FakeUser::findOrFail($fakeUserId);

        $digitalTwin->delete();

        return redirect()->back()->with('success', 'Fake user deleted successfully!');
    }
}
