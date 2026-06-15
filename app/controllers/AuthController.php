<?php
declare(strict_types=1);

use Firebase\JWT\JWT;

class AuthController
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    // POST /auth/register
    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Validierung //request control
        if (empty($data['name']) || empty($data['lastname'])|| empty($data['email']) || empty($data['password'])) {
            Response::json(['error' => 'email und password sind Pflicht'], 422);
            return;
        }

        $id = $this->model->create($data);
        Response::json(['message' => 'Registrierung erfolgreich', 'id' => $id], 201);
    }

    // POST /auth/login
    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            Response::json(['error' => 'email und password sind Pflicht'], 422);
            return;
        }

        // User in DB suchen
        $user = $this->model->findByEmail($data['email']);

        // User nicht gefunden ODER Passwort falsch → immer gleiche Meldung, E-Mail bleibt nicht zu ratten
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::json(['error' => 'Ungültige Anmeldedaten'], 401);
            return;
        }

        // Token generieren
        $payload = [
            'user_id' => $user['id'],
            'role'    => $user['role'],
            'exp'     => time() + (int) $_ENV['JWT_EXPIRY'] //'exp 'Registered Claim Name
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');//HS256 ist der Signaturalgorithmus

        Response::json([
            'token' => $token,
            'user'  => [
                'id'       => $user['id'],
                'role'     => $user['role']
            ]
        ]);
    }
}