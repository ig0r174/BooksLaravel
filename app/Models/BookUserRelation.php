<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="CabinetEntry",
 *  @OA\Property(
 *      property="id",
 *      type="integer"
 *  ),
 *  @OA\Property(
 *      property="user_id",
 *      type="integer",
 *      example=1
 *  ),
 *  @OA\Property(
 *      property="book_id",
 *      type="integer",
 *      example=1
 *  ),
 *  @OA\Property(
 *      property="user_role_id",
 *      type="integer",
 *      example=1
 *  ),
 *  @OA\Property(
 *      property="created_at",
 *      type="date"
 *  ),
 *  @OA\Property(
 *      property="updated_at",
 *      type="date"
 *  )
 * )
 */
class BookUserRelation extends Model
{
    use HasFactory;
}
