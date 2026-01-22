<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class InvitationController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $companies = Company::all();
            $roles = Role::whereIn('name', ['Admin', 'Member'])->get();
        } elseif ($user->isAdmin()) {
            $companies = Company::where('id', $user->company_id)->get();
            $roles = Role::whereIn('name', ['Admin', 'Member'])->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('invitations.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|unique:users',
            'role' => 'required|in:Admin,Member',
            'company_id' => 'required|exists:companies,id',
        ]);

        $tempPassword = $request->name . '@123' ?? Str::random(10);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'company_id' => $request->company_id,
            'is_active' => false,
        ]);

        $newUser->assignRole($request->role);

        Mail::to($request->email)->send(new InvitationMail($newUser, $tempPassword));

        return redirect()->route('short-urls.index')
            ->with('success', 'Invitation sent successfully and password send to your mail');
    }
}
