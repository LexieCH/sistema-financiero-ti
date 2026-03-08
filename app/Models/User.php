<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Permiso;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected ?array $matrizPermisos = null;

    protected $fillable = [
        'name',
        'email',
        'password',
        'empresa_id',
        'rol_id',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function esAdmin()
    {
        return $this->rol->nombre === 'Admin';
    }

    public function tienePermiso(string $modulo, string $accion = 'lectura'): bool
    {
        // Si el usuario no tiene rol, mejor bloquear por seguridad.
        if (!$this->rol) {
            return false;
        }

        // Admin puede todo, así evitamos validar permiso por permiso.
        if ($this->rol->nombre === 'Admin') {
            return true;
        }

        $matriz = $this->obtenerMatrizPermisos();

        return (bool) ($matriz[$modulo][$accion] ?? false);
    }

    private function obtenerMatrizPermisos(): array
    {
        // Si ya cargamos la matriz antes, la reutilizamos para ahorrar consultas.
        if ($this->matrizPermisos !== null) {
            return $this->matrizPermisos;
        }

        $permisos = Permiso::query()
            ->where('rol_id', $this->rol_id)
            ->with('modulo:id,nombre')
            ->get();

        $this->matrizPermisos = [];

        foreach ($permisos as $permiso) {
            $nombreModulo = $permiso->modulo?->nombre;

            if (!$nombreModulo) {
                continue;
            }

            // Armamos la matriz en formato simple para consultarla rápido en vistas.
            $this->matrizPermisos[$nombreModulo] = [
                'lectura' => (bool) $permiso->lectura,
                'crear' => (bool) $permiso->crear,
                'editar' => (bool) $permiso->editar,
                'eliminar' => (bool) $permiso->eliminar,
            ];
        }

        return $this->matrizPermisos;
    }
}