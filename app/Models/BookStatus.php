<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="BookStatus",
 *  @OA\Property(
 *      property="id",
 *      type="integer",
 *      example=1
 *  ),
 *  @OA\Property(
 *      property="status",
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
class BookStatus extends Model
{
    use HasFactory;
}
