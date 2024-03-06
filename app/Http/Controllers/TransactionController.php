<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json(['data' => $transactions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productId = $request->product_id;
        $machineId = $request->machine_id;

        $product = Product::findOrFail($productId);
        $productPrice = $product->price;
        $requestPrice = $request->price;

        if ($productPrice != $requestPrice) {
            return response()->json([
                'success' => false,
                'error' => 'Product price does not match the requested price'
            ], 400);
        }


        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . env('MERCADO_PAGO_ACCESS_TOKEN')
        ])->post(
            'https://api.mercadopago.com/v1/payments',
            [
                "transaction_amount" => $request->price,
                "description" => $product->name,
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => "test@gmail.com"
                ]
            ]
        );

        $body = $response->getBody()->getContents();
        $data = json_decode($body);

        $identifier = $data->id;
        $qrCode = $data->point_of_interaction->transaction_data->qr_code;
        $qrCodeLink = $data->point_of_interaction->transaction_data->ticket_url;
        $qrCodeBase = $data->point_of_interaction->transaction_data->qr_code_base64;

        Transaction::create([
            "machine_id"=> $machineId,
            "product_id"=> $productId,
            "price" => $productPrice,
            "identifier" => $identifier
        ]);

        return response()->json([
            'success' => true,
            'qr_code' => $qrCode,
            'qr_code_link' => $qrCodeLink,
            'qr_code_base64' => $qrCodeBase
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json(['data' => $transaction]);
    }
}