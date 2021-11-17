<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    static public function insert_user(Request $request)
    {
        $name = $request->name ?? $request->username;
        $email = $request->email ?? Str::random(20);;
        $username = $request->username;
        $is_admin = $request->is_admin ?? 0;
        $is_gudang = $request->is_gudang ?? 0 ;
        $is_customer = $request->is_customer ?? 0;
        $is_supplier = $request->is_supplier ?? 0;
        $created_at = new Carbon("now");
        $updated_at =  new Carbon("now");
        $password = $request->password;
        $password = Hash::make($password);

        // todo check jika user sudah terdaftar


        $db = DB::insert(
            "INSERT INTO users (name, email, username, password,
             is_admin, is_gudang,is_customer,is_supplier,created_at,updated_at )

             VALUES('$name','$email', '$username', '$password', 
             '$is_admin', '$is_gudang', '$is_customer', '$is_supplier', '$created_at', '$updated_at' )"
        );
        return $db;
    }


    static public function login($username, $password, $request)
    {
        
        $user = User::where('username','=', $username)->first();
        
        if ($user) {
            if (Hash::check($password, $user->password)) {
                // todo uncomment
                // $a = DB::table("personal_access_tokens")
                // ->where("name", '=', $username)
                // ->delete();
                return $user;
                
            } else {
                return "password invalid";
            }
        }
        return "username not found";
    }
}
