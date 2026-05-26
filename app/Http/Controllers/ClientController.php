<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $this->authorizePermission(Permissions::CLIENTS_VIEW);

        $clients = Client::withCount('ventes')->orderBy('nom')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        $this->authorizePermission(Permissions::CLIENTS_MANAGE);

        return view('clients.create');
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        $this->authorizePermission(Permissions::CLIENTS_MANAGE);

        Client::create($request->validated());

        return redirect()->route('clients.index')->with('success', 'Client enregistré.');
    }

    public function edit(Client $client): View
    {
        $this->authorizePermission(Permissions::CLIENTS_MANAGE);

        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorizePermission(Permissions::CLIENTS_MANAGE);

        $client->update($request->validated());

        return redirect()->route('clients.index')->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorizePermission(Permissions::CLIENTS_MANAGE);

        if ($client->ventes()->exists()) {
            return back()->with('error', 'Ce client possède des ventes associées.');
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé.');
    }
}
