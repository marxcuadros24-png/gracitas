<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RealEmailDomain implements ValidationRule
{
    /**
     * Lista de dominios de correos temporales o desechables no permitidos.
     *
     * @var array<int, string>
     */
    protected array $disposableDomains = [
        'yopmail.com',
        'yopmail.fr',
        'yopmail.net',
        'mailinator.com',
        'mailinator.net',
        'tempmail.com',
        'temp-mail.org',
        '10minutemail.com',
        '10minutemail.net',
        'guerrillamail.com',
        'guerrillamail.net',
        'sharklasers.com',
        'trashmail.com',
        'dispostable.com',
        'getairmail.com',
        'mohmal.com',
        'fakemailgenerator.com',
        'throwawaymail.com',
        'crazymailing.com',
        'mytemp.email',
    ];

    /**
     * Dominios permitidos para pruebas y entorno de testing.
     *
     * @var array<int, string>
     */
    protected array $testingWhitelist = [
        'gracitas.com',
        'example.com',
        'example.org',
        'example.net',
        'ejemplo.com',
        'test.com',
    ];

    /**
     * Ejecuta la regla de validación para asegurar que el correo pertenece a un dominio existente.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! str_contains($value, '@')) {
            $fail('El correo electrónico debe tener una estructura válida con un dominio real.');

            return;
        }

        $parts = explode('@', $value);
        if (count($parts) !== 2 || empty($parts[1])) {
            $fail('El formato del correo electrónico es inválido.');

            return;
        }

        $domain = strtolower(trim($parts[1]));

        // Rechazar dominios de correos desechables / temporales
        if (in_array($domain, $this->disposableDomains, true)) {
            $fail('No se permiten correos electrónicos temporales o desechables. Por favor ingresa un correo real y permanente (ej. Gmail, Outlook, Yahoo o institucional).');

            return;
        }

        // Permitir dominios ficticios controlados únicamente durante pruebas automatizadas
        if (app()->environment('testing') && in_array($domain, $this->testingWhitelist, true)) {
            return;
        }

        // Verificar sintaxis básica de dominio
        if (! filter_var('user@'.$domain, FILTER_VALIDATE_EMAIL) || ! str_contains($domain, '.')) {
            $fail('El dominio del correo (@'.$domain.') no es válido.');

            return;
        }

        // Verificar existencia de registros de correo (MX) o registros de host (A) mediante DNS
        $hasMx = @checkdnsrr($domain, 'MX');
        $hasA = @checkdnsrr($domain, 'A');

        if (! $hasMx && ! $hasA) {
            $fail('El dominio del correo (@'.$domain.') no existe o no tiene un servidor de correo habilitado para recibir mensajes.');
        }
    }
}
