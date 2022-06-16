<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="UserRole",
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="role",
 *      type="string"
 *  ),
 *  @OA\Property(
 *      property="created_at",
 *      type="datetime"
 *  ),
 *  @OA\Property(
 *      property="updated_at",
 *      type="datetime"
 *  )
 * )
 */
class UserRole extends Model
{
    use HasFactory;
}
