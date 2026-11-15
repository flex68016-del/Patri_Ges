<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

/**
 * Administration : gestion des utilisateurs (auth-service) et des
 * referentiels services/categories (patrimoine-service). Reserve a
 * l'administrateur via le middleware EnsureAdmin.
 */
class AdminController extends Controller
{
    public function __construct(private ApiClient $api) {}

    /** Liste des comptes utilisateurs de la plateforme. */
    public function users()
    {
        $response = $this->api->get('auth', '/api/users');

        return view('admin.users', [
            'users' => $response->json('data', []),
        ]);
    }

    public function userCreate()
    {
        $services = $this->api->get('patrimoine', '/api/services')->json();

        return view('admin.users-create', [
            'services' => is_array($services) ? $services : [],
        ]);
    }

    /** Formulaire de creation d'un utilisateur avec choix du role. */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:administrateur,gestionnaire,responsable,inspecteur',
        ]);

        $response = $this->api->post('auth', '/api/users', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['user' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    /** Page des referentiels : liste les services et categories existants. */
    public function references()
    {
        $services = $this->api->get('patrimoine', '/api/services')->json();
        $categories = $this->api->get('patrimoine', '/api/categories')->json();

        return view('admin.references', [
            'services' => is_array($services) ? $services : [],
            'categories' => is_array($categories) ? $categories : [],
        ]);
    }

    public function serviceCreate()
    {
        return view('admin.service-create');
    }

    public function serviceStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:255',
        ]);

        $response = $this->api->post('patrimoine', '/api/services', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['service' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('admin.references')->with('success', 'Service créé avec succès.');
    }

    public function categoryCreate()
    {
        return view('admin.category-create');
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'nom' => 'required|string|max:255',
            'type_bien' => 'required|in:immobilier,vehicule,equipement',
            'duree_vie' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $response = $this->api->post('patrimoine', '/api/categories', $validated);

        if (! $response->successful()) {
            return back()->withErrors(['categorie' => $response->json('message', 'Erreur lors de la création.')])->withInput();
        }

        return redirect()->route('admin.references')->with('success', 'Catégorie créée avec succès.');
    }
}
