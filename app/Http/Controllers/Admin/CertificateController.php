<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::orderBy('order')->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        return view('admin.certificates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('certificates', 'public');
        }

        Certificate::create([
            'title' => $validated['title'],
            'image_path' => $imagePath,
            'description' => $validated['description'],
            'order' => $validated['order'] ?? 0,
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate created');
    }

    public function show(Certificate $certificate)
    {
        return view('admin.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $imagePath = $certificate->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($imagePath && \Storage::disk('public')->exists($imagePath)) {
                \Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('certificates', 'public');
        }

        $certificate->update([
            'title' => $validated['title'],
            'image_path' => $imagePath,
            'description' => $validated['description'],
            'order' => $validated['order'] ?? $certificate->order,
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate updated');
    }

    public function destroy(Certificate $certificate)
    {
        // Delete image if exists before deleting record
        if ($certificate->image_path && \Storage::disk('public')->exists($certificate->image_path)) {
            \Storage::disk('public')->delete($certificate->image_path);
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted');
    }

    /**
     * Get the current count of certificates
     */
    public static function getCurrentCount()
    {
        return Certificate::count();
    }
}
