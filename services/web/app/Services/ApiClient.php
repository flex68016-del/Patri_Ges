<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Client HTTP mutualise pour appeler les microservices backend.
 *
 * Le service web ne touche jamais directement la base de donnees : toutes les
 * donnees transitent par les API REST (auth, patrimoine, operations) en
 * joignant le token JWT de l'utilisateur dans l'en-tete Authorization.
 */
class ApiClient
{
    /**
     * Requete GET vers un service.
     *
     * @param  string  $service  'auth', 'patrimoine' ou 'operations'
     * @param  string  $path     Chemin de l'endpoint (ex : /api/biens)
     * @param  array   $query    Parametres de requete optionnels
     */
    public function get(string $service, string $path, array $query = [])
    {
        return $this->request('get', $service, $path, ['query' => $query]);
    }

    /** Requete POST (creation ou action) vers un service. */
    public function post(string $service, string $path, array $data = [])
    {
        return $this->request('post', $service, $path, ['json' => $data]);
    }

    /** Requete PUT (mise a jour complete) vers un service. */
    public function put(string $service, string $path, array $data = [])
    {
        return $this->request('put', $service, $path, ['json' => $data]);
    }

    /**
     * Execute la requete HTTP reelle vers le microservice cible.
     *
     * Le nom du service est traduit en URL de base via les variables
     * d'environnement (avec fallback sur les ports locaux de developpement).
     * Le token JWT stocke en session est automatiquement ajoute en Bearer.
     */
    private function request(string $method, string $service, string $path, array $options = [])
    {
        $baseUrl = match ($service) {
            'auth' => env('AUTH_SERVICE_URL', 'http://localhost:8001'),
            'patrimoine' => env('PATRIMOINE_SERVICE_URL', 'http://localhost:8002'),
            'operations' => env('OPERATIONS_SERVICE_URL', 'http://localhost:8003'),
            default => throw new \InvalidArgumentException("Service inconnu: {$service}"),
        };

        // Timeout court : l'interface doit rester reactive meme si un
        // service backend est lent ou endormi.
        $pending = Http::acceptJson()->timeout(10);

        // Authentification : le JWT emis par auth-service est presente a
        // chaque appel pour que les API verifient l'identite et le role.
        if ($token = session('token')) {
            $pending = $pending->withToken($token);
        }

        // GET envoie les parametres en query string, POST/PUT en JSON.
        return $pending->{$method}($baseUrl.$path, $options['json'] ?? $options['query'] ?? []);
    }
}
