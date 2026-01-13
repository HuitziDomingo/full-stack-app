<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // Validar parámetros de paginación
        $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ]);

        $perPage = $request->input('per_page', 15);
        $users = User::paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
        ]);
    }

    /**
     * Get the count of users.
     */
    public function count(): JsonResponse
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();

        return response()->json([
            'total_users' => $totalUsers,
            'verified_users' => $verifiedUsers,
            'unverified_users' => $unverifiedUsers,
            'verification_rate' => $totalUsers > 0 
                ? round(($verifiedUsers / $totalUsers) * 100, 2) 
                : 0,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validar los datos de entrada con reglas más estrictas
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚÚñÑ\s]+$/u', // Solo letras, espacios y acentos
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns', // Validación más estricta de email
                'max:255',
                'unique:users,email',
                'lowercase', // Normalizar a minúsculas
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/', // Al menos una minúscula, una mayúscula y un número
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.lowercase' => 'El correo electrónico debe estar en minúsculas.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una mayúscula y un número.',
        ]);

        // Normalizar email a minúsculas
        $validated['email'] = strtolower($validated['email']);

        // Crear el usuario (el password se hash automáticamente por el cast en el modelo)
        $user = User::create([
            'name' => trim($validated['name']), // Eliminar espacios al inicio y final
            'email' => $validated['email'],
            'password' => $validated['password'], // Se hash automáticamente
        ]);

        // Retornar respuesta JSON con el usuario creado (sin el password)
        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user->makeHidden(['password', 'remember_token']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        // Validar que el ID sea numérico
        $request = request();
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($id);
        return response()->json([
            'user' => $user->makeHidden(['password', 'remember_token']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        // Validar que el ID sea numérico y exista
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($id);

        // Validar los datos de entrada con reglas más estrictas
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚÚñÑ\s]+$/u',
            ],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
                'lowercase',
            ],
            'password' => [
                'sometimes',
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/',
            ],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.lowercase' => 'El correo electrónico debe estar en minúsculas.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra minúscula, una mayúscula y un número.',
        ]);

        // Normalizar email a minúsculas si se proporciona
        if (isset($validated['email'])) {
            $validated['email'] = strtolower($validated['email']);
        }

        // Normalizar name si se proporciona
        if (isset($validated['name'])) {
            $validated['name'] = trim($validated['name']);
        }

        // Actualizar solo los campos proporcionados
        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'user' => $user->fresh()->makeHidden(['password', 'remember_token']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        // Validar que el ID sea numérico y exista
        $request = request();
        $request->merge(['id' => $id]);
        $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($id);
        $user->delete();

        // Obtener el nuevo conteo después de eliminar
        $totalUsers = User::count();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente',
            'remaining_users' => $totalUsers,
        ], 200);
    }
}
