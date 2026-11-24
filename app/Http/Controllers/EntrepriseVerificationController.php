<?php

namespace App\Http\Controllers;

use App\Models\EntrepriseDocument;
use App\Support\CurrentEntreprise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class EntrepriseVerificationController extends Controller
{
    public function show(): View
    {
        $entreprise = app(CurrentEntreprise::class)->get();
        $documents = $entreprise->documents()->latest()->get();

        return view('entreprises.verification-status', compact('entreprise', 'documents'));
    }

    public function resubmit(Request $request): RedirectResponse
    {
        $entreprise = app(CurrentEntreprise::class)->get();
        abort_unless($entreprise->verification_status === 'rejected', 403);

        $validated = $request->validate([
            'numero_enregistrement' => ['nullable', 'string', 'max:100'],
            'numero_autorisation' => ['nullable', 'string', 'max:100'],
            'document_enregistrement' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'document_autorisation' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        DB::transaction(function () use ($validated, $request, $entreprise) {
            $entreprise->update([
                'numero_enregistrement' => $validated['numero_enregistrement'] ?? $entreprise->numero_enregistrement,
                'numero_autorisation' => $validated['numero_autorisation'] ?? $entreprise->numero_autorisation,
                'verification_status' => 'pending',
                'verification_note' => null,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            foreach ([
                'document_enregistrement' => 'enregistrement',
                'document_autorisation' => 'autorisation',
            ] as $field => $type) {
                if ($request->hasFile($field)) {
                    $this->storeDocument($entreprise->id, $request, $field, $type);
                }
            }
        });

        return redirect()->route('entreprise.verification.status')
            ->with('success', 'Votre dossier a été renvoyé pour vérification.');
    }

    private function storeDocument(int $entrepriseId, Request $request, string $field, string $type): void
    {
        $file = $request->file($field);
        $path = $file->store("private/entreprise-documents/{$entrepriseId}", 'local');

        if (! $path) {
            throw new RuntimeException('Le document n’a pas pu être enregistré.');
        }

        EntrepriseDocument::create([
            'entreprise_id' => $entrepriseId,
            'uploaded_by' => $request->user()->id,
            'type' => $type,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ]);
    }
}
