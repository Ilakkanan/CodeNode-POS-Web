<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('contact_no', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%") ;
            });
        }
        $vendors = $query->latest()->paginate(10)->appends(['search' => $request->search]);
        return view('vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'contact_no' => 'required|string|max:10|min:10|regex:/^[0-9]+$/',
            'address' => 'required|string',
            'nic' => [
                'nullable',
                'string',
                'max:12',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[0-9]{9}[vVxX]$|^[0-9]{12}$/', $value)) {
                        $fail('The NIC must be either 9 digits followed by V/X or 12 digits.');
                    }
                }
            ]
        ]);

        Vendor::create($request->all());

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'contact_no' => 'required|string|max:10|min:10|regex:/^[0-9]+$/',
            'address' => 'required|string',
            'nic' => [
                'nullable',
                'string',
                'max:12',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[0-9]{9}[vVxX]$|^[0-9]{12}$/', $value)) {
                        $fail('The NIC must be either 9 digits followed by V/X or 12 digits.');
                    }
                }
            ]
        ]);

        $vendor->update($request->all());

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendors.index')
            ->with('success', 'Vendor moved to trash');
    }

    public function trashed(Request $request)
    {
        $query = Vendor::onlyTrashed();
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('contact_no', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%") ;
            });
        }
        $vendors = $query->paginate(10)->appends(['search' => $request->search]);
        return view('vendors.trashed', compact('vendors'));
    }

    public function restore($id)
    {
        $vendor = Vendor::onlyTrashed()->findOrFail($id);
        $vendor->restore();

        return redirect()->route('vendors.trashed')
            ->with('success', 'Vendor restored successfully');
    }

    public function forceDelete($id)
    {
        $vendor = Vendor::onlyTrashed()->findOrFail($id);
        $vendor->forceDelete();

        return redirect()->route('vendors.trashed')
            ->with('success', 'Vendor permanently deleted');
    }
}