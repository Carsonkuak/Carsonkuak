<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Models\VegeProduct;
use App\Models\VegeAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PasswordReset;
use App\Mail\email;
use App\Models\password_reset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VegeUserController extends Controller
{
    public function showRegisterForm()
    {
        return view('vegetables.register');
    }

    public function register(Request $request)
{
    $validatedData = $request->validate([
        'username' => 'required|unique:users,username',
        'email' => ['required', Rule::unique('users', 'email')],
        'password' => 'required|min:6',
    ]);

    $otp = rand(100000, 999999);

    $user = User::create([
        'username' => $validatedData['username'],
        'email' => $validatedData['email'],
        'password' => $validatedData['password'],
        'otp' => $otp,
        'is_verified' => false,
    ]);

    Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Verify Your Email');
    });

    return redirect()->route('verify.otp')->with('message', 'OTP sent to your email. Please check your inbox.');
}


    public function showVerifyOtpForm()
    {
        return view('vegetables.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $validatedData = $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('otp', $validatedData['otp'])->first();

        if ($user) {
            $user->is_verified = 1;
            $user->otp = null;
            $user->save();

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Your account has been successfully verified!');
        }
        return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
    }

    public function showLoginForm()
    {
        return view('vegetables.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        Log::info('Attempting login', ['credentials' => $credentials]);

        if (Auth::attempt($credentials)) {
            Log::info('Login successful');
            return redirect()->route('home');
        }

        Log::info('Login failed');
        return back()->withErrors(['username' => 'The provided credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function showProducts()
    {
        $products = VegeProduct::select('id', 'image', 'p_name', 'price', 'mass')->get();
        $user_cart = Cart::where("user_id", Auth::id())->get();

        $cartCount = $user_cart->count();
        return view('vegetables.home', compact('products', 'cartCount'));
    }

    public function showProductDetails($id)
    {
        $product = VegeProduct::select('id', 'image', 'p_name', 'details', 'mass', 'price', 'created_at', 'updated_at')
                              ->where('id', $id)
                              ->first();

        if (!$product) {
            abort(404, 'Product not found');
        }

        $cartCount = Cart::where('user_id', auth()->id())->count();

        return view('vegetables.product_detail', compact('product', 'cartCount'));
    }

    public function create()
    {
        return view('vegetables.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'p_id' => 'required|string|max:255',
            'image' => 'nullable|image',
            'p_name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'mass' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        VegeProduct::create($validated);

        return redirect()->route('add')->with('success', 'Product added successfully!');
    }

    public function showProfile()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You need to log in to access this page.');
        }

        $user = Auth::user();
        $addresses = $user->addresses;

        return view('vegetables.profile', ['user' => $user, 'addresses' => $addresses]);
    }

    public function storeAddress(Request $request)
    {
        $validatedData = $request->validate([
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:10',
        ]);

        Log::info('Validated Data for Address:', $validatedData);

        try {
            if (!Auth::check()) {
                return redirect()->route('login')->withErrors('You need to log in to save an address.');
            }

            $address = new VegeAddress([
                'u_id' => Auth::id(),
                'address_1' => $validatedData['address_1'],
                'address_2' => $validatedData['address_2'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'postcode' => $validatedData['postcode'],
            ]);

            $address->save();

            Log::info('Address saved successfully:', ['address' => $address]);

            return redirect()->route('profile')->with('success', 'Address added successfully.');

        } catch (\Exception $e) {
            Log::error('Error saving address:', ['error' => $e->getMessage()]);
            return redirect()->route('profile')->withErrors('Failed to save address. Please try again.');
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $validatedData = $request->validate([
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|string|max:10',
        ]);

        try {
            $address = VegeAddress::findOrFail($id);

            if ($address->u_id != Auth::id()) {
                return redirect()->route('profile')->withErrors('Unauthorized access.');
            }

            $address->update([
                'address_1' => $validatedData['address_1'],
                'address_2' => $validatedData['address_2'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
                'postcode' => $validatedData['postcode'],
            ]);

            Log::info('Address updated successfully:', ['address' => $address]);

            return redirect()->route('profile')->with('success', 'Address updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating address:', ['error' => $e->getMessage()]);
            return redirect()->route('profile')->withErrors('Failed to update address. Please try again.');
        }
    }

    public function setPreferredAddress(Request $request, $id)
    {
        try {
            $address = VegeAddress::findOrFail($id);

            if ($address->u_id != Auth::id()) {
                return redirect()->route('profile')->withErrors('Unauthorized access.');
            }

            $user = Auth::user();
            DB::table('users')->where('id', $user->id)->update(['preferred_address_id' => $address->id]);

            Log::info('Preferred address set successfully:', ['address' => $address]);

            return redirect()->route('profile')->with('success', 'Preferred address updated.');

        } catch (\Exception $e) {
            Log::error('Error setting preferred address:', ['error' => $e->getMessage()]);
            return redirect()->route('profile')->withErrors('Failed to set preferred address. Please try again.');
        }
    }

    public function requestEmailUpdate(Request $request)
{
    $validatedData = $request->validate([
        'email' => ['required', 'email', Rule::unique('users', 'email')],
    ]);

    $otp = rand(100000, 999999);
    $user = auth()->user();

    $user->otp = $otp;
    $user->new_email = $validatedData['email'];
    $user->save();

    Mail::raw("Your OTP for email verification is: $otp", function ($message) use ($user) {
        $message->to($user->new_email)
                ->subject('Verify Your New Email');
    });

    return redirect()->route('verify.email.otp')->with('message', 'OTP sent to your new email. Please check your inbox.');
}

    public function verifyEmailOtp(Request $request)
    {
    $request->validate([
        'otp' => 'required|numeric',
    ]);

    $user = auth()->user();

    if ($user->otp == $request->otp) {
        $user->email = $user->new_email;
        $user->new_email = null;
        $user->otp = null;
        $user->save();

        return redirect()->route('profile')->with('message', 'Email updated successfully.');
    }

    return redirect()->back()->with('error', 'Invalid OTP.');
    }

    public function requestPasswordUpdate(Request $request)
{
    $validatedData = $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $otp = rand(100000, 999999);
    $user = auth()->user();

    $user->otp = $otp;
    $user->new_password = $validatedData['password'];
    $user->save();

    Mail::raw("Your OTP for password verification is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Verify Your Password Change');
    });

    return redirect()->route('verify.password.otp')->with('message', 'OTP sent to your email. Please check your inbox.');
}

    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = auth()->user();

        if ($user->otp == $request->otp) {
            $user->password = $user->new_password;
            $user->new_password = null;
            $user->otp = null;
            $user->save();

            return redirect()->route('profile')->with('message', 'Password updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid OTP.');
    }


    public function updateUsername(Request $request)
    {
    $validatedData = $request->validate([
        'username' => 'required|unique:users,username',
    ]);

    $user = auth()->user();
    $user->username = $validatedData['username'];
    $user->save();

    return redirect()->back()->with('message', 'Username updated successfully.');
    }



    public function viewCart()
    {
        $userId = auth()->user()->id;

        $cartItems = Cart::where('user_id', $userId)
                          ->with('product')
                          ->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->mass;
        });

        return view('/vegetables/cart', compact('cartItems', 'totalPrice'));
    }
    public function addToCart(Request $request, $productId)
    {

    $validatedData = $request->validate([
        'mass' => 'required|numeric|min:1',
    ]);

    $userId = auth()->id();

    $mass = $validatedData['mass'];

    $product = VegeProduct::find($productId);

    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found'], 404);
    }

    $cart = new Cart();
    $cart->user_id = $userId;
    $cart->p_id = $productId;
    $cart->mass = $mass;
    $cart->created_at = now();
    $cart->updated_at = now();

    $cart->save();

    return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
    }

    public function destroyAddress($id)
    {
    try {
        $address = VegeAddress::findOrFail($id);

        if ($address->u_id != Auth::id()) {
            return redirect()->route('profile')->withErrors('Unauthorized access.');
        }

        $address->delete();

        Log::info('Address deleted successfully:', ['address_id' => $id]);

        return redirect()->route('profile')->with('success', 'Address deleted successfully.');

    } catch (\Exception $e) {
        Log::error('Error deleting address:', ['error' => $e->getMessage()]);
        return redirect()->route('profile')->withErrors('Failed to delete address. Please try again.');
    }
    }
    public function showForgotPasswordForm()
    {
        return view('vegetables.forget_email');
    }

public function sendResetLink(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Email does not exist.']);
    }

    $otp = rand(100000, 999999);

    password_reset::updateOrInsert(
        ['email' => $request->email],
        ['token' => $otp, 'created_at' => now()]
    );


    Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Verify Your Email');
    });

    Log::info("Your OTP is: $otp");

    return redirect()->route('password.verify')->with(['email' => $request->email]);
}
    public function showVerifyOtpForm2(Request $request)
    {
        $email = $request->session()->get('email');
        return view('vegetables.forget_otp', compact('email'));
    }
    public function getCartCount()
    {
        $count = auth()->user()->cart()->count();
        return response()->json(['count' => $count]);
    }


    public function verifyOtp2(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:password_reset_tokens,email',
            'otp' => 'required|string'
        ]);

        $passwordReset = password_reset::where('email','=',$request->email)
                                    ->where('token', $request->otp)
                                    ->first();

        if (!$passwordReset) {
            return back()->withErrors(['otp' => 'Invalid OTP 123.']);
        }

        return redirect()->route('password.reset')->with('email', $request->email);
    }

    public function showResetPasswordForm(Request $request)
    {
        $email = $request->session()->get('email');
        return view('vegetables.reset_password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password     ;
        $user->save();

        password_reset::where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password has been reset successfully.');
    }


    public function updateCart(Request $request)
    {
        $cartItems = $request->input('mass');

        foreach ($cartItems as $id => $mass) {

            $mass = (float) $mass;
            if ($mass < 0) $mass = 0;

            DB::table('cart')
                ->where('id', $id)
                ->update(['mass' => $mass, 'updated_at' => now()]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        DB::table('cart')
            ->where('id', $productId)
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function editCartItem(Request $request, $id)
    {

        $cartItem = Cart::find($id);

        if ($cartItem) {
            $cartItem->mass = $request->input('mass')[$id];
            $cartItem->save();
        }
        return redirect()->route('view_cart');
    }

    public function updateUsername2(Request $request)
    {
        $request->validate(['username' => 'required|string|max:255']);
        $user = Auth::user();
        $user->username = $request->username;
        $user->save();

        return redirect()->back()->with('status', 'Username updated successfully!');
    }
    public function updateEmail(Request $request)
    {
        $request->validate(['new_email'=>'required|max:255|unique:users,email']);
        $otp = rand(100000, 999999);

        session(['otp' => $otp, 'new_email' => $request->new_email]);

        Mail::to($request->new_email)->send(new email($otp));

        return redirect()->back()->with('status', 'OTP sent to your new email address.');
    }
    public function updatePassword(Request $request)
    {
        $request->validate(['new_password' => 'required|string|min:8|confirmed']);
        $otp = rand(100000, 999999);

        session(['otp' => $otp, 'new_password' => $request->new_password]);

        Mail::to(Auth::user()->email)->send(new \App\Mail\email($otp));

        return redirect()->back()->with('status', 'OTP sent to your email address.');
    }

    public function verifyOtp3(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);
        $sessionOtp = session('otp');
        // dd($sessionOtp);
        if ($request->otp == $sessionOtp) {
            $user = Auth::user();

            if (session('new_email')) {
                $user->email = session('new_email');
                session()->forget('new_email');
            }

            if (session('new_password')) {
                $user->password = Hash::make(session('new_password'));
                session()->forget('new_password');
            }

            $user->save();
            session()->forget('otp');

            return redirect()->back()->with('status', 'Verification successful. Your details have been updated.');
        } else {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }
    }

}

