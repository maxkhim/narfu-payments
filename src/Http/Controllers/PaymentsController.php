<?php

namespace Narfu\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use function view;

class PaymentsController extends Controller
{
    public function index()
    {
        return view('narfu-payments::index');
    }

    public function indexGuest()
    {
        return view('narfu-payments::index-guest');
    }
}
