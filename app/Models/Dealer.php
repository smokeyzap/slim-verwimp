<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $table = 'vw_dealers';
    protected $fillable = [ 
        'customer_number',
        'name',
        'address',
        'zip',
        'country',
        'city',
        'phone',
        'fax',
        'email',
        'password',
        'order_amount',
        'order_cost',
        'invoice_discount',
        'allow',
        'email2',
        'delivery_note_mail',
        'packing_price',
        'transport',
        'vat_number',
        'delivery_name',
        'delivery_address',
        'delivery_zip',
        'delivery_country',
        'delivery_city',
        'invoice_mail',
        'invoice_post',
        'invoice_email',
        'invoice_email2',
        'parent_number',
        'aa13',
        'backorder',
        'holiday_from',
        'holiday_until',
        'drop_shipment',
        'search_field',
        'packing_slip_email',
        'packing_slip_email2',
        'representative',
    ];

    public function auth ($email, $password) 
    {
        $dealer = Dealer::where('email', $email)->first();

        if (!$dealer) {
            return false;
        }

        if (password_verify($password, $dealer->password)) {
            $_SESSION['id'] = $dealer->id;
            $_SESSION['customer_number'] = $dealer->customer_number;
            $_SESSION['name'] = $dealer->name;
            return true;
        }
        return false;
    }

    public function signout () 
    {
        $_SESSION = [];
        unset($_SESSION['id']);
        unset($_SESSION['customer_number']);
        unset($_SESSION['name']);
    }

    public function check () 
    {
        return isset($_SESSION['id']);
    }

    public function generateRandomString () 
    {
        try {
            $string = random_bytes(64);
        } catch (TypeError $e) {
            // Well, it's an integer, so this IS unexpected.
            die("An unexpected error has occurred"); 
        } catch (Error $e) {
            // This is also unexpected because 32 is a reasonable integer.
            die("An unexpected error has occurred");
        } catch (Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            die("Could not generate a random string. Is our OS secure?");
        }
        return bin2hex($string);
    }
}